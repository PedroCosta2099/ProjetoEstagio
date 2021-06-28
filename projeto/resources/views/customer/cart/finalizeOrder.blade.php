@extends('customer.layouts.master')
@section ('title')
Resumo
@stop
@section('content')
<table class="table table-striped table-bordered refresh" style="width:50%">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Preço</th>
      <th scope="col">Quantidade</th>
    </tr>
  </thead>
  <tbody>
    @foreach($orderlines as $orderline)
    <tr>
      <th scope="row" >{{$order->id}}</th>
      <td>€{{ number_format($orderline->total_price, 2,',','.') }}</td>
      <td class=" w-1 subTotal">{{$orderline->quantity}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
<div class="col-sm-12">
    <p>{{$payment->entity}}</p>
    <p>{{$payment->reference}}</p>
    <p>€{{ number_format($payment->amount, 2,',','.') }}</p>
</div>
<a type="button"  class="btn btn-default" href="{{route('customer.cart.deleteCartAndPayment')}}">Continuar</a>
@stop
@section('scripts')

@stop