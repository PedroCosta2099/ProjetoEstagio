{{ Form::model($products,$formOptions) }}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span class="fs-15" aria-hidden="true"><i class="fas fa-times"></i></span>
        <span class="sr-only">Fechar</span>
    </button>
    <h4 class="modal-title">{{ $action }}</h4>
</div>
<div class="modal-body">
    <div class="row row-5">
    @foreach($products as $product)
       <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('product_id', 'Produto') }}
                {{ Form::hidden('product_id', $product['id'], ['class' => 'form-control select2','readonly'])}}
                {{ Form::text('', $product['name'], ['class' => 'form-control select2','readonly'])}}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('total_price', 'Preço') }}
                {{ Form::text('total_price',$product['price'], ['class' => 'form-control uppercase', 'required','readonly']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('vat', 'IVA') }}
                {{ Form::text('vat',$product['vat'], ['class' => 'form-control uppercase', 'required','readonly','step' => '0.01']) }}
            </div>
        
        </div>  
    @endforeach
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            {{ Form::hidden('delete_photo') }}
    <button class="btn btn-primary btn-submit" href="{{ route('admin.orders.createOrder') }}">Gravar</button>
</div>
{{ Form::close() }}

<script>
    $('.select2').select2(Init.select2());
    $('input').iCheck(Init.iCheck());
    $('[data-toggle="tooltip"]').tooltip();
    
    $('#price').change(function() {
    var IVA = 0.23;
    var vat = ($(this).val()*IVA);
    var rounded_vat = parseFloat(vat.toFixed(2));
    document.getElementById("vat").value= rounded_vat;
    });
    
</script>
