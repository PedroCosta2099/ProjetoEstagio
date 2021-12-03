<div>
    @if($row->phone_number === null || $row->phone_number === 0 )
        
    @else
         {{$row->phone_number}}
    @endif
</div>