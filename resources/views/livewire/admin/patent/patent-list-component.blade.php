@section('title', 'Intellectual Property List')

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
                                            <option value="title">Title</option>
                                            <option value="filing_no
                                            ">Filing Number</option>
                                            <option value="registration_date
                                            ">Registration Date</option>
                                            <option value="publication_date
                                            ">Publication Date</option>
                                            <option value="filing_date
                                            ">Filing Date</option>
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
                            <h3 class="card-title">{{ $btnType }} Intellectual Property</h3>
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
                                            <label for="title">Title<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" placeholder="Enter Intellectual Property Title" wire:model='title'>
                                            @error('title')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="filing_no">Filing Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="filing_no" placeholder="Enter Filing Number" wire:model='filing_no'>
                                            @error('filing_no')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="registration_no">Registration Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="registration_no" placeholder="Enter Registration Number" wire:model="registration_no"/>
                                            @error('registration_no')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="inventor_name">Inventor Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="inventor_name" placeholder="Enter Inventor Number" wire:model='inventor_name'>
                                            <small class="form-text text-muted">If multiple names separate name with a semi-colon (;)</small>
                                            @error('inventor_name')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="applicant_company">Applicant Company<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="applicant_company" placeholder="Enter Applicant Company" wire:model='applicant_company'>
                                            @error('applicant_company')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12" >
                                        <div class="form-group">
                                            <label for="type_name">Intellectual Property Type<span class="text-danger">*</span></label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4" id="type_name" wire:model="type_id" style="width: 100%;">
                                                    <option hidden>Choose Type Option</option>
                                                    @foreach($patentTypes as $pType)
                                                    <option value="{{ $pType->id }}">{{ $pType->type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('type_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="kind_name">Intellectual Property Kind<span class="text-danger">*</span></label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4" id="kind_name" wire:model="kind_id" style="width: 100%;">
                                                    <option hidden>Choose Kind Option</option>
                                                    @foreach($patentKinds as $pKind)
                                                    <option value="{{ $pKind->id }}">{{ $pKind->kind }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('kind_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="category-dropdown">Patent Category<span class="text-danger">*</span></label>
                                            <div class="select2-purple" wire:ignore>
                                                <select class="form-control" multiple="multiple" data-placeholder="Choose Categories" data-dropdown-css-class="select2-purple" id="category-dropdown" wire:model="categories">
                                                    @foreach($patentCategories as $category)
                                                    <option value="{{ $category->ipc_code }}">{{ $category->ipc_code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('categories')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="country_name">Country<span class="text-danger">*</span></label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4" id="country_name" wire:model="country_id" style="width: 100%;">
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
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="registration_date">Registration Date<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="registration_date" id="registration_date" wire:model="registration_date" onchange="this.dispatchEvent(new InputEvent('input'))"/>
                                            @error('registration_date')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="publication_date">Publication Date<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="publication_date" id="publication_date" wire:model="publication_date" onchange="this.dispatchEvent(new InputEvent('input'))"/>
                                            @error('publication_date')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="filing_date">Filing Date<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="filing_date" id="filing_date" wire:model="filing_date" onchange="this.dispatchEvent(new InputEvent('input'))"/>
                                            @error('filing_date')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="long">Longitude</label>
                                            <input type="text" class="form-control" id="long" placeholder="Enter Longitude" wire:model='long'>
                                            @error('long')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lat">Latitude</label>
                                            <input type="text" class="form-control" id="lat" placeholder="Enter Latitude" wire:model='lat'>
                                            @error('lat')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="abstract">Abstract</label>
                                            <div wire:ignore>
                                                <textarea id="abstract" wire:model="abstract"></textarea>
                                            </div>
                                            @error('abstract')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <blockquote class="blockquote">
                                    <p class="mb-0"><span class="text-red-400">Note*</span>: Fields with <span class="text-danger">*</span> sign are mendatory.</p>
                                </blockquote>
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
                            <h3 class="card-title">All Intellectual Property List</h3>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>FilingNo</th>
                                        <th>Title</th>
                                        <th>RegistrationNo.</th>
                                        <th>I.P.Type</th>
                                        <th>I.P.Kind</th>
                                        <th>PublicationDate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patents as $patent)
                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $patent->filing_no }}</td>
                                        <td>{{ $patent->title }}</td>
                                        <td>{{ $patent->registration_no }}</td>
                                        <td><span class="badge badge-info">{{ $patent->patentType->type ?? 'N/A' }}</span></td>
                                        <td><span class="badge badge-primary">{{ $patent->patentKind->kind ?? 'N/A' }}</span></td>
                                        <td>{{ $patent->publication_date }}</td>
                                        <td>
                                            @if($patent->deleted_at)
                                            <a href="#" class="btn btn-xs bg-success" wire:click="restore({{$patent->id}})" data-toggle="tooltip" data-placement="top" title="Restore">
                                                <i class="fas fa-trash-restore"></i>
                                            </a>
                                            @else
                                            <a href="javascript:void(0)" class="btn btn-xs bg-warning" wire:click="editForm({{$patent->id}})"  data-toggle="tooltip" data-placement="top" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="confirm('Are you sure? Do you want to delete?') || event.stopImmediatePropagation()" class="btn btn-xs bg-danger" wire:click="softDelete({{$patent->id}})" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                            @endif
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
                                                        <a href="javascript:void(0)" class="btn btn-xs bg-warning"
                                                            wire:click="editForm({{ $patent->id }})"
                                                            data-toggle="tooltip" data-placement="top" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" onclick="confirm('Are you sure? Do you want to delete?') || event.stopImmediatePropagation()"
                                                            class="btn btn-xs bg-danger"
                                                            wire:click="softDelete({{ $patent->id }})"
                                                            data-toggle="tooltip" data-placement="top" title="Delete">
                                                            <i class="far fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Registration Date
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{ $patent->registration_date  ?? 'N/A'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Filing Date
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{ $patent->filing_date  ?? 'N/A'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Registration Number
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{ $patent->registration_no  ?? 'N/A'}}</a>
                                                    </div>
                                                </li>
                                                @if(!is_null($patent->patentCategories))
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Category Name
                                                        </div>
                                                        <p class="product-title">
                                                            @foreach ($patent->patentCategories as $pCategories)
                                                                <span class="badge badge-secondary">{{ $pCategories->category }}</span>
                                                            @endforeach
                                                        </p>
                                                    </div>
                                                </li>
                                                @endisset
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Inventor Name
                                                        </div>
                                                        @if($patent->inventor_name)
                                                            @php $inventor_name = json_decode($patent->inventor_name) @endphp 
                                                            @foreach ($inventor_name as $key)
                                                            <span class="badge badge-secondary">{{$key}}</span>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Applicant Company
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{$patent->applicant_company ?? 'N/A'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Country Name
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{$patent->country->name ?? 'N/A'}}</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Geo Location
                                                        </div>
                                                        <a href="javascript:void(0)" class="product-title">{{$patent->long ?? 'N/A'}} (long), {{$patent->lat ?? 'N/A'}} (Lat)</a>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Abstract
                                                        </div>
                                                        <p class="product-title">{!! $patent->abstract ?? 'N/A' !!}</p>
                                                    </div>
                                                </li>
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Action
                                                        </div>
                                                        <a href="javascript:void(0)" class="btn btn-xs bg-warning"
                                                            wire:click="editForm({{ $patent->id }})"
                                                            data-toggle="tooltip" data-placement="top" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0)" onclick="confirm('Are you sure? Do you want to delete?') || event.stopImmediatePropagation()"
                                                            class="btn btn-xs bg-danger"
                                                            wire:click="softDelete({{ $patent->id }})"
                                                            data-toggle="tooltip" data-placement="top" title="Delete">
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
        rel="stylesheet">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
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
    $(document).ready(function () {
        $('#category-dropdown').select2();
        $('#category-dropdown').on('change', function (e) {
            let data = $(this).val();
                @this.set('categories', data);
        });
        window.livewire.on('categoryEvent', () => {
            $('#category-dropdown').select2();
        });
    });  

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

        $('#type_name').on('change', function (e) {
            let data = $(this).val();
                @this.set('type_id', data);
        });

        $('#kind_name').on('change', function (e) {
            let data = $(this).val();
                @this.set('kind_id', data);
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

        $("#registration_date").datepicker({
            format: "mm/dd/yyyy",
            endDate: new Date(),
            autoclose:true //to close picker once year is selected
        });

        $("#publication_date").datepicker({
            format: "mm/dd/yyyy",
            endDate: new Date(),
            autoclose:true //to close picker once year is selected
        });

        $("#filing_date").datepicker({
            format: "mm/dd/yyyy",
            endDate: new Date(),
            autoclose:true //to close picker once year is selected
        });

        $('#abstract').summernote({
            placeholder: 'Place some text here.',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['fullscreen', 'codeview']] // 'help'
            ],
            callbacks: {
                onChange: function(e) {
                    @this.set('abstract', e);
                }
            }
        })
    });
</script>
@endpush