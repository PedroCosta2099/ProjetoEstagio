@extends('customer.layouts.master')
@section ('title')
Informações Gerais
@stop
@section('content')
{{ Form::model($paymentMethods) }}
<div class="about-page">
<div class="row row-5">
    <div class="col-sm-12 about-box">
    <div class="info-title"><span>Métodos de Pagamento</span></div>
        <div class="col-sm-12 box-details">
        
        <div class="col-sm-12">
            
            @foreach($paymentMethods as $paymentMethod)
            @if($paymentMethod['active'])
            <div class="col-sm-12" style="padding-right:30px !important">
                
                <input type="radio" name="paymentMethod" value="{{$paymentMethod['id']}}" id="{{$paymentMethod['id']}}" class="input"/>
                <label for="{{$paymentMethod['id']}}">
                    <img class="center-block choice" value="{{$paymentMethod['id']}}" src="<?=Croppa::url($paymentMethod['filepath'],50,50)?>" id="{{$paymentMethod['filename']}}"/>
                    {{$paymentMethod['name']}}
                </label>
                <hr></hr>
            </div>
            @endif
            @endforeach

            </div>
        </div>
    </div>
    </div>
    <div class="info-title"><span>Moradas</span></div>
        <div class="col-sm-12 about-box box-details">
        
        <div class="col-sm-6 m-b-5">
            <h4><b>Morada de Faturação</b></h4>
            
            <label>Morada</label>
            <h6>{{$billingAddress[0]['address']}}</h6>
            <label>Código Postal</label>
            <h6>{{$billingAddress[0]['postal_code']}}</h6>
            <label>Localidade</label>
            <h6>{{$billingAddress[0]['city']}}</h6>

            <div><a href="{{route('customer.editBillingAddress',$billingAddress[0]['id'])}}" class="btn-edit pull-left">Alterar</a></div>
        </div>
        <div class="col-sm-6 m-b-5">
            <h4><b>Morada de Envio</b></h4>
            
            <label>Morada</label>
            <h6>{{$shipmentAddress[0]['address']}}</h6>
            <label>Código Postal</label>
            <h6>{{$shipmentAddress[0]['postal_code']}}</h6>
            <label>Localidade</label>
            <h6>{{$shipmentAddress[0]['city']}}</h6>

            <div class="m-b-5"><a href="{{route('customer.shipmentAddresses')}}" class="btn-edit pull-left change">Alterar</a></div>
        </div>
    </div>
    </div>
    <a type="button" class="btn btn-default" href="{{route('customer.cart.index')}}">Voltar</a>
            <a type="submit" id="continue" class="btn btn-primary btn-submit" href="{{route('customer.cart.resumeOrder')}}" style="color:white"><i class="fas fa-plus" style="color:white"></i> Continuar</a>
</div>
</div>
    
</div>
</div>
 
@stop
@section('scripts')
<script>
$(document).ready(function(){
    
    $.ajax({
           type:"get",
           url:"{{url('/savePreviousPage')}}",
           success:function()
           {    
            
           },
           error:function()
           {
                
           }   
        });
});
$('.choice').click(function(){
    var id = $(this).attr('value');
    $.ajax({
           type:"get",
           url:"{{url('cart/paymentMethod')}}/"+id,
           success:function()
           {    
            
           },
           error:function()
           {
                
           }   
        });
});

</script>
@stop