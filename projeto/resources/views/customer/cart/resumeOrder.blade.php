@extends('customer.layouts.master')
@section ('title')
Resumo
@stop
@section('content')
<table class="table table-striped table-bordered refresh" style="width:100%">
  <thead>
    <tr>
      <th scope="col">Imagem</th>
      <th scope="col">Nome</th>
      <th scope="col">Preço</th>
      <th scope="col">Quantidade</th>
    </tr>
  </thead>
  <tbody>
    @foreach($cartProducts as $cartProduct)
    <tr>
      <td class="w-1"><img class="center-block" src="<?=Croppa::url($cartProduct->options->image,20,20)?>"/></td>
      <td>{{$cartProduct->name}}</td>
      <td class=" w-1 subTotal" id="subTotal_{{$cartProduct->id}}" value="{{$cartProduct->subtotal}}">€{{number_format($cartProduct->subtotal,2,',','.')}}</td>
      <td class="w-1">{{$cartProduct->quantity}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
<div class="col-sm-12">
    <p><img id="miniature" src="<?=Croppa::url($paymentMethod->filepath,50,50)?>"/></p>
    <label for="miniature">{{$paymentMethod->name}}</label>
    <p class="totalPrice" id="total_price">€{{$orderTotal}}</p>
</div>
<p class="text-right totalPrice" id="total_price">Total: €{{$orderTotal}}</p>
<div>
  <label>Morada de Faturação</label>
    {{$billingAddress[0]['address']}}
    {{$billingAddress[0]['postal_code']}}
    {{$billingAddress[0]['city']}}
  <label>Morada de Envio</label>
    {{$shipmentAddress[0]['address']}}
    {{$shipmentAddress[0]['postal_code']}}
    {{$shipmentAddress[0]['city']}}
</div>
<a type="button" class="btn btn-default btn-success" href="{{route('customer.cart.finalizeOrder')}}" style="color:white"><i class="fas fa-plus" style="color:white"></i> Confirmar Pedido</a>
<a type="button" class="btn btn-default" href="{{route('customer.cart.payment')}}">Voltar</a>
@stop
@section('scripts')

@stop