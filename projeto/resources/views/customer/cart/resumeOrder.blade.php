@extends('customer.layouts.master')
@section ('title')
Resumo
@stop
@section('styles')
<style>
  .content{
  padding-right:35px;
  }
</style>
@stop
@section('content')
<form method="post" action="{{route('customer.cart.finalizeOrder')}}">
<div class="row row-5">
<div class="col-sm-12 about-box">
<div class="info-title"><span>Pagamentos</span></div>
  
    <div class="table-responsive">
                    <table id="datatable" class="table" style="margin-bottom:0px !important">
                         <thead>
    <tr>
      <!--<th scope="col">#</th>
      <th scope="col">Imagem</th>-->
      <th scope="col">Nome</th>
      <th scope="col">Preço</th>
      <th scope="col">Quantidade</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    @foreach($cartProducts as $cartProduct)
    <tr>
      <!--<th scope="row" >{{$cartProduct->id}}</th>
      <td class="w-1"><img class="center-block" src="<?=Croppa::url($cartProduct->options->image,20,20)?>"/></td>-->
      <td>{{$cartProduct->name}}</td>
      <td class=" w-1 subTotal" id="subTotal_{{$cartProduct->id}}" value="{{$cartProduct->subtotal}}">€{{number_format($cartProduct->subtotal,2,',','.')}}</td>
      <td class="w-1"><input id="quantity_{{$cartProduct->id}}" rowId="{{$cartProduct->rowId}}" autocomplete="off"  class="form-control quantity" type="number" value="{{$cartProduct->quantity}}" min="1"></input></td>
      <td class="w-1"><a type="button" class="btn btn-default btn-danger" href="{{route('customer.cart.destroyRow',$cartProduct->rowId)}}" ><i class="fas fa-trash-alt" style="color:white"></i></td>
    </tr>
    @endforeach
  </tbody>
                    </table>
                    <hr style="margin-bottom:5px !important;margin-top:5px !important;"></hr>
                    <div class="pull-right" style="padding-right:8px;margin-top:0 !important">
                    <p class="text-right totalPrice"  id="total_price" ><h4 style="margin-top:0 !important">Preço dos Produtos: €{{$productsTotal}}</h4></p>
                
                    <p class="text-right totalPrice"  id="total_price" ><h4 style="margin-top:0 !important">Taxa de Entrega: €{{$deliveryFee}}</h4></p>
                
                   <p class="text-right totalPrice"  id="total_price" ><h2 style="margin-top:0 !important">Total: €{{$orderTotal}}</h2></p></div>
                </div>
</div>
</div>
<div class="row row-5">
  <div class="info-title"><span>Observações <i class="fa fa-info-circle" data-toggle="tooltip" title="Ex: Alergias alimentares, alimentos não pretendidos"></i></span></div>
    <div class="col-sm-12 about-box text-center">
      
      {{ Form::text('comments', null, ['class' => 'form-control','style'=>'width:100%','placeholder'=>'Insira aqui as suas observações','id'=>'comments']) }}
      
    </div>
  </div>
  <div class="row row-5">
  <div class="info-title"><span>Método de Pagamento</span></div>
    <div class="col-sm-12 about-box text-center">
  
    <p><img id="miniature" src="<?=Croppa::url($paymentMethod->filepath,50,50)?>"/></p>
    <label for="miniature">{{$paymentMethod->name}}</label>
    
    </div>
  </div>
  <div class="row row-5">
<div class="info-title"><span>Moradas</span></div>
        <div class="col-sm-12 about-box box-details">
        
        <div class="col-sm-6 m-b-5">
            <h4><b>Morada de Faturação</b></h4>
            
            <label>Morada</label>
            <h6>{{$billingAddress[0]['address']}}</h6>
            <label>Código Postal</label>
            <h6>{{$billingAddress[0]['postal_code']}}</h6>
            <label>Localidade</label>
            <h6>{{$billingAddress[0]['city']}}</h6>

            <div><a href="{{route('customer.editBillingAddress',$billingAddress[0]['id'])}}" class="btn-edit pull-left">Alterar</a></div>
        </div>
        <div class="col-sm-6 m-b-5">
            <h4><b>Morada de Envio</b></h4>
            
            <label>Morada</label>
            <h6>{{$shipmentAddress[0]['address']}}</h6>
            <label>Código Postal</label>
            <h6>{{$shipmentAddress[0]['postal_code']}}</h6>
            <label>Localidade</label>
            <h6>{{$shipmentAddress[0]['city']}}</h6>

            <div class="m-b-5"><a href="{{route('customer.shipmentAddresses')}}" class="btn-edit pull-left change">Alterar</a></div>
        </div>
    </div>
</div>

<div class="row row-5">
<button type="submit" class="btn btn-edit" style="color:white;margin-top:0px !important;margin-right:10px !important"><i class="fas fa-plus" style="color:white"></i> Confirmar Pedido</button>
<a type="button" class="btn btn-default" href="{{route('customer.cart.payment')}}">Voltar</a>
</div>

</div>

</form>



@stop
@section('scripts')
<script>
$('#submit').click(function(){
  
  var comments = $('#comments').val();
  
  $.ajax({
    type:"post",
    url:"{{url('cart/finalizeOrder')}}",
    data: comments
  });
});
$(document).ready(function(){
    
    $.ajax({
           type:"get",
           url:"{{url('/savePreviousPage')}}",
           success:function()
           {   
           },
           error:function()
           {
                
           }   
        });
        
});
</script>
@stop