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
use App\Models\Status;


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
        
        return $this->setContent('admin.payments.index');
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

        $status = PaymentStatus::where('name','like','PAGO')
                            ->first();
                                       
        $payment->payment_status_id = $status->id;
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

        $data = Payment::select();
        
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
