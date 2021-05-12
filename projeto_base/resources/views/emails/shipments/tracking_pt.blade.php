@extends('layouts.email')

@section('content')
<h5 style="font-size: 16px">Estimado {{ $shipment->recipient_name }},</h5>
{{--@if(@$shipment->service->code == 'RED')
    <p>
        O nosso cliente {{ $shipment->sender_name }} instruiu-nos para lhe entregar a uma encomenda nas nossas instalações.
        <br/>
        Deverá deslocar-se até às nossas instalações para proceder ao levantamento da sua encomenda.
        <br/>
        <b>
            {{ @$shipment->agency->company }}<br/>
            {{ @$shipment->agency->address }}<br/>
            {{ @$shipment->agency->zip_code }} {{ @$shipment->agency->city }}
        </b>
        <br/>
        Tlf: {{ @$shipment->agency->phone }} / Tlm:{{ @$shipment->agency->mobile }}
    </p>
    <h4>
        Número de encomenda: <span style="font-weight: bold">{{ $shipment->tracking_code }}</span>
    </h4>
@else--}}
<p>
    @if(in_array(Setting::get('app_mode'), ['freight', 'cargo']))
        O nosso cliente {{ @$shipment->customer->name }} solicitou-nos o seguinte serviço:
        <br/>
        <table style="width: 100%">
            <tr>
                <td style="width: 50%">
                    <h4 style="margin: 0">CARGA</h4>
                    <b>
                        {{ $shipment->sender_name }}<br/>
                        {{ $shipment->sender_address }}<br/>
                        {{ $shipment->sender_zip_code }} {{ $shipment->sender_city }}
                    </b>
                    <br/>
                    Data: {{ $shipment->shipping_date }}
                </td>
                <td style="width: 50%">
                    <h4 style="margin: 0">DESCARGA</h4>
                    <b>
                        {{ $shipment->recipient_name }}<br/>
                        {{ $shipment->recipient_address }}<br/>
                        {{ $shipment->recipient_zip_code }} {{ $shipment->recipient_city }}
                    </b>
                    <br/>
                    Data: {{ $shipment->delivery_date }}
                </td>
            </tr>
        </table>
    @else
        O nosso cliente {{ @$shipment->customer->name }} instruiu-nos para fazer uma entrega na seguinte morada:
        <br/>
        <b>
            {{ $shipment->recipient_name }}<br/>
            {{ $shipment->recipient_address }}<br/>
            {{ $shipment->recipient_zip_code }} {{ $shipment->recipient_city }}
        </b>
    @endif
</p>
<p>
    Pode acompanhar o estado do serviço através do nosso portal online.<br/>
    <h4 style="margin-top: 10px; margin-bottom: 0">Tracking Code: {{ $shipment->tracking_code }}</h4>
    <h4 style="margin: 0; float: left">Seguimento Online:</h4>&nbsp;&nbsp;<a href="{{ route('tracking.index', ['tracking' => $shipment->tracking_code]) }}">{{ route('tracking.index', ['tracking' => $shipment->tracking_code]) }}</a>
    <br/>
</p>
{{--@endif--}}

@if($shipment->charge_price || $shipment->obs)
    <p>
        <b>Informações Adicionais:</b><br/>
        @if($shipment->charge_price)
            Esta é uma entrega à cobrança.
            Por favor tenha preparado o valor de {{ money($shipment->charge_price, Setting::get('app_currency')) }} para pagamento no ato da entrega.
            <br/>
        @endif

        @if($shipment->obs)
            {{ $shipment->obs }}
        @endif
    </p>
@endif
@stop