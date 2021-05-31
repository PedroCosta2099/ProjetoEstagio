@section('title')
Produtos
@stop
<div class="row row-5">
    <div class="col-sm-12">
        @foreach($products as $product)
            <div class="col-sm-3">
                <img src="<?=Croppa::url($product['filepath'],100,100)?>" id="{{$product['filename']}}"  class="w-60px"/>
                {{$product['name']}}
                <button type="button" class="btn btn-default"><i class="fas fa-credit-card"></i></button>
            </div>
        @endforeach  
    </div>
</div>