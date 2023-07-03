<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request){

        
        $products = null;
        $categories = Category::all();

        if($request->has('search')){
            $products = Product::where('title','LIKE','%'.$request->input('search').'%')->get();
        }
        if(isset($request->filter,$request->action,$request->interspace)){
            $products = $this->findFilter($request->filter,$request->action,$request->interspace)??Product::all();
        }
        if($products === null ){
            $products = Product::all();
        }
        return view('frontend.products.all' ,compact('categories' ,'products'));
    }


    public function findFilter(string $className=null,string $method=null,$interspace=null) {

        $baseNameSpace = "App\\Http\\Controllers\\Filters\\" ;
        $className = $baseNameSpace . (ucfirst($className) ."Filter");
        if(!class_exists($className)){
            return null;
        }

        $classObject = new $className;

        if(!method_exists($classObject,$method)){
            return null;
        }

        return $classObject->{$method}($interspace);
    }
}
