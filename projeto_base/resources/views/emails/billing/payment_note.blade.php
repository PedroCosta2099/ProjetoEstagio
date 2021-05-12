@extends('layouts.email')

@section('content')
    <h5 style="font-size: 16px">Nota de Pagamento {{ $paymentNote->code }}</h5>
    <p>
        Estimado fornecedor,
        <br/>
        Junto enviamos a nossa nota de pagamento N.º {{ $paymentNote->code }} no valor de {{ money($paymentNote->total, Setting::get('app_currency')) }}
    </p>
@stop