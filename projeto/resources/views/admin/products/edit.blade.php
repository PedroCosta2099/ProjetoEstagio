{{ Form::model($product,$formOptions) }}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span class="fs-15" aria-hidden="true"><i class="fas fa-times"></i></span>
        <span class="sr-only">Fechar</span>
    </button>
    <h4 class="modal-title">{{ $action }}</h4>
</div>
<div class="modal-body">
    <div class="row row-5">
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('name', 'Produto') }}
                {{ Form::text('name', null, ['class' => 'form-control', 'required','maxlength' => '50']) }}
            </div>
        </div>
        <div class="col-sm-4 col-lg-3">
            {{ Form::label('image', 'Imagem', array('class' => 'form-label')) }}<br/>
            <div class="fileinput {{ $product->filepath ? 'fileinput-exists' : 'fileinput-new'}}" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;">
                    <img src="{{ asset('assets/img/default/avatar2.jpg') }}">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 150px; max-height: 150px;">
                    @if($product->filepath)
                        <img src="{{ asset($product->getCroppa(200, 200)) }}">
                    @endif
                </div>
                <div>
                    <span class="btn btn-default btn-block btn-sm btn-file">
                        <span class="fileinput-new">Procurar...</span>
                        <span class="fileinput-exists"><i class="fa fa-refresh"></i> Alterar</span>
                        <input type="file" name="image">
                    </span>
                    <a href="#" class="btn btn-danger btn-block btn-sm fileinput-exists" data-dismiss="fileinput">
                        <i class="fa fa-close"></i> Remover
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group is-required">
                {{ Form::label('description', 'Descrição') }}
                {{ Form::text('description', $product->description, ['class' => 'form-control','required','maxlength' => '200']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('price', 'Preço') }}
                {{ Form::number('price', number_format($product->price,2), ['class' => 'form-control', 'required','step' => '0.01','min'=>'0.01']) }}
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('vat', 'IVA') }}
                {{ Form::number('vat',number_format($product->vat,2), ['class' => 'form-control', 'required','readonly','step' => '0.01','min'=>'0.00']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('actual_price', 'Preço (c/Desconto)') }}
                {{ Form::number('actual_price', number_format($product->actual_price,2), ['class' => 'form-control', 'required','readonly','step' => '0.01','min'=>'0.01']) }}
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('actual_vat', 'IVA (c/Desconto)') }}
                {{ Form::number('actual_vat',number_format($product->actual_vat,2), ['class' => 'form-control', 'required','readonly','step' => '0.01','min'=>'0.00']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {{ Form::label('discount', 'Desconto (em %)') }}
                {{ Form::number('discount',number_format($product->discount), ['class' => 'form-control','step' => '1','min'=>'0','max'=>'100','id' => 'discount']) }}
            </div>
        </div>
       @if(Auth::user()->isAdmin())
       @if(!$product->category['seller_id'])
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('seller_id', 'Vendedor') }}
                {{ Form::select('seller_id', ['' => 'NENHUM'] + $seller,null, ['class' => 'form-control select2','required','id'=>'seller_id']) }}
            </div>
        </div>
        @else
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('seller_id', 'Vendedor') }}
                {{ Form::select('seller_id', ['' => 'NENHUM'] + $seller, $product->category->seller_id, ['class' => 'form-control select2','required','id'=>'seller_id']) }}
            </div>
        </div>   
        @endif
        @endif
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('category_id', 'Categoria') }}
                {{ Form::select('category_id', ['' => 'NENHUM'] + $categories, null, ['class' => 'form-control select2','required','id' => 'category_id']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {{ Form::label('subcategory_id', 'SubCategoria') }}
                {{ Form::select('subcategory_id', ['0' => 'NENHUM'] + $subcategories, null, ['class' => 'form-control select2','id' => 'subcategory_id']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            {{ Form::hidden('delete_photo') }}
    <button class="btn btn-primary btn-submit">Gravar</button>
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
    var discount = $('#discount').val();
    var price = $(this).val();
        
        var actual_price = price - (price * (discount/100));
        var rounded_actual_price = parseFloat(actual_price.toFixed(2));
        var actual_vat = rounded_actual_price * 0.23;
        var rounded_actual_vat = parseFloat(actual_vat.toFixed(2));

        document.getElementById("actual_price").value = rounded_actual_price;
        document.getElementById("actual_vat").value = rounded_actual_vat;
    });

    $('#actual_price').change(function() {
    var IVA = 0.23;
    var vat = ($(this).val()*IVA);
    var rounded_vat = parseFloat(vat.toFixed(2));
    document.getElementById("actual_vat").value= rounded_vat;
    });

    $('#discount').change(function()
    {
        var discount = $(this).val();
        var price = $('#price').val();
        
        var actual_price = price - (price * (discount/100));
        var rounded_actual_price = parseFloat(actual_price.toFixed(2));
        var actual_vat = rounded_actual_price * 0.23;
        var rounded_actual_vat = parseFloat(actual_vat.toFixed(2));

        document.getElementById("actual_price").value = rounded_actual_price;
        document.getElementById("actual_vat").value = rounded_actual_vat;
    });
    
</script>
<script>
$('#seller_id').change(function(){
    var cid = $(this).val();
    if(cid <= 0)
    {
        $("#category_id").empty();
    }
});

$('#seller_id').change(function(){
    var cid = $(this).val();
    if(cid > 0){
        $.ajax({
           type:"get",
           url:"{{url('admin/products/updateCategoryBySeller')}}/"+cid,
           success:function(res)
           {    
            
                if(res)
                {
                    $.each(res,function(key,value){
                        $('#category_id').append($("<option/>", {
                           value: key,
                           text: value
                        }));
                    });
                }
           },
           error:function()
           {
                
           }   
        });
    }
});
$('#category_id').change(function(){
    var cid = $(this).val();
    if(cid <= 0)
    {
        $("#subcategory_id").empty();
    }
});
$('#category_id').change(function(){
    var cid = $(this).val();
    if(cid > 0){
        $.ajax({
           type:"get",
           url:"{{url('admin/products/updateCategory')}}/"+cid,
           success:function(res)
           {    
            $("#subcategory_id").empty();
                if(res)
                {
                    $.each(res,function(key,value){
                        $('#subcategory_id').append($("<option/>", {
                           value: key,
                           text: value
                        }));
                    });
                }
           },
           error:function()
           {
                
           }   
        });
    }
});
</script>

