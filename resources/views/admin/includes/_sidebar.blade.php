        <aside class="main-sidebar sidebar-dark-success elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <img src="{{asset('admin/dist/img/asean-favicon.png')}}" alt="Asean Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">ASEAN</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- SidebarSearch Form -->
                <div class="form-inline mt-2">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-header">MAIN</li>
                        <li class="nav-item">
                            <a href="{{route('admin.dashboard')}}" class="nav-link {{ (request()->routeIs('admin.dashboard')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">ASEAN COUNTRIES</li>
                        <li class="nav-item {{ (request()->routeIs('admin.countries*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.countries*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Countries
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.countries.list')}}" class="nav-link {{ (request()->routeIs('admin.countries.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">DATA</li>
                        <li class="nav-item {{ (request()->routeIs('admin.business*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.business*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Business
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.business.list')}}" class="nav-link {{ (request()->routeIs('admin.business.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.business.add')}}" class="nav-link {{ (request()->routeIs('admin.business.add')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-success"></i>
                                        <p>Add New</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.business.trashed')}}" class="nav-link {{ (request()->routeIs('admin.business.trashed')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>Trashed Data</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.patent*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.patent*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Patent
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.patent.list')}}" class="nav-link {{ (request()->routeIs('admin.patent.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.journals*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.journals*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Journals
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.journals.list')}}" class="nav-link {{ (request()->routeIs('admin.journals.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">GENERAL</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.classification*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Classification
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.classification.list')}}" class="nav-link {{ (request()->routeIs('admin.classification.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.typeBusiness*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.typeBusiness*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Business Type
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.typeBusiness.list')}}" class="nav-link {{ (request()->routeIs('admin.typeBusiness.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.patentCategory*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.patentCategory*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Patent Category
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.patentCategory.list')}}" class="nav-link {{ (request()->routeIs('admin.patentCategory.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.typePatent*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.typePatent*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Patent Types
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.typePatent.list')}}" class="nav-link {{ (request()->routeIs('admin.typePatent.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.kindPatent*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.kindPatent*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Patent Kinds
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.kindPatent.list')}}" class="nav-link {{ (request()->routeIs('admin.kindPatent.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.journalCategory*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.journalCategory*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Journals Category
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.journalCategory.list')}}" class="nav-link {{ (request()->routeIs('admin.journalCategory.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">SETTINGS</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.manpower.list') }}" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Man Power
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Site Settings
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>