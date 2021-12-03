<div>
    @if($row->payment_status->name === 'PAGO' || $row->payment_status_id == 0)
    @else
    <div>
    <a href="{{ route('admin.payments.payed', $row->id) }}" class="btn  btn-sm btn-default">
        Marcar como Pago
    </a>
    </div>
    @endif
</div>