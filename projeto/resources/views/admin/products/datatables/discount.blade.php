@if($row->discount == 0 || $row->discount == null)
    Sem Desconto
@else
{{$row->discount}} % 
@endif