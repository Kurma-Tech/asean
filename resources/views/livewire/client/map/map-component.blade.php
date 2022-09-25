@push('extra-styles')
    <!-- mapbox -->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.css">
    <!-- geocoder -->
    <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css"
        type="text/css">
    <style>
        #map {
            height: 100vh;
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
    </style>
@endpush

<div>
    <div id="map" wire:ignore>
        <div id="loader"><img src="loader.gif" alt="loader"></div>
        {{-- <div id="background">
            <img src="star-background-min.jpg" alt="background" srcset="">
        </div> --}}
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

            console.log(mergedData);
            if (mergedData.length > 0) {
                for (var i = (page - 1) * records_per_page; i < (page * records_per_page) && i < mergedData.length; i++) {
                    listing_table.innerHTML +=
                        `
                    <div class="card card-secondary">
                        <div class="card-header" style="border-radius: 0;">
                            <h4 class="card-title w-100">
                                <a class="d-block w-100" data-toggle="collapse"
                                    href="#business-${mergedData[i].properties.locationId}">
                                    ${mergedData[i].properties.company_name}
                                </a>
                            </h4>
                        </div>
                        <div id="business-${mergedData[i].properties.locationId}"
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
                projection: "equirectangular", // display the map as a 3D globe
                pitch: 45,
                bearing: -17.6,
                antialias: true,
                maxBounds: [
                    [91.56216158463567, -10.491532410391958],
                    [141.79211516906793, 27.60302090835848]
                ] // Set the map's geographical boundaries.
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

        function addBusinessPoint(sourceId) {
            map.addLayer({
                'id': 'business-point' + sourceId,
                'type': 'circle',
                'source': sourceId,
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

        function handleLivewireLoad() {
            console.log("handleLivewireLoad");

            Livewire.emit('mapFirstLoad');
            // geoLocations = {!! $geoJson !!}
            // patentData = {!! $patentJson !!}

            // map.on('load', () => {
            //     map.addSource('business', {
            //         'type': 'geojson',
            //         'data': geoLocations
            //     });

            //     map.addSource('patent', {
            //         'type': 'geojson',
            //         'data': patentData
            //     });

            //     addBusinessHeat();
            //     addBusinessPoint();
            //     addPatentHeat();
            //     addPatentPoint();


            // });

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
                if (typeof mapLayer !== "undefined") {
                    map.removeLayer('business-heat').removeSource('businessHeatData');
                }
                for (let index = 0; index < businessChunkedData; index++) {
                    var mapLayerTemp = map.getLayer('business-heat' + 'business' + index);
                    if (typeof mapLayerTemp !== 'undefined') {
                        map.removeLayer('business-heat' + 'business' + index).removeLayer('business-point' +
                            'business' + index).removeSource('business' + index);
                    }
                }

                var mapLayer = map.getLayer('patent-heat');
                if (typeof mapLayer !== 'undefined') {
                    map.removeLayer('patent-heat').removeLayer('patent-point').removeSource('patent');
                }
            } catch (error) {

            }
            businessChunkedData = data.geoJson.length;
            mergedData = [...new Set([].concat(...data.geoJson.map((element) => element.features)))];
            changePage(1);

            if (data.geoJson != null) {
                map.addSource('businessHeatData', {
                    'type': 'geojson',
                    'data': {
                        'type' : 'FeatureCollection',
                        'features' : mergedData
                    }
                });
                addBusinessHeat('businessHeatData');
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
