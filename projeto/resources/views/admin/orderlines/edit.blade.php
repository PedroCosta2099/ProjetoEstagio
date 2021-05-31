{{ Form::model($orderline,$formOptions) }}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span class="fs-15" aria-hidden="true"><i class="fas fa-times"></i></span>
        <span class="sr-only">Fechar</span>
    </button>
    <h4 class="modal-title">{{ $action }}</h4>
</div>
<div class="modal-body">
<div class="row row-5">

        <div class="col-sm-12">

            {{ Form::label('order_id', 'Pedido') }} : {{ Form::label('', $orderId)}}
            {{ Form::hidden('', $id,['id'=>'orderLineId'])}}

        </div>        
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('product_id', 'Produto') }}
                {{ Form::text('', $productName, ['class' => 'form-control uppercase','readonly'])}}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('total_price', 'Preço') }}
                {{ Form::text('total_price',$totalPrice, ['class' => 'form-control uppercase', 'required','readonly','id' => 'total_price']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('vat', 'IVA') }}
                {{ Form::text('vat',$vat, ['class' => 'form-control uppercase', 'required','readonly','id' => 'vat2']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('quantity', 'Quantidade') }}
                {{ Form::number('quantity',null, ['class' => 'form-control  uppercase', 'required','id' => 'quantity','min' => '0']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('status_id', 'Estados') }}
                {{ Form::select('status_id',$status, null, ['class' => 'form-control select2','id' => 'status_id']) }}
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

    $('#quantity').change(function(){
        
        var quantity = $(this).val();
        if(quantity < 0)
        {
            document.getElementById('total_price').value = 0.00;
            document.getElementById('vat2').value = 0.00;
        }
        else if(quantity >= 0)
        {
        var id = $('#orderLineId').val();
        $.ajax({
           type:"get",
           url:"{{url('admin/orderlines/updatePriceVat')}}/"+ id + "/" +quantity,
           success:function(res)
           {    
                if(res){
               document.getElementById('total_price').value = res['totalPrice'];
               document.getElementById('vat2').value = res['vat'];
               }
           },
           error:function()
           {
                
           }   
        });
        }
    });
</script>


