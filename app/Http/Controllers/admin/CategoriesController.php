<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    
    public function create(){
        
        return view('admin.categories.create');
    }

    public function store(StoreRequest $request){

        $validateData = $request->validated();
        
        $created = Category::create( [
                    'title'=> $validateData['title'],
                    'slug' => $validateData['slug']
        ]);
        if(!$created){
           return back()->with('failed','دسته بندی ایجاد نشد');
        }
        return back()->with('success','دسته بندی ایجاد شد');
    }

    public function all(){
        
        $categories = Category::all();
        return view('admin.categories.all',compact('categories'));
    }

    public function product(){
        
        return view('admin.categories.product');
    }

    public function edit($category_id){
        $category = Category::find($category_id);
        return view('admin.categories.edit' ,compact('category'));
    }

    public function update(UpdateRequest $request , $category_id){

        $validateData = $request->validated();

        $category = Category::find($category_id);
        $createdData = $category->update([
            'title'=> $validateData['title'],
            'slug' => $validateData['slug']
        ]);

        if(!$createdData){
            return back()->with('failed' , 'بروز رسانی انجام نشد');
        }
        return back()->with('success' , 'بروز رسانی انجام شد');

    }

    public function delete($category_id){
        
        $category = Category::find($category_id);
        $category->delete();
        return back()->with('success','دسته بندی حذف شد');
    }
}
