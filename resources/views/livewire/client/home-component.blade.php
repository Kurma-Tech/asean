@push('extra-styles')
@endpush

<div>
    @include('client/includes/_sidebar')
    <div class="content-wrapper">
        <section class="content p-0">
            <div class="container-fluid p-0">
                <div class="row m-0">
                    <div class="col-12 col-sm-12 position-relative overflow-control p-0" id="mapSection">
                        @livewire('client.map.map-component', ['type' => $type, 'country' => $country, 'classification' => $classification])
                        <div class="map-overlay-box" id="map-overlay-scroll">
                            <h1 class="overlay-title">Asean</h1>
                            <h3 class="data-report-title">All Countries</h3>
                            <p class="data-report-count">{{$results}} results</p>
                            <h3 class="view-report" id="view-report-element">Show Report</h3>
                            <hr class="mb-2">
                            <div id="countryChart" wire:ignore></div>
                        </div>
                        <a id="filter-toggle" href="#" class="toggle square"><i class="fas fa-filter fa-lg"
                                aria-hidden="true"></i></a>
                        <div id="filter-wrapper" wire:ignore.self>
                            <a id="close-filter" href="#" class="toggle square"><i
                                    class="fa fa-times fa-lg"></i></a>
                            <h5>Filter Options</h5>
                            <hr class="mb-2">
                            <div class="filter-inputs">
                                <div class="form-group">
                                    <label>Sort by Countries:</label>
                                    <select class="form-control" style="width: 100%;" wire:model="country">
                                        <option hidden>Choose Countries</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Sort by Data:</label>
                                    <select class="form-control" style="width: 100%;" wire:model="type">
                                        <option hidden>Choose Data Type</option>
                                        <option value="all">All</option>
                                        <option value="business">Business</option>
                                        <option value="patent">Patent</option>
                                        <option value="journals">Journals</option>
                                    </select>
                                </div>
                                @if ($type != 'all')
                                    <div class="form-group">
                                        <label>Sort by Classifications:</label>
                                        <select class="form-control" style="width: 100%;" wire:model="classification">
                                            <option hidden>Choose Classifications</option>
                                            <option value="">All</option>
                                            @foreach ($classifications as $classification)
                                                <option value="{{$classification->id}}">{{$classification->classifications}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 p-3 scroll-element" id="reportSection" wire:ignore>
                        @livewire('client.report.report-component', ['type' => $type, 'country' => $country, 'classification' => $classification])
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('extra-scripts')
    <script>
        var countryChartOption = {
            series: [{
                    name: "Business",
                    data: {!! collect($businessCountListByCountry)->toJson(); !!}
                },
                {
                    name: "Patent",
                    data: {!! collect($patentCountListByCountry)->toJson(); !!}
                },
                {
                    name: "Journals",
                    data: []
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
                categories: {!! collect($countriesNameList)->toJson(); !!},
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
                    fontSize: '14px',
                    fontWeight: 'bold',
                    fontFamily: undefined,
                    color: '#fff'
                },
            }
        };

        var countryChart = new ApexCharts(document.querySelector("#countryChart"), countryChartOption);
        countryChart.render();
    </script>
@endpush
