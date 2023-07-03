<?php


namespace App\Http\Controllers\Filters;

use App\Models\Product;

use function PHPSTORM_META\map;

class OrderbyFilter{

    public function newest(){
        return Product::orderBy('created_at', 'desc')->get();
    }
    public function oldest(){
        return Product::all();
    }
    public function popular(){
        return Product::all();
    }
    public function betweenPrice($interspace){


        $matches = array();

        preg_match_all('/[0-9]+/', $interspace, $matches );
        $minAndMax=array_map(function($index){
            return $index*300000;
        },$matches[0]);

        return Product::whereBetween('price', [$minAndMax[0], $minAndMax[1]])->get();
        
    }
    public function desc(){
        return Product::orderBy('price', 'desc')->get();
    }
    public function asc(){
        return Product::orderBy('price', 'asc')->get();
    }
    // public function newest(){

    // }
    // public function newest(){

    // }
    // public function newest(){

    // }
    // public function newest(){

    // }
    // public function newest(){

    // }
}