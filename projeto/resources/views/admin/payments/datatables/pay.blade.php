<!--<div>
    @if($row->payment_status->name === 'PAGO')

    @else
    <div>
    <a href="{{ route('admin.payments.statusEdit', $row->id) }}" class="btn  btn-sm btn-default" data-toggle="modal" data-target="#modal-remote">
        Marcar como Pago
    </a>
    </div>
    @endif
</div>-->