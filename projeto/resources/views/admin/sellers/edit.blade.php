@section('title')
    Vendedores
@stop

@section('content-header')
    Vendedores
    <small>
        {{ $action }}
    </small>
@stop

@section('breadcrumb')
    <li>Configurações</li>
    <li>
        <a href="{{ route('admin.sellers.index') }}">
            Vendedores
        </a>
    </li>
    <li class="active">
        {{ $action }}
    </li>
@stop

@section('content')
    @if($seller->exists)
    <div class="row">
        <div class="col-md-12">
            <div class="box no-border m-b-15">
                <div class="box-body p-5">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="pull-left m-r-10">
                                @if($seller->filepath)
                                    <img src="{{ asset($seller->getCroppa(200, 200)) }}" id="" style="border:none" class="w-60px"/>
                                @else
                                    <img src="{{ asset('assets/img/default/avatar.png') }}" style="border:none" class="w-60px"/>
                                @endif
                            </div>
                            <div class="pull-left">
                                <h4 class="m-t-5 pull-left">{{ $seller->name }}</h4>
                                <div class="clearfix"></div>
                                <ul class="list-inline m-b-0">
                                    <li>
                                        @if($seller->active)
                                            <span class="label label-success">
                                                <i class="fa fa-check-circle"></i> Ativo
                                            </span>
                                        @else
                                            <span class="label label-danger">
                                                <i class="fa fa-ban"></i> Bloqueado
                                            </span>
                                        @endif
                                    </li>
                                    <li>Criado em <b>{{ $seller->created_at }}</b></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            @if($seller->password)
                                <a href="{{ route('admin.sellers.remote-login', $seller->id) }}" class="btn btn-sm btn-warning pull-right"  data-method="post" data-confirm-title="Iniciar Sessão Remota" data-confirm-class="btn-success" data-confirm-label="Iniciar Sessão" data-confirm="Pretende iniciar sessão como {{ $seller->name }}?" target="_blank">
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
        @if($seller->exists)
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
                    @include('admin.sellers.partials.info')
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