@section('title')
    Vendedores
@stop

@section('content-header')
    Vendedores
@stop

@section('breadcrumb')
    <li class="active">Configurações</li>
    <li class="active">Vendedores</li>
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
                        <a href="{{ route('admin.sellers.create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> Novo
                        </a>
                    </li>
                    <li>
                        <strong>Estado</strong> 
                        {{ Form::select('active', array('' => 'Todos') + $status, Request::has('active') ? Request::get('active') : null, array('class' => 'form-control input-sm filter-datatable')) }}
                    </li>
                   
                </ul>
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                                <th></th>
                                <th>Nome</th>
                                <th>Telefone</th>
                                <th>NIF</th>
                                <th>Morada</th>
                                <th>Tempo mínimo de entrega</th>
                                <th>Tempo máximo de entrega</th>
                                <th>Taxa de Entrega</th>
                                <th class="w-1">Estado</th>
                                <th class="w-70px">Criado em</th>
                                <th class="w-65px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="selected-rows-action hide">
                    {{ Form::open(array('route' => 'admin.sellers.selected.destroy')) }}
                    <button class="btn btn-sm btn-danger" data-action="confirm" data-title="Apagar selecionados"><i class="fa fa-trash"></i> Apagar Selecionados</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        var oTable = $('#datatable').DataTable({
            columns: [
                {data: 'select', name: 'select', orderable: false, searchable: false},
                {data: 'id', name: 'id', visible: false},
                {data: 'name', name: 'name'},
                {data: 'phone',name:'phone'},
                {data: 'nif',name:'nif'},
                {data : 'address',name:'address'},
                {data: 'minimum_delivery_time', name:'minimum_delivery_time'},
                {data: 'maximum_delivery_time', name:'maximum_delivery_time'},
                {data: 'delivery_fee', name:'delivery_fee'},
                {data: 'active', name: 'active', orderable: false, searchable: false},
                {data: 'created_at', name: 'created_at'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
            ],
            ajax: {
                url: "{{ route('admin.sellers.datatable') }}",
                type: "POST",
                data: function (d) {
                    d.role   = $('select[name=role]').val();
                    d.active = $('select[name=active]').val();
                    d.shop   = $('select[name=shop]').val();
                },
                complete: function () {
                    $('input').iCheck(Init.iCheck());
                },
                error: function () {
                    $.bootstrapGrowl("<i class='fa fa-exclamation-circle'></i> Ocorreu um erro interno ao obter os dados da tabela.",
                            {type: 'error', align: 'center', width: 'auto', delay: 8000});
                }
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