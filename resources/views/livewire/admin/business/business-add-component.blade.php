@section('title', 'Business Add')

<div>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <form wire:submit.prevent="storeBusiness">
                <div class="row">
                    <div class="col-md-7 col-sm-6 col-xs-12">
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
                                            <label for="company_name">Company Name<span class="text-danger">*</span></label>
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
                                            <label for="sec_no">SEC Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="sec_no"
                                                wire:model="sec_no">
                                            @error('sec_no')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="business_type_name">Business Type<span class="text-danger">*</span></label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4" id="business_type_name"
                                                    wire:model="business_type_id" style="width: 100%;">
                                                    <option hidden>Choose Business Type</option>
                                                    @isset($businessTypes)
                                                    @foreach($businessTypes as $businessType)
                                                    <option value="{{ $businessType->id }}">{{ $businessType->type }}</option>
                                                    @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                            @error('business_type_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="business_group_name">Business Group<span class="text-danger">*</span></label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4" id="business_group_name"
                                                    wire:model="business_group_id" style="width: 100%;">
                                                    <option hidden>Choose Business Group</option>
                                                    @isset($businessTypes)
                                                    @foreach($businessGroups as $businessGroup)
                                                    <option value="{{ $businessGroup->id }}">{{ $businessGroup->group }}</option>
                                                    @endforeach
                                                    @endisset
                                                    
                                                </select>
                                            </div>
                                            @error('business_group_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="industry_classification_name">Industry Classification<span class="text-danger">*</span></label>
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
                                            <label for="year">Business Year<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="year" id="year"
                                                wire:model="year" onchange="this.dispatchEvent(new InputEvent('input'))"/>
                                            @error('year')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="date_registered">Full Date Registred<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="date_registered"
                                                id="date_registered" wire:model="date_registered" onchange="this.dispatchEvent(new InputEvent('input'))"/>
                                            @error('date_registered')
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
                                            <label for="long">longitude<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="long"
                                                wire:model="long" disabled>
                                            @error('long')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="lat">latitude<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="lat"
                                                wire:model="lat" disabled>
                                            @error('lat')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="country_name">Country<span class="text-danger">*</span></label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4"
                                                    id="country_name" wire:model="selectedCountry"
                                                    style="width: 100%;" onchange="this.dispatchEvent(new InputEvent('input'))">
                                                    <option hidden>Select Country</option>
                                                    @foreach($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('selectedCountry')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    @if(!is_null($selectedCountry))
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="region_name">Region<span class="text-danger">*</span></label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4"
                                                    id="region_name" wire:model="selectedRegion"
                                                    style="width: 100%;" onchange="this.dispatchEvent(new InputEvent('input'))">
                                                    <option hidden>Select region</option>
                                                    @foreach($regions as $region)
                                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('selectedRegion')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif

                                    @if(!is_null($selectedRegion))
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="province_name">Province<span class="text-danger">*</span></label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4"
                                                    id="province_name" wire:model="selectedProvince"
                                                    style="width: 100%;" onchange="this.dispatchEvent(new InputEvent('input'))">
                                                    <option hidden>Select province</option>
                                                    @foreach($provinces as $province)
                                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('selectedProvince')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif

                                    @if(!is_null($selectedProvince))
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="district_name">District<span class="text-danger">*</span></label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4"
                                                    id="district_name" wire:model="selectedDistrict"
                                                    style="width: 100%;" onchange="this.dispatchEvent(new InputEvent('input'))">
                                                    <option hidden>Select district</option>
                                                    @foreach($districts as $district)
                                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('selectedDistrict')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif

                                    @if(!is_null($selectedDistrict))
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="city_name">City<span class="text-danger">*</span></label>
                                            <div wire:ignore>
                                                <select class="form-control select2 select2bs4"
                                                    id="city_name" wire:model="city_id"
                                                    style="width: 100%;" onchange="this.dispatchEvent(new InputEvent('input'))">
                                                    <option hidden>Select city</option>
                                                    @foreach($cities as $city)
                                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('city_id')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">Address<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="address"
                                                wire:model="address">
                                            @error('address')
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
                                <button type="submit" class="btn btn-success btn-sm">Add Business</button>
                                <a href="{{ route('admin.business.list') }}" class="btn btn-info btn-sm pull-right">View List</a>
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
                center: [111.09841688936865, 2.37304225637002], // starting position [lng, lat]
                zoom: 3, // starting zoom
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

            const marker = new mapboxgl.Marker(); // initialize a new marker

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
                    @this.set('selectedCountry', data);
            });

            $('#region_name').on('change', function (e) {
                let data = $(this).val();
                    @this.set('selectedRegion', data);
            });

            $('#province_name').on('change', function (e) {
                let data = $(this).val();
                    @this.set('selectedProvince', data);
            });

            $('#district_name').on('change', function (e) {
                let data = $(this).val();
                    @this.set('selectedDistrict', data);
            });

            $('#city_name').on('change', function (e) {
                let data = $(this).val();
                    @this.set('city_id', data);
            });

            $('#business_type_name').on('change', function (e) {
                let data = $(this).val();
                    @this.set('business_type_id', data);
            });

            $('#business_group_name').on('change', function (e) {
                let data = $(this).val();
                    @this.set('business_group_id', data);
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
