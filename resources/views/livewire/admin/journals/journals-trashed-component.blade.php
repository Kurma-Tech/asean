@section('title', 'Journals Trashed List')

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
                                            <option value="citition_no">Citition No</option>
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
                <div class="col-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Trashed Journals List</h3>
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
                                        <th>Citition.No</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($journals->where('deleted_at', '!=', Null) as $journal)
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
                                            <td>{{ $journal->citition_no ?? 'N/A' }}</td>
                                            <td>
                                                <a href="javascript:void(0)" onclick="confirm('Do you want to restore?') || event.stopImmediatePropagation()"
                                                    class="btn btn-xs bg-success"
                                                    wire:click="restore({{ $journal->id }})"
                                                    data-toggle="tooltip" data-placement="top" title="Restore">
                                                    <i class="fas fa-trash-restore"></i>
                                                </a>
                                                <a href="javascript:void(0)" onclick="confirm('Do you want to permantely remove?') || event.stopImmediatePropagation()"
                                                    class="btn btn-xs bg-danger"
                                                    wire:click="delete({{ $journal->id }})"
                                                    data-toggle="tooltip" data-placement="top" title="Permanently Remove">
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
                                                            <a href="javascript:void(0)" onclick="confirm('Do you want to restore?') || event.stopImmediatePropagation()"
                                                                class="btn btn-xs bg-success"
                                                                wire:click="restore({{ $journal->id }})"
                                                                data-toggle="tooltip" data-placement="top" title="Restore">
                                                                <i class="fas fa-trash-restore"></i>
                                                            </a>
                                                            <a href="javascript:void(0)" onclick="confirm('Do you want to permantely remove?') || event.stopImmediatePropagation()"
                                                                class="btn btn-xs bg-danger"
                                                                wire:click="delete({{ $journal->id }})"
                                                                data-toggle="tooltip" data-placement="top" title="Permanently Remove">
                                                                <i class="far fa-trash-alt"></i>
                                                            </a>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Source Title
                                                            </div>
                                                            <a href="javascript:void(0)" class="product-title">{{ $journal->source_title  ?? 'N/A'}}</a>
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
                                                            <a href="javascript:void(0)" onclick="confirm('Do you want to restore?') || event.stopImmediatePropagation()"
                                                                class="btn btn-xs bg-success"
                                                                wire:click="restore({{ $journal->id }})"
                                                                data-toggle="tooltip" data-placement="top" title="Restore">
                                                                <i class="fas fa-trash-restore"></i>
                                                            </a>
                                                            <a href="javascript:void(0)" onclick="confirm('Do you want to permantely remove?') || event.stopImmediatePropagation()"
                                                                class="btn btn-xs bg-danger"
                                                                wire:click="delete({{ $journal->id }})"
                                                                data-toggle="tooltip" data-placement="top" title="Permanently Remove">
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
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
