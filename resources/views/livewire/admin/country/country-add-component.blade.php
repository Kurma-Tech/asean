@section('title', 'Country Add')

<div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                  <!-- general form elements -->
                  <div class="card card-secondary">
                    <div class="card-header">
                      <h3 class="card-title">Add New Country</h3>
                      <span class="pull-right">
                        <a href="{{route('admin.countries.list')}}" class="btn btn-xs bg-danger"><i class="fa fa-arrow-left"></i> Back to list</a>
                      </span>
                      <div class="clear-fix"></div>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form wire:submit.prevent="storeCountry">
                      <div class="card-body">
                        <div class="form-group">
                            <label for="name">Country Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter Country Name" wire:model='name'>
                            @error('name')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="c_code">Country Code</label>
                            <input type="text" class="form-control" id="c_code" placeholder="Enter Country Code" wire:model='c_code'>
                            @error('c_code')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="short_code">Short Code</label>
                            <input type="text" class="form-control" id="short_code" placeholder="Enter Short Code" wire:model='short_code'>
                            @error('short_code')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group" wire:ignore>
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input type="checkbox" class="custom-control-input" id="customSwitch3" value="1" wire:model="status">
                                <label class="custom-control-label" for="customSwitch3">Active / De-active countries</label>
                                @error('status')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                      </div>
                      <!-- /.card-body -->
      
                      <div class="card-footer">
                        <button type="submit" class="btn btn-success">Create Country</button>
                      </div>
                    </form>
                  </div>
                  <!-- /.card -->
      
                </div>
                <!--/.col (left) -->
            </div>
        </div>
    </div>
</div>

@push('extra-scripts')
    
@endpush

