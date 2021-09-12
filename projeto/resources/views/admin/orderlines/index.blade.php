@section('title')
    Linhas de Pedidos
@stop

@section('content-header')
Linhas de Pedidos
@stop

@section('breadcrumb')
    <li class="active">Configurações</li>
    <li class="active">Linhas de Pedidos</li>
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
                                <th class="w-1">Pedido</th>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th>IVA</th>
                                <th>Quantidade</th>
                                @if(Auth::user()->isAdmin())
                                
                                <th>Vendedor</th>
            
                                @endif
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
                    {{ Form::open(array('route' => 'admin.orderlines.selected.destroy')) }}
                    <button class="btn btn-sm btn-danger" data-action="confirm" data-title="Apagar selecionados"><i class="fas fa-trash-alt"></i> Apagar Selecionados</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
 
</script>
<script type="text/javascript">
    var oTable
    $(document).ready(function () {
        oTable = $('#datatable').DataTable({
            order: [[1, "desc"]],
            columns: [
                {data: 'select', name: 'select', orderable: false, searchable: false},
                {data: 'order_id', name: 'order_id'},
                {data: 'name', name:'name', searchable: false},
                {data: 'total_price', name:'total_price', orderable: false, searchable: false},
                {data: 'vat', name:'vat', orderable: false, searchable: false},
                {data: 'quantity',name: 'quantity'},
                @if(Auth::user()->isAdmin())
                    {data: 'seller_id',name:'seller_id'},
                @endif
                {data:'created_at',name:'created_at'},
                {data: 'status_id',name:'status_id'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
            ],
            ajax: {
                url: "{{ route('admin.orderlines.datatable') }}",
                type: "POST",
                data: function (d) {
                    d.status   = $('select[name=status]').val();
                    d.seller  = $('select[name=seller]').val();
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