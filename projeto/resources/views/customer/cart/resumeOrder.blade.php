@extends('customer.layouts.master')
@section ('title')
Resumo
@stop
@section('content')
<table class="table table-striped table-bordered refresh" style="width:50%">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Imagem</th>
      <th scope="col">Nome</th>
      <th scope="col">Preço</th>
      <th scope="col">Quantidade</th>
    </tr>
  </thead>
  <tbody>
    @foreach($cartProducts as $cartProduct)
    <tr>
      <th scope="row" >{{$cartProduct->id}}</th>
      <td class="w-1"><img class="center-block" src="<?=Croppa::url($cartProduct->options->image,20,20)?>"/></td>
      <td>{{$cartProduct->name}}</td>
      <td class=" w-1 subTotal" id="subTotal_{{$cartProduct->id}}" value="{{$cartProduct->subtotal}}">€{{$cartProduct->subtotal}}</td>
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
<a type="button" class="btn btn-default btn-success" href="{{route('customer.cart.finalizeOrder')}}" style="color:white"><i class="fas fa-plus" style="color:white"></i> Confirmar Pedido</a>
<a type="button" class="btn btn-default" href="{{route('customer.cart.payment')}}">Voltar</a>
@stop
@section('scripts')

@stop