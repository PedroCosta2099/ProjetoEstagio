@extends('customer.layouts.master')
@section('title')
My Enovo Eats
@stop
@section('content')
<div class="row">
    <div>
        <h1>{{$customer->name}}</h1>
        <h6>{{$customer->email}}</h6>
        <h6>{{$customer->phone}}</h6>
        <h6>{{$customer->nif}}</h6>
    </div>
</div>
<div class="row">
    <h1>Morada de Faturação</h1>
    @foreach($addresses as $address)
    @if($address['billing_address'])
    <div class="col-sm-3">   
        
            <h4>{{$address['address']}}</h4>
            <h4>{{$address['postal_code']}}</h4>
            <h4>{{$address['city']}}</h4>
        
    </div>
    @endif
    @endforeach
</div>
<div class="row">
    <h1>Pedidos</h1>
    @foreach($orders as $order)
    <div class="col-sm-3">   
        
            <h4>{{$order['id']}}</h4>
            <h4>€{{$order['total_price']}}</h4>
            
        
    </div>
    @endforeach
</div>
@stop