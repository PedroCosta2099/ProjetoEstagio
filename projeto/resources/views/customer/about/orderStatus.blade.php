@extends('customer.layouts.master')
@section('title')
Estado do Pedido
@stop
@section('content')

<div style="display:flex; justify-content:center">
    <img src="{{ asset('assets/img/default/service_1.gif') }}" style="margin:0 auto !important"></img>
    
</div>
<div style="display:flex; justify-content:center">
<p><h1>{{$orderStatus['name']}}</h1></p>
</div>
@if($failed == 0)
<div class="progress" style="height:30px !important">
  <div class="progress-bar bg-warning progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:{{($orderStatus['sort']/count($status))*100}}%"></div>
</div>
@else
<div class="progress" style="height:30px !important">
  <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:100%;background-color:red"></div>
</div>
@endif
@stop