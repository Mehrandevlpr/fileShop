<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function singlePage($product_id){

        $product = Product::findOrFail($product_id);
        return view('frontend.products.single',compact('product'));
    }
}
