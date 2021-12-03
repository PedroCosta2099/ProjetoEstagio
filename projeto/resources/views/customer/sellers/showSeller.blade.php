@extends('customer.layouts.master')
@section('title')
{{$seller['name']}}
@stop
@section('styles')
<style>
    #content-wrapper {
        top: 50px !important;
        left: 0 !important;
        right: 0 !important;
        padding: 0 !important;
        margin-left: 0 !important;
        position: absolute;

    }

    .without-banner {
        height: 240px;
        text-align: center;
        padding: 70px;
    }

    @media (max-width:767px) {
        #content-wrapper {
            top: 100px !important;
        }

    }

    @media (min-width:769px) {
        #content-wrapper {
            top: 50px !important;
        }

    }

    #content {
        padding: 0;

    }

    .content-header {
        padding: 0;
    }

    .product-description {

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 0px;

    }

    .cont {
        padding-top: 0px !important;
    }

    .contSeller {

        padding: 0px !important;
        width: calc(33% - 10px) !important;
        height: 158px;
        margin-top: 15px;
        margin-right: 10px;
        margin-bottom: 15px;
        background: white;
        box-shadow: 1px 3px 13px 0px #524e4e70;

    }

    .contCategories{
        margin-top:25px;
        display:flex;
        flex-wrap:wrap;
        justify-content:space-around;
    }

    .contSeller:hover {
        transform: scale(1.1);
        z-index: 1000;
    }

    .txt-color {
        color: #0B3354;
    }

    .circleSeller {
        background-color: #0B3354;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        width: 40px;
        height: 40px;

        margin-top: 20px !important;
        border-radius: 50%;
    }
</style>
@stop
@section('content')
<div class="img img-responsive" style="position:relative;">
    @if($seller['banner_filepath'])
    <img src="{{ asset($seller->getCroppaBanner(1440, 240)) }}" style="width:100%;height:auto">
    @else
    <div class="without-banner">
        <h1>{{$seller['name']}}</h1>
    </div>
    @endif
</div>
<div class="row row-5" style="padding-left:35px;padding-right:35px;padding-top:10px">

    <div class="col-sm-12 about-box box-details">
        <div class="col-sm-12">
            <h1 class="txt-color" style="float:left;margin-right:10px">{{$seller['name']}}</h1>
            <div class="circleSeller"><b>{{number_format($seller->rating,1)}}</b></div>
            <h5 class="txt-color"><i class="fas fa-hand-holding-usd"></i> €{{number_format($seller['delivery_fee'],2,',','.')}} &nbsp <i class="fas fa-clock"></i> {{$seller['minimum_delivery_time']}} min - {{$seller['maximum_delivery_time']}} min</h5>
            <h5 class="txt-color">{{$seller['address']}} {{$seller['postal_code']}} {{$seller['city']}}</h5>
            @if(Auth::guard('customer')->check())

            @if($customerToSellerRating != null)<h5 class="txt-color">A sua avaliação: {{number_format($customerToSellerRating->rating,1)}}</h5><button class="btn btn-edit" id="edit" onclick="changeVisibilityRating();" style="margin-bottom:10px">Editar</button>
        </div>
        <div class="cont" id="rating-cont" style="display:none;">

            <div class="info-title text-center" style="padding-left:0px">A sua avaliação</div>
            <div class="stars">
                <h2>Faça já a sua avaliação</h2>
                {{Form::open(array('route'=>['customer.sellerRating',$seller['id']]))}}
                {{Form::radio('star_1',1,null)}}
                {{Form::label('star_1',1,['class' => 'star'])}}
                {{Form::radio('star_2',2)}}
                {{Form::label('star_2',2,['class' => 'star'])}}
                {{Form::radio('star_3',3)}}
                {{Form::label('star_3',3,['class' => 'star'])}}
                {{Form::radio('star_4',4)}}
                {{Form::label('star_4',4,['class' => 'star'])}}
                {{Form::radio('star_5',5)}}
                {{Form::label('star_5',5,['class' => 'star'])}}

                <button type="submit" class="btn btn-edit" style="margin-right:0px">Guardar</button>
                {{Form::close()}}
            </div>
        </div>
    
    @else
    <button class="btn btn-edit" id="edit" onclick="changeVisibilityRating();">Faça já a sua avaliação</button>
    <div class="cont" id="rating-cont" style="display:none;">

        <div class="info-title text-center" style="padding-left:0px">A sua avaliação</div>
        <div class="stars">
            <h2>Faça já a sua avaliação</h2>
            {{Form::open(array('route'=>['customer.sellerRating',$seller['id']]))}}
            {{Form::radio('star_1',1,null)}}
            {{Form::label('star_1',1,['class' => 'star'])}}
            {{Form::radio('star_2',2)}}
            {{Form::label('star_2',2,['class' => 'star'])}}
            {{Form::radio('star_3',3)}}
            {{Form::label('star_3',3,['class' => 'star'])}}
            {{Form::radio('star_4',4)}}
            {{Form::label('star_4',4,['class' => 'star'])}}
            {{Form::radio('star_5',5)}}
            {{Form::label('star_5',5,['class' => 'star'])}}

            <button type="submit" class="btn btn-edit" style="margin-right:0px">Guardar</button>
            {{Form::close()}}
        </div>
    </div>
