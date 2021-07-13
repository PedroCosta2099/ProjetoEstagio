@if(!(!Auth::user()->hasRole([config('permissions.role.admin')]) && $row->hasRole([config('permissions.role.admin')])))

    @if($row->id != Auth::user()->id)
    <div class="btn-group btn-group-sm">
        <a href="{{ route('admin.customers.edit', $row->id) }}" class="btn btn-sm btn-default">
            Editar
        </a>
        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu pull-right">
            <li>
                <a href="{{ route('admin.customers.remote-login', $row->id) }}" class="text-yellow"
                   data-method="post" data-confirm-title="Iniciar Sessão Remota" data-confirm-class="btn-success"
                   data-confirm-label="Iniciar Sessão"
                   data-confirm="Pretende iniciar sessão como {{ $row->name }}?" target="_blank">
                    <i class="fa fa-sign-in bigger-120"></i> Iniciar Sessão
                </a>
            </li>
            <li>
                <a href="{{ route('admin.customers.destroy', $row->id) }}" data-method="delete" data-confirm="Confirma a remoção do registo selecionado?">
                    <i class="fa fa-trash bigger-120"></i> Eliminar
                </a>
            </li>
        </ul>
    </div>
    @else
    <div class="text-center">
        <a href="{{ route('admin.customers.edit', $row->id) }}" class="btn btn-sm btn-default">
            Editar
        </a>
    </div>
    @endif
@endif