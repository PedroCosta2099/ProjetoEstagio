<!DOCTYPE html>
<html lang="{{ Lang::locale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('/favicon.png') }}"/>
    <title>@yield('title') {{ config('app.name') }}</title>
    <meta http-equiv="content-language" content="{{ Lang::locale() }}">
    <meta name="author" content="ENOVO">
    <meta name="og:url" content="{{ Request::fullUrl() }}">
    @yield('metatags')

    @if(config('app.source') == 'papiro')
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,900" rel="stylesheet">
    @endif
    {{ Html::style('/assets/admin/fonts/exo_2.css') }}
    {{ Html::style('/vendor/font-awesome/css/all.min.css') }}
    {{ Html::style('/vendor/flag-icon-css/css/flag-icon.min.css') }}
    {{ Html::style('/vendor/bootstrap/dist/css/bootstrap.min.css') }}
    {{ Html::style('/vendor/iCheck/skins/minimal/blue.css') }}
    {{--{{ Html::style('/vendor/intl-tel-input/build/css/intlTelInput.min.css') }}--}}

    {!! Minify::stylesheet([
            '/vendor/datepicker/datepicker3.css',
            '/vendor/datatables/dataTables.bootstrap.css',
            '/vendor/select2/dist/css/select2.min.css',

            '/vendor/jasny-bootstrap/dist/css/jasny-bootstrap.min.css',
            '/vendor/magicsuggest/magicsuggest-min.css',
            '/vendor/animate.css/animate.css',

            '/assets/admin/css/helper.css',
            '/assets/css/helper.css',
            '/assets/admin/css/skins/' . app_skin() . '.css'
        ])->withFullUrl()
    !!}

    @yield('styles')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    @yield('content')

    {!! Minify::javascript([
        '/vendor/jQuery/jquery-3.4.0.min.js',
        '/vendor/bootstrap/dist/js/bootstrap.min.js',
        '/vendor/datatables/jquery.dataTables.min.js',
        '/vendor/datatables/dataTables.bootstrap.min.js',
        '/vendor/pace/pace.min.js',
        '/vendor/bootstrap-growl/jquery.bootstrap-growl.min.js',
        '/vendor/iCheck/icheck.min.js',
        '/vendor/select2/dist/js/select2.min.js',
        '/vendor/select2/dist/js/i18n/'.Lang::locale().'.js',
        '/vendor/select2-multiple/select2-multiple.js',
        '/vendor/magicsuggest/magicsuggest-min.js',
        '/vendor/datepicker/bootstrap-datepicker.js',
        '/vendor/datepicker/locales/bootstrap-datepicker.'.Lang::locale().'.js',
        '/vendor/bootbox/bootbox.js',
        '/vendor/jasny-bootstrap/js/fileinput.js',
        '/vendor/jquery-ujs/src/rails.js',
        '/vendor/moment/moment.min.js',
        '/vendor/push.js/bin/push.js',
        '/vendor/pusher/pusher.min.js',
        '/vendor/jquery-mask-plugin/dist/jquery.mask.js',
        '/vendor/jsvat/jsvat.js',

        '/assets/admin/js/helper.js',
        '/assets/admin/js/validator.js',

        ])->withFullUrl()
    !!}
    <script>
        $("#menu-{{ @$menuOption }}").addClass('active');
        $("#menu-{{ @$sidebarActiveOption }}").addClass('active');
        $('a[href="#tab-{{ Request::get("tab") }}"]').trigger('click');
    </script>
    <script>
        $(document).ready(function(){
            @if (Session::has('success'))
            $.bootstrapGrowl("<i class='fas fa-check'></i> {{ Session::get('success') }}&nbsp;&nbsp;", {type: 'success', align: 'center', width: 'auto', delay: 8000});
            @endif

            @if (Session::has('error'))
            $.bootstrapGrowl("<i class='fas fa-exclamation-circle'></i> {{ Session::get('error') }}&nbsp;&nbsp;", {type: 'error', align: 'center', width: 'auto', delay: 8000});
            @endif

            @if (Session::has('warning'))
            $.bootstrapGrowl("<i class='fas fa-exclamation-triangle'></i> {{ Session::get('warning') }}&nbsp;&nbsp;", {type: 'warning', align: 'center', width: 'auto', delay: 8000});
            @endif

            @if (Session::has('info'))
            $.bootstrapGrowl("<i class='fas fa-info-circle'></i> {{ Session::get('info') }}&nbsp;&nbsp;", {type: 'info', align: 'center', width: 'auto', delay: 8000});
            @endif
        })
    </script>
    @yield('scripts')
    </body>
</html>