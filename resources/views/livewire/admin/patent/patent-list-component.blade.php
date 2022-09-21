@section('title', 'Patent List')

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
                                            <option value="name">name</option>
                                            <option value="isDeactivated">Activated</option>
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
                            <h3 class="card-title">{{ $btnType }} Patent</h3>
                            <button type="button" class="btn btn-xs btn-info pull-right" data-toggle="modal" data-target="#modal-default">
                                <i class="fa fa-file-import"></i> Import CSV
                            </button>
                        </div>
                        <!-- ./card-header -->
                        <form wire:submit.prevent="storePatent">
                            <div class="card-body">
                                <input type="hidden" wire:model="hiddenId">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">Title*</label>
                                            <input type="text" class="form-control" id="title" placeholder="Enter Patent Title" wire:model='title'>
                                            @error('title')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="patent_id">Patent ID*</label>
                                            <input type="text" class="form-control" id="patent_id" placeholder="Enter Patent ID" wire:model='patent_id'>
                                            @error('patent_id')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-12" wire:ignore>
                                        <div class="form-group">
                                            <label for="country_name">Country*</label>
                                            <select class="form-control select2 select2bs4" id="country_name" wire:model="country_id" style="width: 100%;">
                                                <option hidden>Choose Country</option>
                                                @foreach($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('country_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12" wire:ignore>
                                        <div class="form-group">
                                            <label for="kind_name">Kind*</label>
                                            <select class="form-control select2 select2bs4" id="kind_name" wire:model="kind_id" style="width: 100%;">
                                                <option hidden>Choose Patent Kind</option>
                                                @foreach($patentKinds as $pKind)
                                                <option value="{{ $pKind->id }}">{{ $pKind->kind }}</option>
                                                @endforeach
                                            </select>
                                            @error('kind_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12" wire:ignore>
                                        <div class="form-group">
                                            <label for="type_name">Type*</label>
                                            <select class="form-control select2 select2bs4" id="type_name" wire:model="type_id" style="width: 100%;">
                                                <option hidden>Choose Patent Type</option>
                                                @foreach($patentTypes as $pType)
                                                <option value="{{ $pType->id }}">{{ $pType->type }}</option>
                                                @endforeach
                                            </select>
                                            @error('type_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date">Date Registred*</label>
                                            <input type="text" class="form-control" name="date" id="patent_date_registered" wire:model="date" onchange="this.dispatchEvent(new InputEvent('input'))"/>
                                            @error('date')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="long">Longitude*</label>
                                            <input type="text" class="form-control" id="long" placeholder="Enter Longitude" wire:model='long'>
                                            @error('long')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lat">Latitude*</label>
                                            <input type="text" class="form-control" id="lat" placeholder="Enter Latitude" wire:model='lat'>
                                            @error('lat')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
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
                            <h3 class="card-title">All Patent List</h3>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Kind</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Lat-Lon</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patents as $patent)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $patent->patent_id }}</td>
                                        <td>{{ $patent->title }}</td>
                                        <td>
                                            B2
                                        </td>
                                        <td>
                                            utility
                                        </td>
                                        <td>
                                            {{ $patent->date }}
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $patent->lat }}</span>
                                            <span class="badge badge-primary">{{ $patent->long }}</span>
                                        </td>
                                        <td>
                                            @if($patent->deleted_at)
                                            <a href="#" class="btn btn-xs bg-success" wire:click="restore({{$patent->id}})" data-toggle="tooltip" data-placement="top" title="Restore">
                                                <i class="fas fa-trash-restore"></i>
                                            </a>
                                            @else
                                            <a href="javascript:void(0)" class="btn btn-xs bg-warning" wire:click="editForm({{$patent->id}})"  data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-xs bg-danger" wire:click="softDelete({{$patent->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
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
                            {{$patents->links('admin.render.admin-pagination-links')}}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
            @livewire('admin.patent.import-component')
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>

@push('extra-styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
@endpush

@push('extra-scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();
    
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        $('#country_name').on('change', function (e) {
            let data = $(this).val();
                @this.set('country_id', data);
        });

        $('#kind_name').on('change', function (e) {
            let data = $(this).val();
                @this.set('kind_id', data);
        });

        $('#type_name').on('change', function (e) {
            let data = $(this).val();
                @this.set('type_id', data);
        });

        Livewire.on('countryEvent', (data) => {
            $('#country_name').val(data).trigger('change');
        });

        Livewire.on('typeEvent', (data) => {
            $('#type_name').val(data).trigger('change');
        });

        Livewire.on('kindEvent', (data) => {
            $('#kind_name').val(data).trigger('change');
        });

        $("#patent_date_registered").datepicker({
            format: "mm/dd/yyyy",
            autoclose:true //to close picker once year is selected
        });
    });
</script>
@endpush