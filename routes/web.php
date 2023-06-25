<?php

use App\Models\Category;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\CategoriesController;
use App\Http\Controllers\admin\ProductsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return dd(bcrypt('pass11word'));
});



// Route::get('products/all', function () {
  
//     //$could be a variable ==> Category::create( [
//     //     'title'=> 'hello',
//     //     'slug' => 'world'
//     // ]);
//     // dd($var);
//     // dd(Category::where('slug', 'war'));//->update(array('slug' =>'war')));
//     dd(Category::all());
// });



Route::prefix('admin')->group(function () {

        Route::prefix('categories')->group(function () {
            
            Route::get('create',[CategoriesController::class ,'create'])->name('admin.categories.create');
            Route::post('create',[CategoriesController::class ,'store'])->name('admin.categories.store');
            Route::get('',[CategoriesController::class ,'all'])->name('admin.categories.all');
            Route::get('products',[CategoriesController::class ,'product'])->name('admin.categories.products');
            Route::delete('{category_id}/delete',[CategoriesController::class ,'delete'])->name('admin.categories.delete');
            Route::get('{category_id}/edit',[CategoriesController::class ,'edit'])->name('admin.categories.edit');
            Route::put('{category_id}/update',[CategoriesController::class ,'update'])->name('admin.categories.update');
            
        });

        Route::prefix('products')->group(function () {

            Route::get('create',[ProductsController::class ,'create'])->name('admin.products.create');
            Route::post('store',[ProductsController::class ,'store'])->name('admin.products.store');
            Route::get('',[ProductsController::class ,'all'])->name('admin.products.all');
            Route::delete('{product_id}/delete',[ProductsController::class ,'delete'])->name('admin.products.delete');
            Route::get('{product_id}/download/demo_url',[ProductsController::class ,'download_demo'])->name('admin.products.download.demo');
            Route::get('{product_id}/download/source_url',[ProductsController::class ,'download_source'])->name('admin.products.download.source');
            Route::get('{product_id}/edit',[ProductsController::class ,'edit'])->name('admin.products.edit');
            Route::put('{product_id}/update',[ProductsController::class ,'update'])->name('admin.products.update');

        });
});
    