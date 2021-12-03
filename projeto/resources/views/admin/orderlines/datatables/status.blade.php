@if($row->status_id === null || $row->status_id === 0)
    Sem estado
@else
<div>
    <span class="label label"  style="background-color:{{ $row->status->status_color }};color:white;">{{ $row->status->name }}</span>
</div>

@endif