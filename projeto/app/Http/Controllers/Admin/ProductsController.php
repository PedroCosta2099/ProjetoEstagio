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
use App\Models\OrderLine;
use App\Models\Seller;
use Auth;
use Validator;
use File,Croppa;


class ProductsController extends \App\Http\Controllers\Admin\Controller {

    /**
     * Sidebar active menu option
     *
     * @var string
     */
    protected $sidebarActiveOption = 'products';

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
        if(Auth::user()->isAdmin()){
        $category = Category::orderBy('name','asc')
                            ->pluck('name','id')
                            ->toArray();

        $categories = Category::orderBy('name','asc')
                            ->get()
                            ->toArray();
        }
        else
        {
            $category = Category::where('seller_id',Auth::user()->seller_id)
                            ->orderBy('name','asc')
                            ->pluck('name','id')
                            ->toArray();

            $categories = Category::where('seller_id',Auth::user()->seller_id)
                            ->orderBy('name','asc')
                            ->get()
                            ->toArray();
        }        
        $categoriesIds=[];
        foreach($categories as $key)
        {
           
            if(!in_array($key['id'], $categoriesIds, true)){
                array_push($categoriesIds,$key['id']);
            }
            
        }
       
        $subcategory = SubCategory::whereIn('category_id',$categoriesIds)
                                    ->pluck('name','id')
                                    ->toArray();
        $seller = Seller::orderBy('name','asc')
                        ->pluck('name','id')
                        ->toArray();
        return $this->setContent('admin.products.index',compact('category','subcategory','seller'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        $product = new Product();
        $seller = Seller::orderBy('name','asc')
                        ->pluck('name','id')
                        ->toArray();
        if(Auth::user()->isAdmin())
        {
            $categories = Category::where('id',$product->category_id)
                                ->pluck('name','id')
                                ->toArray();
        }
        else{
        $categories = Category::where('seller_id',Auth::user()->seller_id)
                            ->pluck('name','id')
                            ->toArray();
        }
        $subcategories = SubCategory::where('category_id',$product->category_id)
                            ->pluck('name','id')
                            ->toArray();
        $operators = User::orderBy('id', 'asc')
                        ->pluck('name', 'id')
                        ->toArray();
        
        $action = 'Adicionar Produto';
        $formOptions = array('route' => array('admin.products.store'), 'method' => 'POST', 'class' => 'form-products','files' => true);
        
        $data = compact(
            'product',
            'action',
            'categories',
            'subcategories',
            'formOptions',
            'operators',
            'seller'
        );

        return view('admin.products.edit', $data)->render();
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
        
        $product = Product::with('category')->findOrfail($id);
        
        $seller = Seller::orderBy('name','asc')
                        ->pluck('name','id')
                        ->toArray();
        if(Auth::user()->isAdmin())
        {
            $categories = Category::where('id',$product->category_id)
                                ->pluck('name','id')
                                ->toArray();
        }
        else{
        $categories = Category::where('seller_id',Auth::user()->seller_id)
                            ->pluck('name','id')
                            ->toArray();
        }
        $subcategories = SubCategory::where('category_id',$product->category_id)
                            ->pluck('name','id')
                            ->toArray();
        $operators = User::orderBy('id', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        
        $action = 'Editar Produto';
        $formOptions = array('route' => array('admin.products.update', $product->id), 'method' => 'PUT', 'class' => 'form-products','files' => true);

        $data = compact(
            'product',
            'action',
            'categories',
            'subcategories',
            'formOptions',
            'operators',
            'seller'
        );
        return view('admin.products.edit', $data)->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        Product::flushCache(Product::CACHE_TAG);
        User::flushCache(User::CACHE_TAG);

        $input = $request->all();
        
        $product = Product::findOrNew($id);
 
        //delete image
        if ($input['delete_photo'] && !empty($product->filepath)) {
            Croppa::delete($product->filepath);
            $product->filepath = null;
            $product->filename = null;
        }

        //upload image
        if($request->hasFile('image')) {
            if ($product->exists && !empty($product->filepath) && File::exists(public_path(). '/'.$product->filepath)) {
                Croppa::delete($product->filepath);
            }

            if (!$product->upload($request->file('image'), 40, true, [])) {
                return Redirect::back()->withInput()->with('error', 'Não foi possível alterar a imagem do perfil.');
            }

        } else {
            $product->save();
        }

        if ($product->validate($input)) {
            $product->fill($input);
            $product->discount = $input['discount'];
            $product->actual_price = $product->price - (($product->discount/100) * $product->price);
            $product->actual_vat = $input['actual_vat'];
            $product->save();
            
            return Redirect::back()->with('success', 'Dados gravados com sucesso.');
        }
        
        return Redirect::back()->withInput()->with('error', $product->errors()->first());
    }

    public function updateCategory($id)
    {
        
            $subcategories = SubCategory::where('category_id',$id)
                            ->pluck('name','id')
                            ->toArray();
            if(count($subcategories) == 0)
            {
                $subcategories = ["NENHUM"];
            }

            return response()->json($subcategories);
    }

    public function updateCategoryBySeller($id)
    {
        
            $categories = Category::where('seller_id',$id)
                            ->pluck('name','id')
                            ->toArray();
            if(count($categories) == 0)
            {
                $categories = [];
            }

            return response()->json($categories);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        Product::flushCache(Product::CACHE_TAG);

        $result = Product::whereId($id)
                            ->delete();

        if (!$result) {
            return Redirect::back()->with('error', 'Ocorreu um erro ao tentar remover o pedido');
        }

        return Redirect::route('admin.products.index')->with('success', 'Produto removido com sucesso.');
    }
    
    /**
     * Remove all selected resources from storage.
     * GET /admin/users/selected/destroy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request) {

        Product::flushCache(Product::CACHE_TAG);
        
        $ids = explode(',', $request->ids);
        
        $result = Product::whereIn('id', $ids)
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
        if(Auth::user()->isAdmin()){
            
            $data = Product::select();
        }
        else{
           
        $currentUser = Auth::user()->seller_id;
        $categories = Category::where('seller_id',$currentUser)->pluck('id');
        
        $data = Product::whereIn('category_id',$categories)
                        ->orderBy('sort','asc');
        }
        //filter category
        if($request->category)
        {
            $data = $data->where('category_id',$request->category);
        }

        //filter subcategory
        if($request->subcategory)
        {
            
            $data = $data->where('subcategory_id',$request->subcategory);
        }
        //filter seller
        if($request->seller)
        {
            $categoriesIds = [];
            $categories = Category::where('seller_id',$request->seller)
                                    ->get()
                                    ->toArray();
            
            foreach($categories as $category)
            {
                if(!in_array($category['id'], $categoriesIds, true)){
                    array_push($categoriesIds,$category['id']);
                }
            }
            $data = $data->whereIn('category_id',$categoriesIds);
        }
        
        return Datatables::of($data)
                ->edit_column('name', function($row) {
                    return view('admin.products.datatables.name', compact('row'))->render();
                })
                ->edit_column('actual_price', function($row) {
                    return view('admin.products.datatables.actual_price', compact('row'))->render();
                })
                ->edit_column('price', function($row) {
                    return view('admin.products.datatables.price', compact('row'))->render();
                })
                ->edit_column('actual_vat', function($row) {
                    return view('admin.products.datatables.actual_vat', compact('row'))->render();
                })
                ->edit_column('vat', function($row) {
                    return view('admin.products.datatables.vat', compact('row'))->render();
                })
                ->edit_column('discount', function($row) {
                    return view('admin.products.datatables.discount', compact('row'))->render();
                })
                ->edit_column('category_id', function($row) {
                    return view('admin.products.datatables.category', compact('row'))->render();
                })
                ->edit_column('subcategory_id', function($row) {
                    return view('admin.products.datatables.subcategory', compact('row'))->render();
                })
                ->edit_column('seller_id', function($row) {
                    return view('admin.products.datatables.seller', compact('row'))->render();
                })
                ->add_column('image', function($row) {
                    return view('admin.products.datatables.image', compact('row'))->render();
                })
                ->add_column('select', function($row) {
                    return view('admin.partials.datatables.select', compact('row'))->render();
                })
                ->add_column('actions', function($row) {
                    return view('admin.products.datatables.actions', compact('row'))->render();
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

        $items = Product::remember(config('cache.query_ttl'))
                    ->cacheTags(User::CACHE_TAG)
                    ->ordered()
                    ->get(['id', 'name']);

        $route = route('admin.products.sort.update');

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

        Product::flushCache(Product::CACHE_TAG);

        try {
            Product::setNewOrder($request->ids);
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
