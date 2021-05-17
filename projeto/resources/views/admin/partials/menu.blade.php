<aside class="main-sidebar hidden-print">
    <section class="sidebar">
        <ul class="sidebar-menu">
            {!! Html::sidebarOption('dashboard', 'Painel de Resumo', route('admin.dashboard'), null, 'fas fa-fw fa-poll-h') !!}

            @if(Auth::user()->ability(Config::get('permissions.role.admin'),'users,admin_sellers'))

            {!! Html::sidebarTreeOpen('entities', 'Entidades', 'fas fa-fw fa-users') !!}
            {!! Html::sidebarOption('users', 'Colaboradores', route('admin.users.index'), 'admin_sellers') !!}
            <!--{!! Html::sidebarOption('sellers', 'Vendedores', route('admin.sellers.index'), 'sellers') !!}-->
            {!! Html::sidebarOption('admin_roles', 'Perfís e Permissões', route('admin.roles.index'), 'admin_roles') !!}
            {!! Html::sidebarTreeClose() !!} 

            {!! Html::sidebarTreeOpen('orders', 'Pedidos', 'fas fa-fw fa-clipboard-list') !!}
            {!! Html::sidebarOption('status', 'Estados dos Pedidos', route('admin.status.index'),'admin_sellers') !!}
            {!! Html::sidebarOption('orderlines', 'Linhas de Pedidos', route('admin.orderlines.index'),'admin_sellers') !!}
            {!! Html::sidebarOption('orders', 'Pedidos', route('admin.orders.index'), 'admin_sellers') !!} 
            {!! Html::sidebarTreeClose() !!}

            {!! Html::sidebarTreeOpen('products', 'Produtos', 'fas fa-fw fa-clipboard-list','admin_sellers') !!}
            {!! Html::sidebarOption('products', 'Produtos', route('admin.products.index'), 'admin_sellers') !!}
            {!! Html::sidebarOption('categories', 'Categorias', route('admin.categories.index'), 'admin_sellers') !!}
            {!! Html::sidebarOption('subcategories', 'SubCategorias', route('admin.subcategories.index'), 'admin_sellers') !!}
            {!! Html::sidebarOption('extraproducts', 'Acompanhamentos', route('admin.extraproducts.index'), 'admin_sellers') !!}
            {!! Html::sidebarTreeClose() !!}

            {!! Html::sidebarTreeOpen('payments', 'Pagamentos', 'fas fa-credit-card','admin_sellers') !!}
            {!! Html::sidebarOption('payments', 'Pagamentos', route('admin.payments.index'), 'admin_sellers') !!}
            {!! Html::sidebarOption('paymenttypes', 'Métodos de Pagamento', route('admin.paymenttypes.index'), 'admin_sellers') !!}
            {!! Html::sidebarOption('paymentstatus', 'Estados dos Pagamentos', route('admin.paymentstatus.index'), 'admin_sellers') !!}
            {!! Html::sidebarTreeClose() !!}

            {!! Html::sidebarOption('addresses', 'Moradas', route('admin.addresses.index'),'admin_sellers','fas fa-home') !!} <!--nome do menu, nome a apresentar,rota,permissão,icone-->
            @endif
            
        </ul>
    </section>
</aside>