</div>
@endif
@endif

<div class="row row-5">
<div class="col-sm-12 contCategories">
@foreach($categoriesIdsSeller as $categoryIdSeller)
    @foreach($categoriesSeller as $categorySeller)
    @if($categorySeller['id'] == $categoryIdSeller)
    @if($productsSeller->where('category_id',$categorySeller['id'])->count() != 0)
    <a href="#{{$categorySeller['name']}}" style="text-decoration:none !important;color:#0B3354;font-size:20px;"><span>{{$categorySeller['name']}}</span></a>
    @endif
    @endif
    @endforeach
    @endforeach
    
</div> 

</div><hr style="margin-right:30px">

@if(count($productsSeller) != 0)
@foreach($categoriesIdsSeller as $categoryIdSeller)
@foreach($categoriesSeller as $categorySeller)
@if($categorySeller['id'] == $categoryIdSeller)

<div class="row row-5">


    <div class="col-sm-12 about-box box-details" style="background-color:transparent !important;padding-left:0px !important">
        @if($productsSeller->where('category_id',$categorySeller['id'])->count() != 0)
        <h1 class="txt-color catName" id="{{$categorySeller['name']}}">{{$categorySeller['name']}}</h1>
        @endif

        @foreach ($productsSeller as $product)

        @if($product->where('category_id',$categorySeller['id'])->count() == 0)

        @break;
        @endif
        @if($product['category_id'] == $categorySeller['id'])
        <a href="{{route('customer.products.productShow',$product['id'])}}">
            <div class="col-sm-4 contSeller">
                @if($product['filepath'])
                <img src="{{ asset($product->getCroppa(200, 200)) }}" style="border:none;float:right;height:100%" />
                @else
                <img src="{{ asset('assets/img/default/unavailable.png') }}" style="border:none;float:right;height:100%" />
                @endif
                <p>
                <h4 class="product-description txt-color" style="padding-left:10px;">{{$product['name']}}</h4><br>
                @if($product['description'] == null)
                &nbsp
                @else
                <h5 class="product-description txt-color" style="padding-left:10px;">{{$product['description']}}</h5>
                </p>
                @endif
                <h5 class="txt-color" style="padding-left:10px;position:absolute;bottom:5px">
                    @if($product['actual_price'] == $product['price'])
                    €{{ number_format($product['price'], 2,',','.') }}
                    @else
                    <s class="m-r-5">€{{ number_format($product['price'], 2,',','.') }} </s>
                    €{{ number_format($product['actual_price'], 2,',','.') }}
                    @endif
                </h5>
            </div>
        </a>

        @endif
        @endforeach
    </div>
</div>
@endif
@endforeach
@endforeach
@else
<div class="col-sm-12 about-box box-details text-center" style="background-color:transparent !important,padding-left:0px !important">
    <h2>Ainda não tem produtos disponíveis</h2>
</div>
@endif
</div>
</div>

@stop

@section('scripts')
<script>
    function changeVisibility() {
        document.getElementById('rated').style.display = "inline-block";
        document.getElementById('edit').style.display = "none";
    }

    function changeVisibilityRating() {
        document.getElementById('rating-cont').style.display = "inline-block";
        document.getElementById('edit').style.display = "none";
    }
</script>
@stop