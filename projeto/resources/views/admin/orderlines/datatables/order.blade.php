@if(Auth::user()->isAdmin())
<a href="{{ route('admin.orders.edit', $row->order_id) }}" data-toggle="modal" data-target="#modal-remote">
    #{{ $row->order_id }}
</a>
@else
<span>#{{ $row->order_id }}</span>
@endif
