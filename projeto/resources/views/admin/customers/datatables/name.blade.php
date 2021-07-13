@if(!(!Auth::user()->hasRole([config('permissions.role.admin')]) && $row->hasRole([config('permissions.role.admin')])))
{{ Html::link(route('admin.customers.edit', $row->id), $row->name) }}
@else
    {{ $row->name }}
@endif
<br/>
<span>{{ $row->email }}</span>