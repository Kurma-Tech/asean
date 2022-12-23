@section('title', 'Cities Trashed List')

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
                                            <option value="name">Name</option>
                                            <option value="code">Code</option>
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
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="250">250</option>
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
                            <h3 class="card-title">All Trashed Cities List</h3>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>RelatedTo</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($citys->where('deleted_at', '!=', Null) as $city)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $city->id }}</strong></td>
                                        <td><span class="badge badge-primary">{{ $city->name ?? 'NULL' }}</span></td>
                                        <td><span class="badge badge-primary">{{ $city->code ?? 'NULL' }}</span></td>
                                        <td><p class="paragraph-row">{{ $city->districts->provinces->regions->country->name ?? 'NULL' }} | {{ $city->districts->provinces->regions->name ?? 'NULL' }} | {{ $city->districts->provinces->name ?? 'NULL' }} | {{ $city->districts->name ?? 'NULL' }}</p></td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="confirm('Do you want to restore?') || event.stopImmediatePropagation()" class="btn btn-xs bg-success" wire:click="restore({{$city->id}})" data-toggle="tooltip" data-placement="top" title="Restore">
                                                <i class="fas fa-trash-restore"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirm('Do you want to permantely remove?') || event.stopImmediatePropagation()"
                                                class="btn btn-xs bg-danger"
                                                wire:click="delete({{ $city->id }})"
                                                data-toggle="tooltip" data-placement="top" title="Permanently Remove">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            {{$cities->links('admin.render.admin-pagination-links')}}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>