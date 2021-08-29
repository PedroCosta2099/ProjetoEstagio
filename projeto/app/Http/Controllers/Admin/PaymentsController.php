<?php

namespace App\Http\Controllers\Admin;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use App\Models\User;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\PaymentStatus;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Status;
use Auth;

class PaymentsController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'payments';

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
        
            
            $paymentType = PaymentType::orderBy('name','asc')
                                    ->pluck('name','id')
                                    ->toArray();
          
            $paymentStatus = PaymentStatus::orderBy('name','asc')
                                        ->pluck('name','id')
                                        ->toArray();
           
        return $this->setContent('admin.payments.index',compact('paymentType','paymentStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $payment = new Payment();

        $payment_types = PaymentType::orderBy('id', 'asc')
                            ->pluck('name', 'id')
                            ->toArray();

        $payment_status = PaymentStatus::orderBy('id','asc')
                            ->pluck('name','id')
                            ->toArray();

        $operators = User::orderBy('code', 'asc')
                        ->pluck('name', 'id')
                        ->toArray();

        $action = 'Adicionar Pagamento';

        $formOptions = array('route' => array('admin.payments.store'), 'method' => 'POST', 'class' => 'form-status');

        $data = compact(
            'payment',
            'action',
            'payment_types',
            'payment_status',
            'formOptions',
            'operators'
        );

        return view('admin.payments.edit', $data)->render();
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

        $payment = Payment::findOrfail($id);

        $payment_types = PaymentType::orderBy('id', 'asc')
                            ->pluck('name', 'id')
                            ->toArray();

        $payment_status = PaymentStatus::orderBy('id','asc')
                            ->pluck('name','id')
                            ->toArray();
        
        $operators = User::orderBy('code', 'asc')
                ->pluck('name', 'id')
                ->toArray();

        $action = 'Editar Pagamento';

        $formOptions = array('route' => array('admin.payments.update', $payment->id), 'method' => 'PUT', 'class' => 'form-status');
        
        $data = compact(
            'payment',
            'action',
            'payment_types',
            'payment_status',
            'formOptions',
            'operators'
        );

        return view('admin.payments.edit', $data)->render();
    }

    public function payed($id){

        $payment = Payment::where('id',$id)
                            ->first();
        $order = Order::where('payment_id',$id)->first();
        $orderlines = OrderLine::where('order_id',$order->id)->get();
        
        $orderStatus = Status::where('name','like','EM PREPARAÇÃO')->first();
        foreach($orderlines as $orderline)
        {
            $orderline['status_id'] = $orderStatus->id;
            $orderline->save();
        }
        $order->status_id = $orderStatus->id;
        $status = PaymentStatus::where('name','like','PAGO')
                            ->first();
        $payment->paid_at = date('Y-m-d H:i:s');                               
        $payment->payment_status_id = $status->id;
        $order->save();
        $payment->save();
        return Redirect::back()->with('success', 'Dados gravados com sucesso.');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        Payment::flushCache(Payment::CACHE_TAG);
        User::flushCache(User::CACHE_TAG);

        $input = $request->all();

        $payment = Payment::findOrNew($id);
        $order = Order::where('payment_id',$id)->first();
        $orderlines = OrderLine::where('order_id',$order->id)->get();
        $status = Status::where('name','like','EM PREPARAÇÃO')->first();
        foreach($orderlines as $orderline)
        {
            $orderline['status_id'] = $status->id;
            $orderline->save();
        }
        $paymentStatus = PaymentStatus::where('id',$input['payment_status_id'])
                                        ->first();
                                        
        
        if($paymentStatus['name'] == "PAGO")
        {
            $order->status_id = $status->id;
            $payment->paid_at = date('Y-m-d H:i:s');
        }
        else
        {
            $status = Status::where('name','like','PENDENTE')->first();
            $order->status_id = $status->id;
            foreach($orderlines as $orderline)
        {
            $orderline['status_id'] = $status->id;
            $orderline->save();
        }
            $payment->paid_at = null;
        }
        $order->save();
        if ($payment->validate($input)) {
            $payment->fill($input);
            $payment->save();

            return Redirect::back()->with('success', 'Dados gravados com sucesso.');
        }
        
        return Redirect::back()->withInput()->with('error', $payment->errors()->first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        Payment::flushCache(Payment::CACHE_TAG);

        $result = Payment::whereId($id)
                            ->delete();

        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o estado');
        }

        return Redirect::route('admin.payments.index')->with('success', 'Estado removido com sucesso.');
    }
    
    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request) {

        Payment::flushCache(Payment::CACHE_TAG);

        $ids = explode(',', $request->ids);
        
        $result = Payment::whereIn('id', $ids)
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
        $data = Payment::select();
        }
        else{
            $orderIds = [];
            $payments = [];
            $orderlines = OrderLine::where('seller_id',Auth::user()->seller_id)
                                    ->get()
                                    ->toArray();
            foreach($orderlines as $orderline)
            {
                if(!in_array($orderline['order_id'], $orderIds, true)){
                    array_push($orderIds,$orderline['order_id']);
                }
                
            }
            $orders = Order::whereIn('id',$orderIds)
                            ->get()
                            ->toArray();
            foreach($orders as $order)
            {
                if(!in_array($order['payment_id'], $payments, true)){
                    array_push($payments,$order['payment_id']);
                }
            }
            $data = Payment::whereIn('id',$payments);
            }
             //filter payment type
        if($request->paymentType)
        {
            $data = $data->where('payment_type_id',$request->paymentType);
        }
        if($request->paymentStatus)
        {
            $data = $data->where('payment_status_id',$request->paymentStatus);
        }
        return Datatables::of($data)
                ->edit_column('payment_type_id', function($row) {
                    return view('admin.payments.datatables.payment_type', compact('row'))->render();
                })
                ->edit_column('payment_status_id', function($row) {
                    return view('admin.payments.datatables.payment_status', compact('row'))->render();
                })
                ->edit_column('entity', function($row) {
                    return view('admin.payments.datatables.entity', compact('row'))->render();
                })
                ->edit_column('reference', function($row) {
                    return view('admin.payments.datatables.reference', compact('row'))->render();
                })
                ->edit_column('phone_number', function($row) {
                    return view('admin.payments.datatables.phone', compact('row'))->render();
                })
                ->edit_column('order_id', function($row) {
                    return view('admin.payments.datatables.order', compact('row'))->render();
                })
                ->edit_column('id', function($row) {
                    return view('admin.payments.datatables.id', compact('row'))->render();
                })
                ->edit_column('pay', function($row) {
                    return view('admin.payments.datatables.pay', compact('row'))->render();
                })
                ->edit_column('amount', function($row) {
                    return view('admin.payments.datatables.amount', compact('row'))->render();
                })
                ->edit_column('paid_at', function($row) {
                    if($row->paid_at == NULL)
                        return;
                    else
                    {
                    return date('d-m-Y h:s',strtotime($row->paid_at));
                }
                })
                ->add_column('select', function($row) {
                    return view('admin.partials.datatables.select', compact('row'))->render();
                })
                ->add_column('actions', function($row) {
                    return view('admin.payments.datatables.actions', compact('row'))->render();
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

        $items = Payment::remember(config('cache.query_ttl'))
                    ->cacheTags(User::CACHE_TAG)
                    ->ordered()
                    ->get(['id']);

        $route = route('admin.payments.sort.update');

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

        Payment::flushCache(Payment::CACHE_TAG);

        try {
            Payment::setNewOrder($request->ids);
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
