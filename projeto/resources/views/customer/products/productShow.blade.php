@extends('customer.layouts.master')
@section('title')
{{$product['name']}}
@stop
@section('content')
    <div class="container">
		<div class="row">
            <div class="row top25">
                <div class="col-sm-12">
	    		    <div class="col-sm-3">
                        <img class="center-block" src="<?=Croppa::url($product['filepath'],200,200)?>" id="{{$product['filename']}}"/>
                        <p class="text-center"><a href="{{route('customer.products.productShow',$product['id'])}}"> {{$product['name']}} </a></p>
                        <p class="text-center"> €{{$product['price']}} </p>
                        <p class="text-center"><a type="button" class="btn btn-default" href="{{route('customer.products.index')}}">Voltar</a><a href="{{route('customer.cart.addToCart',$product['id'])}}" type="button" class="btn btn-default">Adicionar ao Carrinho</a></p>
	    		    </div>
                </div>
            </div>
        </div>
	</div>		
@stop
                
