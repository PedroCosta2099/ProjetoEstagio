<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orderline;
use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
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
        $customers = Customer::orderBy('id','asc')->get();
        $sellers = Seller::orderBy('id','asc')->get();
        $orderlines = OrderLine::orderBy('id','asc')->get();
        $customerOrders = [];
        foreach($customers as $customer)
        {
            $count = count($orders->where('customer_id',$customer->id)->where('created_at','>',Date::now()->subDays(30)));
            
            array_push($customerOrders,[$customer->id,$count]);
           
        }
        /*$countOrdersSeller = DB::table('sellers')
        ->join('order_lines', 'sellers.id', '=', 'order_lines.seller_id')
        ->join('orders', 'order_lines.order_id', '=', 'orders.id')
        ->select('sellers.name', DB::raw('count(orders.id) as total'))
        ->whereDate('orders.created_at', '>', Date::now()->subDays(30))
        ->groupBy('sellers.name')
        ->pluck('total', 'sellers.name')->all();*/
        if(Auth::user()->isAdmin())
        {
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
        }
        
        $data = compact(
            'countOrdersSeller_labels',
            'countOrdersSeller_data',
            'countOrdersSeller_colours'
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
