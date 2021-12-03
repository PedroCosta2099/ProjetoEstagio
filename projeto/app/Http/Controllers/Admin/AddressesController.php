<?php

namespace App\Http\Controllers\Admin;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Address;
use App\Models\User;
use App\Models\Customer;
use DB,Lang,Input;



class AddressesController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'addresses';

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
        
        return $this->setContent('admin.addresses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $address = new Address();
        
        $countCustomersAddress = count($address->customers);
       
        $operators = User::orderBy('code', 'asc')
                        ->pluck('name', 'id')
                        ->toArray();

        $customers = Customer::select(DB::raw('CONCAT(name," - ",nif) AS name_nif,id'))->pluck('name_nif','id');
        
        $action = 'Adicionar Endereço';

        $formOptions = array('route' => array('admin.addresses.store'), 'method' => 'POST', 'class' => 'form-addresses');

        $data = compact(
            'address',
            'action',
            'formOptions',
            'operators',
            'customers',
            'countCustomersAddress'
        );

        return view('admin.addresses.edit', $data)->render();
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

        $address = Address::with('customers')->findOrfail($id);
        $countCustomersAddress = count($address->customers);
        $customerAddress = $address->customers->toArray();
        
        
        $operators = User::orderBy('code', 'asc')
                ->pluck('name', 'id')
                ->toArray();

        $customers = Customer::select(DB::raw('CONCAT(name," - ",nif) AS name_nif,id'))->pluck('name_nif','id');
        $action = 'Editar Endereço';

        $formOptions = array('route' => array('admin.addresses.update', $address->id), 'method' => 'PUT', 'class' => 'form-addresses');

        $data = compact(
            'address',
            'action',
            'formOptions',
            'operators',
            'customers',
            'customerAddress',
            'countCustomersAddress'
            
        );

        return view('admin.addresses.edit', $data)->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        Address::flushCache(Address::CACHE_TAG);
        User::flushCache(User::CACHE_TAG);

        
        $input = $request->all();
       
        $address = Address::with('customers')->findOrNew($id);
        if(!array_key_exists('customer',$input))
        {
            $addressCustomer = Address::with('customers')->findOrfail($id);
            $countCustomersAddress = count($addressCustomer->customers);
            $customerAddressInfo = $address->customers->toArray();
            $customer = Customer::where('id',$customerAddressInfo[0]['id'])->with('addresses')->first();
        }
        else{
        $customer = Customer::where('id',$input['customer'])->with('addresses')->first();
        }
        foreach($customer->addresses as $customerAddress)
        {
            if(array_key_exists('actual_shipment_address',$input))
            {
                $customerAddress->actual_shipment_address = 0;
            }
            if(array_key_exists('actual_billing_address',$input))
            {
                $customerAddress->actual_billing_address = 0;
            }
            $customerAddress->save();
        }

        if(!array_key_exists('actual_shipment_address',$input))
        {
            $address->actual_shipment_address = 0;
            $address->save();
        }
        else
        {
            $address->actual_shipment_address = 1;
            $address->save();
        }
        if(!array_key_exists('actual_billing_address',$input))
        {
            $address->actual_billing_address = 0;
            $address->save();
        }
        else
        {
            $address->actual_billing_address = 1;
            $address->save();
        }
        
        if(!array_key_exists('shipment_address',$input))
        {
            $address->shipment_address = 0;
            $address->save();
        }
        else
        {
            $address->shipment_address = 1;
            $address->save();
        }
        if(!array_key_exists('billing_address',$input))
        {
            $address->billing_address = 0;
            $address->save();
        }
        else
        {
            $address->billing_address = 1;
            $address->save();
        }
        if($address->billing_address == 0 && $address->actual_billing_address == 1)
        {
            $address->actual_billing_address = 0;
            $address->save();
            return Redirect::back()->with('error',Lang::get('validation.billing_address'));
        }
        if($address->actual_shipment_address == 1 && $address->shipment_address == 0 && $address->billing_address == 0)
        {
            $address->actual_shipment_address = 0;
            $address->save();
            return Redirect::back()->with('error',Lang::get('validation.shipment_address'));
        }
        if ($address->validate($input)) {
            $address->fill($input);

            $address->save();
            $address->customers()->detach();
            $address->customers()->attach($customer);
            return Redirect::back()->with('success', 'Dados gravados com sucesso.');
        }
        
        return Redirect::back()->withInput()->with('error', $address->errors()->first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        Address::flushCache(Address::CACHE_TAG);

        $result = Address::whereId($id)
                            ->delete();

        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o estado');
        }

        return Redirect::route('admin.addresses.index')->with('success', 'Estado removido com sucesso.');
    }
    
    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request) {

        Address::flushCache(Address::CACHE_TAG);

        $ids = explode(',', $request->ids);
        
        $result = Address::whereIn('id', $ids)
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

        $data = Address::select();
       
          
        return Datatables::of($data)
                ->edit_column('address', function($row) {
                    return view('admin.addresses.datatables.address', compact('row'))->render();
                })
                ->edit_column('customer', function($row) {
                    return view('admin.addresses.datatables.customer', compact('row'))->render();
                })
                ->add_column('select', function($row) {
                    return view('admin.partials.datatables.select', compact('row'))->render();
                })
                ->add_column('actions', function($row) {
                    return view('admin.addresses.datatables.actions', compact('row'))->render();
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

        $items = Address::remember(config('cache.query_ttl'))
                    ->cacheTags(User::CACHE_TAG)
                    ->ordered()
                    ->get(['id', 'city']);

        $route = route('admin.addresses.sort.update');

        return view('admin.partials.modals.sort_addresses', compact('items', 'route'))->render();
    }

    /**
     * Update the specified resource order in storage.
     * POST /admin/services/sort
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sortUpdate(Request $request) {

        Address::flushCache(Address::CACHE_TAG);

        try {
            Address::setNewOrder($request->ids);
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
