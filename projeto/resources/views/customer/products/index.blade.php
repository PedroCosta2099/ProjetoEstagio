@extends('customer.layouts.master')
@section('title')
Produtos 
@stop
@section('content')
    <div class="container">
		<div class="row">
            <div class="row top25">
                <div class="col-sm-12">
                @foreach($products as $product)
	    		    <div class="col-sm-3">
                        <p class="text-center">
                            <a href="{{route('customer.products.productShow',$product['id'])}}">
                                <img class="center-block" src="<?=Croppa::url($product['filepath'],200,200)?>" id="{{$product['filename']}}"/>
                                {{$product['name']}} 
                            </a>
                        </p>
                        <p class="text-center"> 
                            €{{$product['price']}} 
                        </p>
	    		    </div>
                @endforeach
                </div>
            </div>
        </div>
        <a type="button" class="btn btn-default" href="{{route('customer.cart.index')}}">Carrinho</a>
	</div>		
@stop

                
