<div>
    @if($row->payment_status_id === null || $row->payment_status_id === 0 )
        Sem Estado
    @else
    <div>
    <span class="label label"  style="background-color:{{ $row->payment_status->status_color }};color:white;">{{ $row->payment_status->name }}</span>
    </div>
    @endif
</div>