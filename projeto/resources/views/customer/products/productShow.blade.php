@extends('customer.layouts.master')
@section('title')
{{$product['name']}}
@stop
@section('content')
    
		<div class="row" style="margin-right:50px;">
            <div class="col-sm-12">
	    		    <div class="col-sm-4" style="margin:0 auto;position:relative">
                        <img class="center-block unique-product-img" src="<?=url($product['filepath'])?>" id="{{$product['filename']}}"/>
                    </div>
                    <div class="col-sm-4 col-sm-offset-1">
                        <div class="p-4">
                            <div class="mb-3"><p><span> {{$product['name']}} </span></p></div>
                            @if($product['actual_price'] == $product['price'])
                            €{{ number_format($product['price'], 2,',','.') }} 
                            @else
                            <s class="m-r-5">€{{ number_format($product['price'], 2,',','.') }} </s>
                            €{{ number_format($product['actual_price'], 2,',','.') }} 
                            @endif
                                <p>{{$product['description']}}</p>

                                <form class="d-flex justify-content-left input-group">
                                <input  id="quantity" style="width:100px" class="form-control" type="number" min="1" value="1" autocomplete="off">
                                <!--<a type="button" class="btn btn-default" href="{{route('customer.products.index')}}">Voltar</a>-->
                                <button onclick="save();" type="button" class="btn btn-primary m-l-5">Adicionar ao Carrinho <i class="fas fa-shopping-cart ml-1"></i></button>
                                </form>
                        </div>
                    </div>
	    		</div>
        </div>

		
@stop
@section('scripts')
<script>
    
</script>
<script>
    function save(){

        var id = {{$product['id'] }};    
        var quantity = document.getElementById('quantity').value;
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {};
            xhr.open('GET', '/cart/insert/'+id+'/'+quantity);
            xhr.send();
            
    }
</script>       
@stop       