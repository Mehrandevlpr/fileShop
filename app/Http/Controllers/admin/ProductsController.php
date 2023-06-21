<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Utilities\ImageUploader;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{

    public function create(){
      $categories = Category::all();
      return view('admin.products.all',compact('categories'));  
    }

    public function store(StoreRequest $request){

        $validatedData = $request->validated();
        $admin = User::where('email','admin@gmail.com')->first();

        $createdProduct = Product::create([
            'title'        =>$validatedData['title'],
            'category_id'  =>$validatedData['category_id'],
            'owner_id'     =>$admin->id,
            'price'        =>$validatedData['price'],
            'description'  =>$validatedData['description'],
        ]);

        //.$validatedData['thumbnail_url']->getClientOriginalName()
        $images =array(
          'thumbnail_url' => $validatedData['thumbnail_url'],
          'demo_url'      => $validatedData['demo_url']
        );
        
        
        try{
          

          DB::beginTransaction();
          $basePath = 'product/'. $createdProduct->id .'/';
          $sourcePath     = $basePath .'url_source_'. $validatedData['source_url']->getClientOriginalName();
  
          $imageSourceUrl = ImageUploader::upload($validatedData['source_url'],$sourcePath,'local_storage');
          $imagesUrl      = ImageUploader::multiUploader($images,$basePath,'public_storage');

          
          $updateProduct = $createdProduct->update([
            'thumbnail_url' => $imagesUrl['thumbnail_url'],
            'demo_url'      => $imagesUrl['demo_url'],
            'source_url'    => $sourcePath
          ]);

          if(! $updateProduct){
            DB::rollBack();
            return back()->with('failed' , 'تصاویر اپلود نشد');
          }

          DB::commit();
          return back()->with('success' , 'محصول ایجاد شد');

        }catch(Exception $e){
          
          return back()->with('failed' , $e->getMessage());
        }
    }
}
