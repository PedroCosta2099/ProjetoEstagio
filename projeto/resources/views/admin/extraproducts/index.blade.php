@section('title')
    Produtos Extra
@stop

@section('content-header')
    Produtos Extra
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
                        <a href="{{ route('admin.extraproducts.create') }}" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-remote">
                            <i class="fas fa-plus"></i> Novo
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.extraproducts.sort') }}" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal-remote">
                            <i class="fas fa-sort-amount-down"></i> Ordenar
                        </a>
                    </li>
                </ul>
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                                <th></th>
                                <th class="w-1">Imagem</th>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th>IVA</th>
                                <th class="w-65px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="selected-rows-action hide">
                    {{ Form::open(array('route' => 'admin.extraproducts.selected.destroy')) }}
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
                {data:'image',name:'image',orderable: false, searchable: false},
                {data: 'name', name:'name'},
                {data: 'price', name:'price', orderable: false, searchable: false},
                {data: 'vat', name:'vat', orderable: false, searchable: false},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
            ],
            ajax: {
                url: "{{ route('admin.extraproducts.datatable') }}",
                type: "POST",
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
        $()
    });
</script>
@stop