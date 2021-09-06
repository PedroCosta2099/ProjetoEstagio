<?php

namespace App\Http\Controllers\Admin;

use App\Models\OrderLine;
use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Date, Session, DB, Setting, Response, File;

class HomeController extends \App\Http\Controllers\Admin\Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $orders = Order::orderBy('id','asc')->get();
        $ordersWithStatus = Order::with('status')->orderBy('id','asc')->get();
        $paymentsWithOrders = Payment::limit(6)->orderBy('created_at','desc')->with('order','payment_status','payment_type')->orderBy('id','asc')->get()->toArray();
   
        $ordersStatusPayments = Order::limit(6)->with('status','payments')->orderBy('created_at','desc')->get()->toArray();
        $countOrdersStatusPayments = Order::with('status','payments')->where('status_id','!=',2)->orderBy('created_at','desc')->get()->toArray();
        
        $paymentsWithPendingStatus = Payment::where('payment_status_id',2)->with('order','payment_status','payment_type')->limit(6)->orderBy('created_at','desc')->get();
        
        $customers = Customer::orderBy('id','asc')->get();
        $sellers = Seller::orderBy('id','asc')->get();
        $orderlines = OrderLine::orderBy('id','asc')->get();
        /**
         * System Admin Statistics
         */
        if(Auth::user()->isAdmin())
        {
            
            /**
             * Count orders per customer
             */
            $customerOrders = [];
        foreach($customers as $customer)
        {
            
            $count = count($orders->where('customer_id',$customer->id)->where('created_at','>',Date::now()->subDays(30)));
            
            array_push($customerOrders,['customer'=>[$customer->name],'count'=>$count]);
           
        }
        do{
            $swapped = false;
        for($i=0;$i<count($customerOrders)-1;$i++)
        {
            
            if($customerOrders[$i]['count'] > $customerOrders[$i+1]['count'])
            {
                $auxCount = $customerOrders[$i+1]['count'];
                $auxCustomer = $customerOrders[$i+1]['customer'];
                $customerOrders[$i+1]['count'] = $customerOrders[$i]['count'];
                $customerOrders[$i+1]['customer'] = $customerOrders[$i]['customer'];
                $customerOrders[$i]['count'] = $auxCount;
                $customerOrders[$i]['customer'] = $auxCustomer ;
                $swapped = true;
            }
        }
        }while($swapped);
        
        $topCustomerOrders = array_slice($customerOrders,count($customerOrders)-6,count($customerOrders));
        
        $topCustomerOrders_labels=[];
        foreach($topCustomerOrders as $key)
        {
            if($key['count'] == 0)
            {

            }
            else
            {
                array_push($topCustomerOrders_labels,$key['customer']);
            }
        }

        $topCustomerOrders_data=[];
        foreach($topCustomerOrders as $key)
        {
            if($key['count'] == 0)
            {

            }
            else
            {
                array_push($topCustomerOrders_data,$key['count']);
            }
        }

        $topCustomerOrders_colours = ['#229954','#ffcc00','#000066','#af0000','#D35400','#AF7AC5'];
       
        /**
         * Order count per seller
         */
        
        $countOrdersSeller = OrderLine::where('created_at','>',Date::now()->subDays(30))->groupBy('order_id')->get();
        $c=[];
        foreach($sellers as $seller)
        {
            $count = count($countOrdersSeller->where('seller_id',$seller->id));
            array_push($c,['seller'=>$seller->name,'count'=>$count]);
        }
        
        /**Bubble Sort to reorder $c */
        do{
            $swapped = false;
        for($i=0;$i<count($c)-1;$i++)
        {
            
            if($c[$i]['count'] > $c[$i+1]['count'])
            {
                $auxCount = $c[$i+1]['count'];
                $auxSeller = $c[$i+1]['seller'];
                $c[$i+1]['count'] = $c[$i]['count'];
                $c[$i+1]['seller'] = $c[$i]['seller'];
                $c[$i]['count'] = $auxCount;
                $c[$i]['seller'] = $auxSeller ;
                $swapped = true;
            }
        }
        }while($swapped);
        /**just top 6 sellers  */
        $d = array_slice($c,count($c)-6,count($c));
        /**labels */
        $countOrdersSeller_labels=[];
       foreach($d as $key)
       {
           
        if($key['count'] == 0)
        {
            
        }
        else{
           array_push($countOrdersSeller_labels,$key['seller']);}
       }
        /**data */
        $countOrdersSeller_data = [];

        foreach($d as $key)
       {
           if($key['count'] == 0)
           {
              
           }
           else{
           array_push($countOrdersSeller_data,$key['count']);}
       }
       
        $countOrdersSeller_colours = ['#229954','#ffcc00','#000066','#af0000','#D35400','#AF7AC5'];
        
        /**
         * Count best selling products
         */
        $products = Product::orderBy('id','asc')->get();
        $orderlinesGroupByProduct = OrderLine::with('product')->groupBy('product_id')->select('product_id',DB::raw('count(order_lines.quantity) as total'))->get()->toArray();
        
        do{
            $swapped = false;
        for($i=0;$i<count($orderlinesGroupByProduct)-1;$i++)
        {
            
            if($orderlinesGroupByProduct[$i]['total'] > $orderlinesGroupByProduct[$i+1]['total'])
            {
                $auxTotal = $orderlinesGroupByProduct[$i+1]['total'];
                $auxProduct = $orderlinesGroupByProduct[$i+1]['product'];
                $orderlinesGroupByProduct[$i+1]['total'] = $orderlinesGroupByProduct[$i]['total'];
                $orderlinesGroupByProduct[$i+1]['product'] = $orderlinesGroupByProduct[$i]['product'];
                $orderlinesGroupByProduct[$i]['total'] = $auxTotal;
                $orderlinesGroupByProduct[$i]['product'] = $auxProduct ;
                $swapped = true;
            }
        }
        }while($swapped);
        /**just top 6 sellers  */
        $d = array_slice($orderlinesGroupByProduct,count($orderlinesGroupByProduct)-6,count($orderlinesGroupByProduct));
        /**labels */
        $orderlinesGroupByProduct_labels=[];
       foreach($d as $key)
       {
           
        if($key['total'] == 0)
        {
            
        }
        else{
           array_push($orderlinesGroupByProduct_labels,Product::where('id',$key['product'])->first()->name);}
       }
        /**data */
        $orderlinesGroupByProduct_data = [];

        foreach($d as $key)
       {
           if($key['total'] == 0)
           {
              
           }
           else{
           array_push($orderlinesGroupByProduct_data,$key['total']);}
       }
       
        $orderlinesGroupByProduct_colours = ['#229954','#ffcc00','#000066','#af0000','#D35400','#AF7AC5'];
        }
        else
        {

        
         /**
         * Seller Admin Statistics
         */
        $ordersSeller = Order::where('created_at','>',Date::now()->subDays(30))
                                    ->where('seller_id',Auth::user()->seller_id)
                                    ->groupBy('id')
                                    ->get();
        $orderIds = [];
        foreach($ordersSeller as $order)
        {
            array_push($orderIds,$order->id);
        }
        
        $ordersStatusPayments = Order::limit(6)->whereIn('id',$orderIds)->with('status','payments')->orderBy('created_at','desc')->get()->toArray();
        $ordersStatusPayments2 = Order::whereIn('id',$orderIds)->with('status','payments')->orderBy('created_at','desc')->get()->toArray();
        $orderPaymentsIds = [];
        foreach($ordersStatusPayments2 as $order)
        {
            array_push($orderPaymentsIds,$order['payment_id']);
        }
        
        $paymentsWithOrders = Payment::whereIn('id',$orderPaymentsIds)->orderBy('created_at','desc')->with('order','payment_status','payment_type')->orderBy('id','asc')->get()->toArray();
        
        $ordersWithStatus = Order::with('status')->whereIn('id',$orderIds)->orderBy('id','asc')->get();
        $paymentsWithPendingStatus = Payment::where('payment_status_id',2)->whereIn('id',$orderPaymentsIds)->with('order','payment_status','payment_type')->limit(6)->orderBy('created_at','desc')->get();
       
        $months = [];
        for($i = 5; $i>=0;$i--)
        {
            $month = Date::today()->startOfMonth()->subMonth($i);
            $number = Date::parse($month)->month;
            array_push($months,$number); 
        }
     
        $ordersMonth = [];
        foreach($months as $month)
        {
            
                $orders = Order::whereMonth('created_at',$month)->where('seller_id',Auth::user()->seller_id)->get();
                $count = count($orders);
                
                array_push($ordersMonth,['month'=>$month,'count'=>$count]);
        }
        $ordersMonthData = [];
        foreach($ordersMonth as $key)
        {
           array_push($ordersMonthData,$key['count']);
        }
       
        
    }
        $data = compact(
            'paymentsWithOrders',
            'ordersWithStatus',
            'paymentsWithPendingStatus',
            'ordersStatusPayments',
            'countOrdersStatusPayments',
            'countOrdersSeller_labels',
            'countOrdersSeller_data',
            'countOrdersSeller_colours',
            'topCustomerOrders_labels',
            'topCustomerOrders_data',
            'topCustomerOrders_colours',
            'orderlinesGroupByProduct_labels',
            'orderlinesGroupByProduct_data',
            'orderlinesGroupByProduct_colours',
            'ordersMonthData'
        );
        
                return $this->setContent('admin.dashboard.index',$data);
    }

    /**
     * Logout a remote login
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function remoteLogout(Request $request) {

        if(Session::has('source_user_id')) {
            $user = User::findOrFail(Session::get('source_user_id'));

            Session::forget('source_user_id');

            $result = Auth::login($user);

            return Redirect::route('admin.dashboard')->with('success', 'Sessão iniciada com sucesso.');
        }

        return Redirect::back()->with('error', 'Nenhuma sessão remota iniciada.');
    }

    /**
     * Show denied page
     *
     * @return \Illuminate\Http\Response
     */
    public function denied(Request $request) {
        return $this->setContent('admin.partials.denied');
    }
}
