@push('extra-styles')
@endpush

<div>
    {{-- @include('client/includes/_sidebar') --}}
    <div class="content-wrapper">
        <section class="content p-0">
            <div class="container-fluid p-0">
                <div class="row m-0">
                    <div class="col-12 col-sm-12 position-relative overflow-control p-0" id="mapSection">
                        @livewire('client.map.map-component')
                        <div class="map-overlay-box overlay-scroll">
                            <h3 class="search-title">{{ GoogleTranslate::trans('Search', app()->getLocale()) }}</h3>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <div class="input-group input-group-sm">
                                        <select class="form-control" wire:model="type">
                                            <option hidden>{{ GoogleTranslate::trans('Choose Data Type', app()->getLocale()) }}</option>
                                            <option value="all">{{ GoogleTranslate::trans('All', app()->getLocale()) }}</option>
                                            <option value="business">{{ GoogleTranslate::trans('Business', app()->getLocale()) }}</option>
                                            <option value="patent">{{ GoogleTranslate::trans('Patent', app()->getLocale()) }}</option>
                                            <option value="journals">{{ GoogleTranslate::trans('Journals', app()->getLocale()) }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-8">
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="search"
                                            placeholder="{{ GoogleTranslate::trans('Search', app()->getLocale()) }}..." wire:model="search">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-sm btn-default btn-flat"
                                                wire:click="handleSearch"><i class="fa fa-search lemongreen"
                                                    aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-inputs mt-0">
                                <div class="form-group">
                                    <label>{{ GoogleTranslate::trans('Sort by Countries', app()->getLocale()) }}:</label>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control" wire:model="country">
                                            <option hidden>{{ GoogleTranslate::trans('Choose Countries', app()->getLocale()) }}</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ GoogleTranslate::trans( $country->name, app()->getLocale()) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if ($type == 'business')
                                    <div class="form-group">
                                        <label>{{ GoogleTranslate::trans('Sort by Classifications', app()->getLocale()) }}:</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control" wire:model="classification">
                                                <option hidden>{{ GoogleTranslate::trans('Choose Classifications', app()->getLocale()) }}</option>
                                                <option value="">{{ GoogleTranslate::trans('All', app()->getLocale()) }}</option>
                                                @foreach ($classifications as $classification)
                                                    <option value="{{ $classification->id }}">
                                                        {{ $classification->classifications }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <span class="data-report-count mr-2">{{ GoogleTranslate::trans('About '.$results.' results.', app()->getLocale()) }}</span>
                                    <a href="{{ route('client.report') }}" class="view-report pull-right" id="view-report-element" target="_blank">{{ GoogleTranslate::trans('Show Report', app()->getLocale()) }}</a>
                                </div>
                            </div>

                            <hr class="mb-2">

                            <div class="row">
                                <div class="col-md-12 mt-3 mb-3">
                                    <div wire:ignore>
                                        {{ GoogleTranslate::trans('PAGE', app()->getLocale()) }}: <span id="page"></span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <a href="javascript:prevPage()" class="btn btn-xs btn-default" id="btn_prev">{{ GoogleTranslate::trans('Prev', app()->getLocale()) }}</a>
                                        <a href="javascript:nextPage()" class="btn btn-xs btn-default pull-right" id="btn_next">{{ GoogleTranslate::trans('Next', app()->getLocale()) }}</a>
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
                        <a id="filter-toggle" href="#" class="btn toggle square"><i class="fas fa-chart-bar fa-lg"
                                aria-hidden="true"></i> {{ GoogleTranslate::trans('Data Report', app()->getLocale()) }}</a>
                        <div id="filter-wrapper" wire:ignore.self class="overlay-scroll active">
                            <a id="close-filter" href="#" class="toggle square-close"><i
                                    class="fa fa-times fa-lg"></i></a>
                            <div id="countryChart" wire:ignore></div>
                        </div>
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
    <script>
        var countryChartOption = {
            series: [{
                    name: "Business",
                    data: {!! collect($businessCountListByCountry)->toJson() !!}
                },
                {
                    name: "Patent",
                    data: {!! collect($patentCountListByCountry)->toJson() !!}
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
                categories: {!! collect($countriesNameList)->toJson() !!},
                colors: ['#fff']
            },
            colors: [ '#b71c1c', '#ffd600', '#01579b'],
            title: {
                text: "{{ GoogleTranslate::trans('Total registered businesses, patents and journals till now.', app()->getLocale()) }}",
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
