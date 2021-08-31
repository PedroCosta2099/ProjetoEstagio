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
    margin-top:10px;
    margin-left:10px;
    background:white;
    box-shadow: 1px 3px 13px 0px #524e4e70;
    
}
.cont2{
    
    
    padding:0px !important;
    
    margin-right:6px;
    background:white;
    box-shadow: 1px 3px 13px 0px #524e4e70;
    
}
.container
{
    margin:0px !important;
}
</style>
@stop
@section('content')
 @if(Auth::user()->isAdmin())   
     <div class="row ">
        <div class="col-sm-12">
            
<div class="row">
    <div class="col-sm-4 cont">
        <h3 class="text-center">
            Vendas por Restaurante
            <hr style="height:1px;background-color:gray">
            <canvas id="orders_seller_chart"></canvas>
        </h3>
    </div>
    <div class="col-sm-4 cont">
        <h3 class="text-center">
            Clientes com mais Pedidos
            <hr style="height:1px;background-color:gray">
            <canvas id="orders_customers_chart"></canvas>
        </h3>
    </div>
    <div class="col-sm-4 cont">
        <h3 class="text-center">
            Produtos mais Vendidos
            <hr style="height:1px;background-color:gray">
            <canvas id="products_chart"></canvas>
        </h3>
    </div>
    <div class="col-sm-4 cont" style="margin-right:10px;">
    <h3 class="text-center">
    Pedidos Recentes Ativos
    <hr style="height:1px;background-color:gray;margin-bottom:1px !important">
    </h3>
        <div class="row">
            <div class="col-sm-12">
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
            </div>
        </div>
        <div class="col-sm-4 cont" style="margin-right:10px;margin-top:10px">
    <h3 class="text-center">
    Pagamentos Recentes
    <hr style="height:1px;background-color:gray;margin-bottom:1px !important">
    </h3>
        <div class="row">
            <div class="col-sm-12">
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
                        </tbody>
                        @endif
       @endforeach
                    </table>
                </div>
            </div>
            </div>
        </div>
        
</div>
</div>
    
    

</div>
            
        </div>
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