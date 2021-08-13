@extends('customer.layouts.master')
@section('title')
Registo
@stop
@section('content')
<div class="limiter">
        <div class="container-login100">
            <div class="col-sm-10 wrap-login100">
                {{ Form::open(array('route' => 'customer.register.submit', 'class' => 'login100-form validate-form')) }}
                <span class="login100-form-title" style="padding-bottom: 48px">
                    <h4>Bem vindo</h4>
                </span>
                <div class="main-block">
                    @if($errors->has('email'))
                    <div class="help-block">
                        <i class="zmdi zmdi-alert-circle"></i> {{ $errors->first('email') }}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-6  wrap-input100 validate-input">
                            {{ Form::text('name', null, ['class' => 'input100']) }}
                            <span class="focus-input100" data-placeholder="Nome"></span>
                        </div>
                        <div class="col-sm-5 wrap-input100 validate-input" style="margin-left:8.3333%">
                            {{ Form::text('nif', null, ['class' => 'input100']) }}
                            <span class="focus-input100" data-placeholder="NIF"></span>
                        </div>
                        <div class="col-sm-12 wrap-input100 validate-input">
                            {{ Form::text('address', null, ['class' => 'input100 ']) }}
                            <span class="focus-input100" data-placeholder="Morada"></span>
                        </div>
                        <div class="col-sm-6 wrap-input100 validate-input">
                            {{ Form::text('postal_code', null, ['class' => 'input100 ']) }}
                            <span class="focus-input100" data-placeholder="Código Postal"></span>
                        </div>
                        <div class="col-sm-5 wrap-input100 validate-input" style="margin-left:8.3333%">
                            {{ Form::text('city', null, ['class' => 'input100 ']) }}
                            <span class="focus-input100" data-placeholder="Localidade"></span>
                        </div>
                        <div class="col-sm-5 wrap-input100 validate-input">
                            {{ Form::text('phone', null, ['class' => 'input100 ']) }}
                            <span class="focus-input100" data-placeholder="Telemóvel"></span>
                        </div>
                        <div class="col-sm-6 wrap-input100 validate-input" style="margin-left:8.3333%">
                            {{ Form::text('email', null, ['class' => 'input100']) }}
                            <span class="focus-input100" data-placeholder="E-mail"></span>
                        </div>
                        
                        <div class="col-sm-5  wrap-input100 validate-input">
                            <span class="btn-show-pass">
                                <i class="zmdi zmdi-eye"></i>
                            </span>
                                {{ Form::password('password', ['class' => 'input100']) }}
                            <span class="focus-input100" data-placeholder="Palavra-Passe"></span>
                        </div>
</div>
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn">
                                Registar
                            </button>
                        </div>
                    </div>

                    <div class="text-center" style="padding-top: 20px; line-height: 0;">
                        <span class="txt1">Já possui conta?<a href="{{route('customer.login')}}"> Inicie Sessão</a></span>
                        <br/>
                    </div>
                </div>

                <div class="text-center submit-loading" style="padding-top: 0; line-height: 0; display: none">
                    <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                    <h4>Bem-vindo!</h4>
                    <p>Estamos a registar os seus dados.</p>
                    <div style="margin-bottom: 30px"></div>
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn">
                                A Iniciar Sessão...
                            </button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop