{{ Form::model($orderlines,$formOptions) }}
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

            {{ Form::label('order_id', 'Pedido') }} : {{ Form::label('', $order['id'])}}
            
        </div>   
    @foreach($orderlines as $orderline)
       <div class="col-sm-3">
            <div class="form-group is-required">
                {{ Form::label('product_id', 'Produto') }}
                {{ Form::hidden('', $orderline['id'],['class'=>'orderLineId'])}}
                {{ Form::text('', $orderline['product_id'], ['class' => 'form-control ','readonly'])}}
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group is-required">
                {{ Form::label('total_price', 'Preço') }}
                {{ Form::text('total_price',$orderline['total_price'], ['class' => 'form-control uppercase', 'required','readonly']) }}
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group is-required">
                {{ Form::label('vat', 'IVA') }}
                {{ Form::text('vat',$orderline['vat'], ['class' => 'form-control uppercase', 'required','readonly','step' => '0.01']) }}
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group is-required">
                {{ Form::label('quantity', 'Quantidade') }}
                {{ Form::number('',$orderline['quantity'], ['class' => 'quantity form-control  uppercase ', 'required','min' => '0']) }}
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
    
    $('.quantity').change(function(){
        
        var quantity = $(this).val();
        
        if(quantity <= 0)
        {
            document.getElementById('total_price').value = 0;
            document.getElementById('vat').value = 0;
        }
        else if(quantity > 0)
        {
            
        var id = $('.orderLineId').val();
        
        $.ajax({
           type:"get",
           url:"{{url('admin/orderlines/updatePriceVat')}}/"+ id + "/" +quantity,
           success:function(res)
           {    
                if(res){
               document.getElementById('total_price').value = res['totalPrice'];
               document.getElementById('vat').value = res['vat'];
               }
           },
           error:function()
           {
                
           }   
        });
        }
    });
</script>
