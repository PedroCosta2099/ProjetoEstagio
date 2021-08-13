
<header class="main-header">
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
                <!--BELL-->
                <!--
            <div class="dropdown" style="float: right; padding: 13px">
    <a href="#" onclick="return false;" role="button" data-toggle="dropdown" id="dropdownMenu1" data-target="#" style="float: left" aria-expanded="true">
        <i class="fas fa-bell" style="font-size: 20px; float: left; color: black">
        </i>
    </a>
    <span class="badge badge-danger">6</span>
    <ul class="dropdown-menu dropdown-menu-left pull-right" role="menu" aria-labelledby="dropdownMenu1">
        <li role="presentation">
            <a href="#" class="dropdown-menu-header">Notifications</a>
        </li>
        <ul class="timeline timeline-icons timeline-sm" style="margin:10px;width:210px">
                                        <li>
                                            <p>
                                                Your “Volume Trendline” PDF is ready <a href="">here</a>
                                                <span class="timeline-icon"><i class="fa fa-file-pdf-o" style="color:red"></i></span>
                                                <span class="timeline-date">Dec 10, 22:00</span>
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                Your “Marketplace Report” PDF is ready <a href="">here</a>
                                                <span class="timeline-icon"><i class="fa fa-file-pdf-o"  style="color:red"></i></span>
                                                <span class="timeline-date">Dec 6, 10:17</span>
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                Your “Top Words” spreadsheet is ready <a href="">here</a>
                                                <span class="timeline-icon"><i class="fa fa-file-excel-o"  style="color:green"></i></span>
                                                <span class="timeline-date">Dec 5, 04:36</span>
                                            </p>
                                        </li>
                                    </ul>
        <li role="presentation">
            <a href="#" class="dropdown-menu-header"></a>
        </li>
    </ul>
</div>-->
<!---->
                {{-- USER ACCOUNT --}}
                <li><a class="align-middle" href="{{route('customer.cart.index')}}" style="color:white"><i class="fas fa-shopping-cart"></i><span id="subtotal" class="align-bottom"> €{{number_format(CartProvider::instance()->total,2,',','.')}}</span></a></li>
                <li class="dropdown user-menu">
                    @if(!Auth::guard('customer')->check())
                        <a href="{{route('customer.login')}}" style="color:white"><i class="fas fa-user"></i> Iniciar sessão</a>
                    @elseif(Auth::guard('customer')->check())
                    

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        
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