@extends('layouts.email')

@section('content')
<h5 style="font-size: 16px">Envio de Conta Corrente</h5>
<p>
    Caro(a) cliente,
    <br/>
    Junto enviamos o resumo da sua conta corrente até ao momento.
</p>
<p>
    Possui atualmente <b>{{ $countDocuments }} documentos</b> por liquidar num total de <b style="color: red">{{ money($totalDocuments, Setting::get('app_currency'))  }}</b>.
</p>
@if(config('app.source') == 'fozpost')
    <p>
        Agradecemos que proceda à liquidação dos valores vencidos, no <u>prazo máximo de 15 dias</u>.
        <br/>
        Após essa data se os valores não estiverem regularizados iremos proceder ao bloqueio do acesso área cliente e enviar a mesma para contencioso.
        <br/>
        IBAN para pagamentos: PT50.0010.0000.3216.1960.0012.4
    </p>
@endif
<table style="width: 100%; border: 1px solid #ddd; font-size: 13px" cellspacing="0" cellpadding="3">
    <tr>
        <th style="background: #dddddd; text-align: left">Data</th>
        <th style="background: #dddddd; text-align: left">Documento</th>
        <th style="background: #dddddd; text-align: left">Referência</th>
        <th style="background: #dddddd; text-align: left">Total</th>
        <th style="background: #dddddd; text-align: left; width: 120px">Vencimento</th>
    </tr>
    @foreach($documents as $document)
        <tr>
            <td style="border-bottom: 1px solid #dddddd;">{{ $document->date->format('Y-m-d') }}</td>
            <td style="border-bottom: 1px solid #dddddd;">{{ $document->doc_serie }} {{ $document->doc_id }}</td>
            <td style="border-bottom: 1px solid #dddddd;">{{ $document->reference }}</td>
            <td style="border-bottom: 1px solid #dddddd;">{{ money($document->total, Setting::get('app_currency')) }}</td>
            <td style="border-bottom: 1px solid #dddddd;">
                <?php $date = new Date($document->due_date); ?>
                @if($date < $today)
                    <span style="color: red">
                        {{ $date->format('Y-m-d') }}<br/>
                        <small>{{ $date->diffInDays($today) }} dias em atraso</small>
                    </span>
                @else
                    {{ $date->format('Y-m-d') }}
                @endif
            </td>
        </tr>
    @endforeach
</table>
@if(Setting::get('bank_iban'))
    <hr/>
    <p>
        <b>IBAN para pagamentos: {{ Setting::get('bank_iban') }}</b><br/>
        @if(Setting::get('bank_name'))
            Banco: {{ Setting::get('bank_name') }}
        @endif
    </p>
@endif
<p>
    Pode consultar e efetuar o download de todos os seus documentos emitidos na sua área de cliente.
    <br/>
    Aceda aqui para <a href="{{ route('account.login') }}">Iniciar Sessão</a> na sua conta.
</p>
@stop