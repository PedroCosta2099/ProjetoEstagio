<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Models\Seller;
use Auth, App, Html, Session;

class SellersController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'sellers';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['ability:' . config('permissions.role.admin') . ',admin_sellers']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        if(Auth::user()->hasRole([config('permissions.role.admin')])) {
            $roles = Role::orderBy('name')
                ->pluck('display_name', 'id')
                ->toArray();
        } else {
            $roles = Role::where('id', '>', '1')
                ->orderBy('name')
                ->pluck('display_name', 'id')
                ->toArray();
        }

        $status = array(
            '1' => 'Ativo',
            '0' => 'Bloqueado'
        );

        return $this->setContent('admin.sellers.index', compact('roles', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        $action = 'Novo Vendedor';
        
        $seller = new Seller();

        $formOptions = array('route' => array('admin.sellers.store'));

        if(Auth::user()->hasRole([config('permissions.role.admin')])) {
            $roles = Role::orderBy('name')
                ->pluck('display_name', 'id')
                ->toArray();
        } else {
            $roles = Role::where('id', '>', '1')
                ->orderBy('name')
                ->pluck('display_name', 'id')
                ->toArray();
        }
        
        $assignedRoles = array();

        $password = str_random(8);

        return $this->setContent('admin.sellers.edit', compact('action', 'formOptions', 'seller', 'password', 'roles', 'assignedRoles'));
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
        
        $action = 'Editar Vendedor';

        $seller = Seller::findOrfail($id);
        
        if(Auth::user()->hasRole([config('permissions.role.admin')])) {
            $roles = Role::orderBy('name')
                ->pluck('display_name', 'id')
                ->toArray();
        } else {
            $roles = Role::where('id', '>', '1')
                ->orderBy('name')
                ->pluck('display_name', 'id')
                ->toArray();
        }
        
        $assignedRoles = $seller->roles()
            ->pluck('role_id')
            ->toArray();
        //dd($seller->id,$assignedRoles);
        $assignedRoles = array_map('intval', $assignedRoles);
        
        $formOptions = array('route' => array('admin.sellers.update', $seller->id), 'method' => 'PUT', 'files' => true);

        return $this->setContent('admin.sellers.edit', compact('seller', 'action', 'formOptions', 'roles', 'assignedRoles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $seller  = Seller::findOrNew($id);

        $input = $request->except('role_id');

        $input['active'] = !$request->get('active', false);
        $input['email'] = strtolower(@$input['email']);

        $changePass = false;
        $feedback = 'Dados gravados com sucesso.';
        $rules = [];
        if ($seller->exists && empty($input['password'])) {
            $rules['name']  = 'required';
            $rules['email'] = 'required|email|unique:users,email,' . $seller->id;
        } elseif($seller->exists) {
            $changePass = true;
            $feedback = 'Palavra-passe alterada com sucesso.';
            $rules['password'] = 'confirmed';
        } elseif(!$seller->exists) {
            $rules['name']  = 'required';
            $rules['email'] = 'required|email|unique:users,email';
        }

        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {

            if (empty($input['password'])) {
                unset($input['password']);
            } else {
                $input['password'] = bcrypt($input['password']);
            }

            $seller->fill($input);
            
            //delete image
            if ($input['delete_photo'] && !empty($seller->filepath)) {
                Croppa::delete($seller->filepath);
                $seller->filepath = null;
                $seller->filename = null;
                $seller->filehost = null;
            }

            //upload image
            if($request->hasFile('image')) {

                if ($seller->exists && !empty($seller->filepath) && File::exists(public_path(). '/'.$seller->filepath)) {
                    Croppa::delete($seller->filepath);
                }

                if (!$seller->upload($request->file('image'), 40, true, [])) {
                    return Redirect::back()->withInput()->with('error', 'Não foi possível alterar a imagem do perfil.');
                }

            } else {
                $seller->save();
            }

            if(!$changePass && $seller->id != Auth::user()->id) {
                $roles = $request->has('role_id') ? $request->get('role_id') : [];
                $seller->roles()->sync($roles);
            }

            return Redirect::route('admin.sellers.edit', $seller->id)->with('success', $feedback);
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
        
        $seller = Seller::findOrFail($id);
        
        $result = $seller->delete();
        
        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o utilizador.');
        }

        return Redirect::route('admin.sellers.index')->with('success', 'Utilizador removido com sucesso.');
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
        
        $result = Seller::whereIn('id', $ids)
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

        $user = Auth::user();

        $data = Seller::with('roles')->select();

        //filter role
        if($request->role) {
            $data = $data->whereHas('roles', function($q) use($request){
                $q->where('role_id', $request->role);
            });
        }

        //filter active
        $active = $request->active;
        if($request->has('active')) {
            $data = $data->where('active', $active);
        }

        return Datatables::of($data)
            ->edit_column('name', function($row) {
                return view('admin.sellers.datatables.name', compact('row'))->render();
            })
            ->add_column('select', function($row) {
                return view('admin.partials.datatables.select', compact('row'))->render();
            })
            ->add_column('roles', function($row) {
                return view('admin.sellers.datatables.roles', compact('row'))->render();
            })
            ->add_column('active', function($row) {
                return view('admin.sellers.datatables.active', compact('row'))->render();
            })
            ->add_column('last_login', function($row) {
                return view('admin.sellers.datatables.last_login', compact('row'))->render();
            })
            ->edit_column('created_at', function($row) {
                return view('admin.partials.datatables.created_at', compact('row'))->render();
            })
            ->add_column('actions', function($row) {
                return view('admin.sellers.datatables.actions', compact('row'))->render();
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

        $user = Seller::findOrFail($customerId);

        Session::set('source_user_id', $currentUser->id);

        $result = Auth::login($user);

        return Redirect::route('admin.dashboard')->with('success', 'Sessão iniciada com sucesso.');

        return Redirect::back()->with('error', 'O utilizador não possui conta criada.');
    }

}
