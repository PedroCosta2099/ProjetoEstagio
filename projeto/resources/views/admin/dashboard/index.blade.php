@section('title')
    Painel de Controlo
@stop

@section('content-header')
    Painel de Controlo
@stop
@section('styles')
<style>
     .cont{
    
    padding:0px !important;
    max-width:487.66px;
    margin-top:10px;
    
    display:inline-flex;
    flex-wrap:wrap;
    justify-content: center;
    background:white;
    box-shadow: 1px 3px 13px 0px #524e4e70;
    
}
@media screen and (min-width:1320px)
{
    .cont{
        min-width:510px;
    }
    .text-size{
        font-size:24px !important;
        
    }
        
    
}
@media screen and (max-width:1319px)
{
    .cont{
        min-width:447.33px;
        
    }

}
@media screen and (max-width:478px)
{
    .cont{
        min-width:calc(100% - 20px);
    }
}
@media screen and (max-width:360px)
{
    .cont{
        min-width:320px;
    }
}
@media screen and (max-width:320px)
{
    .cont{
        min-width:calc(100% - 20px);
    }
    .size{
        max-width:290px;
    }
    h3{
        width:280px;
    }
    
    
}
@media screen and (min-width:280px)
{
    .cont{
        width:280px;
    }
    
    .text-size{
        font-size:20px;
    }
    .size{
        max-width:250px !important;
    }
}

.cont2 {
  --gap: 10px;
  display: inline-flex;
  flex-wrap: wrap;
  justify-content:center;
  margin: calc(-1 * var(--gap)) 0 0 calc(-1 * var(--gap));
  width: calc(100% + var(--gap));
}

.cont2 > * {
  margin: var(--gap) 0 0 var(--gap);
}

</style>
@stop
@section('content')
 @if(Auth::user()->isAdmin())  
            
<div class="row cont2">
    
<div class="col-sm-4 cont">
                <h3 class="text-center text-size">
    Pedidos Recentes Ativos
    <hr class="size" style="height:1px;background-color:gray;margin-bottom:1px !important">
    </h3>
            <div class="table-responsive">
                    <table id="datatable" class="table" style="margin-bottom:1px !important">
                        <thead>
                            <tr>
                                <th class="w-1">Pedido</th>
                                <th class="w-1">Data</th>
                                <th >Estado do Pedido</th>
                                <th class="w-1">Estado do Pagamento</th>
                                <th>Preço</th>
                                <th>Envio</th>
                            </tr>
                        </thead>
                     @foreach($paymentsWithOrders as $paymentWithOrder)  
                        <tbody>
                    
                        
        <td>{{$paymentWithOrder['order']['id']}}</td>
        <td class="order-date">{{date('d/m/Y',strtotime($paymentWithOrder['order']['created_at']))}}</td>
        @foreach($ordersWithStatus as $order)
        @if($order->id == $paymentWithOrder['order']['id'])
        <td>
            {{$order->status->name}}
        </td>
        @endif
        @endforeach
        <td>{{$paymentWithOrder['payment_status']['name']}}</td>
        <td>€ {{number_format($paymentWithOrder['order']['total_price'],2,',','.')}}</td>      
        <td>€ {{number_format($paymentWithOrder['order']['delivery_fee'],2,',','.')}}</td>
                        </tbody>
       @endforeach
                    </table>
                </div>
            </div>
    <!---->
    
    <div class="col-sm-4 cont" >
        
    <h3 class="text-center text-size">
    Pagamentos Pendentes

    
    <hr class="size"  style="height:1px;background-color:gray;margin-bottom:1px !important">
    <br>    @if(count($paymentsWithPendingStatus) == 0)
<p>Sem Pagamentos Pendentes</p>
            @endif
    </h3>
    
    @if(count($paymentsWithPendingStatus) > 0)
            <div class="table-responsive">
                    <table id="datatable" class="table" style="margin-bottom:1px !important">
                        <thead>
                            <tr>
                                <th class="w-1">Pedido</th>
                                <th class="w-1">Data</th>
                                <th>Estado do Pedido</th>
                                <th class="w-1">Estado do Pagamento</th>
                                <th>Pagar <i class="fa fa-info-circle" data-toggle="tooltip" title="Marcar como Pago"></i><th>
                            </tr>
                        </thead>
                     @foreach($paymentsWithOrders as $paymentWithOrder)  
                     @if($paymentWithOrder['payment_status']['name'] == "PENDENTE")
                        <tbody>
        <td>{{$paymentWithOrder['order']['id']}}</td>
        <td class="order-date">{{date('d/m/Y',strtotime($paymentWithOrder['order']['created_at']))}}</td>
        @foreach($ordersWithStatus as $order)
        @if($order->id == $paymentWithOrder['order']['id'])
        <td>
            {{$order->status->name}}
        </td>
        @endif
        @endforeach
        <td>{{$paymentWithOrder['payment_status']['name']}}</td>
        <td><a href="{{ route('admin.payments.payed', $paymentWithOrder['id']) }}" class="btn  btn-sm btn-default">
        Pago
    </a></td>
                        
                        @endif
       @endforeach
                </tbody>
                </table>
            </div>
 
    @endif           </div>
<!----><div class="col-sm-3 cont">
<h3 class="text-center text-size">
            Vendas por Restaurante
            <hr class="size"  style="height:1px;background-color:gray">
            <canvas id="orders_seller_chart"></canvas>
        </h3>
    </div>
    <!----><div class="col-sm-3 cont">
        <h3 class="text-center text-size">
            Clientes com mais Pedidos
            <hr class="size" style="height:1px;background-color:gray">
            <canvas id="orders_customers_chart"></canvas>
        </h3>
    </div>
    <!----><div class="col-sm-3 cont" >
        <h3 class="text-center text-size">
            Produtos mais Vendidos
            <hr class="size"  style="height:1px;background-color:gray">
            <canvas id="products_chart"></canvas>
        </h3>       
    </div>
    <!---->       
</div>
    @endif
@stop

@section('scripts')
@if(Auth::user()->isAdmin())
<script>
        var ctx = document.getElementById('orders_seller_chart').getContext('2d');
        
        var estado_chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!!json_encode($countOrdersSeller_labels)!!},
                datasets: [{
                    label: 'Vendas nos últimos 30 dias',
                    backgroundColor: {!! json_encode($countOrdersSeller_colours)!!} ,
                    data:  {!! json_encode($countOrdersSeller_data)!!} ,
                    borderWidth: 1,
                    hoverOffset: 4,
                    responsive:true

                }]
            }
        });
    </script>
<script>
        var ctx = document.getElementById('orders_customers_chart').getContext('2d');
        
        var estado_chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!!json_encode($topCustomerOrders_labels)!!},
                datasets: [{
                    label: 'Vendas nos últimos 30 dias',
                    backgroundColor: {!! json_encode($topCustomerOrders_colours)!!} ,
                    data:  {!! json_encode($topCustomerOrders_data)!!} ,
                    borderWidth: 1,
                    hoverOffset: 4,
                    responsive:true

                }]
            }
        });
    </script>
    <script>
        var ctx = document.getElementById('products_chart').getContext('2d');
        
        var estado_chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!!json_encode($orderlinesGroupByProduct_labels)!!},
                datasets: [{
                    label: 'Vendas nos últimos 30 dias',
                    backgroundColor: {!! json_encode($orderlinesGroupByProduct_colours)!!} ,
                    data:  {!! json_encode($orderlinesGroupByProduct_data)!!} ,
                    borderWidth: 1,
                    hoverOffset: 4,
                    responsive:true

                }]
            }
        });
    </script>
    @endif
@stop