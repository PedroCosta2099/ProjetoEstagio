<?php

namespace App\Http\Controllers\Customer;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\OrderLine;
use Syscover\ShoppingCart\Facades\CartProvider;
use Auth;
use Validator;
use File,Croppa;


class SellersController extends \App\Http\Controllers\Customer\Controller {

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
    public function index($name)
    {
        
        $sellerName = str_replace('-',' ',$name);
        $seller = Seller::where('name',$sellerName)->first();
        $categoriesSeller = Category::where('seller_id',$seller['id'])->pluck('id')->toArray();
        $productsSeller = Product::whereIn('category_id',$categoriesSeller)->get();
        $data = compact(
            'seller',
            'categoriesSeller',
            'productsSeller'
        );
        return view('customer.sellers.showSeller',$data);
    }


}