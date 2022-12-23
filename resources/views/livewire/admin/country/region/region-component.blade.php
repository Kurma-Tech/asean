@section('title', 'Regions List')

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
                <div class="col-4 col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $btnType }} Region</h3>
                            <button type="button" class="btn btn-xs btn-info pull-right" data-toggle="modal"
                                data-target="#modal-default">
                                <i class="fa fa-file-import"></i> Import CSV
                            </button>
                        </div>
                        <!-- ./card-header -->
                        <form wire:submit.prevent="storeRegion">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="country_name">Country Name<span class="text-danger">*</span></label>
                                    <div wire:ignore>
                                        <select class="form-control select2 select2bs4" id="country_name" wire:model="country_id">
                                            <option hidden>Choose Country</option>
                                            @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('country_id')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Region Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" placeholder="Ex. Region I (PH)"
                                        wire:model='name'>
                                    @error('name')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="code">Region Code</label>
                                    <input type="text" class="form-control" id="code" placeholder="Ex. 10 (2 digits)"
                                        wire:model='code'>
                                    @error('code')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <blockquote class="blockquote">
                                    <p class="mb-0"><span class="text-red-400">Note*</span>: Fields with <span class="text-danger">*</span> sign are mendatory.</p>
                                </blockquote>
                            </div>
                            <!-- /.card-body -->
                        
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-sm">{{ $btnType }} Region</button>
                                <div class="btn btn-sm btn-danger pull-right" data-toggle="tooltip" data-placement="top" title="Reset Form Fields" wire:click="resetFields()"><i class="fas fa-redo-alt"></i> Reset Fields</div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-8 col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Regions List</h3>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Country</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($regions as $region)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $region->id }}</strong></td>
                                        <td><span class="badge badge-info">{{ $region->country->name ?? 'NULL' }}</span></td>
                                        <td><span class="badge badge-secondary">{{ $region->name ?? 'NULL' }}</span></td>
                                        <td><span class="badge badge-warning">{{ $region->code ?? 'NULL' }}</span></td>
                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-xs bg-warning" wire:click="editForm({{$region->id}})"  data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirm('Are you sure? Do you want to delete?') || event.stopImmediatePropagation()" class="btn btn-xs bg-danger" wire:click="softDelete({{$region->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
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
                            {{$regions->links('admin.render.admin-pagination-links')}}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
            @livewire('admin.country.region.import-component')
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>

@push('extra-styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('extra-scripts')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $('#country_name').on('change', function (e) {
                let data = $(this).val();
                    @this.set('country_id', data);
            });
        });
    </script>
@endpush