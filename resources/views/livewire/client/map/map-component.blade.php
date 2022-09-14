@push('extra-styles')
    <!-- mapbox -->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.css">
    <!-- geocoder -->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css"
        type="text/css">
    <style>
        #map {
            height: 90vh;
            width: 100%;
            position: relative;
        }

        #loader {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
            display: none;
            justify-content: center;
            align-items: center;
        }

        #loader img {
            width: 34px;
            height: 34px;
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
    </style>
@endpush

<div>
    <div id="map" wire:ignore>
        <div id="loader"><img src="loader.gif" alt="loader"></div>
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
        var geoLocations;
        var loadLocations;

        window.addEventListener("DOMContentLoaded", handleWindowLoad, true);

        function handleWindowLoad(event) {
            console.log("handleWindowLoad");
            mapboxgl.accessToken = "{{ env('MAPBOX_KEY') }}";
            map = new mapboxgl.Map({
                container: "map", // container ID
                style: "{{ env('MAPBOX_STYLE') }}", // style URL
                center: [111.09841688936865, 2.37304225637002], // starting position [lng, lat]
                zoom: 5, // starting zoom
                projection: "equirectangular", // display the map as a 3D globe
                maxBounds: [
                    [91.56216158463567, -10.491532410391958],
                    [141.79211516906793, 27.60302090835848]
                ] // Set the map's geographical boundaries.
            });
            console.log(map);
        }
        document.addEventListener("livewire:load", handleLivewireLoad, true);

        function addBusinessHeat() {
            map.addLayer({
                'id': 'business-heat',
                'type': 'heatmap',
                'source': 'business',
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
                        'rgba(183, 28, 28, 0)',
                        0.2,
                        'rgba(183, 28, 28, 0.5)',
                        0.4,
                        'rgba(183, 28, 28, 0.10)',
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

        function addBusinessPoint() {
            map.addLayer({
                'id': 'business-point',
                'type': 'circle',
                'source': 'business',
                'minzoom': 7,
                'paint': {
                    'circle-radius': {
                        'base': 1.75,
                        'stops': [
                            [12, 5],
                            [15, 12]
                        ]
                    },
                    'circle-color': "rgba(255, 214, 0, 0.85)",
                    'circle-stroke-color': 'white',
                    'circle-stroke-width': 1
                }
            }, );

            map.on('click', 'business-point', (event) => {
                const content =
                    `<div class="card card-secondary popUp-content">
                            <div class="card-header">
                                <h3 class="card-title">${event.features[0].properties.company_name}</h3>
                            </div>

                            <div class="card-body">
                                <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                                <p class="text-muted">
                                    ${event.features[0].properties.address}
                                </p>

                                <hr>

                                <strong><i class="fas fa-book mr-1"></i> Date Registerd</strong>
                                <p class="text-muted">${event.features[0].properties.date_registerd}</p>

                                <hr>

                                <strong><i class="fas fa-pencil-alt mr-1"></i> Business Type & Ind. Classification</strong>
                                <p class="text-muted">
                                    <p class="badge badge-md badge-info cursor-pointer" title="Business Type">${event.features[0].properties.business_type}</p>
                                    <p class="badge badge-md badge-primary cursor-pointer" title="Industry Classification">${event.features[0].properties.industry_classification}</p>
                                </p>

                                <hr>

                                <strong><i class="far fa-file-alt mr-1"></i> Industry Description</strong>
                                <p class="text-muted">${event.features[0].properties.industry_description}</p>
                            </div>
                        </div>`
                new mapboxgl.Popup()
                    .setLngLat(event.features[0].geometry.coordinates)
                    .setHTML(content)
                    .addTo(map);
            });
        }

        function addPatentPoint() {
            map.addLayer({
                'id': 'patent-point',
                'type': 'circle',
                'source': 'patent',
                'minzoom': 7,
                'paint': {
                    'circle-radius': 8,
                    'circle-color': "rgba(183, 28, 28, 0.85)",
                    'circle-stroke-color': 'white',
                    'circle-stroke-width': 1
                }
            }, );

            map.on('click', 'patent-point', (event) => {
                const content =
                    `<div class="card card-secondary popUp-content">
                            <div class="card-header">
                                <h3 class="card-title">${event.features[0].properties.title}</h3>
                            </div>

                            <div class="card-body">
                                <strong><i class="fas fa-book mr-1"></i> Date Registerd</strong>
                                <p class="text-muted">${event.features[0].properties.date}</p>

                                <hr>
                            </div>
                        </div>`
                new mapboxgl.Popup()
                    .setLngLat(event.features[0].geometry.coordinates)
                    .setHTML(content)
                    .addTo(map);
            });
        }

        function handleLivewireLoad() {
            console.log("handleLivewireLoad");
            geoLocations = {!! $geoJson !!}
            patentData = {!! $patentJson !!}

            map.on('load', () => {
                map.addSource('business', {
                    'type': 'geojson',
                    'data': geoLocations
                });

                map.addSource('patent', {
                    'type': 'geojson',
                    'data': patentData
                });

                addBusinessHeat();
                addBusinessPoint();
                addPatentHeat();
                addPatentPoint();
            });

            // map.on('moveend', () => {
            //     // console.log(currentMarkers);
            //     currentMarkers.forEach((marker) => marker.remove())
            //     loadLocations(geoLocations);
            // });

            // disable map zoom when using scroll
            // map.scrollZoom.disable();

            // loadLocations = (geoJson) => {

            //     // console.log(geoJson.features)
            //     let index;
            //     index = new Supercluster({
            //         radius: 60,
            //         extent: 256,
            //         maxZoom: 16
            //     }).load(geoJson.features);

            //     const bounds = map.getBounds();

            //     // console.log(bounds);

            //     var clusters = index.getClusters([bounds.getWest(), bounds.getSouth(), bounds.getEast(), bounds
            //         .getNorth()
            //     ], parseInt(map.getZoom()));

            //     clusters.forEach((location) => {
            //         const {
            //             geometry,
            //             properties
            //         } = location
            //         const {
            //             iconSize,
            //             locationId,
            //             company_name,
            //             date_registerd,
            //             ngc_code,
            //             address,
            //             business_type,
            //             industry_classification,
            //             industry_description
            //         } = properties

            //         let markerElement = document.createElement('div')
            //         markerElement.className = 'marker-style'
            //         markerElement.id = locationId
            //         markerElement.style.color = 'antiquewhite'
            //         markerElement.style.border = '1.2px solid antiquewhite'
            //         markerElement.style.borderRadius = '50%'
            //         markerElement.innerHTML = properties.point_count_abbreviated

            //         const count = properties.point_count;
            //         if(count > 1000) {
            //             markerElement.style.backgroundColor = '#ff7407'
            //             markerElement.style.width = '50px'
            //             markerElement.style.height = '50px'
            //         }else if(count > 100) {
            //             markerElement.style.backgroundColor = '#ffc107'
            //             markerElement.style.width = '30px'
            //             markerElement.style.height = '30px'
            //         }else {
            //             markerElement.style.backgroundColor = '#007bff'
            //             markerElement.style.width = '21px'
            //             markerElement.style.height = '21px'
            //         }


            //         const content =
            //             `<div class="card card-secondary popUp-content">
        //             <div class="card-header">
        //                 <h3 class="card-title">${company_name}</h3>
        //             </div>

        //             <div class="card-body">
        //                 <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
        //                 <p class="text-muted">
        //                     ${address}
        //                 </p>

        //                 <hr>

        //                 <strong><i class="fas fa-book mr-1"></i> Date Registerd</strong>
        //                 <p class="text-muted">${date_registerd}</p>

        //                 <hr>

        //                 <strong><i class="fas fa-pencil-alt mr-1"></i> Business Type & Ind. Classification</strong>
        //                 <p class="text-muted">
        //                     <p class="badge badge-md badge-info cursor-pointer" title="Business Type">${business_type}</p>
        //                     <p class="badge badge-md badge-primary cursor-pointer" title="Industry Classification">${industry_classification}</p>
        //                 </p>

        //                 <hr>

        //                 <strong><i class="far fa-file-alt mr-1"></i> Industry Description</strong>
        //                 <p class="text-muted">${industry_description}</p>
        //             </div>
        //         </div>`

            //         const popUp = new mapboxgl.Popup({
            //             offset: 24
            //         }).setHTML(content).setMaxWidth("400px")

            //         if (properties.point_count_abbreviated) {
            //             var marker = new mapboxgl.Marker(markerElement)
            //                 .setLngLat(geometry.coordinates)
            //                 // .setPopup(popUp)
            //                 .addTo(map);
            //         }else{
            //             var marker = new mapboxgl.Marker()
            //                 .setLngLat(geometry.coordinates)
            //                 .setPopup(popUp)
            //                 .addTo(map);
            //         }
            //         currentMarkers.push(marker);
            //     })
            // }

            // loadLocations(geoLocations)

            // map.on('click', (e) => {
            //     const longtitude = e.lngLat.lng
            //     const lattitude = e.lngLat.lat

            //     @this.long = longtitude
            //     @this.lat = lattitude
            // })
        }


        Livewire.on('mapUpdated', (data) => {
            try {
                var mapLayer = map.getLayer('business-heat');
                if (typeof mapLayer !== 'undefined') {
                    map.removeLayer('business-heat').removeLayer('business-point').removeSource('business');
                }
                var mapLayer = map.getLayer('patent-heat');
                if (typeof mapLayer !== 'undefined') {
                    map.removeLayer('patent-heat').removeLayer('patent-point').removeSource('patent');
                }
            } catch (error) {

            }

            if (data.geoJson != null) {
                map.addSource('business', {
                    'type': 'geojson',
                    'data': data.geoJson
                });
                addBusinessHeat();
                addBusinessPoint();
            }

            if (data.patentJson != null) {
                map.addSource('patent', {
                    'type': 'geojson',
                    'data': data.patentJson
                });
                addPatentHeat();
                addPatentPoint();
            }
        });

        Livewire.on('loader_on', () => {
            document.getElementById('loader').style.display = 'flex';
        });
        Livewire.on('loader_off', () => {
            document.getElementById('loader').style.display = 'none';
        });
    </script>
    <!-- MAPBOX CUSTOMS -->
    {{-- <script src="{{asset('client/dist/js/mapbox-main.js')}}"></script> --}}
@endpush
