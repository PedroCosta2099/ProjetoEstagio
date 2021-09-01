<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orderline;
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
        $paymentsWithOrders = Payment::limit(6)->orderBy('created_at','desc')->with('order','payment_status')->orderBy('id','asc')->get()->toArray();
        $ordersStatusPayments = Order::limit(6)->with('status','payments')->orderBy('created_at','desc')->get()->toArray();
        
        $paymentsWithPendingStatus = Payment::where('payment_status_id',2)->get()->toArray();
        
        $customers = Customer::orderBy('id','asc')->get();
        $sellers = Seller::orderBy('id','asc')->get();
        $orderlines = OrderLine::orderBy('id','asc')->get();
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
        $data = compact(
            'paymentsWithOrders',
            'ordersWithStatus',
            'paymentsWithPendingStatus',
            'ordersStatusPayments',
            'countOrdersSeller_labels',
            'countOrdersSeller_data',
            'countOrdersSeller_colours',
            'topCustomerOrders_labels',
            'topCustomerOrders_data',
            'topCustomerOrders_colours',
            'orderlinesGroupByProduct_labels',
            'orderlinesGroupByProduct_data',
            'orderlinesGroupByProduct_colours'
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
