@extends('customer.layouts.master')
@section('title')
Registo
@stop
@section('content')
<div class="box no-border">
    <div class="box-body">
        {!!Form::open(array('route'=>'customer.register.submit','method' => 'post'))!!}
        <div class="col-sm-8 col-lg-9">
            <div class="row row-5">
                <div class="col-sm-8">
                    <div class="form-group is-required">
                        {{ Form::label('name', 'Nome')}}
                        {{ Form::text('name', null, array('class' =>'form-control', 'required' => true)) }}
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group is-required">
                        {{ Form::label('nif', 'NIF')}}
                        {{ Form::text('nif', null, array('class' =>'form-control','required'=>true)) }}
                    </div>
                </div>
            </div>
            <div class="form-group is-required col-sm-8">
                {{ Form::label('email', 'E-mail')}}
                {{ Form::email('email', null, array('class' =>'form-control', 'required' => true)) }}
            </div>
            <div class="col-sm-4">
                    <div class="form-group">
                        {{ Form::label('phone', 'Telemóvel')}}
                        {{ Form::text('phone', null, array('class' =>'form-control')) }}
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        {{ Form::label('address', 'Morada')}}
                        {{ Form::text('address', null, array('class' =>'form-control')) }}
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        {{ Form::label('city', 'Localidade')}}
                        {{ Form::text('city', null, array('class' =>'form-control')) }}
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        {{ Form::label('postal_code', 'Código Postal')}}
                        {{ Form::text('postal_code', null, array('class' =>'form-control')) }}
                    </div>
                </div>

                <div class="form-group is-required col-sm-12">
                    {{ Form::label('password', 'Password')}}
                    <div class="input-group input-group">
                        {{ Form::text('password', str_random(8), array('class' =>'form-control', 'required' => true,'autocomplete'=>'off')) }}
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-flat" id="random-password" type="button">
                                <i class="fas fa-redo"></i>
                            </button>
                        </span>
                    </div>
                </div>
            
        </div>
        <div class="col-sm-12">
            {{ Form::hidden('delete_photo') }}
            <button class="btn btn-primary">Gravar</button>
        </div>
        {{ Form::close() }}
    </div>
</div>
@stop