@extends('customer.layouts.master')
@section ('title')
Carrinho
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
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    @foreach($cartProducts as $cartProduct)
    <tr>
      <th scope="row" >{{$cartProduct->id}}</th>
      <td class="w-1"><img class="center-block" src="<?=Croppa::url($cartProduct->options->image,20,20)?>"/></td>
      <td>{{$cartProduct->name}}</td>
      <td class=" w-1 subTotal" id="subTotal_{{$cartProduct->id}}" value="{{$cartProduct->subtotal}}">€{{$cartProduct->subtotal}}</td>
      <td class="w-1"><input id="quantity_{{$cartProduct->id}}" rowId="{{$cartProduct->rowId}}" autocomplete="off"  class="form-control quantity" type="number" value="{{$cartProduct->quantity}}" min="1"></input></td>
      <td class="w-1"><a type="button" class="btn btn-default btn-danger" href="{{route('customer.cart.destroyRow',$cartProduct->rowId)}}" ><i class="fas fa-trash-alt" style="color:white"></i></td>
    </tr>
    @endforeach
  </tbody>
</table>

<p class="text-right totalPrice" id="total_price">Total: €{{$orderTotal}}</p>
<a type="button" class="btn btn-default btn-danger" href="{{route('customer.cart.cleanCart')}}" style="color:white"><i class="fas fa-trash-alt" style="color:white"></i> Limpar Carrinho</a>
<a type="button" class="btn btn-default btn-success" href="{{route('customer.cart.payment')}}" style="color:white"><i class="fas fa-plus" style="color:white"></i> Continuar</a>
<a type="button" class="btn btn-default" href="{{route('customer.products.index')}}">Voltar</a>
@stop
@section('scripts')
<script type="text/javascript">
 $('.quantity').change(function(){
   var input_id = $(this).attr('id');
   var rowId = $(this).attr('rowId');
  	var id = input_id.slice(9, 20);
    var quantity = $(this).val();
    $.ajax({
           type:"get",
           url:"{{url('customer/cart/updatePrice')}}/"+rowId+"/"+ id + "/" +quantity,
           success:function(res)
           {    
                if(res){  
                  
               document.getElementById('subTotal_'+id).innerHTML =  '€'+res;
               document.getElementById('subTotal_'+id).setAttribute('value',res);

               var sum = 0;
                $(".subTotal").each(function(){
                  
                    var value = $(this).attr('value');
                    sum += +value;
                    total = sum.toFixed(2);
                });
                document.getElementById('total_price').innerHTML= 'Total: €'+total;
              
               }
           },
           error:function()
           {
                
           }   
        });
 });


</script>
@stop