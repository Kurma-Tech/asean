@section('title', 'Journals Category Trashed List')

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
                                            <option value="category">Category</option>
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
                <div class="col-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Trashed Category List</h3>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body" style="overflow-x:scroll;">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 5%;">ID</th>
                                        <th>Category</th>
                                        <th style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($journalsCategories->where('deleted_at', '!=', Null) as $category)
                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->category }}</td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="confirm('Do you want to restore?') || event.stopImmediatePropagation()"
                                                class="btn btn-xs bg-success" wire:click="restore({{$category->id}})" 
                                                data-toggle="tooltip" data-placement="top" title="Restore">
                                                <i class="fas fa-trash-restore"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirm('Do you want to permantely remove?') || event.stopImmediatePropagation()"
                                                class="btn btn-xs bg-danger"
                                                wire:click="delete({{ $category->id }})"
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
                            {{$journalsCategories->links('admin.render.admin-pagination-links')}}
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

@push('extra-styles')
    <style>
        .has-error {border: 1px solid #ff7e7e;}
    </style>
@endpush