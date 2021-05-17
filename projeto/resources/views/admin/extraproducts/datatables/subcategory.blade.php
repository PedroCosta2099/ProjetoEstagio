<div>
    @if($row->subcategory_id === null || $row->subcategory_id === 0 )
        Sem SubCategoria
    @else
         {{$row->subcategory->name}}
    @endif
</div>