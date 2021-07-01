<div>
    @if($row->seller_id === null || $row->seller_id === 0 )
        <p class="label label-warning">Não aplicável</p>
    @else
         <p class="label label-success">{{$row->seller->name}}</p>
    @endif
</div>