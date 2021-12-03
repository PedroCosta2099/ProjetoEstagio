<a href="{{ route('admin.addresses.edit', $row->id) }}" id="{{$row->id}}"  data-toggle="modal" data-target="#modal-remote">
    {{ $row->address }}
</a>