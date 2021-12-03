
<header class="main-header" style="background-color: #0B3354 !important">
    <a href="{{route('home.index')}}" class="logo">
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
                <li><a class="align-middle" href="{{route('customer.cart.index')}}" style="color:white"><i class="fas fa-shopping-cart"></i><span id="subtotal" class="align-bottom"> €{{number_format(CartProvider::instance()->total,2,',','.')}}</span></a></li>
                <li class="dropdown user-menu">
                    @if(!Auth::guard('customer')->check())
                        <a href="{{route('customer.login')}}" style="color:white"><i class="fas fa-user"></i> Iniciar sessão</a>
                    @elseif(Auth::guard('customer')->check())
                    

                    <a href="#" class="dropdown-toggle dropdown-color"  data-toggle="dropdown">
                        
                        <span style="color:white"><i class="fas fa-user"></i> {{Auth::guard('customer')->user()->name}}</span>

                    </a>
                   
                    <ul class="dropdown-menu">
                        <li>
                            <ul class="options-menu">
                                <li>
                                    <a href="{{route('customer.about')}}">
                                        <i class="fas fa-user"></i> My Enovo Eats
                                    </a>
                                </li>
                               <!-- <li>
                                    <a href="#" data-toggle="modal" data-target="#account-password">
                                        <i class="fas fa-fw fa-lock"></i> Alterar Palavra-passe
                                    </a>
                                </li>-->
                                <li>
                                    <a href="{{ route('customer.logout') }}">
                                        <i class="fas fa-fw fa-power-off"></i> Terminar Sessão
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    
                </li>
                <li class="hidden-xs">
                    <a href="{{ route('customer.logout') }}"  data-toggle="tooltip" title="Terminar Sessão" data-placement="bottom" style="color:white">
                        <i class="fas fa-power-off fa-lg"></i>
                    </a>
                </li>@endif
            </ul>
        </div>
    </nav>
</header>