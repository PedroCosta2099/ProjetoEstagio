@if(Session::has('source_user_id'))
<div class="remote-login-warning">
    <i class="fas fa-exclamation-triangle"></i> Sessão iniciada como {{ Auth::user()->name }}. <a href="{{ route('admin.users.remote-logout',  Session::get('source_user_id')) }}">Voltar à minha sessão</a>
</div>
@endif

@if(Setting::get('maintenance_mode'))
    <div class="remote-login-warning">
        <i class="fas fa-exclamation-triangle"></i> Sistema em modo de Manutenção.</a>
    </div>
@endif

<header class="main-header">
    <a href="{{ route('admin.dashboard')}}" class="logo">
        <span class="logo-mini">
            <img src="{{ asset(config('app.logo_square')) }}"/>
        </span>
        <span class="logo-lg">
            <img src="{{ asset(config('app.logo_xs')) }}"/>
        </span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">


                {{-- SETTINGS --}}
                @if(Auth::user()->isAdmin())
                    <li class="dropdown user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span><i class="fas fa-wrench"></i></span> <i class="caret"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header bold">Ferramentas de Administrador</li>
                            <li>
                                <ul class="options-menu dinamic-height">
                                    <li>
                                        <a href="{{ route('admin.settings.index', ['tab' => 'maintenance']) }}" target="_blank" class="text-blue">
                                            <i class="fas fa-fw fa-wrench text-blue"></i> Definições Gerais
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.roles.index') }}" class="text-info">
                                            <i class="fas fa-fw fa-users"></i> Perfis e Permissões
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- USER ACCOUNT --}}
                <li class="dropdown user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ Auth::user()->filepath ? asset(Auth::user()->filepath) : '' }}" class="user-image" alt="{{ Auth::user()->name }}" onerror="this.src='{{ asset('assets/img/default/avatar.png') }}';">
                        <span class="hidden-xs">{{ Auth::user()->name }}</span> <i class="caret"></i>
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
</header>