@section('title')
    {{ config('app.name') }} |
@stop

@section('metatags')
    <meta name="description" content="">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:image" content="{{ trans('seo.og-image.url') }}">
    <meta property="og:image:width" content="{{ trans('seo.og-image.width') }}">
    <meta property="og:image:height" content="{{ trans('seo.og-image.height') }}">
    <meta name="robots" content="index, follow">
@stop

@section('content')
<div class="container">
    <div class="col-md-12">
        <h1>Página de Utilizador</h1>
        <p> Página de Administrador
            <a href="{{ route('admin.dashboard') }}">{{ route('admin.dashboard') }}</a>
         <br/>
           
        </p>       
    </div>
</div>
@stop

@section('scripts')
    <script>
        //Escrever aqui scripts exclusivos apenas desta página HTML
    </script>
@stop