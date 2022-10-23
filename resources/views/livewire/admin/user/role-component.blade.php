@section('title', 'Role List')

<div>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-4 col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $btnType }} Role</h3>
                            <button type="button" class="btn btn-xs btn-info pull-right" data-toggle="modal" data-target="#modal-default">
                                <i class="fa fa-file-import"></i> Import CSV
                            </button>
                        </div>
                        <!-- ./card-header -->
                        <form wire:submit.prevent="storeRole">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name*</label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter Role Name" wire:model='name'>
                                    @error('name')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="permission-dropdown">Assing Permission</label>
                                    <div class="select2-purple" wire:ignore>
                                        <select class="form-control select2" multiple="multiple" data-placeholder="Choose Permissions" data-dropdown-css-class="select2-purple" id="permission-dropdown" wire:model="permissions">
                                            @foreach($permissionList as $list)
                                            <option value="{{ $list->id }}">{{ $list->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('permissions')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->
                        
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-sm">{{ $btnType }} Role</button>
                                <div class="btn btn-sm btn-danger pull-right" data-toggle="tooltip" data-placement="top" title="Reset Form Fields" wire:click="resetFields()"><i class="fas fa-redo-alt"></i> Reset Fields</div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-8 col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Role List</h3>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Guard</th>
                                        <th>Permissions</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->guard_name }}</td>
                                        <td>
                                            @foreach($role->permissions as $rolePermission)
                                                <span class="badge badge-primary">{{ $rolePermission->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $role->created_at->diffForHumans() }}</td>
                                        <td>
                                            @if($role->deleted_at)
                                            <a href="#" class="btn btn-xs bg-success" wire:click="restore({{$role->id}})" data-toggle="tooltip" data-placement="top" title="Restore">
                                                <i class="fas fa-trash-restore"></i>
                                            </a>
                                            @else
                                            <a href="javascript:void(0)" class="btn btn-xs bg-warning" wire:click="editForm({{$role->id}})"  data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="btn btn-xs bg-danger" wire:click="softDelete({{$role->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
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
                            {{$roles->links('admin.render.admin-pagination-links')}}
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

@push('extra-styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
@endpush

@push('extra-scripts')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#permission-dropdown').select2();
            $('#permission-dropdown').on('change', function (e) {
                let data = $(this).val();
                    @this.set('permissions', data);
            });
        });
        window.loadSelect2 = () => {
            $('.select2').select2().on('change',function () {
                livewire.emitTo('permissions','selectedItem',$(this).val());

                // or

                // @this.set('propertyName',$(this).val());
            });
        }
    </script>
@endpush
