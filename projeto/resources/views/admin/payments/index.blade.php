@section('title')
    Pagamentos
@stop

@section('content-header')
    Pagamentos
@stop

@section('breadcrumb')
    <li class="active">Configurações</li>
    <li class="active">Pagamentos</li>
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
                </ul>
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                                
                                <th>ID</th>
                                <th>Pedido</th>
                                <th>Método de Pagamento</th>
                                <th>Entidade</th>
                                <th>Referência</th>
                                <th>Número de Telemóvel</th>
                                <th>Montante</th>
                                <th class="w-1">Estado do Pagamento </th>
                                <th class="w-1"></th>
                                <th class="w-65px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="selected-rows-action hide">
                    {{ Form::open(array('route' => 'admin.payments.selected.destroy')) }}
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
                {data: 'id', name: 'id'},
                {data: 'order_id',name:'order_id'},
                {data: 'payment_type_id', name:'payment_type_id'},
                {data: 'entity', name:'entity'},
                {data: 'reference', name:'reference'},
                {data: 'phone_number', name:'phone_number'},
                {data: 'amount', name:'amount'},
                {data: 'payment_status_id',name:'payment_status_id'},
                {data: 'pay', name:'pay',orderable:false,searchable:false},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
            ],
            ajax: {
                url: "{{ route('admin.payments.datatable') }}",
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
    });
</script>
@stop