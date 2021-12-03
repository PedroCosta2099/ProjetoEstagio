{{ Form::model($orderlines,$formOptions,$orderTotalPrice,$orderVat) }}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span class="fs-15" aria-hidden="true"><i class="fas fa-times"></i></span>
        <span class="sr-only">Fechar</span>
    </button>
    <h4 class="modal-title">{{ $action }}</h4>
</div>
<div class="modal-body">
    @if(Auth::user()->isAdmin()) 
    <div class="row row-5">
        <div class="col-sm-4">

            {{ Form::label('order_id', 'Pedido') }} : {{ Form::label('', $order['id'])}}
            
        </div>
       <div class="col-sm-4">

            {{ Form::label('seller_id', 'Vendedor') }} : {{ Form::label('', $seller->name)}}
          
        </div>
        <div class="col-sm-4">
            <div class="form-group is-required">
                {{ Form::label('status_id', 'Estado') }}
                {{ Form::select('status_id',$allStatus, $order['status_id'], ['class' => 'form-control select2','id' => 'status_id']) }}
            </div>
        </div>
        @else
        <div class="row row-5">
        <div class="col-sm-6">

            {{ Form::label('order_id', 'Pedido') }} : {{ Form::label('', $order['id'])}}
            
        </div>
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('status_id', 'Estado') }}
                {{ Form::select('status_id',$allStatus, $order['status_id'], ['class' => 'form-control select2','id' => 'status_id']) }}
            </div>
        </div>
        @endif  
    @foreach($orderlines as $orderline)
       <div class="col-sm-3">
            <div class="form-group is-required">
                {{ Form::label('product_id', 'Produto') }}
                {{ Form::text('', $orderline->product->name, ['class' => 'form-control ','readonly'])}}
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group is-required">
                {{ Form::label('total_price', 'Preço') }}
                {{ Form::number('totalPrice[]',number_format($orderline['total_price'],2), ['class' => 'form-control uppercase totalPrice','data-id'=>$orderline['id'], 'id'=>'total_price_'.$orderline['id'], 'required','readonly','step' => '0.01']) }}
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group is-required">
                {{ Form::label('', 'IVA') }}
                {{ Form::number('orderlineVat[]',number_format($orderline['vat'],2), ['class' => 'form-control uppercase','data-id'=>$orderline['id'], 'id'=>'vat_'.$orderline['id'],  'required','readonly','step' => '0.01']) }}
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group is-required">
                {{ Form::label('quantity', 'Quantidade') }}
                {{ Form::number('quantity[]',$orderline['quantity'], ['class' => 'quantity form-control  uppercase ','data-id'=>$orderline['id'],'required','min' => '0']) }}
            </div>
        </div>  
    @endforeach
    </div>
    <div class = "row row-5 text-center">
        <div class = "col-sm-6 col-sm-offset-3">
            {{ Form::label('orderTotalPrice', 'Total') }}
            {{ Form::number('total_price',number_format($orderTotalPrice,2),['class'=>'form-control uppercase text-center','readonly'])}}
        </div>
    </div>
    <div class = "row row-5 text-center">
        <div class = "col-sm-6 col-sm-offset-3">
            {{ Form::label('vat', 'IVA') }}
            {{ Form::number('vat',number_format($orderVat,2),['class'=>'form-control uppercase text-center','readonly','id'=>'vat2'])}}
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
 
 

    $('.quantity').change(function(){
        
        var quantity = $(this).val();
        var id = $(this).attr('data-id');
        var IVA = 0.23;
        if(quantity < 0)
        {
            document.getElementById('total_price_'+id).value = 0.00;
            document.getElementById('vat_'+id).value = 0.00;
            var sum = 0;
                $(".totalPrice").each(function(){
                    sum += +$(this).val();
                    total = sum.toFixed(2);
                });
                document.getElementById('total_price').value = total;
                document.getElementById('vat2').value = (total*IVA).toFixed(2);
            
        }
        else if(quantity >= 0)
        {
            
        $.ajax({
           type:"get",
           url:"{{url('admin/orderlines/updatePriceVat')}}/"+ id + "/" +quantity,
           success:function(res)
           {    
                if(res){  
                  
               document.getElementById('total_price_'+id).value =  res['totalPrice'];
               document.getElementById('vat_'+id).value = res['vat'];

               var sum = 0;
                $(".totalPrice").each(function(){
                    sum += +$(this).val();
                    total = sum.toFixed(2);
                });
                document.getElementById('total_price').value = total;
                document.getElementById('vat2').value = (total*IVA).toFixed(2);
               }
           },
           error:function()
           {
                
           }   
        });
        }
    });
</script>
