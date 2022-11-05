@section('title', 'Patent Category List')

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
                                            <option value="ipc_code">IPC Code</option>
                                            <option value="title">Title</option>
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
                            <h3 class="card-title">{{ $btnType }} Patent Category</h3>
                            <button type="button" class="btn btn-xs btn-info pull-right" data-toggle="modal" data-target="#modal-default">
                                <i class="fa fa-file-import"></i> Import CSV
                            </button>
                        </div>
                        <!-- ./card-header -->
                        <form wire:submit.prevent="storeCategory">
                            <div class="card-body">
                                <input type="hidden" wire:model="hiddenId">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="classification_category">Category Title<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="classification_category" placeholder="Enter Category Title" wire:model='classification_category'>
                                            @error('classification_category')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="ipc_code">IPC Code<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="ipc_code" placeholder="Enter IPC Code" wire:model='ipc_code'>
                                            @error('ipc_code')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    @if(!$is_parent)
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="category-dropdown-section">Section Category<span class="text-danger">*</span></label>
                                                <select class="form-control" id="category-dropdown-section" wire:model="selectedSection">
                                                    <option value="" {{ $selectedSection == Null ?? 'selected' }}>Select Section Category</option>
                                                    @foreach($sections as $sCategory)
                                                    <option value="{{ $sCategory->id }}">{{ $sCategory->classification_category }}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedSection')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @if(!is_null($selectedSection))
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="category-dropdown-division">Division Category</label>
                                                <select class="form-control" id="category-dropdown-division" wire:model="selectedDivision">
                                                    <option value="" {{ $selectedSection == Null ?? 'selected' }}>Select Division Category</option>
                                                    @foreach($divisions as $dCategory)
                                                    <option value="{{ $dCategory->id }}">{{ $dCategory->classification_category }}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedDivision')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endif

                                        @if(!is_null($selectedDivision))
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="category-dropdown-group">Group Category</label>
                                                <select class="form-control" id="category-dropdown-group" wire:model="selectedGroup">
                                                    <option value="" {{ $selectedSection == Null ?? 'selected' }}>Select Group Category</option>
                                                    @foreach($groups as $gCategory)
                                                    <option value="{{ $gCategory->id }}">{{ $gCategory->classification_category }}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedGroup')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endif

                                        @if(!is_null($selectedGroup))
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="category-dropdown-class">Class Category</label>
                                                <div class="select2-purple">
                                                    <select class="form-control" id="category-dropdown-class" wire:model="selectedClass">
                                                        <option value="" selected="true">Select Class Category</option>
                                                        @foreach($classes as $cCategory)
                                                        <option value="{{ $cCategory->id }}">{{ $cCategory->classification_category }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('selectedClass')
                                                    <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endif

                                    @endif

                                    <div class="col-md-12 m-2">

                                        <hr class="mb-2">

                                        <div class="form-group">
                                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                <input type="checkbox" class="custom-control-input" id="customSwitch3" value="1" wire:model="is_parent">
                                                <label class="custom-control-label" for="customSwitch3">Is Parent Category</label>
                                            </div>
                                        </div>
                                    </div>

                                    <blockquote class="blockquote">
                                        <p class="mb-0"><span class="text-red-400">Note*</span>: Fields with <span class="text-danger">*</span> sign are mendatory.</p>
                                    </blockquote>
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
                            <h3 class="card-title">All Patent Category List</h3>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body" style="overflow-x:scroll;">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>IPC Code</th>
                                        <th>Level</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($patentCategories as $category)
                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $category->classification_category }}</td>
                                        <td>{{ $category->ipc_code }}</td>
                                        <td>
                                            @if(is_null($selectedSection))
                                            <span class="badge badge-success badge-sm">Section Category</span>
                                            @elseif(!is_null($selectedSection) && is_null($selectedDivision))
                                            <span class="badge badge-primary badge-sm">Division Category</span>
                                            @elseif(!is_null($selectedSection) && !is_null($selectedDivision) && is_null($selectedGroup))
                                            <span class="badge badge-info badge-sm">Division Category</span>
                                            @elseif(!is_null($selectedSection) && !is_null($selectedDivision) && !is_null($selectedGroup) && is_null($selectedClass))
                                            <span class="badge badge-warning badge-sm">Group Category</span>
                                            @else
                                            <span class="badge badge-default badge-sm">Child Category</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-xs bg-warning" wire:click="editForm({{$category->id}})"  data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirm('Are you sure? Do you want to delete?') || event.stopImmediatePropagation()"
                                                class="btn btn-xs bg-danger" wire:click="softDelete({{$category->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
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
                                                        <a href="javascript:void(0)" class="btn btn-xs bg-warning" wire:click="editForm({{$category->id}})"  data-toggle="tooltip" data-placement="top" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" onclick="confirm('Are you sure? Do you want to delete?') || event.stopImmediatePropagation()"
                                                            class="btn btn-xs bg-danger" wire:click="softDelete({{$category->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                            <i class="far fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Category
                                                        </div>
                                                        <span class="badge badge-primary">{{ $category->parent->classification_category ?? 'Self' }}</span>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            IPC Code
                                                        </div>
                                                        <span class="badge badge-secondary">{{ $category->ipc_code ?? 'N/A' }}</span>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Action
                                                        </div>
                                                        <a href="javascript:void(0)" class="btn btn-xs bg-warning" wire:click="editForm({{$category->id}})"  data-toggle="tooltip" data-placement="top" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" onclick="confirm('Are you sure? Do you want to delete?') || event.stopImmediatePropagation()"
                                                            class="btn btn-xs bg-danger" wire:click="softDelete({{$category->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
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
                            {{$patentCategories->links('admin.render.admin-pagination-links')}}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->

            @livewire('admin.patent-category.import-component')

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>

@push('extra-styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .has-error {border: 1px solid #ff7e7e;}
    </style>
@endpush