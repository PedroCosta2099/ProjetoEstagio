<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use Auth, App, Html, Session,File,Croppa;

class CustomersController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'customers';

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

     
        $status = array(
            '1' => 'Ativo',
            '0' => 'Bloqueado'
        );



        return $this->setContent('admin.customers.index', compact('status'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        $action = 'Novo Cliente';
        
        $customer = new Customer;

        $formOptions = array('route' => array('admin.customers.store'));

        $password = str_random(8);

        $user = Auth::user();

        return $this->setContent('admin.customers.edit', compact('action', 'formOptions', 'customer', 'password','user'));
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function show($id) {
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        
        $action = 'Editar Cliente';

        $customer = Customer::findOrfail($id);

        $user = Auth::user();

        $formOptions = array('route' => array('admin.customers.update', $customer->id), 'method' => 'PUT', 'files' => true);
                            
        return $this->setContent('admin.customers.edit', compact('customer', 'action', 'formOptions','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $customer  = Customer::findOrNew($id);

        $input = $request->all();
        
        $input['active'] = !$request->get('active', false);
        $input['email'] = strtolower(@$input['email']);
        
        $changePass = false;
        $feedback = 'Dados gravados com sucesso.';
        $rules = [];
        if ($customer->exists && empty($input['password'])) {
            $rules['name']  = 'required';
            $rules['email'] = 'required|email|unique:customers,email,' . $customer->id;
        } elseif($customer->exists) {
            $changePass = true;
            $feedback = 'Palavra-passe alterada com sucesso.';
            $rules['password'] = 'confirmed';
            $rules['nif'] = 'required|unique:customers,nif|min:9|max:9';
            $rules['phone'] = 'required|unique:customers,phone|min:9|max:9';
        } elseif(!$customer->exists) {
            $rules['name']  = 'required';
            $rules['email'] = 'required|email|unique:customers,email';
            $rules['nif'] = 'required|unique:customers,nif|min:9|max:9';
            $rules['phone'] = 'required|unique:customers,phone|min:9|max:9';
        }

        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            if (empty($input['password'])) {
                unset($input['password']);
            } else {
                $input['password'] = bcrypt($input['password']);
            }

            $customer->fill($input);
            $customer->active = $input['active'];
            $customer->save();



            return Redirect::route('admin.customers.edit', $customer->id)->with('success', $feedback);
        }

        return Redirect::back()->withInput()->with('error', $validator->errors()->first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
        $customer = Customer::findOrFail($id);
        
        $result = $customer->delete();
        
        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o utilizador.');
        }

        return Redirect::route('admin.customers.index')->with('success', 'Utilizador removido com sucesso.');
    }
    
    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request) {
        
        $ids = explode(',', $request->ids);
        
        $result = Customer::whereIn('id', $ids)
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

        $data = Customer::select();
       
        //filter active
        $active = $request->active;
        if($request->has('active')) {
            $data = $data->where('active', $active);
        }
        
        return Datatables::of($data)
            ->edit_column('name', function($row) {
                return view('admin.customers.datatables.name', compact('row'))->render();
            })
            ->add_column('select', function($row) {
                return view('admin.partials.datatables.select', compact('row'))->render();
            })
            ->add_column('active', function($row) {
                return view('admin.customers.datatables.active', compact('row'))->render();
            })
            ->add_column('last_login', function($row) {
                return view('admin.customers.datatables.last_login', compact('row'))->render();
            })
            ->edit_column('created_at', function($row) {
                return view('admin.partials.datatables.created_at', compact('row'))->render();
            })
            ->add_column('actions', function($row) {
                return view('admin.customers.datatables.actions', compact('row'))->render();
            })
            ->make(true);
    }


    /**
     * Start a remote login
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function remoteLogin(Request $request, $customerId) {

        $currentUser = Auth::user();

        $customer = Customer::findOrFail($customerId);

        Session::set('source_customer_id', $currentUser->id);

        $result = Auth::login($customer);

        return Redirect::route('admin.dashboard')->with('success', 'Sessão iniciada com sucesso.');

        return Redirect::back()->with('error', 'O utilizador não possui conta criada.');
    }

}
