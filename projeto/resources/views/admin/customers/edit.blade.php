@section('title')
    Clientes
@stop

@section('content-header')
    Clientes
    <small>
        {{ $action }}
    </small>
@stop

@section('breadcrumb')
    <li>Configurações</li>
    <li>
        <a href="{{ route('admin.customers.index') }}">
            Clientes
        </a>
    </li>
    <li class="active">
        {{ $action }}
    </li>
@stop

@section('content')
    @if($customer->exists)
    <div class="row">
        <div class="col-md-12">
            <div class="box no-border m-b-15">
                <div class="box-body p-5">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="pull-left m-r-10">
                                @if($customer->filepath)
                                    <img src="{{ asset($customer->getCroppa(200, 200)) }}" id="" style="border:none" class="w-60px"/>
                                @else
                                    <img src="{{ asset('assets/img/default/avatar.png') }}" style="border:none" class="w-60px"/>
                                @endif
                            </div>
                            <div class="pull-left">
                                <h4 class="m-t-5 pull-left">{{ $customer->name }}</h4>
                                <div class="clearfix"></div>
                                <ul class="list-inline m-b-0">
                                    <li>
                                        @if($customer->active)
                                            <span class="label label-success">
                                                <i class="fa fa-check-circle"></i> Ativo
                                            </span>
                                        @else
                                            <span class="label label-danger">
                                                <i class="fa fa-ban"></i> Bloqueado
                                            </span>
                                        @endif
                                    </li>
                                    <li>Criado em <b>{{ $customer->created_at }}</b></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            @if($customer->password)
                                <a href="{{ route('admin.users.remote-login', $user->id) }}" class="btn btn-sm btn-warning pull-right"  data-method="post" data-confirm-title="Iniciar Sessão Remota" data-confirm-class="btn-success" data-confirm-label="Iniciar Sessão" data-confirm="Pretende iniciar sessão como {{ $user->display_name }}?" target="_blank">
                                    <i class="fa fa-sign-in bigger-120"></i> Iniciar Sessão
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row row-5">
        @if($customer->exists)
        <div class="col-md-3 col-lg-2">
            <div class="box box-solid">
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active">
                            <a href="#tab-info" data-toggle="tab"><i class="fa fa-fw fa-info-circle"></i> Dados Gerais</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-lg-10">
        @else
        <div class="col-md-12">
        @endif
            <div class="tab-content">
                <div class="active tab-pane" id="tab-info">
                    @include('admin.customers.partials.info')
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $(".select2").select2({
            language: 'pt'
        });
    });

    $('[data-dismiss="fileinput"]').on('click', function () {
        $('[name=delete_photo]').val(1);
    })
</script>
@stop