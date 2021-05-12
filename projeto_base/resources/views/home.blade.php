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
        <h1>Esta é uma página HTML de teste</h1>
        <h3>Acesso ao backoffice</h3>
        <p>
            <a href="{{ route('admin.dashboard') }}">{{ route('admin.dashboard') }}</a>
            <br/>
            E-mail: admin@enovo.pt<br/>
            Password: teste
        </p>
        <hr/>
        <h3>Exemplo de ciclo foreach - Lista de utilizadores</h3>
        @if(!$users->isEmpty())
            @foreach($users as $user)
                <div>{{ $user->name }} - {{ $user->email }}</div>
            @endforeach
        @endif
    </div>
</div>
@stop

@section('scripts')
    <script>
        //Escrever aqui scripts exclusivos apenas desta página HTML
    </script>
@stop