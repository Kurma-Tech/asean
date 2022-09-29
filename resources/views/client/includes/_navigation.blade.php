        <nav class="main-header navbar navbar-expand navbar-dark">
            
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <img src="{{asset('client/dist/img/asean-favicon.png')}}" alt="Asean Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Aseana</span>
            </a>
            
            <!-- Left navbar links -->
            {{-- <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul> --}}

            <ul class="navbar-nav ml-auto">
                @guest
                <li class="nav-item">
                    <a class="nav-link" href="javascript::void(0)" data-toggle="modal" data-target="#modal-login">
                        Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript::void(0)" data-toggle="modal" data-target="#modal-register">
                        Register
                    </a>
                </li>
                @else
                <li class="nav-item dropdown">
                    <div class="user-panel pt-1 pb-1 d-flex" data-toggle="dropdown">
                        <div class="info">
                            <a href="javascript::void(0)" class="d-block">Hi! {{Auth::user()->name;}}</a>
                        </div>
                    </div>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                            <i class="fa fa-user mr-2"></i> Dashboard
                        </a>
                        @else
                        <a href="{{ route('client.dashboard') }}" class="dropdown-item">
                            <i class="fa fa-user mr-2"></i> Dashboard
                        </a>
                        @endif
                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item" href="{{ route('logout') }}"
                              onclick="event.preventDefault();
                              this.closest('form').submit();">
                              <i class="fas fa-user"></i> {{ __('Log Out') }}
                            </a>
                        </form>
                        <div class="dropdown-divider"></div>
                    </div>
                </li>
                @endguest
            </ul>
        </nav>

        @livewire('login-component')