@extends('customer.layouts.master')
@section ('title')
Resumo
@stop
@section('content')
<div class="row row-5" style="padding-right:35px !important">
<div class="col-sm-12 about-box">
<div class="info-title"><span>Pagamentos</span></div>
  
    <div class="table-responsive">
                    <table id="datatable" class="table" style="margin-bottom:0px !important">
                    <thead>
    <tr>
      <th scope="col">Pedido</th>
      <th scope="col">Preço</th>
      <th scope="col">Quantidade</th>
    </tr>
  </thead>
  <tbody>
    @foreach($orderlines as $orderline)
    <tr>
      <td scope="row">{{$order->id}}</td>
      <td>€{{ number_format($orderline->total_price, 2,',','.') }}</td>
      <td class=" w-1 subTotal">{{$orderline->quantity}}</td>
    </tr>
    @endforeach
  </tbody>
                    </table>
                    <hr style="margin-bottom:5px !important;margin-top:5px !important;"></hr>
                    <div class="pull-right" style="padding-right:8px;margin-top:0 !important">
                    <p class="text-right totalPrice"  id="total_price" ><h4 style="margin-top:0 !important">Total: €{{number_format($order->total_price,2,',','.')}}</h4></p>
                    <p class="text-right totalPrice"  id="total_price" ><h4 style="margin-top:0 !important">Total: €{{number_format($order->delivery_fee,2,',','.')}}</h4></p>
                   <p class="text-right totalPrice"  id="total_price" ><h2 style="margin-top:0 !important">Total: €{{number_format($orderTotalWithDeliveryFee,2,',','.')}}</h2></p></div>
                </div>
</div>
</div>
<div class="row row-5" style="padding-right:35px !important">
<div class="info-title">Dados de Pagamento</div>
<div class="col-sm-12 about-box text-center" >
  
  @if($paymentType == "DINHEIRO")
  <h1>A pagar no Ato de Entrega</h1>
  <h3>Montante: €{{ number_format($payment->amount, 2,',','.') }}</h3>
  @else
    <h3>Entidade: {{$payment->entity}}</h3>
    <h3>Referência: {{$payment->reference}}</h3>
    <h3>Montante: €{{ number_format($payment->amount, 2,',','.') }}</h3>
  @endif
  
</div>
</div>

<div class="info-title">Observações</div>
<div class="col-sm-12 about-box" >
 @if($order->comments != null) 
    <h2 style="margin-left:10px">{{$order->comments}}</h2>
  @else
    <h2 style="margin-left:10px">Sem observações</h2>
  @endif
</div><a type="button"  class="btn btn-edit" style="margin-top:0px !important" href="{{route('customer.cart.deleteCartAndPayment')}}">Continuar</a>
</div>


</div>
</div>
@stop
@section('scripts')
<script>
$(document).ready(function(){
    
    $.ajax({
           type:"get",
           url:"{{url('/savePreviousPage')}}",
           data:orderId
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