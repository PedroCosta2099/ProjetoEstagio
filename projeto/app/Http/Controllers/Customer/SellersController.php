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
use App\Models\SellerRating;
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
        if(Auth::guard('customer')->check())
        {
            $customerToSellerRating = SellerRating::where('customer_id',Auth::guard('customer')->user()->id)->where('seller_id',$seller->id)->first();
            $countCustomerToSellerRating = count($customerToSellerRating);
        }
        $data = compact(
            'seller',
            'categoriesSeller',
            'productsSeller',
            'customerToSellerRating',
            'countCustomerToSellerRating'
        );
        return view('customer.sellers.showSeller',$data);
    }

    public function sellerRating(Request $request,$id)
    {
        $input = $request->all();
       
        $sellerRating = new SellerRating();
        $sellerRating->customer_id = Auth::guard('customer')->user()->id;
        $sellerRating->seller_id = $id;
        if(array_key_exists('star_5',$input))
        {
            $sellerRating->rating = $input['star_5'];
        }
        elseif(array_key_exists('star_4',$input))
        {
            $sellerRating->rating = $input['star_4'];
        }
        elseif(array_key_exists('star_3',$input))
        {
            $sellerRating->rating = $input['star_3'];
        }
        elseif(array_key_exists('star_2',$input))
        {
            $sellerRating->rating = $input['star_2'];
        }
        elseif(array_key_exists('star_1',$input))
        {
            $sellerRating->rating = $input['star_1'];
        }
        $sellerRating->save();
        return Redirect::back()->with('success', 'Avaliação guardada com sucesso');
        
    }


}