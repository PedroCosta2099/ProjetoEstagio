<div>
    @if($row->seller_id == null || $row->seller_id == 0)
       
    @else
         {{$row->seller->name}}
    @endif
</div>