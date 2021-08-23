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
    margin-bottom:0px;

}
.cont{
    padding-top:0px !important;
}

.contSeller{
    
    padding:0px !important;
    width:calc(33% - 10px)!important;
    height:158px;
    margin-top:15px;
    margin-right:10px;
    margin-bottom:15px;
    background:white;
    box-shadow: 1px 3px 13px 0px #524e4e70;
    
}

.contSeller:hover{
    transform:scale(1.1);
    z-index:1000;
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
<div>
    <h1 style="color:#0B3354">{{$seller['name']}}</h1>
    <h5>A sua avaliação: {{number_format($customerToSellerRating->rating,1)}}</h5>
    
</div>
@if(Auth::guard('customer')->check())
@if($countCustomerToSellerRating > 0)
<div class="col-sm-3 cont">
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
    
    <div class="col-sm-12 about-box box-details" style="background-color:transparent !important;padding-left:0px !important">
    <h1 style="color:#0B3354">Os nossos produtos</h1>
        @if(count($productsSeller) != 0)
    @foreach ($productsSeller as $product)
     <a href="{{route('customer.products.productShow',$product['id'])}}">   <div class="col-sm-4 contSeller">
        @if($product['filepath'])
    <img src="{{ asset($product->getCroppa(200, 200)) }}" style="border:none;float:right;height:100%" />
        @else
    <img src="{{ asset('assets/img/default/avatar2.jpg') }}" style="border:none;float:right" class="w-75px"/>
        @endif
            <p><h4 class="product-description" style="color:#0B3354;padding-left:10px;">{{$product['name']}}</h4><br>
            @if($product['description'] == null)
            &nbsp
            @endif
            <h5 class="product-description" style="color:#0B3354;padding-left:10px;">{{$product['description']}}</h5></p>
        </div>
    </a> 
    @endforeach
    @else
    <div class="col-sm-12 about-box box-details text-center" style="background-color:transparent !important,padding-left:0px !important">
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
