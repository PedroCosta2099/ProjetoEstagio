<?php

namespace App\Http\Controllers\Admin;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\User;
use App\Models\Seller;


class SubCategoriesController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'subcategories';

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
        
        return $this->setContent('admin.subcategories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $subcategory = new SubCategory();

        $categories = Category::orderBy('id','asc')
                ->pluck('name', 'id')
                ->toArray();

        $action = 'Adicionar SubCategoria';
        
        $formOptions = array('route' => array('admin.subcategories.store'), 'method' => 'POST', 'class' => 'form-status');

        $data = compact(
            'subcategory',
            'action',
            'categories',
            'formOptions'
        );

        return view('admin.subcategories.edit', $data)->render();
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

        $subcategory = SubCategory::findOrfail($id);
        $categories = Category::orderBy('id','asc')
        ->pluck('name', 'id')
        ->toArray();
        //dd($subcategory->category_id);
        $action = 'Editar SubCategoria';

        $formOptions = array('route' => array('admin.subcategories.update', $subcategory->id), 'method' => 'PUT', 'class' => 'form-status');

        $data = compact(
            'subcategory',
            'action',
            'categories',
            'formOptions'

        );
        //dd($data);
        return view('admin.subcategories.edit', $data)->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        SubCategory::flushCache(Category::CACHE_TAG);
        User::flushCache(User::CACHE_TAG);

        $input = $request->all();

        $subcategory = SubCategory::findOrNew($id);

        if ($subcategory->validate($input)) {
            $subcategory->fill($input);
            $subcategory->save();

            return Redirect::back()->with('success', 'Dados gravados com sucesso.');
        }
        
        return Redirect::back()->withInput()->with('error', $subcategory->errors()->first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        SubCategory::flushCacheSub(Category::CACHE_TAG);

        $result = SubCategory::whereId($id)
                            ->delete();

        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o estado');
        }

        return Redirect::route('admin.subcategories.index')->with('success', 'Estado removido com sucesso.');
    }
    
    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request) {

        SubCategory::flushCache(SubCategory::CACHE_TAG);

        $ids = explode(',', $request->ids);
        
        $result = SubCategory::whereIn('id', $ids)
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

        $data = SubCategory::select();

        return Datatables::of($data)
                ->edit_column('name', function($row) {
                    return view('admin.subcategories.datatables.name', compact('row'))->render();
                })
                ->edit_column('category_id', function($row) {
                    return view('admin.subcategories.datatables.category', compact('row'))->render();
                })
                ->edit_column('seller', function($row) {
                    return view('admin.subcategories.datatables.seller', compact('row'))->render();
                })
                ->add_column('select', function($row) {
                    return view('admin.partials.datatables.select', compact('row'))->render();
                })
                ->add_column('actions', function($row) {
                    return view('admin.subcategories.datatables.actions', compact('row'))->render();
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

        $items = SubCategory::remember(config('cache.query_ttl'))
                    ->cacheTags(User::CACHE_TAG)
                    ->ordered()
                    ->get(['id', 'name']);

        $route = route('admin.subcategories.sort.update');

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

        SubCategory::flushCache(SubCategory::CACHE_TAG);

        try {
            SubCategory::setNewOrder($request->ids);
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
