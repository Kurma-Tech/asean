@section('title', 'Business List')

<div>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Search:</label>
                                        <div class="input-group input-group-md">
                                            <input type="search" class="form-control form-control-md" placeholder="Type your keywords here" wire:model.debounce.300ms='search'>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Order By:</label>
                                        <select class="form-control" style="width: 100%;" wire:model="orderBy">
                                            <option hidden>Choose Order By</option>
                                            <option value="id">By ID</option>
                                            <option value="company_name">Name</option>
                                            <option value="status">Status</option>
                                            <option value="date_registered">Date Registered</option>
                                            <option value="sec_no">SEC Number</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Sort Order:</label>
                                        <select class="form-control" style="width: 100%;" wire:model="sortBy">
                                            <option hidden>Choose Sort By</option>
                                            <option value="1">ASC</option>
                                            <option value="0">DESC</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>List of:</label>
                                        <select class="form-control" style="width: 100%;" wire:model="perPage">
                                            <option selected>5</option>
                                            <option value="10">10</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="500">500</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Business List</h3>
                            <span class="pull-right">
                                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modal-default">
                                    <i class="fa fa-file-import"></i> Import Business
                                </button>
                                <a href="{{route('admin.business.add')}}" class="btn btn-xs bg-primary"><i class="fa fa-plus"></i> Add Business</a>
                            </span>
                            <div class="clear-fix"></div>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Registered Year</th>
                                        <th>SEC No.</th>
                                        <th>Type</th>
                                        <th>Classification</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($businesses as $business)
                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $business->id }}</td>
                                        <td>{{ $business->company_name ?? 'NULL' }}</td>
                                        <td>{{ $business->year ?? 'NULL' }}</td>
                                        <td>
                                            {{ $business->sec_no ?? 'NULL' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $business->businessType->type ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $business->industryClassification->classifications ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{($business->status == 'REGISTERED') ? 'badge-success':'badge-danger'}}">{{$business->status ?? 'NULL'}}</span>
                                        </td>
                                    </tr>
                                    <tr class="expandable-body d-none">
                                        <td colspan="8">
                                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Action
                                                        </div>
                                                        <a href="#" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>
                                                        <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-alt"></i></a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Date Registered
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{ $business->date_registered ?? 'NULL' }}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Address
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{ $business->address  ?? 'NULL'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            PSIC Code
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{ $business->industryClassification->psic_code ?? 'N/A' }}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            NGC Code
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{$business->ngc_code ?? 'NULL'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Industry Code
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{$business->industry_code ?? 'NULL'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Geographic Code
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{$business->geo_code ?? 'NULL'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Geo Location
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{$business->long ?? 'NULL'}} (long), {{$business->lat ?? 'NULL'}} (Lat)</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Industry Description
                                                        </div>
                                                        <p>
                                                            {{$business->industry_description ?? 'NULL'}}
                                                        </p>
                                                        <div class="product-title">
                                                            Geo Description
                                                        </div>
                                                        <p>
                                                            {{$business->geo_description ?? 'NULL'}}
                                                        </p>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Action
                                                        </div>
                                                        <a href="#" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>
                                                        <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-alt"></i></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            {{$businesses->links('admin.render.admin-pagination-links')}}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->

            @livewire('admin.business.import-component')
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
