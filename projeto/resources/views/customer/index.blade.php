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

</style>
@stop
<div class="row row-5">
    @foreach($sellers as $seller)
    <div class="col-sm-3">
        
        
        <a href="{{route('customer.seller',str_replace(' ','-',$seller[0]['name']))}}">
            @if($seller[0]['thumbnail_filepath'])
        <img src="{{ asset($seller[0]->getCroppaThumbnail(200, 100)) }}" style="width:100%">
        @else
        <img src="{{ asset('assets/img/default/unavailable.png') }}">
        @endif
        <h3>{{$seller[0]['name']}}<h3></a>

    </div>
    @endforeach
</div>
@stop