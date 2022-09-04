@push('extra-styles')
    <link rel="stylesheet" href="{{ asset('client/dist/css/apexcharts.css') }}">
    <style>
        #chart {
            max-width: 650px;
            margin: 15px 0;
        }
        .overflow-control { overflow: hidden; }
        .map-overlay-box {
            position: absolute;
            top: 0;
            bottom: 0;
            height: 100%;
            overflow-y: auto;
            min-width: 450px;
            padding: 15px 15px 20px;
            background-color: rgba(0,0,0,.3);
            z-index: 9;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }
        #map-overlay-scroll::-webkit-scrollbar-track
        {
            background-color: hsl(0deg 0% 2%);
        }

        #map-overlay-scroll::-webkit-scrollbar
        {
            width: 6px;
            background-color: #F5F5F5;
        }

        #map-overlay-scroll::-webkit-scrollbar-thumb
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.1);
            background-color: hsl(18deg 3% 6%);;
        }

        .map-overlay-box > .overlay-title { font-size: 1.5em; line-height: 1em; font-weight: 600; margin: 0; padding: 0; margin-bottom: .3em; }
        .map-overlay-box > .data-report-title { font-size: 1em; line-break: 1em; font-weight: 500; margin: 0; padding: 0; margin-bottom: .3em; }
        .map-overlay-box > .data-report-count { font-size: 1em; line-break: 1em; font-weight: 400; margin: 0; padding: 0; margin-bottom: .3em; }


        #filter-wrapper {
            z-index: 99;
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            min-width: 300px;
            height: 100%;
            transform: translateX(300px);
            overflow-y: auto;
            -webkit-transition: all 0.4s ease 0s;
            -moz-transition: all 0.4s ease 0s;
            -ms-transition: all 0.4s ease 0s;
            -o-transition: all 0.4s ease 0s;
            transition: all 0.4s ease 0s;
            background-color: rgba(0,0,0,.3);
            backdrop-filter: blur(5px);
            padding: 15px;
        }
        
        .filter-nav {
            position: absolute;
            top: 0;
            width: 250px;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        #filter-toggle {
            z-index: 1;
            position: absolute;
            top: 15px;
            right: 15px;
        }
        
        #filter-wrapper.active {
            right: 300px;
            width: 300px;
            -webkit-transition: all 0.4s ease 0s;
            -moz-transition: all 0.4s ease 0s;
            -ms-transition: all 0.4s ease 0s;
            -o-transition: all 0.4s ease 0s;
            transition: all 0.4s ease 0s;
        }
        .square { display: inline-block; padding: 6px; text-align: right; color: hsl(340deg 82% 52%);}
        .square:hover { color: hsl(340deg 72% 42%)!important; }

        .filter-inputs { margin-top: 20px; }

        .apexcharts-tooltip,
        .apexcharts-menu-item {
            color: rgb(0, 0, 0);
        }
    </style>
@endpush

