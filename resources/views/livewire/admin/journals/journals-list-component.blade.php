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
                                            <option value="abstract">Abstract</option>
                                            <option value="author_name">Author Name</option>
                                            <option value="publisher_name">Publisher Name</option>
                                            <option value="issn_no">ISSN No</option>
                                            <option value="cited_score">Cited Score</option>
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
                                            <label for="title">Title<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title"
                                                placeholder="Enter Journals Title" wire:model='title'>
                                            @error('title')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="source_title">Source Title<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="source_title"
                                                placeholder="Enter Source Title" wire:model='source_title'>
                                            @error('source_title')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="author_name">Author Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="author_name"
                                                placeholder="Enter Author Name" wire:model='author_name'>
                                            <small class="form-text text-muted">Separate keywords with a semicolon (;)</small>
                                            @error('author_name')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="publisher_name">Publisher Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="publisher_name"
                                                placeholder="Enter Publisher Name" wire:model='publisher_name'>
                                            @error('publisher_name')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="keywords">Keywords<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="input" id="keywords"
                                                placeholder="Enter Keywords" wire:model='keywords'>
                                            <small class="form-text text-muted">Separate keywords with a semicolon (;)</small>
                                            @error('keywords')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="link">Source Link</label>
                                            <input type="url" class="form-control" id="link"
                                                placeholder="Enter url of source document" wire:model='link'>
                                            @error('link')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="category-dropdown">Category<span class="text-danger">*</span></label>
                                            <div class="select2-purple" wire:ignore>
                                                <select class="form-control select2" multiple="multiple" data-placeholder="Choose Categories" data-dropdown-css-class="select2-purple" id="category-dropdown" wire:model="categories">
                                                    @foreach($categories_data as $category)
                                                    <option value="{{ $category->id }}">{{ $category->category }}</option>
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
                                            <label for="issn_no">ISSN No<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="issn_no"
                                                placeholder="Enter ISSN No" wire:model='issn_no'>
                                            @error('issn_no')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="cited_score">Cited Score<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="cited_score"
                                                placeholder="Enter Cited Score" wire:model='cited_score'>
                                            @error('cited_score')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="published_year">Published Year<span class="text-danger">*</span></label>
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
                                            <label for="country_name">Country<span class="text-danger">*</span></label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4" id="country_name" wire:model="country_id" style="width: 100%;">
                                                    <option hidden>Choose Country</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
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

                                    <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="abstract_text">Abstract</label>
                                            <div wire:ignore>
                                                <textarea id="abstract_text" wire:model="abstract">{{ $abstract }}</textarea>
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
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-plus"></i> {{ $btnType }}
                                </button>
                                <div class="btn btn-sm btn-danger pull-right" data-toggle="tooltip"
                                    data-placement="top" title="Reset Form Fields" wire:click="resetFields()">
                                    <i class="fas fa-redo-alt"></i> Reset Fields
                                </div>
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
                                        <th>AuthorName</th>
                                        <th>PublishedYear</th>
                                        <th>ISSN.No</th>
                                        <th>CitedScore</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($journals as $journal)
                                        <tr data-widget="expandable-table" aria-expanded="false">
                                            <td>{{ $loop->iteration ?? 'N/A' }}</td>
                                            <td>{{ $journal->title ?? 'N/A' }}</td>
                                            <td>
                                                @if($journal->author_name)
                                                    @php $authors = json_decode($journal->author_name) @endphp 
                                                    @foreach ($authors as $a)
                                                    <span class="badge badge-secondary">{{$a}}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{{ $journal->published_year ?? 'N/A' }}</td>
                                            <td>{{ $journal->issn_no ?? 'N/A' }}</td>
                                            <td>{{ $journal->cited_score ?? 'N/A' }}</td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-xs bg-warning"
                                                    wire:click="editForm({{ $journal->id }})"
                                                    data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="javascript:void(0)" onclick="confirm('Do you want to restore?') || event.stopImmediatePropagation()"
                                                    class="btn btn-xs bg-danger"
                                                    wire:click="softDelete({{ $journal->id }})"
                                                    data-toggle="tooltip" data-placement="top" title="Delete">
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
                                                            <a href="javascript:void(0)" class="btn btn-xs bg-warning"
                                                                wire:click="editForm({{ $journal->id }})"
                                                                data-toggle="tooltip" data-placement="top" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="javascript:void(0)" onclick="confirm('Do you want to restore?') || event.stopImmediatePropagation()"
                                                                class="btn btn-xs bg-danger"
                                                                wire:click="softDelete({{ $journal->id }})"
                                                                data-toggle="tooltip" data-placement="top" title="Delete">
                                                                <i class="far fa-trash-alt"></i>
                                                            </a>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Source Title
                                                            </div>
                                                            <p class="product-title">{{ $journal->source_title  ?? 'N/A'}}</p>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Source Link
                                                            </div>
                                                            <a href="{{ $journal->link ?? 'javascript:void(0)'}}" class="product-title">{{ $journal->link ?? 'N/A'}}</a>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Publisher Name
                                                            </div>
                                                            <p class="product-title">{{ $journal->publisher_name  ?? 'N/A'}}</p>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Keywords
                                                            </div>
                                                            <p class="product-title">
                                                                @if($journal->keywords)
                                                                    @php $keywords = json_decode($journal->keywords) @endphp 
                                                                    @foreach ($keywords as $key)
                                                                    <span class="badge badge-secondary">{{$key}}</span>
                                                                    @endforeach
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </li>
                                                    @if(!is_null($journal->journalCategories))
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Category Name
                                                            </div>
                                                            <p class="product-title">
                                                                @foreach ($journal->journalCategories as $jCategories)
                                                                    <span class="badge badge-secondary">{{ $jCategories->category }}</span>
                                                                @endforeach
                                                            </p>
                                                        </div>
                                                    </li>
                                                    @endisset
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Country Name
                                                            </div>
                                                            <p class="product-title">{{$journal->country->name ?? 'N/A'}}</p>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Geo Location
                                                            </div>
                                                            <p class="product-title">{{$journal->long ?? 'N/A'}} (long), {{$journal->lat ?? 'N/A'}} (Lat)</p>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Abstract
                                                            </div>
                                                            <p class="product-title">{!! $journal->abstract ?? 'N/A' !!}</p>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Action
                                                            </div>
                                                            <a href="javascript:void(0)" class="btn btn-xs bg-warning"
                                                                wire:click="editForm({{ $journal->id }})"
                                                                data-toggle="tooltip" data-placement="top" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a href="javascript:void(0)" onclick="confirm('Do you want to restore?') || event.stopImmediatePropagation()"
                                                                class="btn btn-xs bg-danger"
                                                                wire:click="softDelete({{ $journal->id }})"
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

        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $('#country_name').on('change', function(e) {
                let data = $(this).val();
                @this.set('country_id', data);
            });

            $('#category-dropdown').on('change', function(e) {
                let data = $(this).val();
                @this.set('categories', data);
            });

            Livewire.on('countryEvent', (data) => {
                $('#country_name').val(data).trigger('change');
            });

            Livewire.on('categoryEvent', (data) => {
                $('#category-dropdown').val(data).trigger('change');
            });

            Livewire.on('abstractEvent', (data) => {
                $('#abstract_text').val(data).trigger('change');
            });

            $("#published_year").datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true, //to close picker once year is selected
                endDate: new Date()
            });

            $('#abstract_text').summernote({
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
