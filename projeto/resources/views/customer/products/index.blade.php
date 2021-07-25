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
                            €{{ number_format($product['price'], 2,',','.') }} 
                        </p>
	    		    </div>
                @endforeach
                </div>
            </div>
        </div>
	</div>		
@stop

                
