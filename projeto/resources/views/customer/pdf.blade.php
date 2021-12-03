<head>
<style>

    body{
     
        font-family: 'Roboto', 'Segoe UI', Tahoma, sans-serif;
    }
    table,td,th{
        border-collapse: collapse;
        border:1px dashed gray;
        border-top:none;
        border-right:none;
    }
    .table{
        width:100%;
        font-family: 'Roboto', 'Segoe UI', Tahoma, sans-serif;
    }
    th{
        
        background:#0B3354;
        color:white;
    }
    td{
        padding-bottom:10px;
    }
    .address{
        width:100%;
        border:1px dashed gray;
        border-left:none;
        border-right:none;
        margin-bottom:30px;
    }
    .shipmentAddress{
        
        width:50%;
        float:left;
        line-height:1.2;
        margin-left:-1px;
        font-size:16px;

    }
    .billingAddress{
        width:50%;
        text-align:left;
        float:right;
        margin-right:-1px;
        line-height:1.2;
        font-size:16px;
    }
    .cont{
        margin-left:10px;
    }
    footer{
        width:50%;
        float:right;
        margin-top:30px;
    }

</style>
</head>
<body>
<h1>Fatura</h1>
<h2>Pedido Nº{{$order['id']}}</h2>
<h2>Data: {{$order['created_at']->format('d-m-Y')}}
<div class="address">
         <div class="shipmentAddress">
             <h5 style="text-align:right;padding-right:10px;margin-bottom:0px;">Morada de Envio</h5>
             <h3>{{$order['customers']['name']}}</h3>
             <h4>{{$shipmentAddress['address']}}</h4>
             <h4>{{$shipmentAddress['postal_code']}} {{$shipmentAddress['city']}}
             <br>Portugal</h4>
        </div>
        <div class="billingAddress">
            <div class="cont">
             <h5 style="text-align:right;margin-bottom:0px;">Morada de Faturação</h5>
             <h3>{{$order['customers']['name']}}</h3>
             <h4>{{$billingAddress['address']}}</h4>
             <h4>{{$billingAddress['postal_code']}} {{$billingAddress['city']}}
             <br>Portugal</h4>
            </div>
        </div>
</div>

<div>
                    <table style="border:none" id="datatable" class="table">
                        <thead>
                            <tr>
                                <th style="border-left:none;">CÓD. PRODUTO</th>
                                <th>QTD</th>
                                <th>DESCRIÇÃO PRODUTO</th>
                                <th>PVP</th>
                                <th id="last">IVA</th>

                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach($orderlines as $orderline)
    <tr>
                    <td style="border-left:none">{{$orderline['product']['id']}}</td>
                    <td>{{$orderline['quantity']}}</td>
                    <td style="text-align:left;padding-left:10px">{{$orderline['product_name']}}</td> 
                    <td style="text-align:right;padding-right:10px">€{{number_format($orderline['total_price'], 2,',','.')}}</td>
                    <td style="text-align:right;padding-right:10px">23%</td>
                
    </tr> 
                        @endforeach
    </tbody>

                    </table>
                </div>
<footer >
<table  style="width:100%;border:none;" >
  <tr>
    <th style="border:none">Valor Líquido</th>
    <td style="border:none"> €{{number_format($order['total_price'],2,',','.')}}</td>
  </tr>
  <tr>
    <th style="border:none">Portes</th>
    <td style="border:none"> €{{number_format($order['delivery_fee'],2,',','.')}}</td>
  </tr>
  <tr>
    <th style="border:none">Total</th>
    <td style="border-top:1px dashed grey;border-left:none;border-bottom:none"> €{{number_format($order['delivery_fee']+$order['total_price'],2,',','.')}}</td>
  </tr>
</table>
</footer>
</body>