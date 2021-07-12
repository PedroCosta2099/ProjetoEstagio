{{ Form::model($subcategory, $formOptions) }}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span class="fs-15" aria-hidden="true"><i class="fas fa-times"></i></span>
        <span class="sr-only">Fechar</span>
    </button>
    <h4 class="modal-title">{{ $action }}</h4>
</div>
<div class="modal-body">
    <div class="row row-5">
        <div class="col-sm-3">
            <div class="form-group is-required">
                {{ Form::label('name', 'SubCategoria') }}
                {{ Form::text('name', null, ['class' => 'form-control uppercase', 'required']) }}
            </div>
        </div>
        @if(Auth::user()->isAdmin())
        <div class="col-sm-3">
            <div class="form-group is-required">
                {{ Form::label('seller_id', 'Vendedor') }}
                {{ Form::select('seller_id', ['0' => 'NENHUM'] + $seller, null, ['class' => 'form-control select2', 'required','id'=>'seller_id']) }}
            </div>
        </div>
        @endif
        <div class="col-sm-3">
            <div class="form-group is-required">
                {{ Form::label('category_id', 'Categoria') }}
                {{ Form::select('category_id', ['0' => 'NENHUM'] + $categories, null, ['class' => 'form-control select2', 'required','id' => 'category_id']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
    <button class="btn btn-primary btn-submit">Gravar</button>
</div>
{{ Form::close() }}

<script>
    $('.select2').select2(Init.select2());
    $('input').iCheck(Init.iCheck());
    $('[data-toggle="tooltip"]').tooltip();

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
           url:"{{url('admin/subcategories/updateCategory')}}/"+cid,
           success:function(res)
           {    
            $("#category_id").empty();
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

</script>
