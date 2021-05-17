<!--<a href="{{ route('admin.status.edit', $row->id) }}" style="background-color:{{ $row->status_color }};color:white;" data-toggle="modal" data-target="#modal-remote">
    {{ $row->name }}
</a>-->

<div>
    <span class="label label"  style="background-color:{{ $row->status_color }};color:white;">{{ $row->name }}</span>
</div>