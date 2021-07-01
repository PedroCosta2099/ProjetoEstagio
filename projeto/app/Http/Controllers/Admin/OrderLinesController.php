<?php

namespace App\Http\Controllers\Admin;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use App\Models\OrderLine;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Status;
use App\Models\Payment;
use Auth;

class OrderLinesController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'orderlines';

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
        else{
        $status = Status::where('seller_id',Auth::user()->seller_id)
                            ->orderBy('name','asc')
                            ->pluck('name','id')
                            ->toArray();
        }
        return $this->setContent('admin.orderlines.index',compact('status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $orderline = new OrderLine();

        $product = Product::orderBy('id','asc')
                        ->pluck('name','id')
                        ->toArray();
        $status = Status::orderBy('id','asc')
                            ->pluck('name','id')
                            ->toArray();
        $operators = User::orderBy('code', 'asc')
                        ->pluck('name', 'id')
                        ->toArray();

        $action = 'Adicionar Linhas de Pedido';

        $formOptions = array('route' => array('admin.orderlines.store'), 'method' => 'POST', 'class' => 'form-orderlines');

        $data = compact(
            'orderline',
            'action',
            'product',
            'formOptions',
            'status',
            'operators'
        );
        return view('admin.orderlines.edit', $data)->render();
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
        $orderline = OrderLine::findOrfail($id);
        $status = Status::orderBy('id','asc')
                            ->pluck('name','id')
                            ->toArray();
        $product = Product::where('id', $orderline->product_id)
                        ->get()
                        ->toArray();
        $productId = $product[0]['id'];
        $productName = $product[0]['name'];
        $productPrice = $product[0]['price'];
        $totalPriceNotRounded = $product[0]['price']*$orderline->quantity;
        $vatNotRounded = $totalPriceNotRounded*$IVA;
        $orderId = $orderline->order_id;
        $totalPrice = number_format((float)$totalPriceNotRounded, 2, '.', '');
        $vat = number_format((float)$vatNotRounded, 2, '.', '');

        $operators = User::orderBy('code', 'asc')
                ->pluck('name', 'id')
                ->toArray();   
        
        $action = 'Editar Estado';
        
        $formOptions = array('route' => array('admin.orderlines.update', $orderline->id), 'method' => 'PUT', 'class' => 'form-orderlines');

        $data = compact(
            'id',
            'orderId',
            'orderline',
            'action',
            'productName',
            'formOptions',
            'totalPrice',
            'vat',
            'productId',
            'productPrice',
            'status',
            'operators'
        );
        return view('admin.orderlines.edit', $data)->render();
    }

    public function updatePriceVat($id,$quantity)
    { 
        
        $IVA = 0.23;
        $orderline = OrderLine::findOrfail($id);
        $product = Product::where('id', $orderline->product_id)
                                ->get()
                                ->toArray();
        $totalPriceNotRounded = $product[0]['price']*$quantity;
        $totalPrice = number_format((float)$totalPriceNotRounded,2, '.', '');
        $vatNotRounded = $totalPrice*$IVA;
        $vat = number_format((float)$vatNotRounded,2, '.', '');
        
        $data = compact(
            'totalPrice',
            'vat'
        );
            return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        OrderLine::flushCache(OrderLine::CACHE_TAG);
        User::flushCache(User::CACHE_TAG);
        $IVA = 0.23;
        $input = $request->all();

        $orderline = OrderLine::findOrNew($id);       
        $order = Order::findOrFail($orderline->order_id);
        
        if ($orderline->validate($input)) {
            $orderline->fill($input);

            if($orderline->quantity <= 0)
            {
                $this->destroy($orderline->id);
            }
            $orderline->save();
            $orderTotalPrice = Orderline::where('order_id',$orderline->order_id)
                                    ->sum('total_price');
            $order->total_price = $orderTotalPrice;
            $orderVatNotRounded = $orderTotalPrice * $IVA;
            $order->vat = number_format((float)$orderVatNotRounded,2, '.', '');
            $order->save();
            $payment = Payment::findOrFail($order->payment_id);
            $payment->amount = $order->total_price;
            $payment->save();
            
            return Redirect::back()->with('success', 'Dados gravados com sucesso.');
        }
        
        return Redirect::back()->withInput()->with('error', $orderline->errors()->first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        OrderLine::flushCache(OrderLine::CACHE_TAG);

        $result = OrderLine::whereId($id)
                            ->delete();

        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o estado');
        }

        return Redirect::route('admin.orderlines.index')->with('success', 'Estado removido com sucesso.');
    }
    
    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request) {

        OrderLine::flushCache(OrderLine::CACHE_TAG);

        $ids = explode(',', $request->ids);
        
        $result = OrderLine::whereIn('id', $ids)
                            ->delete();
        
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
            $data = OrderLine::select();
        }
        else{
        $data = OrderLine::where('seller_id',Auth::user()->seller_id);
        }

         //filter status
         if($request->status)
         {
             $data = $data->where('status_id',$request->status);
         }
        return Datatables::of($data)
                ->edit_column('name', function($row) {
                    return view('admin.orderlines.datatables.name', compact('row'))->render();
                })
                ->edit_column('seller_id', function($row) {
                    return view('admin.orderlines.datatables.seller', compact('row'))->render();
                })
                ->edit_column('total_price', function($row) {
                    return view('admin.orderlines.datatables.price', compact('row'))->render();
                })
                ->edit_column('vat', function($row) {
                    return view('admin.orderlines.datatables.vat', compact('row'))->render();
                })
                ->edit_column('status_id', function($row) {
                    return view('admin.orderlines.datatables.status', compact('row'))->render();
                })
                ->edit_column('order_id', function($row) {
                    return view('admin.orderlines.datatables.order', compact('row'))->render();
                })
                ->add_column('select', function($row) {
                    return view('admin.partials.datatables.select', compact('row'))->render();
                })
                ->add_column('actions', function($row) {
                    return view('admin.orderlines.datatables.actions', compact('row'))->render();
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

        $items = OrderLine::remember(config('cache.query_ttl'))
                    ->cacheTags(User::CACHE_TAG)
                    ->ordered()
                    ->get(['id', 'name']);

        $route = route('admin.orderlines.sort.update');

        return view('admin.partials.modals.sort_status', compact('items', 'route'))->render();
    }

    /**
     * Update the specified resource order in storage.
     * POST /admin/services/sort
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sortUpdate(Request $request) {

        OrderLine::flushCache(OrderLine::CACHE_TAG);

        try {
            OrderLine::setNewOrder($request->ids);
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
