@if(Auth::user()->isAdmin())
<a href="{{ route('admin.orders.edit', $row->order->id) }}" data-toggle="modal" data-target="#modal-remote">
    #{{ $row->order->id }}
</a>
@else
<span>{{ $row->order->id }}</span>
@endif

