<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Models\User;
use App\Models\Seller;
use Auth, App, Html, Session,File,Croppa;

class UsersController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['ability:' . config('permissions.role.admin') . ',admin_users']);
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

        $seller = Seller::orderBy('name')
                        ->pluck('name','id')
                        ->toArray();
        
        return $this->setContent('admin.users.index', compact('roles', 'status','seller'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        $action = 'Novo Colaborador';
        
        $user = new User;

        $formOptions = array('route' => array('admin.users.store'));

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

        $sellers = Seller::orderBy('name','asc')
                            ->pluck('name','id')
                            ->toArray();

        return $this->setContent('admin.users.edit', compact('action', 'formOptions', 'user', 'password', 'roles', 'assignedRoles','sellers'));
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
        
        $action = 'Editar Colaborador';

        $user = User::findOrfail($id);

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

        $assignedRoles = $user->roles()
            ->pluck('role_id')
            ->toArray();

        $assignedRoles = array_map('intval', $assignedRoles);

        $formOptions = array('route' => array('admin.users.update', $user->id), 'method' => 'PUT', 'files' => true);

        $sellers = Seller::orderBy('name','asc')
                            ->pluck('name','id')
                            ->toArray();
                            
        return $this->setContent('admin.users.edit', compact('user', 'action', 'formOptions', 'roles', 'assignedRoles','sellers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $user  = User::findOrNew($id);

        $input = $request->except('role_id');

        $input['active'] = !$request->get('active', false);
        $input['email'] = strtolower(@$input['email']);
        if(Auth::user()->isAdmin())
        {
        $user->seller_id = $input['seller_id'];
        }
        else
        {
            $user->seller_id = Auth::user()->seller_id;
        }
        
        $changePass = false;
        $feedback = 'Dados gravados com sucesso.';
        $rules = [];
        if ($user->exists && empty($input['password'])) {
            $rules['name']  = 'required';
            $rules['email'] = 'required|email|unique:users,email,' . $user->id;
        } elseif($user->exists) {
            $changePass = true;
            $feedback = 'Palavra-passe alterada com sucesso.';
            $rules['password'] = 'confirmed';
        } elseif(!$user->exists) {
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

            $user->fill($input);
            //delete image
            if ($input['delete_photo'] && !empty($user->filepath)) {
                Croppa::delete($user->filepath);
                $user->filepath = null;
                $user->filename = null;
            }

            //upload image
            if($request->hasFile('image')) {
                if ($user->exists && !empty($user->filepath) && File::exists(public_path(). '/'.$user->filepath)) {
                    Croppa::delete($user->filepath);
                }

                if (!$user->upload($request->file('image'), 40, true, [])) {
                    return Redirect::back()->withInput()->with('error', 'Não foi possível alterar a imagem do perfil.');
                }

            } else {
                
                $user->save();
            }
    
            if(!$changePass && $user->id != Auth::user()->id) {
                $roles = $request->has('role_id') ? $request->get('role_id') : [];
                $user->roles()->sync($roles);
            }

            return Redirect::route('admin.users.edit', $user->id)->with('success', $feedback);
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
        
        $user = User::findOrFail($id);
        
        $result = $user->delete();
        
        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o utilizador.');
        }

        return Redirect::route('admin.users.index')->with('success', 'Utilizador removido com sucesso.');
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
        
        $result = User::whereIn('id', $ids)
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
        if($user->isAdmin())
        {
        $data = User::with('roles')->select();
        }
        else{
            $data = User::where('seller_id',Auth::user()->seller_id)
                        ->select();
        }
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
        //filter seller
        if($request->seller)
        {
            $data = $data->where('seller_id',$request->seller);
        }
    
        return Datatables::of($data)
            ->edit_column('name', function($row) {
                return view('admin.users.datatables.name', compact('row'))->render();
            })
            ->edit_column('seller_id', function($row) {
                return view('admin.users.datatables.seller', compact('row'))->render();
            })
            ->add_column('select', function($row) {
                return view('admin.partials.datatables.select', compact('row'))->render();
            })
            ->add_column('roles', function($row) {
                return view('admin.users.datatables.roles', compact('row'))->render();
            })
            ->add_column('active', function($row) {
                return view('admin.users.datatables.active', compact('row'))->render();
            })
            ->add_column('last_login', function($row) {
                return view('admin.users.datatables.last_login', compact('row'))->render();
            })
            ->edit_column('created_at', function($row) {
                return view('admin.partials.datatables.created_at', compact('row'))->render();
            })
            ->add_column('actions', function($row) {
                return view('admin.users.datatables.actions', compact('row'))->render();
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

        $user = User::findOrFail($customerId);

        Session::set('source_user_id', $currentUser->id);

        $result = Auth::login($user);

        return Redirect::route('admin.dashboard')->with('success', 'Sessão iniciada com sucesso.');

        return Redirect::back()->with('error', 'O utilizador não possui conta criada.');
    }

}
