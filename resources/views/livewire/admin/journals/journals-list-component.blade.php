@section('title', 'Journals List')

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
                                            <input type="search" class="form-control form-control-md"
                                                placeholder="Type your keywords here"
                                                wire:model.debounce.300ms='search'>
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
                                            <option value="title">name</option>
                                            <option value="source_title">Source Title</option>
                                            <option value="Abstract">Abstract</option>
                                            <option value="author_name">Author Name</option>
                                            <option value="publisher_name">Publisher Name</option>
                                            <option value="issn_no">ISSN No</option>
                                            <option value="citition_no">Citition No</option>
                                            <option value="eid_no">EID No</option>
                                            <option value="published_year">Published Year</option>
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
                            <h3 class="card-title">{{ $btnType }} Journals</h3>
                            <button type="button" class="btn btn-xs btn-info pull-right" data-toggle="modal"
                                data-target="#modal-default">
                                <i class="fa fa-file-import"></i> Import CSV
                            </button>
                        </div>
                        <!-- ./card-header -->
                        <form wire:submit.prevent="storeJournal">
                            <div class="card-body">
                                <input type="hidden" wire:model="hiddenId">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">Title*</label>
                                            <input type="text" class="form-control" id="title"
                                                placeholder="Enter Journals Title" wire:model='title'>
                                            @error('title')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="source_title">Source Title*</label>
                                            <input type="text" class="form-control" id="source_title"
                                                placeholder="Enter Source Title" wire:model='source_title'>
                                            @error('source_title')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="author_name">Author Name*</label>
                                            <input type="text" class="form-control" id="author_name"
                                                placeholder="Enter Author Name" wire:model='author_name'>
                                            @error('author_name')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="publisher_name">Publisher Name*</label>
                                            <input type="text" class="form-control" id="publisher_name"
                                                placeholder="Enter Publisher Name" wire:model='publisher_name'>
                                            @error('publisher_name')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="keywords">Keywords*</label>
                                            <input type="text" class="form-control" name="input" id="keywords"
                                                placeholder="Enter Keywords" wire:model='keywords'>
                                            <small class="form-text text-muted">Separate keywords with a comma</small>
                                            @error('keywords')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="issn_no">ISSN No*</label>
                                            <input type="text" class="form-control" id="issn_no"
                                                placeholder="Enter ISSN No" wire:model='issn_no'>
                                            @error('issn_no')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="citition_no">Citition No*</label>
                                            <input type="text" class="form-control" id="citition_no"
                                                placeholder="Enter Publisher Name" wire:model='citition_no'>
                                            @error('citition_no')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="published_year">Published Year*</label>
                                            <input type="text" class="form-control" name="date"
                                                id="published_year" wire:model="published_year" placeholder="YYYY"
                                                onchange="this.dispatchEvent(new InputEvent('input'))" />
                                            @error('published_year')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="category_id">Category*</label>
                                            <select class="form-control" id="category_id"
                                                wire:model="category_id" style="width: 100%;">
                                                <option hidden>Choose Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="country_id">Country*</label>
                                            <select class="form-control" id="country_id"
                                                wire:model="country_id" style="width: 100%;">
                                                <option hidden>Choose Category</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('country_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="long">Longitude</label>
                                            <input type="text" class="form-control" id="long"
                                                placeholder="Enter Longitude" wire:model='long'>
                                            @error('long')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lat">Latitude</label>
                                            <input type="text" class="form-control" id="lat"
                                                placeholder="Enter Latitude" wire:model='lat'>
                                            @error('lat')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12" wire:ignore>
                                    <div class="form-group">
                                        <label for="abstract">Abstract</label>
                                        <textarea id="abstract" wire:model="abstract"></textarea>
                                        @error('abstract')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-plus"></i>
                                    {{ $btnType }}</button>
                                <div class="btn btn-sm btn-danger pull-right" data-toggle="tooltip"
                                    data-placement="top" title="Reset Form Fields" wire:click="resetFields()"><i
                                        class="fas fa-redo-alt"></i> Reset Fields</div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-8 col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Journals List</h3>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>SourceTitle</th>
                                        <th>AuthorName</th>
                                        <th>PublishedYear</th>
                                        <th>ISSN.No</th>
                                        <th>Citition.No</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($journals as $journal)
                                        <tr data-widget="expandable-table" aria-expanded="false">
                                            <td>{{ $loop->iteration ?? 'N/A' }}</td>
                                            <td>{{ $journal->title ?? 'N/A' }}</td>
                                            <td>{{ $journal->source_title ?? 'N/A' }}</td>
                                            <td>{{ $journal->author_name ?? 'N/A' }}</td>
                                            <td>{{ $journal->published_year ?? 'N/A' }}</td>
                                            <td>{{ $journal->issn_no ?? 'N/A' }}</td>
                                            <td>{{ $journal->citition_no ?? 'N/A' }}</td>
                                            <td>
                                                @if ($journal->deleted_at)
                                                    <a href="#" class="btn btn-xs bg-success"
                                                        wire:click="restore({{ $journal->id }})"
                                                        data-toggle="tooltip" data-placement="top" title="Restore">
                                                        <i class="fas fa-trash-restore"></i>
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0)" class="btn btn-xs bg-warning"
                                                        wire:click="editForm({{ $journal->id }})"
                                                        data-toggle="tooltip" data-placement="top" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-xs bg-danger"
                                                        wire:click="softDelete({{ $journal->id }})"
                                                        data-toggle="tooltip" data-placement="top" title="Delete">
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
                                                            @if ($journal->deleted_at)
                                                                <a href="#" class="btn btn-xs bg-success"
                                                                    wire:click="restore({{ $journal->id }})"
                                                                    data-toggle="tooltip" data-placement="top" title="Restore">
                                                                    <i class="fas fa-trash-restore"></i>
                                                                </a>
                                                            @else
                                                                <a href="javascript:void(0)" class="btn btn-xs bg-warning"
                                                                    wire:click="editForm({{ $journal->id }})"
                                                                    data-toggle="tooltip" data-placement="top" title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="#" class="btn btn-xs bg-danger"
                                                                    wire:click="softDelete({{ $journal->id }})"
                                                                    data-toggle="tooltip" data-placement="top" title="Delete">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Abstract
                                                            </div>
                                                            <a href="javascript:void(0)" class="product-title">{{ $journal->abstract ?? 'N/A' }}</a>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Publisher Name
                                                            </div>
                                                            <a href="javascript:void(0)" class="product-title">{{ $journal->publisher_name  ?? 'N/A'}}</a>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Keywords
                                                            </div>
                                                            <a href="javascript:void(0)" class="product-title">
                                                                @if($journal->keywords)
                                                                    @php $keywords = json_decode($journal->keywords) @endphp 
                                                                    @foreach ($keywords as $key)
                                                                    <span class="badge badge-secondary">{{$key}}</span>
                                                                    @endforeach
                                                                @endif
                                                            </a>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Category Name
                                                            </div>
                                                            <a href="javascript:void(0)" class="product-title">{{$journal->journalCategory->category ?? 'N/A'}}</a>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Country Name
                                                            </div>
                                                            <a href="javascript:void(0)" class="product-title">{{$journal->country->name ?? 'N/A'}}</a>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Geo Location
                                                            </div>
                                                            <a href="javascript:void(0)" class="product-title">{{$journal->long ?? 'N/A'}} (long), {{$journal->lat ?? 'N/A'}} (Lat)</a>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Action
                                                            </div>
                                                            @if ($journal->deleted_at)
                                                                <a href="#" class="btn btn-xs bg-success"
                                                                    wire:click="restore({{ $journal->id }})"
                                                                    data-toggle="tooltip" data-placement="top" title="Restore">
                                                                    <i class="fas fa-trash-restore"></i>
                                                                </a>
                                                            @else
                                                                <a href="javascript:void(0)" class="btn btn-xs bg-warning"
                                                                    wire:click="editForm({{ $journal->id }})"
                                                                    data-toggle="tooltip" data-placement="top" title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="#" class="btn btn-xs bg-danger"
                                                                    wire:click="softDelete({{ $journal->id }})"
                                                                    data-toggle="tooltip" data-placement="top" title="Delete">
                                                                    <i class="far fa-trash-alt"></i>
                                                                </a>
                                                            @endif
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
                            {{ $journals->links('admin.render.admin-pagination-links') }}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
            @livewire('admin.journals.import-component')
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
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            $('#country_name').on('change', function(e) {
                let data = $(this).val();
                @this.set('country_id', data);
            });

            $('#category_name').on('change', function(e) {
                let data = $(this).val();
                @this.set('category_id', data);
            });

            Livewire.on('countryEvent', (data) => {
                $('#country_name').val(data).trigger('change');
            });

            Livewire.on('categoryEvent', (data) => {
                $('#category_name').val(data).trigger('change');
            });

            $("#published_year").datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true, //to close picker once year is selected
                endDate: new Date()
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
