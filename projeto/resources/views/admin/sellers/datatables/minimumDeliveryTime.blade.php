@if($row->minimum_delivery_time == 0)
Não Definido
@else
{{$row->minimum_delivery_time}} minutos
@endif