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
                                                <label for="code">Code*</label>
                                                <input type="text" class="form-control" id="code" placeholder="Enter Code" wire:model='code'>
                                                @error('code')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-12 m-2">

                                        <hr class="mb-2">

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
                                        <th style="width: 2.5%;">#</th>
                                        <th style="width: 2.5%;">ID</th>
                                        <th>Classification</th>
                                        <th style="width: 10%;">Code</th>
                                        <th>Parent Classification</th>
                                        <th style="width: 15%;">Type</th>
                                        <th style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($industryClassificationsList as $industryClassification)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $industryClassification->id }}</td>
                                        <td>{{ $industryClassification->classifications }}</td>
                                        <td>{{ $industryClassification->code ?? 'N/A'}}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $industryClassification->parent->classifications ?? 'Self' }}</span>
                                        </td>
                                        <td>
                                            @if($industryClassification->parent_id)
                                            <span class="badge badge-success badge-sm">Child Category</span>
                                            @else
                                            <span class="badge badge-info badge-sm">Parent Category</span>
                                            @endif
                                            @if($industryClassification->deleted_at)
                                            <span class="badge badge-danger badge-sm">Trashed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-xs bg-warning" wire:click="editForm({{$industryClassification->id}})"  data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirm('Are you sure? Do you want to delete?') || event.stopImmediatePropagation()"
                                                class="btn btn-xs bg-danger" wire:click="softDelete({{$industryClassification->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
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
                            {{$industryClassificationsList->links('admin.render.admin-pagination-links')}}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->

            @livewire('admin.classification.import-component')

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>

@push('extra-styles')
    <style>
        .has-error {border: 1px solid #ff7e7e;}
    </style>
@endpush
