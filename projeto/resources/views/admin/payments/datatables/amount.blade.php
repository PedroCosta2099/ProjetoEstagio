<div>
    @if($row->amount === null || $row->amount=== 0 )
        
    @else
    €{{number_format($row->amount,2,',','.')}}
    @endif
</div>