@extends('customer.layouts.master')
@section ('title')
Carrinho
@stop
@section('content')
<table class="table table-striped table-bordered">
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
      <th scope="row">{{$cartProduct->id}}</th>
      <td><img class="center-block" src="<?=Croppa::url($cartProduct->options->image,20,20)?>"/></td>
      <td>{{$cartProduct->name}}</td>
      <td>{{$cartProduct->subtotal}}</td>
      <td>{{$cartProduct->quantity}}</td>
    </tr>
    @endforeach
  </tbody>
</table>

Total: €{{$orderTotal}}

@stop