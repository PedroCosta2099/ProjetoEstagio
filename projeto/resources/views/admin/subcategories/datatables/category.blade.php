<div>
    @if($row->category_id === null || $row->category_id === 0 )
        Sem Categoria
    @else
         {{$row->category->name}}
    @endif
</div>