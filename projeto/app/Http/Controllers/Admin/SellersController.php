<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Models\Seller;
use Auth, App, Html, Session,File,Croppa;

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

        $status = array(
            '1' => 'Ativo',
            '0' => 'Bloqueado'
        );

        return $this->setContent('admin.sellers.index', compact('status'));
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

        return $this->setContent('admin.sellers.edit', compact('action', 'formOptions', 'seller'));
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
       
        
        $formOptions = array('route' => array('admin.sellers.update', $seller->id), 'method' => 'PUT', 'files' => true);

        return $this->setContent('admin.sellers.edit', compact('seller', 'action', 'formOptions'));
    }

    public function editOneSeller()
    {
        $id = Auth::user()->seller_id;
        $action = 'Editar Vendedor';
        $seller = Seller::findOrfail($id);
    
        
        $formOptions = array('route' => array('admin.sellers.update', $seller->id), 'method' => 'PUT', 'files' => true);

        return $this->setContent('admin.sellers.edit', compact('seller', 'action', 'formOptions'));
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
        
        $seller->nif = $input['nif'];
        $seller->postal_code = $input['postal_code'];
        $seller->fill($input);
        
        //dd($input['thumbnail_image'],$input['banner_image']);
        
           if($request->hasFile('thumbnail_image'))
           {
               
               $thumbnail_filepath = explode('/tmp/',$request->file('thumbnail_image')->getRealPath());
               $thumbnail_filepath = $thumbnail_filepath[1];
               $seller->thumbnail_filename = $request->file('thumbnail_image')->getClientOriginalName();
               //dd(public_path().'/'.SELLER::DIRECTORY.'/'.$thumbnail_filepath);
               $request->file('thumbnail_image')->move(public_path().'/'.SELLER::DIRECTORY.'/thumbnails/',$thumbnail_filepath.'.'.$request->file('thumbnail_image')->getClientOriginalExtension());
               $seller->thumbnail_filepath = '/'.SELLER::DIRECTORY.'/thumbnails/'.$thumbnail_filepath.'.'.$request->file('thumbnail_image')->getClientOriginalExtension();
               $seller->save();
           }
           
           if(!empty($seller->thumbnail_filepath) && $input['delete_photo_thumbnail'])
           {
               $seller->thumbnail_filename = null;
               $seller->thumbnail_filepath = null;
               $seller->save();
           }

           if($request->hasFile('banner_image'))
           {
               
               $banner_filepath = explode('/tmp/',$request->file('banner_image')->getRealPath());
               
               $banner_filepath = $banner_filepath[1];
               
               $seller->banner_filename = $request->file('banner_image')->getClientOriginalName();

               $request->file('banner_image')->move(public_path().'/'.SELLER::DIRECTORY.'/banners/',$banner_filepath.'.'.$request->file('banner_image')->getClientOriginalExtension());
               $seller->banner_filepath = '/'.SELLER::DIRECTORY.'/banners/'.$banner_filepath.'.'.$request->file('banner_image')->getClientOriginalExtension();
               
               $seller->save();
           }
           
           if(!empty($seller->banner_filepath) && $input['delete_photo_banner'])
           {
               $seller->banner_filename = null;
               $seller->banner_filepath = null;
               $seller->save();
           }
        
            return Redirect::route('admin.sellers.edit', $seller->id)->with('success', 'Gravado com sucesso');

        
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
            ->edit_column('address', function($row) {
                return view('admin.sellers.datatables.address', compact('row'))->render();
            })
            ->add_column('select', function($row) {
                return view('admin.partials.datatables.select', compact('row'))->render();
            })
            ->add_column('active', function($row) {
                return view('admin.sellers.datatables.active', compact('row'))->render();
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
