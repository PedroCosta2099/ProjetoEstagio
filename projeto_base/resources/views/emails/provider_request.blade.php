@extends('layouts.email')

@section('content')
    <div style="width: 700px">
        {!! $input['message'] !!}
    </div>
@stop