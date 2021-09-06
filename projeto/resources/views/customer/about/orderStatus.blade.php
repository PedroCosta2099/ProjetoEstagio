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
  <div class="progress-bar bg-warning progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:{{($orderStatus['sort']/count($status))*100}}%;"></div>
</div>
<div class="text-center" style="padding-bottom:10px;"><button class="btn btn-edit" id="show" onclick="data()">Ver dados de Pagamento</button></div>

<div class="cont text-center" id="cont" style="padding-top:0px !important;visibility:hidden">
  <div class="info-title">Dados de Pagamento</div>
  <div style="padding-top:10px;">
  <p>Entidade: {{$payment->entity}}</p>
  <p>Referência: {{$payment->reference}}</p>
  <p>Montante: €{{number_format($payment->amount,2,',','.')}}</p>
</div>
</div>


@else
<div class="progress" style="height:30px !important">
  <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:100%;background-color:red"></div>
</div>
@endif
@stop
@section('scripts')
<script>
  function data()
  {
    if(document.getElementById('cont').style.visibility == "visible")
    {
      document.getElementById('cont').style.visibility = "hidden";
      document.getElementById('show').innerHTML = "Ver dados de Pagamento";
    }
    else
    {
    document.getElementById('cont').style.visibility = "visible";
    document.getElementById('show').innerHTML = "Esconder";
    }
     
  }
  </script>
@stop