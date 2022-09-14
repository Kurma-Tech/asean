@push('extra-styles')

@endpush

<div>
    @include('client/includes/_sidebar')
    <div class="content-wrapper">
        <section class="content p-0">
            <div class="container-fluid p-0">
                <div class="row m-0">
                    <div class="col-12 col-sm-12 position-relative overflow-control p-0" id="mapSection">
                        @livewire('client.map.map-component', ['type' => $type])
                        <div class="map-overlay-box" id="map-overlay-scroll">
                            <h1 class="overlay-title">Asean</h1>
                            <h3 class="data-report-title">All Countries</h3>
                            <p class="data-report-count">1,419,71 results</p>
                            <h3 class="view-report" id="view-report-element">Show Report</h3>
                            <hr class="mb-2">
                            <div id="countryChart"></div>
                        </div>
                        <a id="filter-toggle" href="#" class="toggle square"><i class="fas fa-filter fa-lg" aria-hidden="true"></i></a>
                        <div id="filter-wrapper" wire:ignore.self>
                            <a id="close-filter" href="#" class="toggle square"><i class="fa fa-times fa-lg"></i></a>
                            <h5>Filter Options</h5>
                            <hr class="mb-2">
                            <div class="filter-inputs">
                                <div class="form-group">
                                    <label>Sort by Countries: {{$type}}</label>
                                    <select class="form-control" style="width: 100%;" wire:model="type">
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
                    <div class="col-12 col-sm-12 p-3 scroll-element" id="reportSection" wire:ignore>
                        @livewire('client.report.report-component', ['type' => $type])
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('extra-scripts')
    <script>
        var countryChartOption = {
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

        var countryChart = new ApexCharts(document.querySelector("#countryChart"), countryChartOption);
        countryChart.render();
    </script>
@endpush