<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Status;
use Illuminate\Http\Request;
use Setting,Auth;

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
    public function index()
    {
        return $this->setContent('customer.about.info');
    }

    public function about()
    {
        $id = Auth::guard('customer')->user()->id;
        $customer = Customer::findOrFail($id);
        $addresses = $customer->addresses()
                              ->get()
                              ->toArray();
        $orders = Order::where('customer_id',$id)
                        ->with('status')
                        ->get();
        $count = 0;
        foreach($addresses as $address)
        {
            if($address['actual_shipment_address'])
                {
                    $count++;
                }

        }  
        
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

        return view('customer.about.info',compact('customer','addresses','orders','orderlines','count'))->render();
    }

    public function orderStatus($id)
    {
    
        $order = Order::where('id',$id)
                        ->first();
                        
        if(Auth::guard('customer')->user()->id == $order['customer_id'])
        {
            $orderStatusId = $order['status_id'];
            $orderStatus = Status::where('id',$orderStatusId)
                                    ->first();
            if($orderStatus->name == "FALHA NA ENTREGA")
            {
                $failed = 1;
            }
            else
            {
                $failed = 0;
                $status = Status::where('name','not like','FALHA NA ENTREGA')
                                ->orderBy('sort','asc')
                                ->get()
                                ->toArray();
            }
            
            return view('customer.about.orderStatus',compact('order','status','orderStatus','failed'))->render();
        }
        else
        {
            return view('errors.403');
        }
    }

}