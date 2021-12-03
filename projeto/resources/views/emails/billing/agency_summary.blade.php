@extends('layouts.email')

@section('content')
<h5 style="font-size: 16px">Resumo de Faturação {{ $monthName }} {{ $data['year'] }}</h5>
<p>
    Estimado parceiro,
    <br/>
    Junto enviamos o resumo de liquidação do mês de <b>{{ $monthName }}</b> de <b>{{ $data['year'] }}</b>.
</p>
<p>
    Estes documentos estão de igual forma disponíveis a qualquer momento na sua Área de Gestão para consulta ou download.
    Para mais informações sobre a sua fatura ou resumo de envios, contacte-nos através dos canais habituais.
</p>
<br/>
@stop