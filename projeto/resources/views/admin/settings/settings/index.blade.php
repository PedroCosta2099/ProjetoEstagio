@section('title')
Definições Gerais
@stop

@section('content-header')
Definições Gerais 
@stop

@section('breadcrumb')
<li>Administração</li>
<li class="active">
    Definições Gerais
</li>
@stop

@section('content')
    {{ Form::open(['route' => 'admin.settings.store', 'class' => 'form-horizontal', 'files' => true]) }}
    <div class="row box-settings">
        <div class="col-md-3 col-lg-2 p-r-0">
            <div class="box box-solid">
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active">
                            <a href="#tab-geral" data-toggle="tab"><i class="fas fa-fw fa-globe"></i> Geral</a>
                        </li>
                        @if(Auth::user()->hasRole('administrator'))
                        <li>
                            <a href="#tab-maintenance" data-toggle="tab"><i class="fas fa-fw fa-wrench"></i> Manutenção e Erros</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-lg-10">
            <div class="tab-content">
                <div class="active tab-pane" id="tab-geral">
                    @include('admin.settings.settings.partials.geral')
                </div>
                @if(Auth::user()->hasRole('administrator'))
                <div class="tab-pane" id="tab-maintenance">
                    @include('admin.settings.settings.partials.maintenance')
                </div>
                @endif
            </div>
        </div>
    </div>
    {{ Form::close() }}
@stop

@section('styles')
    {{ HTML::style('vendor/ios-checkbox/dist/css/iosCheckbox.min.css')}}
@stop

@section('scripts')
{{ HTML::script('vendor/ios-checkbox/dist/js/iosCheckbox.min.js')}}
{{ HTML::script('vendor/ckeditor/ckeditor.js')}}
<script>

    $(".ios").iosCheckbox();

    $(document).on('click', '.btn-remove-bg-img', function () {
        $('[name="delete_pdf_bg"]').val(1);
    })

    $(document).on('mouseover', '.select2-results__option', function(){
        var skinText = $(this).html();
        var skin = $('[name="app_skin"] option, [name="customization_app_skin"] option').filter(function () { return $(this).html() == skinText; }).val();
        var currentSkin = $('.skin-preview').data('current-skin');

        $('#try-skin').remove();

        $('.skin-master').parent().removeClass().addClass(skin)
        $('body').addClass(skin)
        $("head").append("<link id='try-skin' href='/assets/admin/css/skins/"+skin+".css' type='text/css' rel='stylesheet' />");
    });

    //Adding not to the query selector only selects input's without the *dont-style* class
    $('input').iCheck({
        labelHover: false,
        cursor: true
    });

    @if(Auth::user()->isAdmin())
    $.post('{{ route('admin.settings.directory.load') }}', function(data){
        $('.storage-dir-content').html(data.storage)
        $('.uploads-dir-content').html(data.uploads)
    }).fail(function(){
        var html = '<div class="text-center text-red m-t-20">' +
            '<i class="fas fa-exclamation-circle fs-20"></i><br/>' +
            'Erro de carregamento' +
            '</div>';
        $('.storage-dir-content,.uploads-dir-content').html(html)
    })
    @endif
</script>
@stop