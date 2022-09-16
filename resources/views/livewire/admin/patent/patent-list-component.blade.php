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
                                <div class="form-group">
                                    <label for="title">Title*</label>
                                    <input type="text" class="form-control" id="title" placeholder="Enter Patent Title" wire:model='title'>
                                    @error('title')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="patent_id">Patent ID*</label>
                                    <input type="text" class="form-control" id="patent_id" placeholder="Enter Patent ID" wire:model='patent_id'>
                                    @error('patent_id')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="country_id">Country*</label>
                                    <select class="form-control select2 select2bs4" id="kind_id" wire:model="kind_id" style="width: 100%;">
                                        <option hidden>Choose Patent Kind</option>
                                        <option>Alaska</option>
                                        <option>California</option>
                                        <option>Delaware</option>
                                        <option>Tennessee</option>
                                        <option>Texas</option>
                                        <option>Washington</option>
                                    </select>
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="kind_id">Kind*</label>
                                    <select class="form-control select2 select2bs4" id="kind_id" wire:model="kind_id" style="width: 100%;">
                                        <option hidden>Choose Patent Kind</option>
                                        <option>Alaska</option>
                                        <option>California</option>
                                        <option>Delaware</option>
                                        <option>Tennessee</option>
                                        <option>Texas</option>
                                        <option>Washington</option>
                                    </select>
                                    @error('kind_id')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="type_id">Type*</label>
                                    <select class="form-control select2 select2bs4" id="type_id" wire:model="type_id" style="width: 100%;">
                                        <option hidden>Choose Patent Type</option>
                                        <option>Alaska</option>
                                        <option>California</option>
                                        <option>Delaware</option>
                                        <option>Tennessee</option>
                                        <option>Texas</option>
                                        <option>Washington</option>
                                    </select>
                                    @error('type_id')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="date">Date Registred*</label>
                                    <input type="text" class="form-control" name="date" id="patent_date_registerd" wire:model="date"/>
                                    @error('date')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->
                        
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">{{ $btnType }}</button>
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
                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td>1</td>
                                        <td>7473386</td>
                                        <td>Mold for tire</td>
                                        <td>
                                            B2
                                        </td>
                                        <td>
                                            utility
                                        </td>
                                        <td>
                                            2009-01-06
                                        </td>
                                        <td>
                                            <span class="badge badge-info">6.40308</span>
                                            <span class="badge badge-primary">101.707</span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a>
                                            <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                    <tr class="expandable-body">
                                        <td colspan="8">
                                            <ul class="products-list product-list-in-card pl-2 pr-2">
                                                <li class="item">
                                                    <div class="product-info">
                                                        <div class="product-title">
                                                            Description
                                                        </div>
                                                        <p>The invention provides an integrated fully-integrated elevator car and a work method thereof. The integrated fully-integrated elevator ca comprises a car top plate, a car bottom plate, a car left wallplate and a car right car plate, and the car left wall plate and the car right wall plate are arranged between the car top plate and the car bottom plate. The front side of the car left wall plate and the front side of the car right wall plate are fixedly connected with a front wall, and the car top plate comprises a fixed car top plate body and a movable car top plate body hinged to the rear side of the fixed car top plate body. The car bottom plate comprises a fixed car bottom plate body and a movable car bottom plate body hinged to the rear side of the fixed car bottom plate body. The carright wall plate comprises a fixed right car wall and a movable right car wall hinged to the fixed right car wall. The car left wall plate comprises a fixed left car wall and a movable left car wall hinged to the rear side of the fixed left car wall. The movable car top plate body, the fixed car bottom plate body, the fixed right car wall and the fixed left car wall are integrated, and an openingfor allowing personnel to get in and out is reserved in the middle of the front wall. According to the integrated fully-integrated elevator car and the work method thereof, the foldable structure is utilized, the elevator car can conveniently enter a built door of a villa and enter a room, the elevator mounting workload is reduced, and the labor charges are reduced.</p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            {{$patentKinds->links('admin.render.admin-pagination-links')}}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
            @livewire('admin.patent-kind.import-component')
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>