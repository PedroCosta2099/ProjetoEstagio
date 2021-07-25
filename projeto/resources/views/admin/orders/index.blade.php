@section('title')
    Pedidos
@stop

@section('content-header')
    Pedidos
    
@stop

@section('breadcrumb')
    <li class="active">Configurações</li>
    <li class="active">Pedidos</li>
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
                        <strong>Estado</strong> 
                        {{ Form::select('status', array('' => 'Todos') + $status, Request::has('status') ? Request::get('status') : null, array('class' => 'form-control input-sm filter-datatable select2')) }}
                    </li>
                </ul>
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w-1">{{ Form::checkbox('select-all', '') }}</th> 
                                <th>Pedido</th>
                                <th>Preço</th>
                                <th>IVA</th>
                                <th>Data</th>
                                <th class="w-1">Estado</th>
                                <th class="w-65px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="selected-rows-action hide">
                    {{ Form::open(array('route' => 'admin.orders.selected.destroy')) }}
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
                {data: 'id', name: 'id',orderable:false,searchable:true},
                {data: 'total_price', name:'price', orderable: false, searchable: false},
                {data: 'vat', name:'vat', orderable: false, searchable: false},
                {data: 'created_at',name:'created_at',orderable:true,searchable:true},
                {data: 'status_id', name:'status_id'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
            ],
            ajax: {
                url: "{{ route('admin.orders.datatable') }}",
                type: "POST",
                data: function (d) {
                    d.status   = $('select[name=status]').val();
                    
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
       
    });
</script>
@stop