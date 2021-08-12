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
    @foreach ($productsSeller as $product )
        <div class="col-sm-3">
            {{$product['name']}}
        </div>
    @endforeach
</div>
@stop
