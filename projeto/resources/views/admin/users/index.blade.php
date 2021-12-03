@section('title')
    Colaboradores
@stop

@section('content-header')
    Colaboradores
@stop

@section('breadcrumb')
    <li class="active">Configurações</li>
    <li class="active">Colaboradores</li>
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
                        <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-plus"></i> Novo
                        </a>
                    </li>
                    <li>
                        <strong>Perfil</strong>
                        {{ Form::select('role', array('' => 'Todos') + $roles, Request::has('role') ? Request::get('role') : null, array('class' => 'form-control input-sm filter-datatable select2')) }}
                    </li>
                    <li>
                        <strong>Estado</strong> 
                        {{ Form::select('active', array('' => 'Todos') + $status, Request::has('active') ? Request::get('active') : null, array('class' => 'form-control input-sm filter-datatable select2')) }}
                    </li>
                    <li>
                        <strong>Vendedor</strong>
                        {{ Form::select('seller', array('' => 'Todos') + $seller, Request::has('seller') ? Request::get('seller') : null, array('class' => 'form-control input-sm filter-datatable select2')) }}
                    </li>
                </ul>
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-dashed table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w-1">{{ Form::checkbox('select-all', '') }}</th>
                                <th></th>
                                <th>Nome</th>
                                <th>Perfís</th>
                                <th class="w-1">Vendedor</th>
                                <th class="w-1">Estado</th>
                                <th class="w-110px">Último Login</th>
                                <th class="w-70px">Criado em</th>
                                <th class="w-65px">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="selected-rows-action hide">
                    {{ Form::open(array('route' => 'admin.users.selected.destroy')) }}
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
                {data: 'roles', name: 'roles', orderable: false, searchable: false},
                {data:'seller_id',name:'seller_id'},
                {data: 'active', name: 'active', orderable: false, searchable: false},
                {data: 'last_login', name: 'last_login'},
                {data: 'created_at', name: 'created_at'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false},
                {data: 'email', name: 'email', visible: false},
            ],
            ajax: {
                url: "{{ route('admin.users.datatable') }}",
                type: "POST",
                data: function (d) {
                    d.role   = $('select[name=role]').val();
                    d.active = $('select[name=active]').val();
                    d.seller = $('select[name=seller]').val();

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
    $(document).ready(function () {
        $(".select2").select2({
            language: 'pt'
        });
    });
</script>
@stop