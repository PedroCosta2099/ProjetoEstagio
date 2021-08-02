@extends('customer.layouts.master')
@section('title')
{{$seller['name']}}
@stop
@section('content')
<div class="img img-responsive" style="position:relative;">
@if($seller['banner_filepath'])
<img src="{{ asset($seller->getCroppaBanner(1440, 450)) }}" style="width:100%;height:auto">
@endif
</div>
@stop
