<?php

namespace App\Http\Controllers\Customer;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Product;
use App\Models\User;
use App\Models\Seller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\OrderLine;
use Syscover\ShoppingCart\Facades\CartProvider;
use Auth;
use Validator;
use File,Croppa;


class ProductsController extends \App\Http\Controllers\Customer\Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function listProducts()
    {
       
        $products = Product::all()
                        ->toArray();
                        
        return view('customer.products.index',compact('products'))->render();
    }

    public function showProduct($id)
    {
        $product = Product::where('id',$id)
                    ->first()
                    ->toArray();
        $productCategory = Category::where('id',$product['category_id'])->first();
        $seller = Seller::where('id',$productCategory->seller_id)->first();
        $sellerCategories = Category::where('seller_id',$seller->id)->pluck('id')->toArray();
        $sellerProducts = Product::whereIn('category_id',$sellerCategories)->pluck('name','id')->toArray();
        
        $subtotal = CartProvider::instance()->subtotal;
        return view('customer.products.productShow',compact('product','subtotal'))->render();
    }
}