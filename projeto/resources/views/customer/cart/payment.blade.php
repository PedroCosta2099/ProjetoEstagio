@extends('customer.layouts.master')
@section ('title')
Método de Pagamento
@stop
@section('content')
{{ Form::model($paymentMethods) }}
<form required>
<div class="col-sm-12">
    <div class="form-group">
            {{ Form::label('payment_type', 'Método de Pagamento') }}
            
            @foreach($paymentMethods as $paymentMethod)
            <div class="col-sm-2">
                <input type="radio" name="paymentMethod" value="{{$paymentMethod['id']}}" id="{{$paymentMethod['id']}}" class="input"/>
                <label for="{{$paymentMethod['id']}}">
                    <img class="center-block choice" value="{{$paymentMethod['id']}}" src="<?=Croppa::url($paymentMethod['filepath'],50,50)?>" id="{{$paymentMethod['filename']}}"/>
                    {{$paymentMethod['name']}}
                </label>
            </div>
            @endforeach

            <a type="button" class="btn btn-default" href="{{route('customer.cart.index')}}">Voltar</a>
            <a type="submit" id="continue" class="btn btn-primary btn-submit" href="{{route('customer.cart.resumeOrder')}}" style="color:white"><i class="fas fa-plus" style="color:white"></i> Continuar</a>
    </div>
</div>
</form> 
@stop
@section('scripts')
<script>
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