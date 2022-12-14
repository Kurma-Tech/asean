        <aside class="main-sidebar sidebar-light-primary elevation-4" style="display: none;">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <img src="{{asset('client/dist/img/asean-favicon.png')}}" alt="Asean Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Aseana</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <div class="row">
                    <div class="col-8 col-sm-8">
                        <!-- SidebarSearch Form -->
                        <div class="form-inline my-2">
                            <div class="input-group" data-widget="sidebar-search">
                                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" wire:model.debounce.1000ms='search'>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 col-sm-4">
                        <div class="form-group">
                            <select class="form-control" style="width: 100%;" wire:model="parent_id">
                                <option hidden>Filter Categoiry</option>
                                @foreach($industryClass as $category)
                                <option value="{{ $category->id }}">{{ $category->classifications }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                

                <div class="search-result-text d-flex justify-content-between">
                    <p>Search Result for - All</p>
                    <p> <a class="" href="javascript::void(0);"><i class="far fa-chart-bar"></i> {{ count($filters_all) }} results</a></p>
                </div>

                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item mr-2 mb-2">
                        <a class="btn btn-sm warning active" id="business-tab" data-toggle="pill" href="#business" role="tab" aria-controls="business" aria-selected="true">Business</a>
                    </li>
                    <li class="nav-item mr-2 mb-2">
                        <a class="btn btn-sm info" id="patient-tab" data-toggle="pill" href="#patient" role="tab" aria-controls="patient" aria-selected="false">Patient</a>
                    </li>
                    <li class="nav-item mr-2 mb-2">
                        <a class="btn btn-sm danger" id="journals-tab" data-toggle="pill" href="#journals" role="tab" aria-controls="journals" aria-selected="false">Journals</a>
                    </li>
                </ul>

                <div class="search-results mt-2 tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="business" role="tabpanel" aria-labelledby="business-tab">
                        @foreach($filters as $filter)
                        <div class="card">
                            <div class="card-body py-2">
                                <h4 class="title mb-0">{{ $filter->company_name }}</h4>
                                <p class="mb-0">{{ $filter->address }}</p>
                                <p class="mb-0">{{ $filter->industryClassification->classifications ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @endforeach

                        <div class="card-footer clearfix">
                            {{$filters->links('client.render.client-pagination-links')}}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="patient" role="tabpanel" aria-labelledby="patient-tab">
                        <div class="card">
                            <div class="card-body py-2">
                                <h4 class="title mb-0">Invertor</h4>
                                <p class="mb-0">Location, Address</p>
                                <p class="mb-0">Automobile Industry</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body py-2">
                                <h4 class="title mb-0">Ship 369 AL 20 Model</h4>
                                <p class="mb-0">Location, Address</p>
                                <p class="mb-0">Automobile Industry</p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="journals" role="tabpanel" aria-labelledby="journals-tab">
                        <div class="card">
                            <div class="card-body py-2">
                                <h4 class="title mb-0">Rain Fall</h4>
                                <p class="mb-0">Location, Address</p>
                                <p class="mb-0">Automobile Industry</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
