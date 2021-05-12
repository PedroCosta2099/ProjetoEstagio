<aside class="main-sidebar hidden-print">
    <section class="sidebar">
        <ul class="sidebar-menu">
            {!! Html::sidebarOption('dashboard', 'Painel de Resumo', route('admin.dashboard'), null, 'fas fa-fw fa-poll-h') !!}

            @if(Auth::user()->ability(Config::get('permissions.role.admin'), 'customers,providers,users,operators'))
            {!! Html::sidebarTreeOpen('entities', 'Entidades', 'fas fa-fw fa-users') !!}
            {!! Html::sidebarOption('users', 'Colaboradores', route('admin.users.index'), 'users') !!}
            {!! Html::sidebarOption('admin_roles', 'Perfís e Permissões', route('admin.roles.index'), 'admin_roles') !!}
            {!! Html::sidebarTreeClose() !!}
            @endif

            {!! Html::sidebarOption('vehicles', 'Gerir Viaturas', route('admin.vehicles.index'), 'vehicles', 'fas fa-fw fa-car') !!}
            {!! Html::sidebarOption('brands', 'Gerir Marcas', route('admin.brands.index'), 'brands', 'fas fa-fw fa-car') !!}
            {!! Html::sidebarOption('status', 'Estados', route('admin.status.index'), 'status','fas fa-exclamation-triangle') !!}
        </ul>
    </section>
</aside>