@if($row->maximum_delivery_time == 0)
Não Definido
@else
{{$row->maximum_delivery_time}} minutos
@endif