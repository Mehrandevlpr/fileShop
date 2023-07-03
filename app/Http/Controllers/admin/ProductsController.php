<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Utilities\ImageUploader;
use Exception;


class ProductsController extends Controller
{

    public function create(){
      $categories = Category::all();
      return view('admin.products.create',compact('categories'));  
    }
    public function all(){
      $products = Product::paginate(10);
      return view('admin.products.all',compact('products'));  
    }

    public function store(StoreRequest $request){

        $validatedData = $request->validated();
        //TODO: update serach by loggin user_id
        $admin = User::where('email','admin@gmail.com')->first();

        $createdProduct = Product::create([
            'title'        =>$validatedData['title'],
            'category_id'  =>$validatedData['category_id'],
            'owner_id'     =>$admin->id,
            'price'        =>$validatedData['price'],
            'description'  =>$validatedData['description'],
        ]);

        if(!$this->uploadImages($createdProduct,$validatedData)){

          return back()->with('failed' , 'تصاویر اپلود نشد');
        
        }

        return back()->with('success' , 'محصول ایجاد شد');
        

    }

    private function uploadImages($createdProduct ,$validatedData){

      try{


        $basePath = 'product/'. $createdProduct->id .'/';
        $sourceImageFullPath = null;
        $data = [];


        if(isset($validatedData['thumbnail_url'])){
          
          $fullPath = $basePath .'thumbnail_url'. urldecode($validatedData['thumbnail_url']->getClientOriginalName());

          ImageUploader::upload($validatedData['thumbnail_url'],$fullPath,'public_storage');

          $data += ['thumbnail_url'=> $fullPath];
        }

        if(isset($validatedData['demo_url'])){

          $fullPath = $basePath .'demo_url'.  urldecode($validatedData['demo_url']->getClientOriginalName());

          ImageUploader::upload($validatedData['demo_url'],$fullPath,'public_storage');

          $data += ['demo_url'=> $fullPath];
        }

        if(isset($validatedData['source_url'])){

          $sourceImageFullPath = $basePath .'source_url'. urldecode($validatedData['source_url']->getClientOriginalName());
         
          ImageUploader::upload($validatedData['source_url'],$sourceImageFullPath,'local_storage');

          $data += ['source_url'=> $sourceImageFullPath];
        }

        $updateProduct = $createdProduct->update($data);
        if(! $updateProduct){
          throw new Exception('تصاویر اپلود نشدند');
        }

        return true;

      }catch(Exception $e){
        
        return false;
      }
    }

    public function download_demo($product_id){

      $product = Product::findOrFail($product_id);

      return  response()->download(public_path($product->demo_url));

    }

    public function download_source($product_id){

      $product = Product::findOrFail($product_id);

      return  response()->download(storage_path('app\\local_storage\\'.$product->source_url));
    }

    public function delete($product_id){

      $product = Product::findOrFail($product_id);

      $product->delete();

      return back()->with('success' , 'محصول مورد نظر حذف شد');

    }

    public function edit($product_id){

      $categories = Category::all();
      $product = Product::findOrFail($product_id);
      return view('admin.products.edit' , compact('product','categories'));  

    }
    
    public function update(UpdateRequest $request ,$category_id){
      
      $validatedData = $request->validated();

      $product = Product::findOrFail($category_id);

      $updateProduct = $product->update([
        'title'        =>$validatedData['title'],
        'category_id'  =>$validatedData['category_id'],
        'price'        =>$validatedData['price'],
        'description'  =>$validatedData['description'],
      ]);
      
      if(!$this->uploadImages($product,$validatedData)){
        return back()->with('failed' , 'تصاویر اپلود نشد');
      }

      return back()->with('success' , 'محصول ایجاد شد');
      
    }
}
