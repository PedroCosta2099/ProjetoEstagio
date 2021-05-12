<div>
    @if($row->status_id === null || $row->status_id === 0 )
                    
            Atribuir Estado
        
    @else
         {{$row->status->name}}
    @endif
</div>