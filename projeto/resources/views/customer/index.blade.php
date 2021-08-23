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
    text-align:center;
    padding:0px !important;
    margin-bottom:15px;
    background:white;
    box-shadow: 1px 3px 13px 0px #524e4e70;
    
}


</style>
@stop
<div class="row row-5">
    
    <h1>Restaurantes</h1>
    @foreach($sellers as $seller)
    @if(!Auth::guard('customer')->check() || $count > 0)
    
    <div class="col-sm-3 contSeller">
        
        <a href="{{route('customer.seller',str_replace(' ','-',$seller['name']))}}">
         @if($seller['thumbnail_filepath'])
        <img src="{{ asset($seller->getCroppaThumbnail(200, 100)) }}" style="width:100%">
        @else
        <img src="{{ asset('assets/img/default/unavailable.png') }}">
        @endif
        <h3 class="seller-name">{{$seller['name']}}<h3></a>
    </div>
    @elseif($count > 0)
    <div class="col-sm-3 contSeller">
        
        <a href="{{route('customer.seller',str_replace(' ','-',$seller[0]['name']))}}">
         @if($seller[0]['thumbnail_filepath'])
        <img src="{{ asset($seller[0]->getCroppaThumbnail(200, 100)) }}" style="width:100%">
        @else
        <img src="{{ asset('assets/img/default/unavailable.png') }}" style="width:100%">
        @endif
        <h3>{{$seller[0]['name']}}<h3></a>
    </div>
    @else
    <div class="col-sm-3 contSeller">
        
        <a href="{{route('customer.seller',str_replace(' ','-',$seller['name']))}}">
         @if($seller['thumbnail_filepath'])
        <img src="{{ asset($seller->getCroppaThumbnail(200, 100)) }}" style="width:100%">
        @else
        <img src="{{ asset('assets/img/default/unavailable.png') }}" style="width:100%">
        @endif
        <h3>{{$seller['name']}}<h3></a>
    </div>
    @endif
   @endforeach
   
    
</div>
@stop