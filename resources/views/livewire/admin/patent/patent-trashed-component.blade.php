@section('title', 'Intellectual Property List')

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
                                        <label>Sort Order:</label>
                                        <select class="form-control" style="width: 100%;" wire:model="sortBy">
                                            <option hidden>Choose Sort By</option>
                                            <option value="1">ASC</option>
                                            <option value="0">DESC</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <div class="form-group">
                                        <label>Order By:</label>
                                        <select class="form-control" style="width: 100%;" wire:model="orderBy">
                                            <option hidden>Choose Order By</option>
                                            <option value="id">By ID</option>
                                            <option value="title">Title</option>
                                            <option value="filing_no
                                            ">Filing Number</option>
                                            <option value="registration_date
                                            ">Registration Date</option>
                                            <option value="publication_date
                                            ">Publication Date</option>
                                            <option value="filing_date
                                            ">Filing Date</option>
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
                <div class="col-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Trashed Intellectual Property List</h3>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>FilingNo</th>
                                        <th>Title</th>
                                        <th>RegistrationNo.</th>
                                        <th>I.P.Type</th>
                                        <th>I.P.Kind</th>
                                        <th>PublicationDate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patents->where('deleted_at', '!=', Null) as $patent)
                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $patent->filing_no }}</td>
                                        <td>{{ $patent->title }}</td>
                                        <td>{{ $patent->registration_no }}</td>
                                        <td><span class="badge badge-info">{{ $patent->patentType->type ?? 'N/A' }}</span></td>
                                        <td><span class="badge badge-primary">{{ $patent->patentKind->kind ?? 'N/A' }}</span></td>
                                        <td>{{ $patent->publication_date }}</td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="confirm('Do you want to restore?') || event.stopImmediatePropagation()" class="btn btn-xs bg-success" wire:click="restore({{$patent->id}})" data-toggle="tooltip" data-placement="top" title="Restore">
                                                <i class="fas fa-trash-restore"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirm('Do you want to permantely remove?') || event.stopImmediatePropagation()"
                                                class="btn btn-xs bg-danger"
                                                wire:click="delete({{ $patent->id }})"
                                                data-toggle="tooltip" data-placement="top" title="Permanently Remove">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
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
                                                        <a href="javascript:void(0)" onclick="confirm('Do you want to restore?') || event.stopImmediatePropagation()"
                                                            class="btn btn-xs bg-success"
                                                            wire:click="restore({{ $patent->id }})"
                                                            data-toggle="tooltip" data-placement="top" title="Restore">
                                                            <i class="fas fa-trash-restore"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" onclick="confirm('Do you want to permantely remove?') || event.stopImmediatePropagation()"
                                                            class="btn btn-xs bg-danger"
                                                            wire:click="delete({{ $patent->id }})"
                                                            data-toggle="tooltip" data-placement="top" title="Permanently Remove">
                                                            <i class="far fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Registration Date
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{ $patent->registration_date  ?? 'N/A'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Filing Date
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{ $patent->filing_date  ?? 'N/A'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Registration Number
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{ $patent->registration_no  ?? 'N/A'}}</a>
                                                    </div>
                                                </li>
                                                @if(!is_null($patent->patentCategories))
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Category Name
                                                        </div>
                                                        <p class="product-title">
                                                            @foreach ($patent->patentCategories as $pCategories)
                                                                <span class="badge badge-secondary">{{ $pCategories->category }}</span>
                                                            @endforeach
                                                        </p>
                                                    </div>
                                                </li>
                                                @endisset
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Inventor Name
                                                        </div>
                                                        @if($patent->inventor_name)
                                                            @php $inventor_name = json_decode($patent->inventor_name) @endphp 
                                                            @foreach ($inventor_name as $key)
                                                            <span class="badge badge-secondary">{{$key}}</span>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Applicant Company
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{$patent->applicant_company ?? 'N/A'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Country Name
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{$patent->country->name ?? 'N/A'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Geo Location
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{$patent->long ?? 'N/A'}} (long), {{$patent->lat ?? 'N/A'}} (Lat)</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Abstract
                                                        </div>
                                                        <p class="product-title">{!! $patent->abstract ?? 'N/A' !!}</p>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Action
                                                        </div>
                                                        <a href="javascript:void(0)" onclick="confirm('Do you want to restore?') || event.stopImmediatePropagation()"
                                                            class="btn btn-xs bg-success"
                                                            wire:click="restore({{ $patent->id }})"
                                                            data-toggle="tooltip" data-placement="top" title="Restore">
                                                            <i class="fas fa-trash-restore"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" onclick="confirm('Do you want to permantely remove?') || event.stopImmediatePropagation()"
                                                            class="btn btn-xs bg-danger"
                                                            wire:click="delete({{ $patent->id }})"
                                                            data-toggle="tooltip" data-placement="top" title="Permanently Remove">
                                                            <i class="far fa-trash-alt"></i>
                                                        </a>
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
                            {{$patents->links('admin.render.admin-pagination-links')}}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>