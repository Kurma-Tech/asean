@section('title', 'Business Add')

<div>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <form wire:submit.prevent="storeBusiness">
                <div class="row">
                    <div class="col-md-8 col-sm-6 col-xs-12">
                        <!-- general form -->
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Create Business</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="company_name">Company Name*</label>
                                            <input type="text" class="form-control" id="company_name" placeholder="Enter company name here..." wire:model="company_name">
                                            @error('company_name')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="sec_no">SEC Number*</label>
                                            <input type="text" class="form-control" id="sec_no" wire:model="sec_no">
                                            @error('sec_no')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="ngc_code">NGC Code</label>
                                            <input type="text" class="form-control" id="ngc_code" wire:model="ngc_code">
                                            @error('ngc_code')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="business_type_id">Business Type*</label>
                                            <select class="form-control select2 select2bs4" id="business_type_id" wire:model="business_type_id" style="width: 100%;">
                                                <option hidden>Choose Business Type</option>
                                                <option>Alaska</option>
                                                <option>California</option>
                                                <option>Delaware</option>
                                                <option>Tennessee</option>
                                                <option>Texas</option>
                                                <option>Washington</option>
                                            </select>
                                            @error('business_type_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="industry_classification_id">Industry Classification*</label>
                                            <select class="form-control select2 select2bs4" id="industry_classification_id" wire:model="industry_classification_id" style="width: 100%;">
                                                <option hidden>Choose Industry Classification</option>
                                                <option>Alaska</option>
                                                <option>California</option>
                                                <option>Delaware</option>
                                                <option>Tennessee</option>
                                                <option>Texas</option>
                                                <option>Washington</option>
                                            </select>
                                            @error('industry_classification_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="year">Business Year*</label>
                                            <input type="text" class="form-control" name="year" id="year" wire:model="year"/>
                                            @error('year')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="date_registerd">Full Date Registred*</label>
                                            <input type="text" class="form-control" name="date_registerd" id="date_registerd" wire:model="date_registerd"/>
                                            @error('date_registerd')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="geo_code">Geo Code</label>
                                            <input type="text" class="form-control" id="geo_code" wire:model="geo_code">
                                            @error('geo_code')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="industry_code">Industry Code</label>
                                            <input type="text" class="form-control" id="industry_code" wire:model="industry_code">
                                            @error('industry_code')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group" wire:ignore>
                                            <label for="geo_description">Geo Description</label>
                                            <textarea id="geo_description" wire:model="geo_description"></textarea>
                                            @error('geo_description')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group" wire:ignore>
                                            <label for="industry_description">Industry Description</label>
                                            <textarea id="industry_description" wire:model="industry_description"></textarea>
                                            @error('industry_description')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <!-- general form -->
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Business Location</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="long">longitude*</label>
                                            <input type="text" class="form-control" id="long" wire:model="long">
                                            @error('long')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lat">latitude*</label>
                                            <input type="text" class="form-control" id="lat" wire:model="lat">
                                            @error('lat')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">Address*</label>
                                            <input type="text" class="form-control" id="address" wire:model="address">
                                            @error('address')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-sm">Add Business</button>
                                <a href="#" class="btn btn-danger btn-sm pull-right">Discard</a>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </form>
        </div>
    </div>
</div>

@push('extra-styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
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
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        $("#year").datepicker({
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years",
            autoclose:true //to close picker once year is selected
        });

        $("#date_registerd").datepicker({
            format: "mm/dd/yyyy",
            autoclose:true //to close picker once year is selected
        });

        // Summernote
        $('#geo_description').summernote({
            placeholder: 'Place some text here.',
            tabsize: 2,
            height: 300,
            toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            // ['insert', ['link']], // 'picture', 'video'
            ['view', ['fullscreen', 'codeview']] // 'help'
            ],
            callbacks: {
                onChange: function(e) {
                    @this.set('geo_description', e);
                }
            }
        })
        
        $('#industry_description').summernote({
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
                    @this.set('industry_description', e);
                }
            }
        })
    });
</script>
@endpush
