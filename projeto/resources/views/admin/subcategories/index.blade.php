@section('title')
    SubCategorias
@stop

@section('content-header')
    SubCategorias
@stop

@section('breadcrumb')
    <li class="active">Configurações</li>
    <li class="active">SubCategorias</li>
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
                        <a href="{{ route('admin.subcategories.create') }}" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-remote">
                            <i class="fas fa-plus"></i> Novo
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.subcategories.sort') }}" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal-remote">
                            <i class="fas fa-sort-amount-down"></i> Ordenar
                        </a>
                    </li>
                    <li>
                        <strong>Categoria</strong> 
                        {{ Form::select('category', array('' => 'Todos') + $category, Request::has('category') ? Request::get('category') : null, array('class' => 'form-control input-sm filter-datatable select2')) }}
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
                                <th>Nome</th>
                                <th>Categoria</th>
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
                    {{ Form::open(array('route' => 'admin.subcategories.selected.destroy')) }}
                    <button class="btn btn-sm btn-danger" data-action="confirm" data-title="Apagar selecionados"><i class="fas fa-trash-alt"></i> Apagar Selecionados</button>
                    {{ Form::close() }}
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
                {data: 'name', name:'name'},
                {data: 'category_id', name:'category_id'},
                @if(Auth::user()->isAdmin())
                {data: 'seller', name:'seller'},
                
                @endif
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
            ],
            ajax: {
                url: "{{ route('admin.subcategories.datatable') }}",
                type: "POST",
                data: function (d) {
                    d.seller   = $('select[name=seller]').val();
                    d.category  = $('select[name=category]').val();
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