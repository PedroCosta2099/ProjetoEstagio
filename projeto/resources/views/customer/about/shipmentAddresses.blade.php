@extends('customer.layouts.master')
@section('title')
Morada de Envio
@stop
@section('styles')
<style>
    hr{
        border-color:#aaa;
        box-sizing:border-box;
        width:100%;
    }
</style>
@stop
@section('content')
<div class="about-page">
<div class="row row-5">
    <div class="col-sm-12 about-box">
    <div class="info-title"><span>Morada de Envio</span></div>
    <div class="pull-right">
        <a class="btn btn-success" style="margin-top:20px" href="#"><i class="fas fa-plus"></i> Novo</a>
        @if($previousPage == route('customer.cart.payment'))
            <a class="btn btn-edit " href="{{route('customer.cart.payment')}}">Voltar</a>
        @elseif($previousPage == route('customer.about'))
            <a class="btn btn-edit " href="{{route('customer.about')}}">Voltar</a>
        @endif
            
    </div>
    <div class="col-sm-12 box-details" style="padding-top:0 !important;padding-right:30px !important">
        <div class="m-l-5 m-b-5"><h4><b>Morada de Envio Atual</b></h4></div>
        <div class="col-sm-12">
        <hr></hr>
            <label>Morada</label>
            <h6>{{$actualShipmentAddress['address']}}</h6>
            <label>Código Postal</label>
            <h6>{{$actualShipmentAddress['postal_code']}}</h6>
            <label>Localidade</label>
            <h6>{{$actualShipmentAddress['city']}}</h6>
            <div><a style="margin-bottom:20px" href="{{route('customer.editShipmentAddress',$actualShipmentAddress['id'])}}" class="btn-edit pull-left">Alterar</a></div>
            
    </div>
    <div class="col-sm-12 m-t-5">
        <div class="m-l-5 m-b-5"><h4><b>Outras Moradas</b></h4></div>
        <hr></hr>
        @foreach($addresses as $address)
        <div class="col-sm-12" style="margin-top:10px">
            <label>Morada</label>
            <h6>{{$address['address']}}</h6>
            <label>Código Postal</label>
            <h6>{{$address['postal_code']}}</h6>
            <label>Localidade</label>
            <h6>{{$address['city']}}</h6>
            <div><a style="margin-bottom:20px" href="{{route('customer.updateActualShipmentAddress',$address['id'])}}" class="btn-edit pull-left">Definir como Morada de Envio</a></div>
            <div><a style="margin-bottom:20px" href="{{route('customer.editShipmentAddress',$address['id'])}}" class="btn-edit pull-left">Alterar</a></div>
            <hr></hr>
        </div>
        @endforeach
    </div>
    
    </div>
   
@stop