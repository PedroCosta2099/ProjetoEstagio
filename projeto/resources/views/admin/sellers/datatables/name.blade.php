@if(!(!Auth::user()->hasRole([config('permissions.role.admin')]) && $row->hasRole([config('permissions.role.admin')]))
|| (!Auth::user()->hasRole([config('permissions.role.admin')]) && !$row->hasShop(Auth::user()->shops)))
{{ Html::link(route('admin.sellers.edit', $row->id), $row->name) }}
@else
    {{ $row->name }}
@endif
<br/>
<span>{{ $row->email }}</span>