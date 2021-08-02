@section('content')
@extends('customer.layouts.master')
@section('title')
Enovo Eats
@stop

<div class="row row-5">
    @foreach($sellers as $seller)
    <div class="col-sm-4">
        <a href="{{route('customer.seller',str_replace(' ','-',$seller['name']))}}">{{$seller['name']}}</a>
    </div>
    @endforeach
</div>
@stop