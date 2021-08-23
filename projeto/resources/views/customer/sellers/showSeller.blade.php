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
.cont{
    padding-top:0px !important;
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
@if(Auth::guard('customer')->check())
@if($countCustomerToSellerRating > 0)
<div class="cont">
    <div class="info-title text-center" style="padding-left:0px">A sua avaliação</div>
    <div class="stars"><h2>A sua avaliação para este restaurante é: {{$customerToSellerRating->rating}}/5</h2>
        <button class="btn btn-edit" id="edit" onclick="changeVisibility()" style="margin-right:0px">Editar</button>
    </div>
    <div class="stars" id="rated" style="display:none">
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
       
        <button type="submit" class="btn btn-edit" style="margin-right:0px">Guardar</button>
        {{Form::close()}}
    </div>
</div>
@else
<div class="cont">
<div class="info-title text-center" style="padding-left:0px">A sua avaliação</div>
    <div class="stars"><h2>Faça já a sua avaliação</h2>
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
       
        <button type="submit" class="btn btn-edit" style="margin-right:0px">Guardar</button>
        {{Form::close()}}
    </div>
</div>
@endif
@endif
<div class="row row-5" style="padding-left:35px;padding-right:35px;">
    <div class="info-title">Os nossos produtos</div>
    <div class="col-sm-12 about-box box-details">
        @if(count($productsSeller) != 0)
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
    @else
    <div class="col-sm-12 about-box box-details text-center">
    <h2>Ainda não tem produtos disponíveis</h2>
    </div>
    @endif
    </div>
</div>
@stop
@section('scripts')
<script>
    function changeVisibility()
    {
        document.getElementById('rated').style.display = "inline-block";
        document.getElementById('edit').style.display = "none";
    }
</script>
@stop
