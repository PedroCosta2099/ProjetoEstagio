@extends('customer.layouts.master')
@section('title')
{{$product['name']}}
@stop
@section('content')
    <div class="container">
		<div class="row">
                <div class="col-sm-12">
	    		    <div class="col-sm-3">
                        <img class="center-block" src="<?=Croppa::url($product['filepath'],200,200)?>" id="{{$product['filename']}}"/>
                        <p class="text-center"><a href="{{route('customer.products.productShow',$product['id'])}}"> {{$product['name']}} </a></p>
                        <p class="text-center"> €{{ number_format($product['price'], 2,',','.') }}  </p>
                        <label>Quantidade</label>
                        <input  id="quantity" class="form-control" type="number" min="1" value="1" autocomplete="off"></input>
                        <p class="text-center">
                        <a type="button" class="btn btn-default" href="{{route('customer.products.index')}}">Voltar</a>
                        <button onclick="save()" type="button" class="btn btn-default">Adicionar ao Carrinho</button>
                        </p>
	    		    </div>
                </div>
        </div>
	</div>		
@stop
@section('scripts')
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