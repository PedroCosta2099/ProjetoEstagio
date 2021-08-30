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
.seller-name{
    white-space: nowrap;
    overflow: hidden;
    text-overflow:ellipsis;
}
.contSeller{
    
    padding:0px !important;
    width:calc(25% - 10px)!important;
    margin-right:10px;
    margin-bottom:15px;
    background:white;
    box-shadow: 1px 3px 13px 0px #524e4e70;
    
}
.contSeller:hover{
    transform:scale(1.1);
    z-index:1000;
}
.contProduct{
    
    padding:0px !important;
    width:calc(33% - 10px)!important;
    
    margin-top:15px;
    margin-right:10px;
    margin-bottom:15px;
    background:white;
    box-shadow: 1px 3px 13px 0px #524e4e70;
    
}
.contProduct:hover{
    transform:scale(1.1);
    z-index:1000;
}

.txt-color{
    color:#0B3354;
}
.circle{
    background-color:#0B3354;
    display:flex;
    justify-content:center;
    align-items:center;
    color:white;
    width:50px;
    height:50px;
    margin: auto;
    margin-top:20px !important;
    border-radius:50%;
}

</style>
@stop<form method="post" action="{{route('home.index')}}">
<div class="row row-5">
    <div class="col-sm-12" style="width:100%;">
    <div class="col-sm-12">
        <div class="col-sm-9">
            <h1 class="txt-color">Restaurantes <a href="{{route('customer.allSellers')}}" style="font-size:26px"><i class="fas fa-plus txt-color"></i></a></h1>
        </div>
    </div>
    @foreach($sellers as $seller)
    @if(!Auth::guard('customer')->check() || $count > 0)
    
    <div class="col-sm-3 contSeller">
        
        <a href="{{route('customer.seller',str_replace(' ','-',$seller['name']))}}">
         @if($seller['thumbnail_filepath'])
        <img src="{{ asset($seller->getCroppaThumbnail(200, 100)) }}" style="width:100%">
        @else
        <img src="{{ asset('assets/img/default/unavailable.png') }}">
        @endif
        <div class="col-sm-9">
        <h3 class="seller-name txt-color">{{$seller['name']}}<h3></a>
        <h5  class="txt-color">Taxa de Entrega: €{{number_format($seller['delivery_fee'],2,',','.')}}<br> Tempo de Entrega: {{$seller['minimum_delivery_time']}} min - {{$seller['maximum_delivery_time']}} min</h5>
        </div>
        <div class="col-sm-3" >
            <div class="circle"><b>{{number_format($seller['rating'],1)}}</b></div>
        </div>
    </div>
    @elseif($count > 0)
    <div class="col-sm-3 contSeller">
        
        <a href="{{route('customer.seller',str_replace(' ','-',$seller[0]['name']))}}">
         @if($seller[0]['thumbnail_filepath'])
        <img src="{{ asset($seller[0]->getCroppaThumbnail(200, 100)) }}" style="width:100%">
        @else
        <img src="{{ asset('assets/img/default/unavailable.png') }}" style="width:100%">
        @endif
        <div class="col-sm-9">
        <h3 class="seller-name txt-color">{{$seller[0]['name']}}<h3></a>
        <h5  class="txt-color">Taxa de Entrega: €{{number_format($seller[0]['delivery_fee'],2,',','.')}}<br> Tempo de Entrega: {{$seller[0]['minimum_delivery_time']}} min - {{$seller[0]['maximum_delivery_time']}} min</h5>
        </div>
        <div class="col-sm-3" >
            <div class="circle"><b>{{number_format($seller[0]['rating'],1)}}</b></div>
        </div>
    </div>
    @else
    <div class="col-sm-4 contProduct">
        
        <a href="{{route('customer.seller',str_replace(' ','-',$seller['name']))}}">
         @if($seller['thumbnail_filepath'])
        <img src="{{ asset($seller->getCroppaThumbnail(200, 100)) }}" style="width:100%">
        @else
        <img src="{{ asset('assets/img/default/unavailable.png') }}" style="width:100%">
        @endif
        <div class="col-sm-9">
        <h3 class="seller-name txt-color">{{$seller['name']}}<h3></a>
        <h5 class="txt-color">Taxa de Entrega: €{{number_format($seller['delivery_fee'],2,',','.')}}<br> Tempo de Entrega: {{$seller['minimum_delivery_time']}} min - {{$seller['maximum_delivery_time']}} min</h5>
        </div>
        <div class="col-sm-3" >
            <div class="circle"><b>{{number_format($seller['rating'],1)}}</b></div>
        </div>
    </div>
    @endif
   @endforeach
   
    </div>
    <div class="col-sm-12">
        
        <h1 class="txt-color">Produtos em Desconto</h1>
        @foreach($productsWithDiscount as $productWithDiscount)
        <div class="col-sm-3 contProduct">
            <a href="{{route('customer.products.productShow',$productWithDiscount->id)}}">
        @if($productWithDiscount->filepath)
    <img src="{{ asset($productWithDiscount->getCroppa(200, 200)) }}" style="border:none;float:right;height:100%" />
        @else
    <img src="{{ asset('assets/img/default/unavailable.png') }}" style="border:none;float:right;width:200px;height:200px"/>
        @endif
            <p><h4 class="product-description txt-color" style="padding-left:10px;">{{$productWithDiscount->name}}</h4><br>
            
            <h5 class="txt-color" style="padding-left:10px;position:absolute;bottom:5px">
            @if($productWithDiscount->actual_price == $productWithDiscount->price)
                            €{{ number_format($productWithDiscount->price, 2,',','.') }} 
                            @else
                            <s class="m-r-5">€{{ number_format($productWithDiscount->price, 2,',','.') }} </s>
                            €{{ number_format($productWithDiscount->actual_price, 2,',','.') }} 
                            @endif
            </h5>
        </a>
        </div>
        @endforeach
        
    </div>
</div>

</form>
@stop
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){

        $.ajax({
           type:"get",
           url:"{{route('home.index')}}",
           data: function (d) {
               
                    d.orderBy   = $('select[name=orderBy]').val();
                    
                },
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