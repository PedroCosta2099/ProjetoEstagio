@extends('customer.layouts.master')
@section('title')
My Enovo Eats
@stop
@section('content')
<div class="about-page">
<div class="row row-5">
    <div class="col-sm-12 about-box">
    <div class="info-title"><span>Os meus dados pessoais</span></div>
    <div class="col-sm-12 box-details">
        {{Form::open(array('route'=>'customer.savePersonalData'))}}
        
                <div class="col-sm-12">
                    <div class="form-group is-required">
                        {{ Form::label('name', 'Nome')}}
                        {{ Form::text('name', $customer->name, array('class' =>'form-control', 'required' => true)) }}
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group is-required">
                        {{ Form::label('email', 'E-mail')}}
                        {{ Form::text('email', $customer->email, array('class' =>'form-control', 'required' => true)) }}
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group is-required">
                        {{ Form::label('phone', 'Telemóvel')}}
                        {{ Form::text('phone', $customer->phone, array('class' =>'form-control', 'required' => true)) }}
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group is-required">
                        {{ Form::label('nif', 'NIF')}}
                        {{ Form::text('nif', $customer->nif, array('class' =>'form-control', 'required' => true)) }}
                    </div>
                </div>
                <div class="row row-5">
                    <div class="col-sm-6">
                        <div class="form-group m-b-0">
                            {{ Form::label('password', 'Password')}}
                            {{ Form::password('password', array('class' =>'form-control', 'autocomplete' => 'off', 'placeholder' => 'Deixar vazio para não alterar')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-b-0">
                            {{ Form::label('password_confirmation', 'Confirmar Password')}}
                            {{ Form::password('password_confirmation', array('class' =>'form-control', 'autocomplete' => 'off')) }}
                        </div>
                    </div>
                </div>
                <div class="m-t-5">
                <button class="btn btn-edit pull-right">Guardar</button>
                <a class="btn btn-edit pull-right" href="{{route('customer.about')}}">Voltar</a>
                </div>
                
        {{Form::close()}}
    </div>
    </div>
   
@stop