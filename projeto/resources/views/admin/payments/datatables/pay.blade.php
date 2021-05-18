<div>
    @if($row->payment_status->name === 'PAGO')

    @else
    <div>
    <a href="{{ route('admin.payments.payed', $row->id) }}" class="btn  btn-sm btn-default">
        Marcar como Pago
    </a>
    </div>
    @endif
</div>