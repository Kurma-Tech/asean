@section('title', 'Countries List')

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
                                            <option value="short_code">Short Code</option>
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
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $btnType }} Country</h3>
                        </div>
                        <!-- ./card-header -->
                        <form wire:submit.prevent="storeCountry">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Country Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Country Name" wire:model='name'>
                                    @error('name')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="short_code">Short Code<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="short_code" placeholder="Ex. IND for Indonesia"
                                        wire:model='short_code'>
                                    @error('short_code')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="c_code">Country Code</label>
                                    <input type="text" class="form-control" id="c_code" placeholder="Ex. +62 for Indonesia" wire:model='c_code'>
                                    @error('c_code')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group" wire:ignore>
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch3" value="1" wire:model="status">
                                        <label class="custom-control-label" for="customSwitch3">Active / De-active country</label>
                                        @error('status')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <blockquote class="blockquote">
                                    <p class="mb-0"><span class="text-red-400">Note*</span>: Fields with <span class="text-danger">*</span> sign are mendatory.</p>
                                </blockquote>
                            </div>
                            <!-- /.card-body -->
                        
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-sm">{{ $btnType }} Country</button>
                                <div class="btn btn-sm btn-danger pull-right" data-toggle="tooltip" data-placement="top" title="Reset Form Fields" wire:click="resetFields()"><i class="fas fa-redo-alt"></i> Reset Fields</div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-8 col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Countries List</h3>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Country Code</th>
                                        <th>Short Code</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($countries as $country)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $country->id }}</td>
                                        <td>{{ $country->name ?? 'NULL' }}</td>
                                        <td>{{ $country->c_code ?? 'NULL' }}</td>
                                        <td>
                                            {{ $country->short_code ?? 'NULL' }}
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-xs bg-warning" wire:click="editForm({{$country->id}})"  data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirm('Are you sure? Do you want to delete?') || event.stopImmediatePropagation()" class="btn btn-xs bg-danger" wire:click="softDelete({{$country->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
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
                            {{$countries->links('admin.render.admin-pagination-links')}}
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