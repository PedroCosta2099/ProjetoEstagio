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
use Auth;
use Validator;
use File,Croppa;
use Syscover\ShoppingCart\Facades\CartProvider;
use Syscover\ShoppingCart\Item;
use Syscover\ShoppingCart\TaxRule;

class CartController extends \App\Http\Controllers\Customer\Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
        
   
    public function index()
    {
        $product = Product::first();
        return view('customer.cart.index',compact('product'))->render();
    }

    public function addToCart($id)
    {
        $product = Product::where('id',$id)
                            ->first()
                            ->toArray();
        
        CartProvider::instance()->add(new Item($product['id'],$product['name'],1,$product['price'],0,true,[],['image' => $product['filepath']]));
        return Redirect::back();
    }

    public function cartItems()
    {
        $productIds = [];
        $cartProducts = CartProvider::instance()->getCartItems();
        
        foreach($cartProducts as $cartProduct)
        {
            
        $product = Product::where('id',$cartProduct->id)
                                    ->get()
                                    ->toArray();
                                    
         array_push($productIds,$product[0]['id']);
        }
        
        $products = Product::whereIn('id',$productIds)->get()->toArray();
        $orderTotal = CartProvider::instance()->getTotal();
        
        return view('customer.cart.index',compact('cartProducts','products','orderTotal'))->render();
    }
}