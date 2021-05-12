@if($row->last_login)
{{ $row->last_login }}
<br/>
<small>IP: {{ $row->ip }}</small>
@endif