@push('extra-styles')
    <!-- mapbox -->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.css">
    <!-- geocoder -->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css"
        type="text/css">
    <style>
        .content-wrapper{
            position: relative;
            height: calc(100vh - 48px)!important;
        }
        .content{
            position: absolute;
            width: 100%;
            height: calc(100vh - 48px)!important;
        }
        #maps {
            height: 100vh;
            width: 100%;
            position: relative;
        }

        #map {
            height: 100vh;
            width: 100%;
            z-index: 1;
            position: absolute;
        }

        #density-map {
            height: 100vh;
            width: 100%;
            z-index: 2;
            position: absolute;
        }

        #change-maps{
            position: absolute;
            z-index: 10;
            left: 330px;
            top: 20px;
        }

        #loader {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9;
            display: none;
            justify-content: center;
            align-items: center;
        }

        #loader img {
            width: 34px;
            height: 34px;
        }

        #background {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 0.5;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #background img {
            width: 100%;
            height: 100%;
        }

        .popUp-content {
            overflow-y: auto;
            max-height: 400px;
            width: 100%;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .marker-style {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .position-relative {
            position: relative;
        }

        .mapboxgl-popup-content {
            width: max-content;
            padding-top: 18px;
        }

        .mapboxgl-ctrl-center {
            bottom: 72px;
            left: 50%;
            position: absolute;
            pointer-events: none;
            z-index: 2;
        }

        .mapboxgl-ctrl-center .mapboxgl-ctrl-group {
            display: flex;
            margin-bottom: 5px;
        }

        .legend {
            background-color: #fff;
            border-radius: 3px;
            bottom: 50px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            color: black;
            padding: 10px;
            position: absolute;
            z-index: 11;
            left: 330px;
        }
        
        .legend h4 {
            margin: 0 0 10px;
        }
        
        .legend div span {
            border-radius: 50%;
            display: inline-block;
            height: 10px;
            margin-right: 5px;
            width: 10px;
        }
    </style>
@endpush
<div>
    <div class="content-wrapper">
        <section class="content p-0">
            <div class="container-fluid p-0">
                <div class="row m-0">
                    <div class="col-12 col-sm-12 position-relative overflow-control p-0" id="mapSection">
                        <div id="maps">
                            <div id="state-legend" class="legend" style="display: {{ $isDensityMap ? 'none' : 'block'}};">
                                <h4>Business</h4>
                                <div><span style="background-color: #723122"></span>25,000</div>
                                <div><span style="background-color: #8b4225"></span>10,000</div>
                                <div><span style="background-color: #a25626"></span>7,500</div>
                                <div><span style="background-color: #b86b25"></span>5,000</div>
                                <div><span style="background-color: #ca8323"></span>2,500</div>
                                <div><span style="background-color: #da9c20"></span>1,000</div>
                                <div><span style="background-color: #e6b71e"></span>750</div>
                                <div><span style="background-color: #eed322"></span>500</div>
                                <div><span style="background-color: #f2f12d"></span>0</div>
                            </div>
                        
                            <div id="change-maps">
                                <button class="btn {{ $isDensityMap ? 'btn-success' : 'btn-default' }} btn-sm pull-right" wire:click="changeMap(true)">Density Map</button>
                                <button class="btn {{ $isDensityMap ? 'btn-default' : 'btn-success' }} btn-sm pull-right" wire:click="changeMap(false)">Heat Map</button>
                            </div>
                            <div id="loader"><img src="loader.gif" alt="loader"></div>
                            <div style="display: {{ $isDensityMap ? 'block' : 'none' }}; position: relative; height: 100vh; width: 100%;">
                                <div id="map" wire:ignore></div>
                            </div>
                            <div style="display: {{ $isDensityMap ? 'none' : 'block' }}; position: relative; height: 100vh; width: 100%;">
                                <div id="density-map" wire:ignore></div>
                            </div>
                        </div>
                        <div class="map-overlay-box overlay-scroll">
                            <h3 class="search-title">{{ GoogleTranslate::trans('Search', app()->getLocale()) }}</h3>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="search"
                                            placeholder="{{ GoogleTranslate::trans('Search', app()->getLocale()) }}..."
                                            wire:model="searchValue">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-sm btn-default btn-flat"
                                                wire:click="handleSearch"><i class="fa fa-search lemongreen"
                                                    aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label>{{ GoogleTranslate::trans('Sort by Type', app()->getLocale()) }}:</label>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control" wire:model="type">
                                            <option hidden>{{ GoogleTranslate::trans('Choose Data Type', app()->getLocale()) }}</option>
                                            {{-- <option value="all">{{ GoogleTranslate::trans('All', app()->getLocale()) }}</option> --}}
                                            <option value="business">{{ GoogleTranslate::trans('Business', app()->getLocale()) }}</option>
                                            <option value="patent">{{ GoogleTranslate::trans('Patent', app()->getLocale()) }}</option>
                                            <option value="journal">{{ GoogleTranslate::trans('Journals', app()->getLocale()) }}</option>
                                        </select>
                                        @error('type')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="filter-inputs mt-0 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ GoogleTranslate::trans('Sort by Countries', app()->getLocale()) }}:</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" wire:model="country">
                                                <option hidden>{{ GoogleTranslate::trans('Choose Countries', app()->getLocale()) }}</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country["id"] }}">{{ GoogleTranslate::trans( $country["name"], app()->getLocale()) }}</option>
                                                @endforeach
                                            </select>
                                            @error('country')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    @if ($type == 'business')
                                    <div class="form-group">
                                        <label>{{ GoogleTranslate::trans('Sort by Classifications', app()->getLocale()) }}:</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" wire:model="classification">
                                                <option hidden>
                                                    {{ GoogleTranslate::trans('Choose Classifications', app()->getLocale()) }}
                                                </option>
                                                <option value="">
                                                    {{ GoogleTranslate::trans('All', app()->getLocale()) }}</option>
                                                @foreach ($classifications as $classification)
                                                    <option value="{{ $classification->id }}">
                                                        {{ $classification->classifications }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ GoogleTranslate::trans('Sort by Business Groups', app()->getLocale()) }}:</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" wire:model="business_group">
                                                <option hidden>
                                                    {{ GoogleTranslate::trans('Choose Business Groups', app()->getLocale()) }}
                                                </option>
                                                {{-- <option value="">
                                                    {{ GoogleTranslate::trans('All', app()->getLocale()) }}</option> --}}
                                                @foreach ($business_groups as $business_group)
                                                    <option value="{{ $business_group->group }}">
                                                        {{ $business_group->group }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ GoogleTranslate::trans('Sort by Business Types', app()->getLocale()) }}:</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" wire:model="business_type">
                                                <option hidden>
                                                    {{ GoogleTranslate::trans('Choose Business Types', app()->getLocale()) }}
                                                </option>
                                                {{-- <option value="">
                                                    {{ GoogleTranslate::trans('All', app()->getLocale()) }}</option> --}}
                                                @foreach ($business_types as $business_type)
                                                    <option value="{{ $business_type->id }}">
                                                        {{ $business_type->type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label>{{ GoogleTranslate::trans('Sort by Year', app()->getLocale()) }}:</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" wire:model="year">
                                                <option hidden>{{ GoogleTranslate::trans('Choose Year', app()->getLocale()) }}</option>
                                                <option value="">{{ GoogleTranslate::trans('All', app()->getLocale()) }}</option>
                                                @foreach ($years as $year)
                                                    <option value="{{ $year->year }}">
                                                        {{ $year->year }}</option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
                                    @if ($type == 'patent')
                                    <div class="form-group">
                                        <label>{{ GoogleTranslate::trans('Sort by Category', app()->getLocale()) }}:</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" wire:model="classification">
                                                <option hidden>{{ GoogleTranslate::trans('Choose Category', app()->getLocale()) }}</option>
                                                <option value="">{{ GoogleTranslate::trans('All', app()->getLocale()) }}</option>
                                                @foreach ($classifications as $classification)
                                                    <option value="{{ $classification->id }}">
                                                        {{ $classification->classification_category }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ GoogleTranslate::trans('Sort by Patent Kind', app()->getLocale()) }}:</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" wire:model="patent_kind">
                                                <option hidden>{{ GoogleTranslate::trans('Choose Patent Kind', app()->getLocale()) }}</option>
                                                {{-- <option value="">{{ GoogleTranslate::trans('All', app()->getLocale()) }}</option> --}}
                                                @foreach ($patent_kinds as $patent_kind)
                                                    <option value="{{ $patent_kind->id }}">
                                                        {{ $patent_kind->kind }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ GoogleTranslate::trans('Sort by Patent Type', app()->getLocale()) }}:</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" wire:model="patent_type">
                                                <option hidden>{{ GoogleTranslate::trans('Choose Patent Type', app()->getLocale()) }}</option>
                                                {{-- <option value="">{{ GoogleTranslate::trans('All', app()->getLocale()) }}</option> --}}
                                                @foreach ($patent_types as $patent_type)
                                                    <option value="{{ $patent_type->id }}">
                                                        {{ $patent_type->type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label>{{ GoogleTranslate::trans('Sort by Year', app()->getLocale()) }}:</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" wire:model="year">
                                                <option hidden>{{ GoogleTranslate::trans('Choose Year', app()->getLocale()) }}</option>
                                                <option value="">{{ GoogleTranslate::trans('All', app()->getLocale()) }}</option>
                                                @foreach ($years as $year)
                                                    <option value="{{ $year->year }}">
                                                        {{ $year->year }}</option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
                                    @if ($type == 'journal')
                                    <div class="form-group">
                                        <label>{{ GoogleTranslate::trans('Sort by Category', app()->getLocale()) }}:</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" wire:model="classification">
                                                <option hidden>{{ GoogleTranslate::trans('Choose Category', app()->getLocale()) }}</option>
                                                <option value="">{{ GoogleTranslate::trans('All', app()->getLocale()) }}</option>
                                                @foreach ($classifications as $classification)
                                                    <option value="{{ $classification->id }}">
                                                        {{ $classification->category }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label>{{ GoogleTranslate::trans('Sort by Year', app()->getLocale()) }}:</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" wire:model="year">
                                                <option hidden>{{ GoogleTranslate::trans('Choose Year', app()->getLocale()) }}</option>
                                                <option value="">{{ GoogleTranslate::trans('All', app()->getLocale()) }}</option>
                                                @foreach ($years as $year)
                                                    <option value="{{ $year->year }}">
                                                        {{ $year->year }}</option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif
                                    <a class="btn btn-sm btn-success float-end" wire:click="filterSubmit()">{{ GoogleTranslate::trans('Submit', app()->getLocale()) }}</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <span
                                        class="data-report-count mr-2">{{ GoogleTranslate::trans('About ' . $results . ' results.', app()->getLocale()) }}</span>
                                    <a href="{{ route('client.report') }}" class="view-report pull-right"
                                        id="view-report-element"
                                        target="_blank">{{ GoogleTranslate::trans('Show Report', app()->getLocale()) }}</a>
                                </div>
                            </div>

                            <hr class="mb-2">

                            <div class="row">
                                <div class="col-md-12 mt-3 mb-3">
                                    <div wire:ignore>
                                        {{ GoogleTranslate::trans('PAGE', app()->getLocale()) }}: <span
                                            id="page"></span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <a href="javascript:prevPage()" class="btn btn-xs btn-default"
                                            id="btn_prev">{{ GoogleTranslate::trans('Prev', app()->getLocale()) }}</a>
                                        <a href="javascript:nextPage()" class="btn btn-xs btn-default pull-right"
                                            id="btn_next">{{ GoogleTranslate::trans('Next', app()->getLocale()) }}</a>
                                    </div>
                                </div>
                            </div>

                            <div id="accordion" wire:ignore>
                                {{-- @if (array_key_exists('features', $businessResults))
                                    @foreach ($businessResults['features'] as $businessResult)
                                        <div class="card card-secondary">
                                            <div class="card-header" style="border-radius: 0;">
                                                <h4 class="card-title w-100">
                                                    <a class="d-block w-100" data-toggle="collapse"
                                                        href="#result{{ $businessResult['properties']['locationId'] }}">
                                                        {{ $businessResult['properties']['company_name'] }}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="result{{ $businessResult['properties']['locationId'] }}"
                                                class="collapse {{ $loop->index == 0 ? 'show' : '' }}"
                                                data-parent="#accordion" wire:ignore.self>
                                                <div class="card-body">
                                                    <p><strong>NGC Code:</strong>
                                                        {{ $businessResult['properties']['ngc_code'] }}</p>
                                                    <p><strong>Date Registered:</strong>
                                                        {{ $businessResult['properties']['date_registerd'] }}</p>
                                                    <p><strong>Address:</strong>
                                                        {{ $businessResult['properties']['address'] }}</p>
                                                    <p><strong>Business Type:</strong>
                                                        {{ $businessResult['properties']['business_type'] }}</p>
                                                        <button class="btn btn-danger btn-sm fly-over-btn"
                                                            wire:click="handleFlyOver({{ $businessResult['geometry']['coordinates'][0] }}, {{ $businessResult['geometry']['coordinates'][1] }})"
                                                            data-lat="{{ $businessResult['geometry']['coordinates'][0] }}"
                                                            data-long="{{ $businessResult['geometry']['coordinates'][1] }}">Show
                                                            in map</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif --}}

                                {{-- @if (array_key_exists('features', $patentResults))
                                    @foreach ($patentResults['features'] as $patentResult)
                                        <div class="card card-secondary">
                                            <div class="card-header">
                                                <h4 class="card-title w-100">
                                                    <a class="d-block w-100" data-toggle="collapse"
                                                        href="#result{{ $patentResult['properties']['id'] }}">
                                                        {{ $patentResult['properties']['title'] }}
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="result{{ $patentResult['properties']['id'] }}"
                                                class="collapse {{ $loop->index == 0 ? 'show' : '' }}"
                                                data-parent="#accordion" wire:ignore.self>
                                                <div class="card-body">
                                                    <p><strong>Patent Id:</strong>
                                                        {{ $patentResult['properties']['patent_id'] }}</p>
                                                    <p><strong>Date Registered:</strong>
                                                        {{ $patentResult['properties']['date_registerd'] }}</p>
                                                        <button class="btn btn-danger btn-sm fly-over-btn"
                                                            data-lat="{{ $patentResult['geometry']['coordinates'][0] }}"
                                                            data-long="{{ $patentResult['geometry']['coordinates'][1] }}">Show
                                                            in map</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif --}}
                            </div>
                        </div>
                        {{-- <a id="filter-toggle" href="#" class="btn toggle square"><i class="fas fa-chart-bar fa-lg"
                                aria-hidden="true"></i>
                            {{ GoogleTranslate::trans('Data Report', app()->getLocale()) }}</a>
                        <div id="filter-wrapper" wire:ignore.self class="overlay-scroll active">
                            <a id="close-filter" href="#" class="toggle square-close"><i
                                    class="fa fa-times fa-lg"></i></a>
                            <div id="countryChart" wire:ignore></div>
                        </div> --}}
                    </div>
                    {{-- <div class="col-12 col-sm-12 p-3 scroll-element" id="reportSection" wire:ignore>
                        @livewire('client.report.report-component')
                    </div> --}}
                </div>
            </div>
        </section>
    </div>

</div>


@push('extra-scripts')
    <!-- mapbox -->
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.js"></script>
    <!-- geocoder -->
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>

    <script src="https://unpkg.com/supercluster@7.1.2/dist/supercluster.min.js"></script>

    <!-- MAPBOX SCRIPTS -->
    <script>
        var currentMarkers = [];
        var map;
        var density_map;
        var geoLocations;
        var loadLocations;
        var businessChunkedData = 0;
        var current_page = 1;
        var records_per_page = 20;
        var mergedData = [];

        function prevPage() {
            if (current_page > 1) {
                current_page--;
                changePage(current_page);
            }
        }

        function nextPage() {
            if (current_page < numPages()) {
                current_page++;
                changePage(current_page);
            }
        }

        function changePage(page) {
            var btn_next = document.getElementById("btn_next");
            var btn_prev = document.getElementById("btn_prev");
            var listing_table = document.getElementById("accordion");
            var page_span = document.getElementById("page");

            // Validate page
            if (page < 1) page = 1;
            if (page > numPages()) page = numPages();

            listing_table.innerHTML = "";
            if (mergedData.length > 0) {
                for (var i = (page - 1) * records_per_page; i < (page * records_per_page) && i < mergedData.length; i++) {
                    listing_table.innerHTML +=
                        `
                    <div class="card card-secondary">
                        <div class="card-header" style="border-radius: 0;">
                            <h4 class="card-title w-100">
                                <a class="d-block w-100" data-toggle="collapse"
                                    href="#business-${(mergedData[i].properties.locationId !== undefined) ? mergedData[i].properties.locationId : ("patent-" + mergedData[i].properties.id)}">
                                    ${mergedData[i].properties.company_name}
                                </a>
                            </h4>
                        </div>
                        <div id="business-${(mergedData[i].properties.locationId !== undefined) ? mergedData[i].properties.locationId : ("patent-" + mergedData[i].properties.id)}"
                            class="collapse"
                            data-parent="#accordion" wire:ignore.self>
                            <div class="card-body">
                                <button class="btn btn-danger btn-sm fly-over-btn"
                                    data-lat="${mergedData[i].geometry.coordinates[0]}"
                                    data-long="${mergedData[i].geometry.coordinates[1]}">Show
                                    in map</button>
                            </div>
                        </div>
                    </div>
                    `;
                }
            }

            var showInMapButtons = document.getElementsByClassName('fly-over-btn');
            for (let i = 0; i < showInMapButtons.length; i++) {
                showInMapButtons[i].addEventListener("click", function() {
                    map.flyTo({
                        center: [this.dataset.lat, this.dataset.long, ],
                        essential: true, // this animation is considered essential with respect to prefers-reduced-motion
                        zoom: 18
                    });
                })
            }

            page_span.innerHTML = page + "/" + numPages();

            if (page == 1) {
                btn_prev.style.visibility = "hidden";
            } else {
                btn_prev.style.visibility = "visible";
            }

            if (page == numPages()) {
                btn_next.style.visibility = "hidden";
            } else {
                btn_next.style.visibility = "visible";
            }
        }


        function numPages() {
            return Math.ceil(mergedData.length / records_per_page);
        }


        window.addEventListener("DOMContentLoaded", handleWindowLoad, true);

        function handleWindowLoad(event) {
            console.log("handleWindowLoad");
            mapboxgl.accessToken = "{{ env('MAPBOX_KEY') }}";
            map = new mapboxgl.Map({
                container: "map", // container ID
                style: "{{ env('MAPBOX_STYLE') }}", // style URL
                center: [111.09841688936865, 2.37304225637002], // starting position [lng, lat]
                zoom: 5, // starting zoom
                projection: "equirectangular", // display map style
                bearing: -17.6,
                antialias: true,
                maxBounds: [
                    [91.56216158463567, -10.491532410391958],
                    [141.79211516906793, 27.60302090835848]
                ] // Set the map's geographical boundaries.
            });
            density_map = new mapboxgl.Map({
                container: "density-map", // container ID
                style: "{{ env('MAPBOX_STYLE') }}", // style URL
                center: [111.09841688936865, 2.37304225637002], // starting position [lng, lat]
                zoom: 6, // starting zoom
                minZoom: 6,
                projection: "equirectangular", // display map style
                bearing: -17.6,
                antialias: true,
                maxBounds: [
                    [91.56216158463567, -10.491532410391958],
                    [141.79211516906793, 27.60302090835848]
                ] // Set the map's geographical boundaries.
            });


            const zoomThreshold = 4;

            // density_map.on('load', () => {


                // density_map.addLayer({
                //     'id': 'poi-labels',
                //     'type': 'symbol',
                //     'source': 'population',
                //     'source-layer': "ph-region-4myng8",
                //     'layout': {
                //         'text-field': ['get', 'name'],
                //         'text-variable-anchor': ['top', 'bottom', 'left', 'right'],
                //         'text-radial-offset': 0.5,
                //         'text-justify': 'auto'
                //     }
                // });
            // });

            // var nav = map.addControl(new mapboxgl.AttributionControl(), 'bottom-left');

            var nav = new mapboxgl.NavigationControl(); // position is optional
            map.addControl(nav, 'bottom-left');

            nav._container.parentNode.className = "mapboxgl-ctrl-center"

            map.loadImage(
                '/light-bulb.png',
                (error, image) => {
                    if (error) throw error;
                    map.addImage('custom-marker', image);
                });
            map.loadImage(
                '/bookmark.png',
                (error, image) => {
                    if (error) throw error;
                    map.addImage('custom-marker-bookmark', image);
                });
            // add3dLayer();
        }
        document.addEventListener("livewire:load", handleLivewireLoad, true);

        function add3dLayer() {
            const layers = map.getStyle().layers;
            const labelLayerId = layers.find(
                (layer) => layer.type === 'symbol' && layer.layout['text-field']
            ).id;
            map.addLayer({
                    'id': 'add-3d-buildings',
                    'source': 'composite',
                    'source-layer': 'building',
                    'filter': ['==', 'extrude', 'true'],
                    'type': 'fill-extrusion',
                    'minzoom': 15,
                    'paint': {
                        'fill-extrusion-color': '#aaa',

                        // Use an 'interpolate' expression to
                        // add a smooth transition effect to
                        // the buildings as the user zooms in.
                        'fill-extrusion-height': [
                            'interpolate',
                            ['linear'],
                            ['zoom'],
                            15,
                            0,
                            15.05,
                            ['get', 'height']
                        ],
                        'fill-extrusion-base': [
                            'interpolate',
                            ['linear'],
                            ['zoom'],
                            15,
                            0,
                            15.05,
                            ['get', 'min_height']
                        ],
                        'fill-extrusion-opacity': 0.6
                    }
                },
                labelLayerId
            );
        }

        function addBusinessHeat(sourceId) {
            map.addLayer({
                'id': 'business-heat',
                'type': 'heatmap',
                'source': sourceId,
                'maxzoom': 9,
                'paint': {
                    // Increase the heatmap weight based on frequency and property magnitude
                    'heatmap-weight': [
                        'interpolate',
                        ['linear'],
                        ['get', 'mag'],
                        0,
                        0,
                        6,
                        1
                    ],
                    // Increase the heatmap color weight weight by zoom level
                    // heatmap-intensity is a multiplier on top of heatmap-weight
                    'heatmap-intensity': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        0,
                        1,
                        9,
                        3
                    ],
                    // Color ramp for heatmap.  Domain is 0 (low) to 1 (high).
                    // Begin color ramp at 0-stop with a 0-transparancy color
                    // to create a blur-like effect.
                    'heatmap-color': [
                        'interpolate',
                        ['linear'],
                        ['heatmap-density'],
                        0,
                        'rgba(183, 28, 28, 0)',
                        0.2,
                        'rgba(183, 28, 28, 0.5)',
                        0.4,
                        'rgba(183, 28, 28, 0.20)',
                        0.6,
                        'rgba(183, 28, 28, 0.15)',
                        0.8,
                        'rgba(183, 28, 28, 0.20)',
                        1,
                        'rgba(183, 28, 28, 0.25)'
                    ],
                    // Adjust the heatmap radius by zoom level
                    'heatmap-radius': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        0,
                        2,
                        9,
                        20
                    ],
                    // Transition from heatmap to circle layer by zoom level
                    'heatmap-opacity': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        7,
                        1,
                        9,
                        0
                    ]
                }
            }, );
        }

        function addPatentHeat() {
            map.addLayer({
                'id': 'patent-heat',
                'type': 'heatmap',
                'source': 'patent',
                'maxzoom': 9,
                'paint': {
                    // Increase the heatmap weight based on frequency and property magnitude
                    'heatmap-weight': [
                        'interpolate',
                        ['linear'],
                        ['get', 'mag'],
                        0,
                        0,
                        6,
                        1
                    ],
                    // Increase the heatmap color weight weight by zoom level
                    // heatmap-intensity is a multiplier on top of heatmap-weight
                    'heatmap-intensity': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        0,
                        1,
                        9,
                        3
                    ],
                    // Color ramp for heatmap.  Domain is 0 (low) to 1 (high).
                    // Begin color ramp at 0-stop with a 0-transparancy color
                    // to create a blur-like effect.
                    'heatmap-color': [
                        'interpolate',
                        ['linear'],
                        ['heatmap-density'],
                        0,
                        'rgba(255, 214, 0, 0)',
                        0.2,
                        'rgba(255, 214, 0, 0.5)',
                        0.4,
                        'rgba(255, 214, 0, 0.10)',
                        0.6,
                        'rgba(255, 214, 0, 0.15)',
                        0.8,
                        'rgba(255, 214, 0, 0.20)',
                        1,
                        'rgba(255, 214, 0, 0.25)'
                    ],
                    // Adjust the heatmap radius by zoom level
                    'heatmap-radius': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        0,
                        2,
                        9,
                        20
                    ],
                    // Transition from heatmap to circle layer by zoom level
                    'heatmap-opacity': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        7,
                        1,
                        9,
                        0
                    ]
                }
            }, );
        }

        function addJournalHeat() {
            map.addLayer({
                'id': 'journal-heat',
                'type': 'heatmap',
                'source': 'journal',
                'maxzoom': 9,
                'paint': {
                    // Increase the heatmap weight based on frequency and property magnitude
                    'heatmap-weight': [
                        'interpolate',
                        ['linear'],
                        ['get', 'mag'],
                        0,
                        0,
                        6,
                        1
                    ],
                    // Increase the heatmap color weight weight by zoom level
                    // heatmap-intensity is a multiplier on top of heatmap-weight
                    'heatmap-intensity': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        0,
                        1,
                        9,
                        3
                    ],
                    // Color ramp for heatmap.  Domain is 0 (low) to 1 (high).
                    // Begin color ramp at 0-stop with a 0-transparancy color
                    // to create a blur-like effect.
                    'heatmap-color': [
                        'interpolate',
                        ['linear'],
                        ['heatmap-density'],
                        0,
                        'rgba(1, 87, 155, 0)',
                        0.2,
                        'rgba(1, 87, 155, 0.5)',
                        0.4,
                        'rgba(1, 87, 155, 0.10)',
                        0.6,
                        'rgba(1, 87, 155, 0.15)',
                        0.8,
                        'rgba(1, 87, 155, 0.20)',
                        1,
                        'rgba(1, 87, 155, 0.25)'
                    ],
                    // Adjust the heatmap radius by zoom level
                    'heatmap-radius': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        0,
                        2,
                        9,
                        20
                    ],
                    // Transition from heatmap to circle layer by zoom level
                    'heatmap-opacity': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        7,
                        1,
                        9,
                        0
                    ]
                }
            }, );
        }

        function addBusinessPoint(sourceId) {
            map.addLayer({
                'id': 'business-point' + sourceId,
                'type': 'circle',
                'source': sourceId,
                'minzoom': 0,
                'paint': {
                    'circle-radius': {
                        'base': 1.75,
                        'stops': [
                            [0, 1.5],
                            [12, 2.5],
                            [15, 8],
                            [18, 12]
                        ]
                    },
                    'circle-color': "rgba(242, 94, 94, 1)",
                    // 'circle-stroke-color': 'white',
                    // 'circle-stroke-width': 0.5
                }
            }, );

            map.on('click', 'business-point' + sourceId, (event) => {
                coordinates = event.features[0].geometry.coordinates;
                @this.getBusinessDataFromId(event.features[0].properties.locationId).then((businessData) => {
                    const content =
                        `<div class="card card-secondary popUp-content">
                            <div class="card-header">
                                <h3 class="card-title">${businessData.company_name}</h3>
                            </div>

                            <div class="card-body">
                                <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                                <p class="text-muted">
                                    ${businessData.address}
                                </p>

                                <hr>

                                <strong><i class="fas fa-book mr-1"></i> Date Registerd</strong>
                                <p class="text-muted">${businessData.date_registerd}</p>

                                <hr>

                                <strong><i class="fas fa-pencil-alt mr-1"></i> Business Type & Ind. Classification</strong>
                                <p class="text-muted">
                                    <p class="badge badge-md badge-info cursor-pointer" title="Business Type">${businessData.business_type}</p>
                                    <p class="badge badge-md badge-primary cursor-pointer" title="Industry Classification">${businessData.industry_classification}</p>
                                </p>

                                <hr>

                                <strong><i class="far fa-file-alt mr-1"></i> Industry Description</strong>
                                <p class="text-muted">${businessData.industry_description}</p>
                            </div>
                        </div>`;
                    new mapboxgl.Popup()
                        .setLngLat(coordinates)
                        .setHTML(content)
                        .addTo(map);
                })


            });
        }

        function addPatentPoint() {
            map.addLayer({
                'id': 'patent-heat-point',
                'type': 'circle',
                'source': 'patent',
                'minzoom': 0,
                'paint': {
                    'circle-radius': {
                        'base': 1.75,
                        'stops': [
                            [0, 2],
                            [12, 3],
                            [15, 8],
                            [16, 0],
                        ]
                    },
                    'circle-color': "rgba(242, 210, 46, 1)",
                    // 'circle-stroke-color': 'white',
                    // 'circle-stroke-width': 1
                },
            }, );
            map.addLayer({
                'id': 'patent-point',
                // 'type': 'circle',
                'type': 'symbol',
                'source': 'patent',
                'minzoom': 15,
                // 'paint': {
                //     'circle-radius': 8,
                //     'circle-color': "rgba(183, 28, 28, 0.85)",
                //     'circle-stroke-color': 'white',
                //     'circle-stroke-width': 1
                // },
                'layout': {
                    'icon-image': 'custom-marker',
                    'icon-size': 0.4,
                }
            }, );

            map.on('click', 'patent-point', (event) => {
                coordinates = event.features[0].geometry.coordinates;
                @this.getPatentDataFromId(event.features[0].properties.id).then((patentData) => {
                    const content =
                        `<div class="card card-secondary popUp-content">
                            <div class="card-header">
                                <h3 class="card-title">${patentData.title}</h3>
                            </div>

                            <div class="card-body">
                                <strong><i class="fas fa-book mr-1"></i> Date Registerd</strong>
                                <p class="text-muted">${patentData.date}</p>

                                <hr>
                            </div>
                        </div>`
                    new mapboxgl.Popup()
                        .setLngLat(coordinates)
                        .setHTML(content)
                        .addTo(map);
                })

            });
        }

        function addJournalPoint() {
            map.addLayer({
                'id': 'journal-heat-point',
                'type': 'circle',
                'source': 'journal',
                'minzoom': 0,
                'paint': {
                    'circle-radius': {
                        'base': 1.75,
                        'stops': [
                            [0, 3],
                            [12, 3],
                            [15, 8],
                            [16, 0],
                        ]
                    },
                    'circle-color': "rgba(82, 136, 242, 1)",
                    // 'circle-stroke-color': 'white',
                    // 'circle-stroke-width': 1
                },
            }, );
            map.addLayer({
                'id': 'journal-point',
                // 'type': 'circle',
                'type': 'symbol',
                'source': 'journal',
                'minzoom': 8,
                // 'paint': {
                //     'circle-radius': 8,
                //     'circle-color': "rgba(183, 28, 28, 0.85)",
                //     'circle-stroke-color': 'white',
                //     'circle-stroke-width': 1
                // },
                'layout': {
                    'icon-image': 'custom-marker-bookmark',
                    'icon-size': 0.5
                }
            }, );

            map.on('click', 'journal-point', (event) => {
                coordinates = event.features[0].geometry.coordinates;
                @this.getJournalDataFromId(event.features[0].properties.id).then((journalData) => {
                    const content =
                        `<div class="card card-secondary popUp-content">
                            <div class="card-header">
                                <h3 class="card-title">${journalData.title}</h3>
                            </div>

                            <div class="card-body">
                                <strong><i class="fas fa-book mr-1"></i>Author Name</strong>
                                <p class="text-muted">${journalData.author_name}</p>

                                <hr>

                                <strong><i class="fas fa-book mr-1"></i>Publisher Name</strong>
                                <p class="text-muted">${journalData.publisher_name}</p>

                                <hr>

                                <strong><i class="fas fa-book mr-1"></i>Published Year</strong>
                                <p class="text-muted">${journalData.year}</p>
                                <hr>
                                <strong><i class="fas fa-book mr-1"></i>ISSN No</strong>
                                <p class="text-muted">${journalData.issn_no}</p>
                                <hr>
                                <strong><i class="fas fa-book mr-1"></i>Citition No</strong>
                                <p class="text-muted">${journalData.citition_no}</p>
                                <hr>
                            </div>
                        </div>`
                    new mapboxgl.Popup()
                        .setLngLat(coordinates)
                        .setHTML(content)
                        .addTo(map);
                });

            });
        }

        function handleLivewireLoad() {
            console.log("handleLivewireLoad");
            // Livewire.emit('mapFirstLoad');
        }

        Livewire.on('mapUpdated', (data) => {
            try {
                // var mapLayer = map.getLayer('business-heat');
                // if (typeof mapLayer !== "undefined") {
                //     map.removeLayer('business-heat').removeSource('businessHeatData');
                // }
                for (let index = 0; index < businessChunkedData; index++) {
                    var mapLayerTemp = map.getLayer('business-point' + 'business' + index);
                    if (typeof mapLayerTemp !== 'undefined') {
                        map.removeLayer('business-point' + 'business' + index).removeSource('business' + index);
                    }
                }

                var mapLayer = map.getLayer('patent-heat-point');
                if (typeof mapLayer !== 'undefined') {
                    map.removeLayer('patent-heat-point').removeLayer('patent-point').removeSource('patent');
                }

                var mapLayer = map.getLayer('journal-heat-point');
                if (typeof mapLayer !== 'undefined') {
                    map.removeLayer('journal-heat-point').removeLayer('journal-point').removeSource('journal');
                }

                var mapLayer = map.getLayer('journal-heat-point');
                if (typeof mapLayer !== 'undefined') {
                    map.removeLayer('journal-heat-point').removeLayer('journal-point').removeSource('journal');
                }
            } catch (error) {

            }
            businessChunkedData = data.geoJson.length;
            mergedDataBusiness = [...new Set([].concat(...data.geoJson.map((element) => element.features)))];
            mergedDataPatent = data.patentJson.features;
            mergedDataJournal = data.journalJson.features;
            // mergedDataPatent = [...new Set([].concat(...data.patentJson.features))];
            // mergedDataJournal = [...new Set([].concat(...data.journalJson.features))];
            mergedData = mergedDataBusiness.concat(mergedDataPatent).concat(mergedDataJournal);
            changePage(1);

            if (data.geoJson != null) {
                // map.addSource('businessHeatData', {
                //     'type': 'geojson',
                //     'data': {
                //         'type': 'FeatureCollection',
                //         'features': mergedDataBusiness
                //     }
                // });
                // addBusinessHeat('businessHeatData');
                for (let index = 0; index < data.geoJson.length; index++) {
                    map.addSource('business' + index, {
                        'type': 'geojson',
                        'data': data.geoJson[index]
                    });
                    addBusinessPoint('business' + index);
                }
            }

            if (data.patentJson != null) {
                map.addSource('patent', {
                    'type': 'geojson',
                    'data': data.patentJson
                });
                // addPatentHeat();
                addPatentPoint();
            }

            if (data.journalJson != null) {
                map.addSource('journal', {
                    'type': 'geojson',
                    'data': data.journalJson
                });
                // addJournalHeat();
                addJournalPoint();
            }
        });

        Livewire.on('densityMapUpdated', (data) => {

            var mapLayer = density_map.getLayer('state-business-density');
            if (typeof mapLayer !== 'undefined') {
                density_map.removeLayer('state-business-density');
            }

            var mapLayer = density_map.getSource('business-density');
            if (typeof mapLayer !== 'undefined') {
                density_map.removeSource('business-density');
            }

            density_map.addSource('business-density', {
                    type: 'vector',
                    url: 'mapbox://kurmatech.7s6qx4no'
                    // 'type': 'geojson',
                    // 'data': testData
                });

            const zoomThreshold = 4;

            const matchExpression = ['match', ['get', 'long']];
            for (const row of data.densityBusinessData) {
                // Convert the range of data values to a suitable color
                const red = Math.floor((row['count'] / 20000) * 1000);
                if (row['count'] > 0 && row['count'] <= 500){
                    var color = '#F2F12D';
                }else if (row['count'] > 500 && row['count'] <= 750){
                    var color = '#E6B71E';
                }else if (row['count'] > 750 && row['count'] <= 1000){
                    var color = '#DA9C20';
                }else if (row['count'] > 1000 && row['count'] <= 2500){
                    var color = '#CA8323';
                }else if (row['count'] > 2500 && row['count'] <= 5000){
                    var color = '#B86B25';
                }else if (row['count'] > 5000 && row['count'] <= 7500){
                    var color = '#A25626';
                }else if (row['count'] > 7500 && row['count'] <= 10000){
                    var color = '#8B4225';
                }else if (row['count'] > 10000){
                    var color = '#723122';
                }else{
                    const color = `(0, 0, 0)`;
                }
                matchExpression.push(row['name'], color);
            }

            matchExpression.push('rgba(0, 0, 0, 0)');

            if (!(data.densityBusinessData <= 0)){
                density_map.addLayer({
                    'id': 'state-business-density',
                    'source': 'business-density',
                    'source-layer': "ph-region-4myng8",
                    // 'maxzoom': zoomThreshold,
                    'type': 'fill',
                    // only include features for which the "isState"
                    // property is "true"
                    // 'paint': {
                    //     'fill-color': '#0080ff', // blue color fill
                    //     'fill-opacity': 0.5
                    // }
                    // 'filter': ['==', 'isState', true],

                    'paint': {
                        'fill-color': matchExpression,
                        'fill-opacity': 0.60
                    }
                }, );
            }



            
                // density_map.addLayer({
                //     'id': 'poi-labels',
                //     'type': 'symbol',
                //     'source': 'population',
                //     'source-layer': "ph-region-4myng8",
                //     'layout': {
                //         'text-field': ['get', 'name'],
                //         'text-variable-anchor': ['top', 'bottom', 'left', 'right'],
                //         'text-radial-offset': 0.5,
                //         'text-justify': 'auto'
                //     }
                // });
        });

        Livewire.on('loader_on', () => {
            console.log("on");
            document.getElementById('loader').style.display = 'flex';
        });
        Livewire.on('loader_off', () => {
            console.log("off");
            document.getElementById('loader').style.display = 'none';
        });
        Livewire.on('map_changed', () => {
            map.resize();
            density_map.resize();
        });
        Livewire.on('flyover', (data) => {
            map.flyTo({
                center: [data.long, data.lat],
                essential: true, // this animation is considered essential with respect to prefers-reduced-motion
                zoom: 15
            });
            // map.fireEvent('click', {latlng: L.latLng(data.lat, data.long)});
        });
    </script>
    <!-- MAPBOX CUSTOMS -->
    {{-- <script src="{{asset('client/dist/js/mapbox-main.js')}}"></script> --}}
@endpush
