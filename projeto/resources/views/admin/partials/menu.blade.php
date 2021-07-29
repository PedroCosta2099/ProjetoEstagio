<aside class="main-sidebar hidden-print">
    <section class="sidebar">
        <ul class="sidebar-menu">
            {!! Html::sidebarOption('dashboard', 'Painel de Resumo', route('admin.dashboard'), null, 'fas fa-fw fa-poll-h') !!}

            @if(Auth::user()->ability(Config::get('permissions.role.admin'),'users,admin_sellers'))

            {!! Html::sidebarTreeOpen('entities', 'Entidades', 'fas fa-fw fa-users') !!}
            {!! Html::sidebarOption('users', 'Colaboradores', route('admin.users.index')) !!}
            {!! Html::sidebarOption('sellers', 'Vendedores', route('admin.sellers.index'),'admin') !!}
            {!! Html::sidebarOption('customers', 'Clientes', route('admin.customers.index'),'admin') !!}
            {!! Html::sidebarOption('admin_roles', 'Perfís e Permissões', route('admin.roles.index')) !!}
            {!! Html::sidebarTreeClose() !!} 
            @endif
            
            {!! Html::sidebarTreeOpen('orders', 'Pedidos', 'fas fa-fw fa-clipboard-list') !!}
            {!! Html::sidebarOption('status', 'Estados dos Pedidos', route('admin.status.index'),'admin') !!}
            {!! Html::sidebarOption('orderlines', 'Linhas de Pedidos', route('admin.orderlines.index')) !!}
            {!! Html::sidebarOption('orders', 'Pedidos', route('admin.orders.index'),'admin') !!} 
            {!! Html::sidebarTreeClose() !!}
            @if(Auth::user()->ability(Config::get('permissions.role.admin'),'admin_sellers'))
            {!! Html::sidebarTreeOpen('products', 'Produtos', 'fas fa-fw fa-clipboard-list','admin_sellers') !!}
            {!! Html::sidebarOption('products', 'Produtos', route('admin.products.index'), 'admin_sellers') !!}
            {!! Html::sidebarOption('categories', 'Categorias', route('admin.categories.index'), 'admin_sellers') !!}
            {!! Html::sidebarOption('subcategories', 'SubCategorias', route('admin.subcategories.index'), 'admin_sellers') !!}
            {!! Html::sidebarOption('extraproducts', 'Acompanhamentos', route('admin.extraproducts.index'), 'admin_sellers') !!}
            {!! Html::sidebarTreeClose() !!}
            @endif
            {!! Html::sidebarTreeOpen('payments', 'Pagamentos', 'fas fa-credit-card') !!}
            {!! Html::sidebarOption('payments', 'Pagamentos', route('admin.payments.index')) !!}
            {!! Html::sidebarOption('paymenttypes', 'Métodos de Pagamento', route('admin.paymenttypes.index'),'admin') !!}
            {!! Html::sidebarOption('paymentstatus', 'Estados dos Pagamentos', route('admin.paymentstatus.index'),'admin') !!}
            {!! Html::sidebarTreeClose() !!}
            
            {!! Html::sidebarOption('addresses', 'Moradas', route('admin.addresses.index'),'admin','fas fa-home') !!} <!--nome do menu, nome a apresentar,rota,permissão,icone-->
            
            
        </ul>
    </section>
</aside>