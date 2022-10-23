        <nav class="main-header navbar navbar-expand navbar-dark">

            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <img src="{{ asset('client/dist/img/asean-favicon.png') }}" alt="Asean Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light" style="color: #8bc34a;">Asean</span>
            </a>

            <ul class="navbar-nav ml-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="javascript::void(0)" data-toggle="modal" data-target="#modal-login">
                            {{ GoogleTranslate::trans('Login', app()->getLocale()) }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript::void(0)" data-toggle="modal" data-target="#modal-register">
                            {{ GoogleTranslate::trans('Register', app()->getLocale()) }}
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <div class="user-panel pt-1 pb-1 d-flex" data-toggle="dropdown">
                            <div class="info">
                                <a href="javascript::void(0)" class="d-block">{{ GoogleTranslate::trans('Hi!', app()->getLocale()) }} {{ Auth::user()->name }}</a>
                            </div>
                        </div>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                <i class="fa fa-user mr-2"></i> {{ GoogleTranslate::trans('Dashboard', app()->getLocale()) }}
                            </a>
                            
                            <div class="dropdown-divider"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                   this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> {{ GoogleTranslate::trans('Log Out', app()->getLocale()) }}
                                </a>
                            </form>
                            <div class="dropdown-divider"></div>
                        </div>
                    </li>
                @endguest
            </ul>

            <div class="drop-down"> 
                <select class="changeLang">
                    <option class="en" value="en" style="background-image:url({{ asset('client/dist/flag-ret/gb.svg') }});" {{ session()->get('locale') == 'en' ? 'selected' : '' }}>{{ GoogleTranslate::trans('English', app()->getLocale()) }}</option>
                    <option class="bn" value="bn" style="background-image:url({{ asset('client/dist/flag-ret/bn.svg') }});" {{ session()->get('locale') == 'bn' ? 'selected' : '' }}>{{ GoogleTranslate::trans('Burnei', app()->getLocale()) }}</option>
                    <option class="km" value="km" style="background-image:url({{ asset('client/dist/flag-ret/kh.svg') }});" {{ session()->get('locale') == 'km' ? 'selected' : '' }}>{{ GoogleTranslate::trans('Combodia', app()->getLocale()) }}</option>
                    <option class="id" value="id" style="background-image:url({{ asset('client/dist/flag-ret/id.svg') }});" {{ session()->get('locale') == 'id' ? 'selected' : '' }}>{{ GoogleTranslate::trans('Indonesia', app()->getLocale()) }}</option>
                    <option class="lo" value="lo" style="background-image:url({{ asset('client/dist/flag-ret/la.svg') }});" {{ session()->get('locale') == 'lo' ? 'selected' : '' }}>{{ GoogleTranslate::trans('Laos', app()->getLocale()) }}</option>
                    <option class="ms-MY" value="ms-MY" style="background-image:url({{ asset('client/dist/flag-ret/my.svg') }});" {{ session()->get('locale') == 'ms-MY' ? 'selected' : '' }}>{{ GoogleTranslate::trans('Malaysia', app()->getLocale()) }}</option>
                    <option class="my-MM" value="my-MM" style="background-image:url({{ asset('client/dist/flag-ret/mm.svg') }});" {{ session()->get('locale') == 'my-MM' ? 'selected' : '' }}>{{ GoogleTranslate::trans('Myanmar', app()->getLocale()) }}</option>
                    <option class="tl-PH" value="tl-PH" style="background-image:url({{ asset('client/dist/flag-ret/ph.svg') }});" {{ session()->get('locale') == 'tl-PH' ? 'selected' : '' }}>{{ GoogleTranslate::trans('Philippines', app()->getLocale()) }}</option>
                    <option class="zh-SG" value="zh-SG" style="background-image:url({{ asset('client/dist/flag-ret/sg.svg') }});" {{ session()->get('locale') == 'zh-SG' ? 'selected' : '' }}>{{ GoogleTranslate::trans('Singapore', app()->getLocale()) }}</option>
                    <option class="th" value="th" style="background-image:url({{ asset('client/dist/flag-ret/th.svg') }});" {{ session()->get('locale') == 'th' ? 'selected' : '' }}>{{ GoogleTranslate::trans('Thailand', app()->getLocale()) }}</option>
                    <option class="vi" value="vi" style="background-image:url({{ asset('client/dist/flag-ret/vn.svg') }});" {{ session()->get('locale') == 'vi' ? 'selected' : '' }}>{{ GoogleTranslate::trans('Vietnam', app()->getLocale()) }}</option>
                </select>
            </div>
        </nav>

        @livewire('login-component')
