<?php

namespace App\Http\Controllers\Admin;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use App\Models\User;
use App\Models\PaymentType;
use File,Croppa;


class PaymentTypesController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'paymenttypes';

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
        
        return $this->setContent('admin.paymenttypes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $type = new PaymentType();

        $operators = User::orderBy('code', 'asc')
                        ->pluck('name', 'id')
                        ->toArray();

        $action = 'Adicionar Método de Pagamento';

        $formOptions = array('route' => array('admin.paymenttypes.store'), 'method' => 'POST', 'class' => 'form-paymenttypes','files' => true);

        $data = compact(
            'type',
            'action',
            'formOptions',
            'operators'
        );

        return view('admin.paymenttypes.edit', $data)->render();
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

        $type = PaymentType::findOrfail($id);

        $operators = User::orderBy('code', 'asc')
                ->pluck('name', 'id')
                ->toArray();

        $action = 'Editar Método de Pagamento';

        $formOptions = array('route' => array('admin.paymenttypes.update', $type->id), 'method' => 'PUT', 'class' => 'form-paymenttypes','files' => true);

        $data = compact(
            'type',
            'action',
            'formOptions',
            'operators'
        );

        return view('admin.paymenttypes.edit', $data)->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        PaymentType::flushCache(PaymentType::CACHE_TAG);
        User::flushCache(User::CACHE_TAG);

        $input = $request->all();

        $type = PaymentType::findOrNew($id);

        //delete image
        if ($input['delete_photo'] && !empty($type->filepath)) {
            Croppa::delete($type->filepath);
            $type->filepath = null;
            $type->filename = null;
        }

        //upload image
        if($request->hasFile('image')) {
            if ($type->exists && !empty($type->filepath) && File::exists(public_path(). '/'.$type->filepath)) {
                Croppa::delete($type->filepath);
            }

            if (!$type->upload($request->file('image'), 40, true, [])) {
                return Redirect::back()->withInput()->with('error', 'Não foi possível alterar a imagem do perfil.');
            }

        } else {
            $type->save();
        }

        if ($type->validate($input)) {
            $type->fill($input);
            $type->save();

            return Redirect::back()->with('success', 'Dados gravados com sucesso.');
        }
        
        return Redirect::back()->withInput()->with('error', $type->errors()->first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        PaymentType::flushCache(PaymentType::CACHE_TAG);

        $result = PaymentType::whereId($id)
                            ->delete();

        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o estado');
        }

        return Redirect::route('admin.paymenttypes.index')->with('success', 'Estado removido com sucesso.');
    }
    
    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request) {

        PaymentType::flushCache(PaymentType::CACHE_TAG);

        $ids = explode(',', $request->ids);
        
        $result = PaymentType::whereIn('id', $ids)
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

        $data = PaymentType::select();
        
        return Datatables::of($data)
                ->edit_column('name', function($row) {
                    return view('admin.paymenttypes.datatables.name', compact('row'))->render();
                })
                ->add_column('image', function($row) {
                    return view('admin.paymenttypes.datatables.image', compact('row'))->render();
                })
                ->add_column('select', function($row) {
                    return view('admin.partials.datatables.select', compact('row'))->render();
                })
                ->add_column('actions', function($row) {
                    return view('admin.paymenttypes.datatables.actions', compact('row'))->render();
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

        $items = PaymentType::remember(config('cache.query_ttl'))
                    ->cacheTags(User::CACHE_TAG)
                    ->ordered()
                    ->get(['id', 'name']);

        $route = route('admin.paymenttypes.sort.update');

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

        PaymentType::flushCache(PaymentType::CACHE_TAG);

        try {
            PaymentType::setNewOrder($request->ids);
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
