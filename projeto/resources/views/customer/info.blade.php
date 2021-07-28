@extends('customer.layouts.master')
@section('title')
My Enovo Eats
@stop
@section('content')
<div class="about-page">
<div class="row row-5">
    <div class="col-sm-6 about-box">
    <div class="info-title"><span>Os meus dados pessoais</span></div>
    <div><a href="#" class="btn-edit pull-right">Editar</a></div>
    <div class="col-sm-12 box-details">
        
        
        <div class="col-sm-6">
            <label>Nome</label>
            <h6>{{$customer->name}}</h6>
        </div>
        <div class="col-sm-6">
            <label>E-mail</label>
            <h6>{{$customer->email}}</h6>
        </div>
        <div class="col-sm-6">
            <label>Contacto Telefónico</label>
            <h6>{{$customer->phone}}</h6>
        </div>
        <div class="col-sm-6">
            <label>NIF</label>
            <h6>{{$customer->nif}}</h6>
        </div>
  
    </div>
    </div>
    <div class="col-sm-5 about-box col-sm-offset-1">
    <div class="info-title"><span>Moradas</span></div>
    
    @foreach($addresses as $address)
    
    @if($address['billing_address'])
    
    <div class="col-sm-6 box-details">
    <label>Morada de Faturação</label><br>  
    <label>Nome</label>
            <h6>{{$address['address']}}</h6>
            <h6>{{$address['postal_code']}}</h6>
            <h6>{{$address['city']}}</h6>
    </div>
    @endif
    @if($address['shipment_address'])
    
    <div class="col-sm-6 box-details">
    <label>Morada de Envio</label><br> 
    <label>Nome</label>
            <h6>{{$address['address']}}</h6>
            <h6>{{$address['postal_code']}}</h6>
            <h6>{{$address['city']}}</h6>
            
    
    </div>
    @elseif(!$address['shipment_address'] && $address['billing_address'])
    
       <div class="col-sm-6 box-details">
    <label>Morada de Envio</label><br> 
    <label>Nome</label>
            <h6>{{$address['address']}}</h6>
            <h6>{{$address['postal_code']}}</h6>
            <h6>{{$address['city']}}</h6>
            
    
    </div>
    
    @endif
    @endforeach
</div>
</div>
<div class="row row-5">
<div class="col-sm-12 about-box">
<div class="info-title"><span>Pedidos</span></div>
  
       @if(!$orders)
    <span>Ainda não realizou nenhum pedido</span>
    @else
    <div class="table-responsive">
                    <table id="datatable" class="table">
                        <thead>
                            <tr>
                                <th>Pedido</th>
                                <th>Data</th>
                                <th>Estado do Pedido</th>
                                <th>Produto</th>
                                <th>Preço</th>
                            </tr>
                        </thead>
                        @foreach($orders as $order)
                        <tbody>
    
        <td>{{$order['id']}}</td>
        <td class="order-date">{{$order['created_at']}}</td>
        <td>{{$order->status->name}}</td>
        <td>    @foreach($orderlines as $orderline)
                @if($order['id'] == $orderline->order->id)
                    {{$orderline->product->name}}<br>
                @endif
            @endforeach</td>
        <td>€{{$order['total_price']}}</td>      
                           
                        </tbody>
        @endforeach
    @endif  
                    </table>
                </div>
  
    
</div>
</div>
@stop