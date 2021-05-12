@extends('layouts.email')

@section('content')
    <h5 style="font-size: 16px">{{ $customerMessage->subject }}</h5>
    <p>
        Caro(a) cliente,<br/>
    </p>
    <p>
        {!! nl2br($customerMessage->message) !!}
    </p>
@stop