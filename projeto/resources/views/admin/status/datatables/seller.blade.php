<div>
    @if($row->seller_id == null || $row->seller_id == 0 || $row->seller_id != Auth::user()->id )
       
    @else
         {{$row->seller->name}}
    @endif
</div>