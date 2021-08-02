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
                            <a name ="product" href="{{route('customer.products.productShow',$product['id'])}}">
                                <img class="center-block" src="<?=Croppa::url($product['filepath'],200,200)?>" id="{{$product['filename']}}"/>
                                <span>{{$product['name']}}</span>
                                
                            </a>
                        </p>
                        <p class="text-center"> 
                            @if($product['actual_price'] == $product['price'])
                            €{{ number_format($product['price'], 2,',','.') }} 
                            @else
                            <s class="m-r-5">€{{ number_format($product['price'], 2,',','.') }} </s>
                            €{{ number_format($product['actual_price'], 2,',','.') }} 
                            @endif
                        </p>
	    		    </div>
                @endforeach
                </div>
            </div>
        </div>
	</div>		
@stop

                
