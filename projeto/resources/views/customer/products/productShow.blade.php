@extends('customer.layouts.master')
@section('title')
{{$product['name']}}
@stop
@section('styles')
<style>
    .contProduct{
    
    
    background:white;
    box-shadow: 1px 3px 13px 0px #524e4e70;
    
}
.txt-color{
    color:#0B3354;
}
</style>
@stop
@section('content')
    
		<div class="row" style="margin-right:50px;">
            <div class="col-sm-12 about-box box-details contProduct" style="height:540px;">
	    		    <div class="col-sm-4" style="margin:0 auto;position:relative;height:100%;display:flex;align-items:center;justify-content:center;" >
                        @if($product['filepath'])<img class="center-block unique-product-img" src="<?=url($product['filepath'])?>" id="{{$product['filename']}}"/>
                        @else
                        <img class="center-block unique-product-img" src="{{ asset('assets/img/default/unavailable.png') }}" id="{{$product['filename']}}"/>
                        @endif

                    </div>
                    <div class="col-sm-4 col-sm-offset-1" style="position:relative;height:100%;display:flex;align-items:center;justify-content:center;" >
                        <div class="p-4">
                            
                            <div class="mb-3"><p><h2 class="txt-color">{{$product['name']}}</h2></p></div>
                            <br>
                            @if($product['actual_price'] == $product['price'])
                            <h4 class="txt-color">€{{ number_format($product['price'], 2,',','.') }}</h4>
                            @else
                            <h4 class="txt-color"><s class="m-r-5">€{{ number_format($product['price'], 2,',','.') }} </s>
                            €{{ number_format($product['actual_price'], 2,',','.') }}</h4>
                            @endif
                            <br>
                                <div style="display:table;position:relative;;word-wrap: break-word">
                                <p class="txt-color" style="width:300px;font-size:16px;line-height:1.2">{{$product['description']}}</p>
                                </div>
                                <br>
                                <form class="d-flex justify-content-left input-group">
                                    <br>
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