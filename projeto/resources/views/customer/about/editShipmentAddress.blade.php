@extends('customer.layouts.master')
@section('title')
Morada de Envio
@stop
@section('content')
<div class="about-page">
<div class="row row-5">
    <div class="col-sm-12 about-box">
    <div class="info-title"><span>Morada de Envio</span></div>
    <div class="col-sm-12 box-details">
        @include('customer.about.editAddress')

        
        @if($previousPage == route('customer.shipmentAddresses'))
        <a class="btn btn-edit pull-right" href="{{route('customer.shipmentAddresses')}}">Voltar</a>
    @elseif($previousPage == route('customer.cart.payment'))
        <a class="btn btn-edit pull-right" href="{{route('customer.cart.payment')}}">Voltar</a>
    @endif
    </div>
    </div>
   
@stop