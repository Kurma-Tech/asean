@section('title', 'Business Update')

<div>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <form wire:submit.prevent="updateBusiness">
                <div class="row">
                    <div class="col-md-7 col-sm-6 col-xs-12">
                        <!-- general form -->
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Update Business</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="company_name">Company Name*</label>
                                            <input type="text" class="form-control" id="company_name"
                                                placeholder="Enter company name here..." wire:model="company_name">
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
                                            <input type="text" class="form-control" id="sec_no"
                                                wire:model="sec_no">
                                            @error('sec_no')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="ngc_code">NGC Code</label>
                                            <input type="text" class="form-control" id="ngc_code"
                                                wire:model="ngc_code">
                                            @error('ngc_code')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="business_type_name">Business Type*</label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4" id="business_type_name"
                                                    wire:model="business_type_id" style="width: 100%;">
                                                    <option hidden>Choose Business Type</option>
                                                    @foreach($businessTypes as $businessType)
                                                    <option value="{{ $businessType->id }}">{{ $businessType->type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('business_type_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="industry_classification_name">Industry Classification*</label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4"
                                                    id="industry_classification_name" wire:model="industry_classification_id"
                                                    style="width: 100%;">
                                                    <option hidden>Choose Industry Classification</option>
                                                    @foreach($industryClassifications as $classification)
                                                    <option value="{{ $classification->id }}">{{ $classification->classifications }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
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
                                            <input type="text" class="form-control" name="year" id="year"
                                                wire:model="year" onchange="this.dispatchEvent(new InputEvent('input'))"/>
                                            @error('year')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="date_registered">Full Date Registred*</label>
                                            <input type="text" class="form-control" name="date_registered"
                                                id="date_registered" wire:model="date_registered" onchange="this.dispatchEvent(new InputEvent('input'))"/>
                                            @error('date_registered')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="geo_code">Geo Code</label>
                                            <input type="text" class="form-control" id="geo_code"
                                                wire:model="geo_code">
                                            @error('geo_code')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="industry_code">Industry Code</label>
                                            <input type="text" class="form-control" id="industry_code"
                                                wire:model="industry_code">
                                            @error('industry_code')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="geo_description">Geo Description</label>
                                            <div wire:ignore>
                                                <textarea id="geo_description" wire:model="geo_description"></textarea>
                                            </div>
                                            @error('geo_description')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="industry_description">Industry Description</label>
                                            <div wire:ignore>
                                                <textarea id="industry_description" wire:model="industry_description"></textarea>
                                            </div>
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

                    <div class="col-md-5 col-sm-6 col-xs-12">
                        <!-- general form -->
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Business Location</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="map" wire:ignore></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="long">longitude*</label>
                                            <input type="text" class="form-control" id="long"
                                                wire:model="long" disabled>
                                            @error('long')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lat">latitude*</label>
                                            <input type="text" class="form-control" id="lat"
                                                wire:model="lat" disabled>
                                            @error('lat')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12" wire:ignore>
                                        <div class="form-group">
                                            <label for="country_name">Country*</label>
                                            <select class="form-control select2 select2bs4"
                                                id="country_name" wire:model="country_id"
                                                style="width: 100%;" onchange="this.dispatchEvent(new InputEvent('input'))">
                                                <option hidden>Select Country</option>
                                                @foreach($countries as $country)
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">Address*</label>
                                            <input type="text" class="form-control" id="address"
                                                wire:model="address">
                                            @error('address')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-sm">Update Business</button>
                                <a href="{{ route('admin.business.list') }}" class="btn btn-danger btn-sm pull-right">Discard</a>
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
        rel="stylesheet">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <!-- mapbox -->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.css">
    <!-- geocoder -->
    <link rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css"
        type="text/css">
    <style>
        #map {
            height: 450px;
            width: 100%;
            position: relative;
            margin-bottom: 20px;
        }
    </style>
@endpush

@push('extra-scripts')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- mapbox -->
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.js"></script>
    <!-- geocoder -->
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>
    <script>
        document.addEventListener('livewire:load', () => {
            mapboxgl.accessToken = "{{ env('MAPBOX_KEY') }}";
            map = new mapboxgl.Map({
                container: "map", // container ID
                style: "{{ env('MAPBOX_STYLE') }}", // style URL
                center: [@this.long, @this.lat], // starting position [lng, lat]
                zoom: 15, // starting zoom
                pitch: 45,
                projection: "equirectangular", // display the map as a 3D globe
                maxBounds: [
                    [91.56216158463567, -10.491532410391958],
                    [141.79211516906793, 27.60302090835848]
                ] // Set the map's geographical boundaries.
            });

            const geocoder = new MapboxGeocoder({
                // Initialize the geocoder
                accessToken: mapboxgl.accessToken, // Set the access token
                mapboxgl: mapboxgl, // Set the mapbox-gl instance
                marker: true, // Do not use the default marker style
                placeholder: 'Search for Asean',
                bbox: [91.56216158463567, -10.491532410391958, 141.79211516906793,
                    27.60302090835848
                ], // Boundary for Berkeley
            });

            const marker = new mapboxgl.Marker() // initialize a new marker
            marker.setLngLat([@this.long, @this.lat]) // Marker [lng, lat] coordinates
                  .addTo(map); // Add the marker to the map

            // Add the geocoder to the map
            map.addControl(geocoder);

            map.on('click', (e) => {
                const longtitude = e.lngLat.lng
                const lattitude = e.lngLat.lat

                @this.long = longtitude
                @this.lat = lattitude

                marker.setLngLat([longtitude, lattitude]) // Marker [lng, lat] coordinates
                      .addTo(map); // Add the marker to the map
            })
        })

        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $('#country_name').on('change', function (e) {
                let data = $(this).val();
                    @this.set('country_id', data);
            });

            $('#business_type_name').on('change', function (e) {
                let data = $(this).val();
                    @this.set('business_type_id', data);
            });

            $('#industry_classification_name').on('change', function (e) {
                let data = $(this).val();
                    @this.set('industry_classification_id', data);
            });

            $("#year").datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                endDate: new Date(),
                autoclose: true //to close picker once year is selected
            });

            $("#date_registered").datepicker({
                format: "mm/dd/yyyy",
                endDate: new Date(),
                autoclose: true //to close picker once year is selected
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
