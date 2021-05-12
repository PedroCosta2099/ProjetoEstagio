@extends('layouts.email')

@section('content')
<h5 style="font-size: 16px">Envio ficheiro SAF-T {{ trans('datetime.month.'.$saft->month) }} {{ $saft->year }}</h5>
<p>
    Estimado Sr(a),
    <br/>
    Enviamos em anexo o ficheiro SAF-T referente ao movimentos fiscais da nossa empresa<br/>
    no mês <b>{{ trans('datetime.month.'.$saft->month) }} de {{ $saft->year }}</b>.
</p>
<p>
    Qualquer dúvida, não hesite em contactar.
    <br/>
    Obrigado desde já.
</p>
@stop