<?php

namespace App\Http\Controllers\Admin;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Brand;
use App\Models\User;


class BrandsController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'brands';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*$this->middleware(['ability:' . config('permissions.role.admin') . ',brands']);
        validateModule('brands');*/
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
        return $this->setContent('admin.brands.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $brand = new Brand();

        $operators = User::orderBy('code', 'asc')
                        ->pluck('name', 'id')
                        ->toArray();

        $action = 'Adicionar Marca';

        $formOptions = array('route' => array('admin.brands.store'), 'method' => 'POST', 'class' => 'form-brands');

        $data = compact(
            'brand',
            'action',
            'formOptions',
            'operators'
        );

        return view('admin.brands.edit', $data)->render();
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

        $brand = Brand::findOrfail($id);

        $operators = User::orderBy('code', 'asc')
                ->pluck('name', 'id')
                ->toArray();

        $action = 'Editar Marca';

        $formOptions = array('route' => array('admin.brands.update', $brand->id), 'method' => 'PUT', 'class' => 'form-brands');

        $data = compact(
            'brand',
            'action',
            'formOptions',
            'operators'
        );

        return view('admin.brands.edit', $data)->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        Brand::flushCache(Brand::CACHE_TAG);
        User::flushCache(User::CACHE_TAG);

        $input = $request->all();

        $brand = Brand::findOrNew($id);

        if ($brand->validate($input)) {
            $brand->fill($input);
            $brand->save();

            return Redirect::back()->with('success', 'Dados gravados com sucesso.');
        }
        
        return Redirect::back()->withInput()->with('error', $brand->errors()->first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        Brand::flushCache(Brand::CACHE_TAG);

        $result = Brand::whereId($id)
                            ->delete();

        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover a viatura');
        }

        return Redirect::route('admin.brands.index')->with('success', 'Marca removida com sucesso.');
    }
    
    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request) {

        Brand::flushCache(Brand::CACHE_TAG);

        $ids = explode(',', $request->ids);
        
        $result = Brand::whereIn('id', $ids)
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

        $data = Brand::select();
        
        return Datatables::of($data)
                ->add_column('select', function($row) {
                    return view('admin.partials.datatables.select', compact('row'))->render();
                })
                ->add_column('actions', function($row) {
                    return view('admin.brands.datatables.actions', compact('row'))->render();
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

        $items = Brand::remember(config('cache.query_ttl'))
                    ->cacheTags(User::CACHE_TAG)
                    ->ordered()
                    ->get(['id', 'name']);

        $route = route('admin.brands.sort.update');

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

        Brand::flushCache(Brand::CACHE_TAG);

        try {
            Brand::setNewOrder($request->ids);
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
