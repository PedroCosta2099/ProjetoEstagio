<?php

namespace App\Http\Controllers\Admin;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ExtraProduct;
use File,Croppa;


class ExtraProductsController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'extraproducts';

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
        
        return $this->setContent('admin.extraproducts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $extra_product = new ExtraProduct();
        $categories = Category::orderBy('id','asc')
                        ->pluck('name','id')
                        ->toArray();
        $subcategories = SubCategory::where('category_id',$extra_product->category_id)
                            ->pluck('name','id')
                            ->toArray();
        $operators = User::orderBy('id', 'asc')
                        ->pluck('name', 'id')
                        ->toArray();
        
        $action = 'Adicionar Produto Extra';
        $formOptions = array('route' => array('admin.extraproducts.store'), 'method' => 'POST', 'class' => 'form-products','files' => true);

        $data = compact(
            'extra_product',
            'action',
            'categories',
            'subcategories',
            'formOptions',
            'operators'
        );

        return view('admin.extraproducts.edit', $data)->render();
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
        
        $extra_product = ExtraProduct::findOrfail($id);
        $categories = Category::orderBy('id','asc')
                            ->pluck('name','id')
                            ->toArray();
        $subcategories = SubCategory::where('category_id',$extra_product->category_id)
                            ->pluck('name','id')
                            ->toArray();
        $operators = User::orderBy('id', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        
        $action = 'Editar Produto Extra';
        $formOptions = array('route' => array('admin.extraproducts.update', $extra_product->id), 'method' => 'PUT', 'class' => 'form-products','files' => true);

        $data = compact(
            'extra_product',
            'action',
            'categories',
            'subcategories',
            'formOptions',
            'operators'
        );
        //dd($product->toArray());
        //dd($cal_vat);
        return view('admin.extraproducts.edit', $data)->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        ExtraProduct::flushCache(ExtraProduct::CACHE_TAG);
        User::flushCache(User::CACHE_TAG);

        $input = $request->all();
        $extra_product = ExtraProduct::findOrNew($id);

        //delete image
        if ($input['delete_photo'] && !empty($extra_product->filepath)) {
            Croppa::delete($extra_product->filepath);
            $extra_product->filepath = null;
            $extra_product->filename = null;
        }

        //upload image
        if($request->hasFile('image')) {
            if ($extra_product->exists && !empty($extra_product->filepath) && File::exists(public_path(). '/'.$extra_product->filepath)) {
                Croppa::delete($extra_product->filepath);
            }

            if (!$extra_product->upload($request->file('image'), 40, true, [])) {
                return Redirect::back()->withInput()->with('error', 'Não foi possível alterar a imagem do perfil.');
            }

        } else {
            $extra_product->save();
        }

        if ($extra_product->validate($input)) {
            $extra_product->fill($input);
            $extra_product->save();
            //dd('filepath:',$product->filepath,'filename:',$product->filename);
            return Redirect::back()->with('success', 'Dados gravados com sucesso.');
        }
        
        return Redirect::back()->withInput()->with('error', $extra_product->errors()->first());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        ExtraProduct::flushCache(ExtraProduct::CACHE_TAG);

        $result = ExtraProduct::whereId($id)
                            ->delete();

        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o estado');
        }

        return Redirect::route('admin.extraproducts.index')->with('success', 'Estado removido com sucesso.');
    }
    
    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request) {

        ExtraProduct::flushCache(ExtraProduct::CACHE_TAG);

        $ids = explode(',', $request->ids);
        
        $result = ExtraProduct::whereIn('id', $ids)
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

        $data = ExtraProduct::select()
                        ->orderBy('sort','asc');
        
        return Datatables::of($data)
                ->edit_column('name', function($row) {
                    return view('admin.extraproducts.datatables.name', compact('row'))->render();
                })
                ->edit_column('price', function($row) {
                    return view('admin.extraproducts.datatables.price', compact('row'))->render();
                })
                ->edit_column('category_id', function($row) {
                    return view('admin.extraproducts.datatables.category', compact('row'))->render();
                })
                ->edit_column('subcategory_id', function($row) {
                    return view('admin.extraproducts.datatables.subcategory', compact('row'))->render();
                })
                ->add_column('image', function($row) {
                    return view('admin.extraproducts.datatables.image', compact('row'))->render();
                })
                ->add_column('select', function($row) {
                    return view('admin.partials.datatables.select', compact('row'))->render();
                })
                ->add_column('actions', function($row) {
                    return view('admin.extraproducts.datatables.actions', compact('row'))->render();
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

        $items = ExtraProduct::remember(config('cache.query_ttl'))
                    ->cacheTags(User::CACHE_TAG)
                    ->ordered()
                    ->get(['id', 'name']);

        $route = route('admin.extraproducts.sort.update');

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

        ExtraProduct::flushCache(ExtraProduct::CACHE_TAG);

        try {
            ExtraProduct::setNewOrder($request->ids);
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
