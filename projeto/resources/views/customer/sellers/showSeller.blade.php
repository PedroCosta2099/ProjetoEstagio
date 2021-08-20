@extends('customer.layouts.master')
@section('title')
{{$seller['name']}}
@stop
@section('styles')
<style>
    #content-wrapper{
    top:50px !important;
    left:0 !important;
    right:0 !important;
    padding:0 !important;
    margin-left:0 !important;
    position:absolute;
    
}

.without-banner{
    height:240px;
    text-align:center;
    padding:70px;
}

@media (max-width:767px) {
    #content-wrapper{
        top:100px !important;
    }
    
}
@media (min-width:769px) {
    #content-wrapper{
        top:50px !important;
    }
    
}
    #content{
    padding:0;
    
}
.content-header{
    padding:0;
}

.product-description{
    white-space: nowrap;
    overflow: hidden;
    text-overflow:ellipsis;
}


</style>
@stop
@section('content')
<div class="img img-responsive" style="position:relative;">
@if($seller['banner_filepath'])
<img src="{{ asset($seller->getCroppaBanner(1440, 240)) }}" style="width:100%;height:auto">
@else
<div class="without-banner">
<h1>{{$seller['name']}}</h1>
</div>
@endif
</div>
@if($countCustomerToSellerRating > 0)
<div class="cont">
    <div class="stars">
        <h2>A sua avaliação para este restaurante é: {{$customerToSellerRating->rating}}/5
    </div>
</div>
@else
<div class="cont">
    <div class="stars">
        {{Form::open(array('route'=>['customer.sellerRating',$seller['id']]))}}
        {{Form::radio('star_1',1,null)}}
        {{Form::label('star_1',1,['class' => 'star'])}}
        {{Form::radio('star_2',2)}}
        {{Form::label('star_2',2,['class' => 'star'])}}
        {{Form::radio('star_3',3)}}
        {{Form::label('star_3',3,['class' => 'star'])}}
        {{Form::radio('star_4',4)}}
        {{Form::label('star_4',4,['class' => 'star'])}}
        {{Form::radio('star_5',5)}}
        {{Form::label('star_5',5,['class' => 'star'])}}
       
        <button type="submit" class="btn btn-edit">Guardar</button>
        {{Form::close()}}
    </div>
</div>
@endif

<div class="row">
    @foreach ($productsSeller as $product)
        <div class="col-sm-3">
        @if($product['filepath'])
    <img src="{{ asset($product->getCroppa(200, 200)) }}" style="border:none;float:right" class="w-75px"/>
        @else
    <img src="{{ asset('assets/img/default/avatar2.jpg') }}" style="border:none;float:right" class="w-75px"/>
        @endif
            {{$product['name']}}
            <h3 class="product-description">{{$product['description']}}</h3>
        </div>
        
    @endforeach
</div>
@stop
