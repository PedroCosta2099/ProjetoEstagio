<?php

namespace App\Http\Controllers\Admin;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Status;
use App\Models\User;


class StatusController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'status';

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
        
        return $this->setContent('admin.status.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $status = new Status();

        $operators = User::orderBy('code', 'asc')
                        ->pluck('name', 'id')
                        ->toArray();

        $action = 'Adicionar Estado';

        $formOptions = array('route' => array('admin.status.store'), 'method' => 'POST', 'class' => 'form-status');

        $data = compact(
            'status',
            'action',
            'formOptions',
            'operators'
        );

        return view('admin.status.edit', $data)->render();
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

        $status = Status::findOrfail($id);

        $operators = User::orderBy('code', 'asc')
                ->pluck('name', 'id')
                ->toArray();

        $action = 'Editar Estado';

        $formOptions = array('route' => array('admin.status.update', $status->id), 'method' => 'PUT', 'class' => 'form-status');

        $data = compact(
            'status',
            'action',
            'formOptions',
            'operators'
        );

        return view('admin.status.edit', $data)->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        Status::flushCache(Status::CACHE_TAG);
        User::flushCache(User::CACHE_TAG);

        $input = $request->all();

        $status = Status::findOrNew($id);

        if ($status->validate($input)) {
            $status->fill($input);
            $status->save();

            return Redirect::back()->with('success', 'Dados gravados com sucesso.');
        }
        
        return Redirect::back()->withInput()->with('error', $status->errors()->first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        Status::flushCache(Status::CACHE_TAG);

        $result = Status::whereId($id)
                            ->delete();

        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o estado');
        }

        return Redirect::route('admin.status.index')->with('success', 'Estado removido com sucesso.');
    }
    
    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request) {

        Status::flushCache(Status::CACHE_TAG);

        $ids = explode(',', $request->ids);
        
        $result = Status::whereIn('id', $ids)
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

        $data = Status::select();
        
        return Datatables::of($data)
                ->edit_column('name', function($row) {
                    return view('admin.status.datatables.name', compact('row'))->render();
                })
                ->add_column('select', function($row) {
                    return view('admin.partials.datatables.select', compact('row'))->render();
                })
                ->add_column('actions', function($row) {
                    return view('admin.status.datatables.actions', compact('row'))->render();
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

        $items = Status::remember(config('cache.query_ttl'))
                    ->cacheTags(User::CACHE_TAG)
                    ->ordered()
                    ->get(['id', 'name']);

        $route = route('admin.status.sort.update');

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

        Status::flushCache(Status::CACHE_TAG);

        try {
            Status::setNewOrder($request->ids);
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
