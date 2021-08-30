<?php

namespace App\Http\Controllers\Admin;

use Response;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Orderline;
use App\Models\Payment;
use App\Models\Status;



class OrdersController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'orders';

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
    public function index() {

        if(Auth::user()->isAdmin())
        {
            $status = Status::orderBy('name','asc')
                            ->pluck('name','id')
                            ->toArray();
        }
        else
        {
        $status = Status::where('seller_id',Auth::user()->seller_id)
                        ->orderBy('name','asc')
                        ->pluck('name','id')
                        ->toArray();
        }
        return $this->setContent('admin.orders.index',compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        return $this->update($request, null);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        $IVA = 0.23;
        $order = Order::findOrfail($id);
        
        $orderlines = OrderLine::where('order_id',$order->id)
                                ->with('product')
                                ->get();
                                  
        $orderTotalPrice = OrderLine::where('order_id',$order->id)
                                    ->sum('total_price');
        $orderVatNotRounded = $orderTotalPrice * $IVA;
        $orderVat = number_format((float)$orderVatNotRounded,2, '.', '');
        $status = Status::where('id',$order->status_id)
                            ->first()
                            ->toArray(); 
    
        $operators = User::orderBy('code', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        $allStatus = Status::orderBy('sort','asc')->pluck('name','id')->toArray();
        $action = 'Editar Pedido';

        $formOptions = array('route' => array('admin.orders.update', $order->id), 'method' => 'PUT', 'class' => 'form-orders');
        
        $data = compact(
            'order',
            'action',
            'orderTotalPrice',
            'orderVat',
            'status',
            'orderlines',
            'formOptions',
            'operators',
            'allStatus'
        );

        return view('admin.orders.edit', $data)->render();
    }

    public function createOrder(Request $request){

        $ids = explode(',', $request->ids);
        $user = Auth::user();
        $order = $user->orders()->create([]);;
        foreach($ids as $id){
           $order->orderCols()->attach($id);
        }
       return Redirect::route('admin.orders.index')->with('success', 'Pedido criado com sucesso.');
    }

    /** 
     * Selected Products 
     */
    public function addOrderLines(Request $request) {
        
        $ids = explode(',', $request->ids);
        
        $operators = User::orderBy('code', 'asc')
                        ->pluck('name', 'id')
                        ->toArray();
        
        $products = Product::whereIn('id',$ids)
                    ->get()
                    ->toArray();
         
        $action = 'Criar Pedido';

        $formOptions = array('route' => array('admin.orderlines.store'), 'method' => 'post', 'class' => 'form-orders');
        //dd($formOptions);
        $data = compact(
            'products',
            'action',
            'formOptions',
            'operators'
        );
       // dd($products);
       
        return view('admin.orders.edit', $data)->render();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        Order::flushCache(Order::CACHE_TAG);
        User::flushCache(User::CACHE_TAG);

        $input = $request->all();
        $order = Order::findOrNew($id);
        $orderlines = OrderLine::where('order_id',$id)
                                ->pluck('id')
                                ->toArray();


        $totalPrices = $request->get('totalPrice');
        $orderLineVats = $request->get('orderlineVat');
        $quantities = $request->get('quantity');
        
        $x=0;
        foreach($orderlines as $orderline){
            $aux = OrderLine::findOrFail($orderline);
            //dd($aux);
            $aux->total_price = $totalPrices[$x];
            $aux->vat = $orderLineVats[$x]; 
            $aux->quantity = $quantities[$x];
            if($aux->quantity == 0)
            {
                OrderLine::destroy($orderline);
            }
            
            $aux->save();
            $x++;
            
        }

        if($orderlines == null)
            {
                Payment::destroy($order->payment_id);
                $this->destroy($id);
            }
        if ($order->validate($input)) {
            $order->fill($input);
            $order->save();

            $payment = Payment::findOrFail($order->payment_id);
            $payment->amount = $order->total_price;
            $payment->save();
            return Redirect::back()->with('success', 'Dados gravados com sucesso.');
        }
        
        return Redirect::back()->withInput()->with('error', $order->errors()->first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        Order::flushCache(Order::CACHE_TAG);
        $order = Order::where('id',$id)->first();
        
        $payment = Payment::where('id',$order->payment_id)
                            ->delete();
        $result = Order::whereId($id)
                            ->delete();
        $orderlines = OrderLine::where('order_id',$id)
                            ->delete();
        
        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o pedido');
        }

        return Redirect::route('admin.orders.index')->with('success', 'Pedido removido com sucesso.');
    }
    
    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request) {

        Order::flushCache(Order::CACHE_TAG);

        $ids = explode(',', $request->ids);
        
        $result = Order::whereIn('id', $ids)
                            ->delete();
        foreach($ids as $id){
        $orderline = OrderLine::where('order_id',$id)
                            ->delete();
        
        }
        if (!$result) {
            return Redirect::back()->with('error', 'Não foi possível remover os registos selecionados');
        }

        return Redirect::back()->with('success', 'Registos selecionados removidos com sucesso.');
    }
    
    /**
     * Loading table data
     * 
     * @return Datatables
     */
    public function datatable(Request $request) {
        
        if(Auth::user()->isAdmin())
        {
            $data = Order::select();
        }
        else{
        $orderIds = [];
        $orderlines = OrderLine::where('seller_id',Auth::user()->seller_id)->get()->toArray();
        foreach($orderlines as $orderline)
        {
            if(!in_array($orderline['order_id'], $orderIds, true)){
                array_push($orderIds,$orderline['order_id']);
            }
            
        }
        $data = Order::whereIn('id',$orderIds);
        }

         //filter status
         if($request->status)
         {
             $data = $data->where('status_id',$request->status);
         }
        return Datatables::of($data)
                ->edit_column('id', function($row) {
                    return view('admin.orders.datatables.id', compact('row'))->render();
                })
                ->edit_column('total_price', function($row) {
                    return view('admin.orders.datatables.price', compact('row'))->render();
                })
                ->edit_column('vat', function($row) {
                    return view('admin.orders.datatables.vat', compact('row'))->render();
                })
                ->edit_column('status_id', function($row) {
                    return view('admin.orders.datatables.status', compact('row'))->render();
                })
                
                ->edit_column('created_at', function($row) {
                    return date('d-m-Y',strtotime($row->created_at));
                })
                ->add_column('select', function($row) {
                    return view('admin.partials.datatables.select', compact('row'))->render();
                })
                ->add_column('actions', function($row) {
                    return view('admin.orders.datatables.actions', compact('row'))->render();
                })
                ->make(true);
    }


    /**
     * Remove the specified resource from storage.
     * GET /admin/services/sort
     *
     * @return Response
     */
    public function sortEdit() {

        $items = Order::remember(config('cache.query_ttl'))
                    ->cacheTags(User::CACHE_TAG)
                    ->ordered()
                    ->get(['id', 'name']);

        $route = route('admin.orders.sort.update');

        return view('admin.partials.modals.sort', compact('items', 'route'))->render();
    }

    /**
     * Update the specified resource order in storage.
     * POST /admin/services/sort
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sortUpdate(Request $request) {

        Order::flushCache(Order::CACHE_TAG);

        try {
            Order::setNewOrder($request->ids);
            $response = [
                'result'  => true,
                'message' => 'Ordenação gravada com sucesso.',
            ];
        } catch (\Exception $e) {
            $response = [
                'result'  => false,
                'message' => 'Erro ao gravar ordenação. ' . $e->getMessage(),
            ];
        }

        return Response::json($response);
    }

}
