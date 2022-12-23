        <aside class="main-sidebar sidebar-dark-success elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <img src="{{asset('admin/dist/img/asean-favicon.png')}}" alt="Asean Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">ASEANA</span>
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
                    <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-header">MAIN</li>
                        <li class="nav-item">
                            <a href="{{route('admin.dashboard')}}" class="nav-link {{ (request()->routeIs('admin.dashboard')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">USER</li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.list') }}" class="nav-link {{ (request()->routeIs('admin.users*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users text-default"></i>
                                <p>
                                    Users
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.roles.list') }}" class="nav-link {{ (request()->routeIs('admin.roles*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-shield text-warning"></i>
                                <p>
                                    Roles
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.permissions.list') }}" class="nav-link {{ (request()->routeIs('admin.permissions*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-lock text-danger"></i>
                                <p>
                                    Permissions
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">ASEAN COUNTRIES</li>
                        <li class="nav-item {{ (request()->routeIs('admin.countries*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.countries*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-globe-asia text-primary"></i>
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
                                <li class="nav-item">
                                    <a href="{{route('admin.countries.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.countries.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.regions*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.regions*')) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-location-arrow text-warning"></i>
                                <p>
                                    Regions
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.regions.list')}}" class="nav-link {{ (request()->routeIs('admin.regions.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.regions.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.regions.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.provinces*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.provinces*')) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-mail-bulk text-secondary"></i>
                                <p>
                                    Provinces
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.provinces.list')}}" class="nav-link {{ (request()->routeIs('admin.provinces.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.provinces.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.provinces.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.districts*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.districts*')) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-mail-bulk text-secondary"></i>
                                <p>
                                    Districts
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.districts.list')}}" class="nav-link {{ (request()->routeIs('admin.districts.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.districts.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.districts.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.cities*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.cities*')) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-mail-bulk text-secondary"></i>
                                <p>
                                    Cities
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.cities.list')}}" class="nav-link {{ (request()->routeIs('admin.cities.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.cities.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.cities.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">DATA</li>
                        <li class="nav-item {{ (request()->routeIs('admin.business*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.business*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-building text-danger"></i>
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
                                <i class="nav-icon fas fa-certificate text-warning"></i>
                                <p>
                                    Intellectual Property
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
                                <li class="nav-item">
                                    <a href="{{route('admin.patent.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.patent.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.journals*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.journals*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-book text-info"></i>
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
                                <li class="nav-item">
                                    <a href="{{route('admin.journals.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.journals.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">BUSINESS GENERAL <span></span></li>
                        <li class="nav-item {{ (request()->routeIs('admin.classification*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.classification*')) ? 'active' : '' }}">
                                <img src="{{ asset('admin/dist/img/icons/businessClassIcon.png') }}" alt="bc-icon" style="width:24px;">
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
                                <li class="nav-item">
                                    <a href="{{route('admin.classification.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.classification.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.typeBusiness*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.typeBusiness*')) ? 'active' : '' }}">
                                <img src="{{ asset('admin/dist/img/icons/businessTypeIcon.png') }}" alt="bc-icon" style="width:24px;">
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
                                <li class="nav-item">
                                    <a href="{{route('admin.typeBusiness.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.typeBusiness.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.groupBusiness*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.groupBusiness*')) ? 'active' : '' }}">
                                <img src="{{ asset('admin/dist/img/icons/businessTypeIcon.png') }}" alt="bc-icon" style="width:24px;">
                                <p>
                                    Business Group
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.groupBusiness.list')}}" class="nav-link {{ (request()->routeIs('admin.groupBusiness.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.groupBusiness.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.groupBusiness.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">INTELLECTUAL PROPERTY GENERAL</li>
                        <li class="nav-item {{ (request()->routeIs('admin.categoryPatent*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.categoryPatent*')) ? 'active' : '' }}">
                                <img src="{{ asset('admin/dist/img/icons/patentCatIcon.png') }}" alt="bc-icon" style="width:24px;">
                                <p>
                                    Patent Category
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route('admin.categoryPatent.list')}}" class="nav-link {{ (request()->routeIs('admin.categoryPatent.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-info"></i>
                                        <p>View All</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.categoryPatent.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.categoryPatent.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.typePatent*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.typePatent*')) ? 'active' : '' }}">
                                <img src="{{ asset('admin/dist/img/icons/patentTypeIcon.png') }}" alt="bc-icon" style="width:24px;">
                                <p>
                                    I.P. Types
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
                                <li class="nav-item">
                                    <a href="{{route('admin.typePatent.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.typePatent.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ (request()->routeIs('admin.kindPatent*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.kindPatent*')) ? 'active' : '' }}">
                                <img src="{{ asset('admin/dist/img/icons/patentKindIcon.png') }}" alt="bc-icon" style="width:24px;">
                                <p>
                                    I.P. Kinds
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
                                <li class="nav-item">
                                    <a href="{{route('admin.kindPatent.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.kindPatent.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">JOURNALS GENERAL</li>
                        <li class="nav-item {{ (request()->routeIs('admin.journalCategory*')) ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ (request()->routeIs('admin.journalCategory*')) ? 'active' : '' }}">
                                <img src="{{ asset('admin/dist/img/icons/journalCatIcon.png') }}" alt="bc-icon" style="width:24px;">
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
                                <li class="nav-item">
                                    <a href="{{route('admin.journalCategory.trashed.list')}}" class="nav-link {{ (request()->routeIs('admin.journalCategory.trashed.list')) ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle text-danger"></i>
                                        <p>View Trashed</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-header">SETTINGS</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cogs text-warning"></i>
                                <p>
                                    Site Settings
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>