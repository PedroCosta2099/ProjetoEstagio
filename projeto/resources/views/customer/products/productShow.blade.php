@extends('customer.layouts.master')
@section('title')
{{$product['name']}}
@stop
@section('content')
    <div class="container" style="background-color:red;padding:50px;">
		<div class="row row-5">
                <div class="col-sm-12">
	    		    <div class="col-sm-3">
                        <img class="center-block" src="<?=Croppa::url($product['filepath'],300,300)?>" id="{{$product['filename']}}"/>
                    </div>
                    <div class="col-sm-8 col-sm-offset-1">
                        <p class="text-center"><a href="{{route('customer.products.productShow',$product['id'])}}"> {{$product['name']}} </a></p>
                        <p class="text-center"> €{{ number_format($product['price'], 2,',','.') }}  </p>
                        <label>Quantidade</label>
                        <input  id="quantity" class="form-control w-50" type="number" min="1" value="1" autocomplete="off"></input>
                        <p class="text-center">
                        <a type="button" class="btn btn-default" href="{{route('customer.products.index')}}">Voltar</a>
                        <button onclick="save();" type="button" class="btn btn-default">Adicionar ao Carrinho</button>
                        </p>
                    </div>
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