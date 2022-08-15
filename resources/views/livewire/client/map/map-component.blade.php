@push('extra-styles')
    <!-- mapbox -->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css">
    <!-- geocoder -->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css"
        type="text/css">
    <style>
        #map {
            height: 75vh;
            width: 100%;
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
    </style>
@endpush

<div>
    <div class="card">
        {{-- <div class="card-header bg-dark text-white py-1">
            <h2 class="text-md m-0 p-0">ASEAN MAP</h2>
        </div> --}}
        <div class="card-body p-1">
            <div wire:ignore id="map"></div>
        </div>
    </div>
</div>

@push('extra-scripts')
    <!-- mapbox -->
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js"></script>
    <!-- geocoder -->
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"></script>

    <script src="https://unpkg.com/supercluster@7.1.2/dist/supercluster.min.js"></script>

    <!-- MAPBOX SCRIPTS -->
    <script>
        var currentMarkers = [];
        var map;
        var geoLocations;
        var loadLocations;
        document.addEventListener('livewire:load', () => {
            geoLocations = {!! $geoJson !!}
            // mapbox key
            mapboxgl.accessToken = "{{ env('MAPBOX_KEY') }}";

            // mapbox setting
            map = new mapboxgl.Map({
                container: "map", // container ID
                style: "{{ env('MAPBOX_STYLE') }}", // style URL
                center: [111.09841688936865, 2.37304225637002], // starting position [lng, lat]
                zoom: 3.69, // starting zoom
                projection: "equirectangular" // display the map as a 3D globe
            });

            map.on('moveend', () => {
                // console.log(currentMarkers);
                currentMarkers.forEach((marker) => marker.remove())
                loadLocations(geoLocations);
            });

            

            loadLocations = (geoJson) => {

                // console.log(geoJson.features)
                let index;
                index = new Supercluster({
                    radius: 60,
                    extent: 256,
                    maxZoom: 16
                }).load(geoJson.features);

                const bounds = map.getBounds();

                var clusters = index.getClusters([bounds.getWest(), bounds.getSouth(), bounds.getEast(), bounds
                    .getNorth()
                ], parseInt(map.getZoom()));

                clusters.forEach((location) => {
                    const {
                        geometry,
                        properties
                    } = location
                    const {
                        iconSize,
                        locationId,
                        company_name,
                        date_registerd,
                        ngc_code,
                        address,
                        business_type,
                        industry_classification,
                        industry_description
                    } = properties

                    let markerElement = document.createElement('div')
                    markerElement.className = 'marker-style'
                    markerElement.id = locationId
                    markerElement.style.color = 'antiquewhite'
                    markerElement.style.border = '1.2px solid antiquewhite'
                    markerElement.style.borderRadius = '50%'
                    markerElement.innerHTML = properties.point_count_abbreviated

                    const count = properties.point_count;
                    if(count > 1000) {
                        markerElement.style.backgroundColor = '#ff7407'
                        markerElement.style.width = '50px'
                        markerElement.style.height = '50px'
                    }else if(count > 100) {
                        markerElement.style.backgroundColor = '#ffc107'
                        markerElement.style.width = '30px'
                        markerElement.style.height = '30px'
                    }else {
                        markerElement.style.backgroundColor = '#007bff'
                        markerElement.style.width = '21px'
                        markerElement.style.height = '21px'
                    }
                    

                    const content =
                        `<div class="card card-secondary popUp-content">
                        <div class="card-header">
                            <h3 class="card-title">${company_name}</h3>
                        </div>
                        
                        <div class="card-body">
                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                            <p class="text-muted">
                                ${address}
                            </p>

                            <hr>

                            <strong><i class="fas fa-book mr-1"></i> Date Registerd</strong>
                            <p class="text-muted">${date_registerd}</p>

                            <hr>

                            <strong><i class="fas fa-pencil-alt mr-1"></i> Business Type & Ind. Classification</strong>
                            <p class="text-muted">
                                <p class="badge badge-md badge-info cursor-pointer" title="Business Type">${business_type}</p>
                                <p class="badge badge-md badge-primary cursor-pointer" title="Industry Classification">${industry_classification}</p>
                            </p>

                            <hr>

                            <strong><i class="far fa-file-alt mr-1"></i> Industry Description</strong>
                            <p class="text-muted">${industry_description}</p>
                        </div>
                    </div>`

                    const popUp = new mapboxgl.Popup({
                        offset: 24
                    }).setHTML(content).setMaxWidth("400px")
    
                    if (properties.point_count_abbreviated) {
                        var marker = new mapboxgl.Marker(markerElement)
                            .setLngLat(geometry.coordinates)
                            // .setPopup(popUp)
                            .addTo(map);
                    }else{
                        var marker = new mapboxgl.Marker()
                            .setLngLat(geometry.coordinates)
                            .setPopup(popUp)
                            .addTo(map);
                    }
                    currentMarkers.push(marker);
                })
            }

            loadLocations(geoLocations)

            map.on('click', (e) => {
                const longtitude = e.lngLat.lng
                const lattitude = e.lngLat.lat

                @this.long = longtitude
                @this.lat = lattitude
            })
        })

        Livewire.on('mapUpdated', (data) => {
            geoLocations = data;
            currentMarkers.forEach((marker) => marker.remove())
            loadLocations(geoLocations);
        })
    </script>
    <!-- MAPBOX CUSTOMS -->
    {{-- <script src="{{asset('client/dist/js/mapbox-main.js')}}"></script> --}}
@endpush
