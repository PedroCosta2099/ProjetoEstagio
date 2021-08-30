<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderLine;
use Setting,Auth,Redirect;
use Illuminate\Http\Request;

class HomeController extends \App\Http\Controllers\Controller
{

    /**
     * The layout that should be used for responses
     *
     * @var string
     */
    protected $layout = 'customer.layouts.master';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Login index controller
     *
     * @return \App\Http\Controllers\type
     */
    public function index(Request $request) {

    
        $this->sellerAlgorithm();
        $sellers = $this->sellers;
        $count = $this->countAtualSellerIds;
        $productsWithDiscount = Product::where('discount','>',0)->take(9)->get();
        
        if($request->orderBy)
        {
            dd($request->orderBy);
        }
        return $this->setContent('customer.index', compact('sellers','count','productsWithDiscount')); 
    }

    /*public function filterSellers($value)
    {
        
        if($value == 1)
        {
            $count = 0;
            $sellers = Seller::orderBy('rating','desc')->take(8)->get();
        }
        if($value == 2)
        {
            $count = 0;
            $sellers = Seller::orderBy('minimum_delivery_time','asc')->take(8)->get();
        }
        if($value == 3)
        {
            $count = 0;
            $sellers = Seller::orderBy('delivery_fee','asc')->take(8)->get();
        }

        $data = compact('sellers','count');
        return view('customer.index',$data);

    }*/

    public function sellerAlgorithm()
    {
        if(Auth::guard('customer')->check())
        {
        $orders = Order::where('customer_id',Auth::guard('customer')->user()->id)
                        ->orderBy('created_at','desc')
                        ->get()
                        ->toArray();
        $orderIds = [];
        foreach($orders as $order)
        {
            if(!in_array($order['id'], $orderIds, true)){
                array_push($orderIds,$order['id']);
            }
        }

        $orderlines = OrderLine::whereIn('order_id',$orderIds)
                                ->get()
                                ->toArray();
        $sellerIds = [];

        foreach($orderlines as $orderline)
        {
            if(!in_array($orderline['seller_id'], $sellerIds, true)){
                array_push($sellerIds,$orderline['seller_id']);
            }
        }
        
        $countAtualSellerIds = count($sellerIds);
        
        if($countAtualSellerIds > 8)
        {
            
            $sellers = Seller::whereIn('id',$sellerIds)
                                ->take(8)
                                ->get();                    
            
        }
        else
        {
            $sellers = Seller::whereNotIn('id',$sellerIds)
                                ->take(8-$countAtualSellerIds)
                                ->get();
                                

            foreach($sellers as $seller)
            {
                if(!in_array($seller['id'], $sellerIds, true)){
                    array_push($sellerIds,$seller['id']);
                }
            }
           
            $finalSellers=[];
            foreach($sellerIds as $seller) 
            {
            if(!in_array($seller, $finalSellers, true)){
                    $finalSeller = Seller::where('id',$seller)->first();         
                     array_push($finalSellers,$finalSeller);
                 }
            }
          
            $this->sellers = $finalSellers;
            $this->countAtualSellerIds = $countAtualSellerIds;
            return response()->json([$this->sellers,$this->countAtualSellerIds]);
                              
        }
        
        if($countAtualSellerIds > 8)
        {
            
            $this->sellers = $sellers;
            $this->countAtualSellerIds = $countAtualSellerIds;
            return response()->json([$this->sellers,$this->countAtualSellerIds]);
        }

        else
        {
            
            $this->sellers = $finalSellers;
            $this->countAtualSellerIds = $countAtualSellerIds;
            return response()->json([$this->sellers,$this->countAtualSellerIds]);
        }
    }
    else
    {
        $sellers = Seller::take(8)
                           ->get();
        $countAtualSellerIds = count($sellers);                 
        $this->sellers = $sellers;
        $this->countAtualSellerIds = $countAtualSellerIds;
        return response()->json([$this->sellers,$this->countAtualSellerIds]);
    }
    }

}