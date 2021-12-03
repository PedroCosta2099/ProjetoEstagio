<div>
    @if($row->payment_type_id === null || $row->payment_type_id === 0 )
        Sem Método de Pagamento
    @else
         {{$row->payment_type->name}}
    @endif
</div>