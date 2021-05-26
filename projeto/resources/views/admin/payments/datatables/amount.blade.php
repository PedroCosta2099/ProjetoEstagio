<div>
    @if($row->amount === null || $row->amount=== 0 )
        
    @else
         €{{$row->amount}}
    @endif
</div>