<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title','Main') | Enovo Eats</title>
        <link rel="shortcut icon" type="image/png" href="{{ asset('/favicon.png') }}"/>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="csrf-param" content="_token" />
        @include('customer.partials.styles')
        @yield('styles')
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    </head>
    <body class="{{ app_skin() }} fixed sidebar-mini sidebar-collapse">
        
            @include('customer.partials.header')
            
            <div class="content-wrapper">
                
                <section class="content-header">
                    <h1>@yield('content-header')</h1>
                    @include('customer.partials.breadcrumb')
                </section>
                <section class="content">
                    @yield('content')
                </section>
            </div>
            @include('customer.partials.footer')
        

        @yield('modals')

        @include('customer.partials.scripts')
        @include('customer.partials.alerts')

    </body>
</html>
