@section('title')
    Produtos
@stop

@section('content-header')
    Produtos
@stop

@section('breadcrumb')
    <li class="active">Configurações</li>
    <li class="active">Produtos</li>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box no-border">
            <div class="box-body">
                <ul class="datatable-filters list-inline hide pull-left" data-target="#datatable">
                    <li>
                        <button class="btn btn-default btn-sm" id="refresh"><i style="color:rgba(0,0,0,0.6)" class="fas fa-sync"></i></button>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-remote">
                            <i class="fas fa-plus"></i> Novo
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.sort') }}" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal-remote">
                            <i class="fas fa-sort-amount-down"></i> Ordenar
                        </a>
                    </li>
                    <li>
                        <strong>Categoria</strong> 
                        {{ Form::select('category', array('' => 'Todos') + $category, Request::has('category') ? Request::get('category') : null, array('class' => 'form-control input-sm filter-datatable select2')) }}
                    </li>
                    <li>
                        <strong>Subcategoria</strong> 
                        {{ Form::select('subcategory', array('' => 'Todos') + $subcategory, Request::has('subcategory') ? Request::get('subcategory') : null, array('class' => 'form-control input-sm filter-datatable select2')) }}
                    </li>
                    @if(Auth::user()->isAdmin())
                    <li>
                        <strong>Vendedor</strong> 
                        {{ Form::select('seller', array('' => 'Todos') + $seller, Request::has('seller') ? Request::get('seller') : null, array('class' => 'form-control input-sm filter-datatable select2')) }}
                    </li>
                    @endif
                </ul>
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                                <th></th>
                                <th>Imagem</th>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th>IVA</th>
                                <th>Preço c/Desconto</th>
                                <th>IVA c/Desconto</th>
                                <th>Desconto</th>
                                <th>Categoria</th>
                                <th>SubCategoria</th>
                                @if(Auth::user()->isAdmin())
                                <th>Vendedor</th>
                                @endif
                                <th class="w-65px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="selected-rows-action hide">
                    {{ Form::open(array('route' => 'admin.products.selected.destroy')) }}
                    <button class="btn btn-sm btn-danger" data-action="confirm" data-title="Apagar selecionados"><i class="fas fa-trash-alt"></i> Apagar Selecionados</button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">
    var oTable
    $(document).ready(function () {
        oTable = $('#datatable').DataTable({
            order: [[1, "desc"]],
            columns: [
                {data: 'select', name: 'select', orderable: false, searchable: false},
                {data: 'id', name: 'id', visible: false},
                {data:'image',name:'image',orderable: false, searchable: false},
                {data: 'name', name:'name',searchable: true},
                {data: 'price', name:'price', searchable: true},
                {data: 'vat', name:'vat', orderable: false, searchable: false},
                {data: 'actual_price', name:'actual_price', orderable: true, searchable: true},
                {data: 'actual_vat', name:'actual_vat', orderable: false, searchable: false},
                {data: 'discount', name:'discount', orderable: true, searchable: true},
                {data: 'category_id', name:'category_id', orderable: true, searchable: true},
                {data: 'subcategory_id', name:'subcategory_id', orderable: true, searchable: true},
                @if(Auth::user()->isAdmin())
                {data: 'seller_id', name:'seller_id', orderable: true, searchable:false},
                @endif
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
            ],
            ajax: {
                url: "{{ route('admin.products.datatable') }}",
                type: "POST",
                data: function (d) {
                    d.category   = $('select[name=category').val();
                    d.subcategory   = $('select[name=subcategory').val();
                    d.seller   = $('select[name=seller').val();
                    
                },
                beforeSend: function () { Datatables.cancelDatatableRequest(oTable) },
                complete: function () { Datatables.complete() }
            }
        });

        $('#refresh').on('click', function() {
        oTable.ajax.reload(null,false);
        });
        
        $('.filter-datatable').on('change', function (e) {
            oTable.draw();
            e.preventDefault();
        });
        $(document).ready(function () {
        $(".select2").select2({
            language: 'pt'
        });
    });
    });

</script>
@stop