<div>
    @include('client/includes/_sidebar')
    <!-- Content Wrapper. Contains page content -->
    {{-- <div class="content-wrapper p-3">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-sm-12" wire:ignore>
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="map-display-tab" data-toggle="pill" href="#map-display" role="tab" aria-controls="map-display" aria-selected="true">Map</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="reports-display-tab" data-toggle="pill" href="#reports-display" role="tab" aria-controls="reports-display" aria-selected="false">Reports</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade active show" id="map-display" role="tabpanel" aria-labelledby="map-display-tab">
                                        @livewire('client.map.map-component', ['type' => $type])
                                    </div>
                                    <div class="tab-pane fade" id="reports-display" role="tabpanel" aria-labelledby="reports-display-tab">
                                        @livewire('client.report.report-component', ['filters_data' => $filter, 'type' => $type])
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div> --}}

    <div class="content-wrapper">
        <section class="content p-0">
            <div class="container-fluid p-0">
                <div class="row m-0">
                    <div class="col-12 col-sm-12 position-relative overflow-control p-0" wire:ignore>
                        @livewire('client.map.map-component', ['type' => $type])
                        <div class="map-overlay-box" id="map-overlay-scroll">
                            <h1 class="overlay-title">Asean</h1>
                            <h3 class="data-report-title">All Countries</h3>
                            <p class="data-report-count">1,419,71 results</p>
                            <hr class="mb-2">
                            <div id="chart"></div>
                        </div>
                        <a id="filter-toggle" href="#" class="toggle square"><i class="fas fa-filter fa-lg" aria-hidden="true"></i></a>
                        <div id="filter-wrapper">
                            <a id="close-filter" href="#" class="toggle square"><i class="fa fa-times fa-lg"></i></a>
                            <h5>Filter Options</h5>
                            <hr class="mb-2">
                            <div class="filter-inputs">
                                <div class="form-group">
                                    <label>Sort by Countries:</label>
                                    <select class="form-control" style="width: 100%;">
                                        <option hidden>Choose Countries</option>
                                        <option value="Brunei">Brunei</option>
                                        <option value="Cambodia">Cambodia</option>
                                        <option value="Indonesia">Indonesia</option>
                                        <option value="Laos">Laos</option>
                                        <option value="Malaysia">Malaysia</option>
                                        <option value="Myanmar">Myanmar</option>
                                        <option value="Philippines">Philippines</option>
                                        <option value="Singapore">Singapore</option>
                                        <option value="Thailand">Thailand</option>
                                        <option value="Vietnam">Vietnam</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Sort by Data:</label>
                                    <select class="form-control" style="width: 100%;">
                                        <option hidden>Choose Data Type</option>
                                        <option value="Business">Business</option>
                                        <option value="Patent">Patent</option>
                                        <option value="Journals">Journals</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Sort by Classifications:</label>
                                    <select class="form-control" style="width: 100%;">
                                        <option hidden>Choose Classifications</option>
                                        <option value="Classifications 1">Classifications 1</option>
                                        <option value="Classifications 2">Classifications 2</option>
                                        <option value="Classifications 3">Classifications 3</option>
                                        <option value="Classifications 4">Classifications 4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('extra-scripts')
    <script src="{{ asset('client/dist/js/apexcharts.min.js') }}"></script>
    <script>
        var options = {
            series: 
                [
                    {
                        name: "Business",
                        data: [4400, 5500, 4100, 3000, 2200, 4300, 2100, 2160, 3300, 4400]
                    }, 
                    {
                        name: "Patent",
                        data: [5300, 3200, 3300, 5200, 1300, 6600, 3300, 5500, 4400, 3200]
                    },
                    {
                        name: "Journals",
                        data: [4600, 3600, 4100, 5400, 2200, 4300, 3300, 5500, 4400, 3200]
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 750,
                    foreColor: '#fff',
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        dataLabels: {
                            position: 'top',
                        },
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetX: -6,
                    style: {
                        fontSize: '11px',
                        colors: ['#fff'],

                    }
                },
                stroke: {
                    show: true,
                    width: 1,
                    colors: ['#333']
                },
                tooltip: {
                    shared: true,
                    intersect: false
                },
                xaxis: {
                    categories: ['Brunei', 'Cambodia', 'Indonesia', 'Laos', 'Malaysia', 'Myanmar', 'Philippines', 'Singapore', 'Thailand', 'Vietnam'],
                    colors: ['#fff']
                },
                colors: ['#ffd600', '#b71c1c', '#01579b'],
                title: {
                    text: "Showing result of 2022, All Categories, All Countries",
                    align: 'left',
                    margin: 0,
                    offsetX: 0,
                    offsetY: 0,
                    floating: false,
                    style: {
                        fontSize:  '14px',
                        fontWeight:  'bold',
                        fontFamily:  undefined,
                        color:  '#fff'
                    },
                }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
    <script>
        // Close menu
        $("#close-filter").click(function(e) {
            e.preventDefault();
            $("#filter-wrapper").toggleClass("active");
        });
        // Open menu
        $("#filter-toggle").click(function(e) {
            e.preventDefault();
            $("#filter-wrapper").toggleClass("active");
        });
    </script>
@endpush