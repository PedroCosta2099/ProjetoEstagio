<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderLine;
use Setting;

class HomeController extends \App\Http\Controllers\Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'logout']);
    }

    /**
     * Login index controller
     *
     * @return \App\Http\Controllers\type
     */
    public function index() {
        return $this->setContent('customer.test');
    }

    public function about($id)
    {
        $customer = Customer::findOrFail($id);
        $addresses = $customer->addresses()
                              ->get()
                              ->toArray();
        $orders = Order::where('customer_id',$id)
                        ->with('status')
                        ->get();
                        
        
        $orderIds = [];
        foreach($orders as $order)
        {
            if(!in_array($orders,$orderIds, true)){
                array_push($orderIds,$order['id']);
            }
            
        }      
        
        $orderlines = OrderLine::whereIn('order_id',$orderIds)
                                ->with('product','seller','order')
                                ->get();

        return view('customer.info',compact('customer','addresses','orders','orderlines'))->render();
    }

}