@section('title', 'Classification List')

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
                                            <option value="psic_code">PSIC Code</option>
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
                            <h3 class="card-title">{{ $btnType }} Classification</h3>
                            <button type="button" class="btn btn-xs btn-info pull-right" data-toggle="modal" data-target="#modal-default">
                                <i class="fa fa-file-import"></i> Import CSV
                            </button>
                        </div>
                        <!-- ./card-header -->
                        <form wire:submit.prevent="storeClassification">
                            <div class="card-body">
                                <input type="hidden" wire:model="hiddenId">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="classifications">Classification Title*</label>
                                            <input type="text" class="form-control" id="classifications" placeholder="Enter Classification Title" wire:model='classifications'>
                                            @error('classifications')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    @if(!$is_parent)
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="parent_id">Parent Classification*</label>
                                                <select class="form-control select2 select2bs4" id="parent_id" wire:model="parent_id">
                                                    <option hidden>Choose Parent</option>
                                                    @foreach($parentClassifications as $parent)
                                                    <option value="{{ $parent->id }}">{{ $parent->classifications }}</option>
                                                    @endforeach
                                                </select>
                                                @error('parent_id')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="psic_code">PSIC Code*</label>
                                                <input type="text" class="form-control" id="psic_code" placeholder="Enter PSIC Code" wire:model='psic_code'>
                                                @error('psic_code')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                    @endif

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch3" value="1" wire:model="is_parent">
                                                <label class="custom-control-label" for="customSwitch3">Is Parent Classification</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        
                            <div class="card-footer">
                                <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> {{ $btnType }}</button>
                                <div class="btn btn-sm btn-danger pull-right" data-toggle="tooltip" data-placement="top" title="Reset Form Fields" wire:click="resetFields()"><i class="fas fa-redo-alt"></i> Reset Fields</div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-8 col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Classification List</h3>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body" style="overflow-x:scroll;">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width:5%;">#</th>
                                        <th style="width:50%;">Classification</th>
                                        <th style="width:40%;">Parent Classification</th>
                                        <th style="width:5%;">Assign</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($industryClassificationsList as $industryClassification)
                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $industryClassification->classifications }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $industryClassification->parent->classifications ?? 'Self' }}</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#modal-assign-manpower" data-toggle="tooltip" data-placement="top" title="Assign Manpower">
                                                <i class="fa fa-plus"></i> <i class="fa fa-user"></i>
                                            </button>
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
                                                        @if($industryClassification->deleted_at)
                                                        <a href="#" class="btn btn-xs bg-success" wire:click="restore({{$industryClassification->id}})" data-toggle="tooltip" data-placement="top" title="Restore">
                                                            <i class="fas fa-trash-restore"></i>
                                                        </a>
                                                        @else
                                                        <a href="javascript:void(0)" class="btn btn-xs bg-warning" wire:click="editForm({{$industryClassification->id}})"  data-toggle="tooltip" data-placement="top" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-xs bg-danger" wire:click="softDelete({{$industryClassification->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                            <i class="far fa-trash-alt"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            PSIC Code
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{ $industryClassification->psic_code ?? 'N/A'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Number of (PROFESSIONAL) Manpower
                                                        </div>
                                                        <span class="badge badge-primary badge-md">Software Engineer (50 PROFESSIONAL)</span>
                                                        <span class="badge badge-primary badge-md">Management Staff (10 TRADESMAN)</span>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Number of (TRADESMAN) Manpower
                                                        </div>
                                                        <span class="badge badge-info badge-md">Cook (50 PROFESSIONAL)</span>
                                                        <span class="badge badge-info badge-md">Cleaner (10 TRADESMAN)</span>
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
                            {{$industryClassificationsList->links('admin.render.admin-pagination-links')}}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->

            @livewire('admin.classification.import-component')
            @livewire('admin.classification.assign-manpower-component')
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
