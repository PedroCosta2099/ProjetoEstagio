@section('content')
@extends('customer.layouts.master')
@section('title')
Enovo Eats
@stop
@section('styles')
<style>
    #content-wrapper{
    margin-right:50px !important;  
}
.seller-name{
    white-space: nowrap;
    overflow: hidden;
    text-overflow:ellipsis;
}
.contSeller{
    
    padding:0px !important;
    width:calc(25% - 10px)!important;
    margin-right:10px;
    margin-bottom:15px;
    background:white;
    box-shadow: 1px 3px 13px 0px #524e4e70;
    
}
.circle{
    background-color:#0B3354;
    
    display:flex;
    justify-content:center;
    align-items:center;
    color:white;
    width:50px;
    height:50px;
    margin: auto;
    margin-top:20px !important;
    border-radius:50%;
}

</style>
@stop
<div class="row row-5">
    <div class="col-sm-12" style="width:100%;">
    <h1 style="color:#0B3354">Restaurantes</h1>
    @foreach($sellers as $seller)
    @if(!Auth::guard('customer')->check() || $count > 0)
    
    <div class="col-sm-3 contSeller">
        
        <a href="{{route('customer.seller',str_replace(' ','-',$seller['name']))}}">
         @if($seller['thumbnail_filepath'])
        <img src="{{ asset($seller->getCroppaThumbnail(200, 100)) }}" style="width:100%">
        @else
        <img src="{{ asset('assets/img/default/unavailable.png') }}">
        @endif
        <div class="col-sm-9">
        <h3 class="seller-name" style="color:#0B3354">{{$seller['name']}}<h3></a>
        <h5>Taxa de Entrega: €{{number_format($seller['delivery_fee'],2,',','.')}}<br> Tempo de Entrega: {{$seller['minimum_delivery_time']}} min - {{$seller['maximum_delivery_time']}} min</h5>
        </div>
        <div class="col-sm-3" >
            <div class="circle"><b>{{number_format($seller['rating'],1)}}</b></div>
        </div>
    </div>
    @elseif($count > 0)
    <div class="col-sm-3 contSeller">
        
        <a href="{{route('customer.seller',str_replace(' ','-',$seller[0]['name']))}}">
         @if($seller[0]['thumbnail_filepath'])
        <img src="{{ asset($seller[0]->getCroppaThumbnail(200, 100)) }}" style="width:100%">
        @else
        <img src="{{ asset('assets/img/default/unavailable.png') }}" style="width:100%">
        @endif
        <div class="col-sm-9">
        <h3 class="seller-name" style="color:#0B3354">{{$seller[0]['name']}}<h3></a>
        <h5>Taxa de Entrega: €{{number_format($seller[0]['delivery_fee'],2,',','.')}}<br> Tempo de Entrega: {{$seller[0]['minimum_delivery_time']}} min - {{$seller[0]['maximum_delivery_time']}} min</h5>
        </div>
        <div class="col-sm-3" >
            <div class="circle"><b>{{number_format($seller[0]['rating'],1)}}</b></div>
        </div>
    </div>
    @else
    <div class="col-sm-3 contSeller">
        
        <a href="{{route('customer.seller',str_replace(' ','-',$seller['name']))}}">
         @if($seller['thumbnail_filepath'])
        <img src="{{ asset($seller->getCroppaThumbnail(200, 100)) }}" style="width:100%">
        @else
        <img src="{{ asset('assets/img/default/unavailable.png') }}" style="width:100%">
        @endif
        <div class="col-sm-9">
        <h3 class="seller-name" style="color:#0B3354">{{$seller['name']}}<h3></a>
        <h5>Taxa de Entrega: €{{number_format($seller['delivery_fee'],2,',','.')}}<br> Tempo de Entrega: {{$seller['minimum_delivery_time']}} min - {{$seller['maximum_delivery_time']}} min</h5>
        </div>
        <div class="col-sm-3" >
            <div class="circle"><b>{{number_format($seller['rating'],1)}}</b></div>
        </div>
    </div>
    @endif
   @endforeach
   
    </div>
</div>
@stop