<?php

namespace App\Http\Controllers\Customer;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Product;
use App\Models\User;
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
        $subtotal = CartProvider::instance()->subtotal;
        return view('customer.products.productShow',compact('product','subtotal'))->render();
    }
}