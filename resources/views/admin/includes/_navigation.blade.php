        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('client.home') }}" class="nav-link">Client Site</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li> --}}
                <li class="nav-item dropdown">
                    <div class="user-panel pt-1 pb-1 d-flex" data-toggle="dropdown">
                        <div class="info">
                            <a href="javascript::void(0)" class="d-block">Hi! {{Auth::user()->name;}}</a>
                        </div>
                    </div>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
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
            </ul>
        </nav>