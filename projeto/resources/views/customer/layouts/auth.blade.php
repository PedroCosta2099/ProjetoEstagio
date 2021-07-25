<!DOCTYPE html>
<html lang="pt">
    <head>
        <title>Área de Cliente | {{ config('app.name') }}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}"/>

        {{ Html::style(asset('assets/customer/auth/fonts/iconic/css/material-design-iconic-font.min.css')) }}
        {{ Html::style(asset('assets/customer/auth/css/bootstrap.min.css')) }}
        {{ Html::style(asset('assets/customer/auth/css/animate.css')) }}
        {{ Html::style(asset('assets/customer/auth/css/pretty-checkbox.min.css')) }}
        {{ Html::style(asset('assets/customer/auth/css/main.css')) }}
        {{ Html::style(asset('/assets/customer/css/skins/' . app_skin() . '.css')) }}
    </head>
    <body class="{{ app_skin() }}">
        @yield('content')
        {{ Html::script(asset('assets/customer/auth/js/jquery-3.2.1.min.js')) }}
        {{ Html::script(asset('assets/customer/auth/js/main.js')) }}
    </body>
</html>