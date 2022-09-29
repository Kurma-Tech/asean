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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Journals List</h3>
                            <span class="pull-right">
                                <a href="" class="btn btn-xs bg-primary"><i class="fa fa-plus"></i> Add Journals</a>
                            </span>
                            <div class="clear-fix"></div>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>App Number</th>
                                        <th>Applicant</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-widget="expandable-table" aria-expanded="false">
                                        <td>1</td>
                                        <td>Integrated fully-integrated elevator car and work method thereof</td>
                                        <td>567FGUJKHG</td>
                                        <td>
                                            Honda Motors llc
                                        </td>
                                        <td>
                                            2019/08/22
                                        </td>
                                        <td>
                                            <span class="badge badge-success">Active</span>
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
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                            </ul>
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