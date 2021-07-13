<!--@if(Session::has('source_user_id'))
<div class="remote-login-warning">
    <i class="fas fa-exclamation-triangle"></i> Sessão iniciada como {{ Auth::user()->name }}. <a href="{{ route('admin.users.remote-logout',  Session::get('source_user_id')) }}">Voltar à minha sessão</a>
</div>
@endif

@if(Setting::get('maintenance_mode'))
    <div class="remote-login-warning">
        <i class="fas fa-exclamation-triangle"></i> Sistema em modo de Manutenção.</a>
    </div>
@endif
-->
<header class="main-header">
    <a href="" class="logo">
        <span class="logo-mini">
            <img src="{{ asset(config('app.logo_square')) }}"/>
        </span>
        <span class="logo-lg">
            <img src="{{ asset(config('app.logo_xs')) }}"/>
        </span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
       
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                {{-- USER ACCOUNT --}}
                <li><a class="align-middle" href="{{route('customer.cart.index')}}" style="color:white"><i class="fas fa-shopping-cart"></i><span class="align-bottom">€{{number_format(CartProvider::instance()->subtotal,2,',','.')}}</span></a></li>
                <li class="dropdown user-menu">
                    
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                         
                        @if(Auth::check())
                        <span>{{Auth::user()->name}}</span>
                        @endif
                    </span> <i class="caret"></i>
                    
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <ul class="options-menu">
                                <li>
                                    <a href="#" data-toggle="modal" data-target="#account-password">
                                        <i class="fas fa-fw fa-lock"></i> Alterar Palavra-passe
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.logout') }}">
                                        <i class="fas fa-fw fa-power-off"></i> Terminar Sessão
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="hidden-xs">
                    <a href="{{ route('admin.logout') }}"  data-toggle="tooltip" title="Terminar Sessão" data-placement="bottom">
                        <i class="fas fa-power-off fa-lg"></i>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>>