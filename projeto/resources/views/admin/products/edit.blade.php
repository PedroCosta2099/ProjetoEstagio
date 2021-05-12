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
                {{ Form::text('name', null, ['class' => 'form-control uppercase', 'required']) }}
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
                {{ Form::text('description', null, ['class' => 'form-control', 'required']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('price', 'Preço') }}
                {{ Form::text('price', null, ['class' => 'form-control uppercase', 'required']) }}
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('vat', 'IVA') }}
                {{ Form::number('vat',null, ['class' => 'form-control uppercase', 'required','readonly','step' => '0.01']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {{ Form::label('category_id', 'Categoria') }}
                {{ Form::select('category_id', ['0' => 'NENHUM'] + $categories, null, ['class' => 'form-control select2','id' => 'category_id']) }}
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
    });  
    
</script>
<script>
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

