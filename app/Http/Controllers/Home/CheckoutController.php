<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CheckoutController extends Controller
{
    public function checkout(){
        $products = json_decode(Cookie::get('basket'),true);
        if((json_decode(Cookie::get('basket'),true)) === null){
             return back()->with('failed','سبد خرید شما خالی است');
        }
        $price = array_sum(array_column($products,'price'));
        return view('frontend.checkout',compact('price' ,'products')); 
    }
}
