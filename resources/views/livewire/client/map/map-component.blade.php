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

        .mapboxgl-ctrl-center {
            bottom: 72px;
            left: 50%;
            position:absolute; 
            pointer-events:none; 
            z-index:2;
        }

        .mapboxgl-ctrl-center .mapboxgl-ctrl-group {
            display: flex;
            margin-bottom: 5px;
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

            // var nav = map.addControl(new mapboxgl.AttributionControl(), 'bottom-left');

            var nav = new mapboxgl.NavigationControl(); // position is optional
            map.addControl(nav, 'bottom-left');

            nav._container.parentNode.className="mapboxgl-ctrl-center"

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
                'minzoom': 15,
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
                                <p class="text-muted">${journalData.published_year}</p>
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
            Livewire.emit('mapFirstLoad');
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

                var mapLayer = map.getLayer('patent-point');
                if (typeof mapLayer !== 'undefined') {
                    map.removeLayer('patent-point').removeSource('patent');
                }

                var mapLayer = map.getLayer('journal-point');
                if (typeof mapLayer !== 'undefined') {
                    map.removeLayer('journal-point').removeSource('journal');
                }
            } catch (error) {

            }
            businessChunkedData = data.geoJson.length;
            mergedDataBusiness = [...new Set([].concat(...data.geoJson.map((element) => element.features)))];
            mergedDataPatent = [...new Set([].concat(...data.patentJson.features))];
            mergedDataJournal = [...new Set([].concat(...data.journalJson.features))];
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
