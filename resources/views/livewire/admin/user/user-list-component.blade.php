@section('title', 'User List')

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
                                            <option value="email">E-mail</option>
                                            <option value="created_at">Date Registered</option>
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
                <div class="col-4 col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $btnType }} User</h3>
                        </div>
                        <!-- ./card-header -->
                        <form wire:submit.prevent="storeUser">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Full Name" wire:model='name'>
                                    @error('type')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">E-mail Address</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter E-mail Address" wire:model='email'>
                                    @error('email')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <blockquote class="blockquote">
                                    <p class="mb-0"><span class="text-red-400">Note*</span>: Password will be sent to user via mailing address.</p>
                                </blockquote>
                            </div>
                            <!-- /.card-body -->
                        
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-sm">{{ $btnType }} & Send Email</button>
                                <div class="btn btn-sm btn-danger pull-right" data-toggle="tooltip" data-placement="top" title="Reset Form Fields" wire:click="resetFields()"><i class="fas fa-redo-alt"></i> Reset Fields</div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-8 col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All User List</h3>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>UID</th>
                                        <th>Profile</th>
                                        <th>FullName</th>
                                        <th>Email</th>
                                        <th>Verified</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <div class="img-holder">
                                                <img src="hello" 
                                                style="object-fit: cover; border-radius: 3px; max-width: 50px;" 
                                                onerror="this.src='{{ asset('admin/dist/img/404/default-image.png')}}';" 
                                                alt="user-image">
                                            </div>
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge {{ ($user->verified) ? 'bg-success':'bg-danger' }}">{{ ($user->verified) ? 'True':'false' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ ($user->status) ? 'bg-success':'bg-danger' }}">{{ ($user->status) ? 'Active':'Deactive' }}</span>
                                        </td>
                                        <td>
                                            @if($user->deleted_at)
                                            <a href="#" class="btn btn-xs bg-success" wire:click="restore({{$user->id}})" data-toggle="tooltip" data-placement="top" title="Restore">
                                                <i class="fas fa-trash-restore"></i>
                                            </a>
                                            @else
                                            <a href="javascript:void(0)" class="btn btn-xs bg-warning" wire:click="editForm({{$user->id}})"  data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-xs bg-success" wire:click="changeStatus({{$user->id}})"  data-toggle="tooltip" data-placement="top" title="Activate">
                                                <i class="fas fa-unlock"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-xs bg-danger" wire:click="changeStatus({{$user->id}})"  data-toggle="tooltip" data-placement="top" title="Dectivate">
                                                <i class="fas fa-lock"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-xs bg-info" data-toggle="tooltip" data-placement="top" title="Roles & Permission">
                                                <i class="fas fa-user-shield"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-xs bg-danger" wire:click="softDelete({{$user->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            {{$users->links('admin.render.admin-pagination-links')}}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            @livewire('admin.business.import-component')
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
