@section('title', 'Report')

<div>
    <div class="content bg-background-black">
        <div class="container-fluid" style="padding-top: 72px;">
            {{-- <div class="row" style="margin-top: 60px;">
                <div class="col-12 col-sm-12 p-3">
                    <h3>{{ GoogleTranslate::trans('Current Report', app()->getLocale()) }}</h3>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-md-12" wire:ignore>
                    <div class="card bg-card-black">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10 remove-padding">
                                    <div id="line-chart"></div>
                                </div>

                                <div class="col-md-2 pl-4">
                                    <h2>Totals</h2>
                                    <div class="info-box bg-danger">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Businesses</span>
                                            <span class="info-box-number" id="business-count">-</span>
                                        </div>
                                    </div>
                                    <div class="info-box bg-warning">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Intellectual properties</span>
                                            <span class="info-box-number" id="patent-count">-</span>
                                        </div>
                                    </div>
                                    <div class="info-box bg-info">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Journals</span>
                                            <span class="info-box-number" id="journal-count">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-card-black">
                                <div class="card-header">
                                    <div class="row">
                                        <h3 class="col-md-12 col-sm-12 card-title mb-2">Popular Businesses</h3>
                                        <div class="col-md-9 col-sm-12">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" wire:model="popularCountryBusiness">
                                                    <option hidden>
                                                        {{ GoogleTranslate::trans('Select Country', app()->getLocale()) }}
                                                    </option>
                                                    @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">
                                                        {{ GoogleTranslate::trans($country->name, app()->getLocale()) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" wire:model="topLimitBusiness">
                                                    <option hidden>
                                                        {{ GoogleTranslate::trans('Select', app()->getLocale()) }}
                                                    </option>
                                                    <option value="10">
                                                        {{ GoogleTranslate::trans('Top 10', app()->getLocale()) }}
                                                    </option>
                                                    <option value="20">
                                                        {{ GoogleTranslate::trans('Top 20', app()->getLocale()) }}
                                                    </option>
                                                    <option value="30">
                                                        {{ GoogleTranslate::trans('Top 30', app()->getLocale()) }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive overlay-scroll p-0" style="height: 300px;">
                                    <table class="table table-head-fixed" id="business-emerging">
                                        <thead>
                                            <tr>
                                                <th>S.N</th>
                                                <th>Industry Type</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-card-black">
                                <div class="card-header">
                                    <div class="row">
                                        <h3 class="col-md-12 col-sm-12 card-title mb-2">Popular Intellectual properties</h3>
                                        <div class="col-md-9 col-sm-12">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" wire:model="popularCountryPatent">
                                                    <option hidden>
                                                        {{ GoogleTranslate::trans('Select Country', app()->getLocale()) }}
                                                    </option>
                                                    @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">
                                                        {{ GoogleTranslate::trans($country->name, app()->getLocale()) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" wire:model="topLimitPatent">
                                                    <option hidden>
                                                        {{ GoogleTranslate::trans('Select', app()->getLocale()) }}
                                                    </option>
                                                    <option value="10">
                                                        {{ GoogleTranslate::trans('Top 10', app()->getLocale()) }}
                                                    </option>
                                                    <option value="20">
                                                        {{ GoogleTranslate::trans('Top 20', app()->getLocale()) }}
                                                    </option>
                                                    <option value="30">
                                                        {{ GoogleTranslate::trans('Top 20', app()->getLocale()) }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive overlay-scroll p-0" style="height: 300px;">
                                    <table class="table table-head-fixed" id="patent-emerging">
                                        <thead>
                                            <tr>
                                                <th>S.N</th>
                                                <th>Patent Kind</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-card-black">
                                <div class="card-header">
                                    <div class="row">
                                        <h3 class="col-md-12 col-sm-12 card-title mb-2">Popular Journals</h3>
                                        <div class="col-md-9 col-sm-12">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" wire:model="selectedCountryJournal">
                                                    <option hidden>
                                                        {{ GoogleTranslate::trans('Select Country', app()->getLocale()) }}
                                                    </option>
                                                    @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">
                                                        {{ GoogleTranslate::trans($country->name, app()->getLocale()) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="input-group input-group-sm">
                                                <select class="form-control" wire:model="topLimitJournal">
                                                    <option hidden>
                                                        {{ GoogleTranslate::trans('Select', app()->getLocale()) }}
                                                    </option>
                                                    <option value="10">
                                                        {{ GoogleTranslate::trans('Top 10', app()->getLocale()) }}
                                                    </option>
                                                    <option value="20">
                                                        {{ GoogleTranslate::trans('Top 20', app()->getLocale()) }}
                                                    </option>
                                                    <option value="30">
                                                        {{ GoogleTranslate::trans('Top 30', app()->getLocale()) }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive overlay-scroll p-0" style="height: 300px;">
                                    <table class="table table-head-fixed" id="journal-emerging">
                                        <thead>
                                            <tr>
                                                <th>S.N</th>
                                                <th>Industry Type</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-md-6">
                    <div class="col-md-12 col-sm-12">

                    </div>
                    <div class="col-md-12 col-sm-12" wire:ignore>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{ GoogleTranslate::trans('Business Manpower', app()->getLocale()) }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>{{ GoogleTranslate::trans('Skill Type', app()->getLocale()) }}:</label>
                                            <select class="form-control">
                                                <option hidden>
                                                    {{ GoogleTranslate::trans('Choose Skill Type', app()->getLocale()) }}
                                                </option>
                                                <option value="1">
                                                    {{ GoogleTranslate::trans('Professional', app()->getLocale()) }}
                                                </option>
                                                <option value="2">
                                                    {{ GoogleTranslate::trans('Tradesman', app()->getLocale()) }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>{{ GoogleTranslate::trans('Sort by Classifications', app()->getLocale()) }}:</label>
                                            <select class="form-control">
                                                <option hidden>
                                                    {{ GoogleTranslate::trans('Choose Industry', app()->getLocale()) }}
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
                                </div>
                                <table class="table table-bordered table-hover" id="business-manpower">
                                    <tbody>
                                        <tr>
                                            <td>IT Staffing and Consulting - 6</td>
                                            <td>Healthcare Staff - 2</td>
                                            <td>Customer Respresentative - 7</td>
                                        </tr>
                                        <tr>
                                            <td>Accountants - 12</td>
                                            <td>Auditors - 2</td>
                                            <td>Electrical Engineer - 5</td>
                                        </tr>
                                        <tr>
                                            <td>Chef - 2</td>
                                            <td>Car Mechanic - 3</td>
                                            <td>Cleaner - 1</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>{{ GoogleTranslate::trans('Sort by Country', app()->getLocale()) }}:</label>
                                            <select class="form-control" wire:model="country">
                                                <option hidden>
                                                    {{ GoogleTranslate::trans('Choose Countries', app()->getLocale()) }}
                                                </option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>{{ GoogleTranslate::trans('Sort by Classifications', app()->getLocale()) }}:</label>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12" wire:ignore>
                        <div class="card">
                            <div class="card-body">
                                <div id="forcast-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>

@push('extra-styles')
    <style>
        body {
            overflow: auto !important;
        }
        .bg-background-black {
            background-color: #202124 !important;
            color: white;
        }

        .bg-card-black {
            background-color: #303134 !important;
            color: white;
        }

        .remove-padding {
            padding: 0px !important;
        }
    </style>
@endpush

@push('extra-scripts')
    <script>
        document.addEventListener("livewire:load", handleLivewireLoad, true);

        function handleLivewireLoad() {
            console.log("handleLivewireLoad");
            Livewire.emit('reportFirstLoad');
        }
        Livewire.on('reportsFirstLoad', (data) => {
            // Line Draw
            var lineChartOptions = {
                series: [{
                        name: "Business",
                        data: []
                    },
                    {
                        name: "Intellectual property",
                        data: []
                    },
                    {
                        name: 'Journal',
                        data: []
                    }
                ],
                chart: {
                    height: 350,
                    type: 'line',
                    foreColor: '#fff',
                    zoom: {
                        enabled: false
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: [3, 3, 3],
                    curve: 'straight',
                    dashArray: [0, 0, 0]
                },
                title: {
                    text: 'Total Reporting of Businesses, Intellectual properties and Journals',
                    align: 'left'
                },
                legend: {
                    tooltipHoverFormatter: function(val, opts) {
                        return val + ' - ' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] +
                            ''
                    }
                },
                markers: {
                    size: 0,
                    hover: {
                        sizeOffset: 6
                    }
                },
                xaxis: {
                    // categories: [
                    //     '01 Jan', '02 Jan', '03 Jan', '04 Jan', '05 Jan', '06 Jan', '07 Jan', '08 Jan',
                    //     '09 Jan', '10 Jan', '11 Jan', '12 Jan'
                    // ],
                    categories: data.lineChartYears
                },
                colors: ['#b71c1c', '#ffd600', '#01579b'],
                tooltip: {
                    y: [{
                            title: {
                                formatter: function(val) {
                                    return val + " "
                                }
                            }
                        },
                        {
                            title: {
                                formatter: function(val) {
                                    return val + " "
                                }
                            }
                        },
                        {
                            title: {
                                formatter: function(val) {
                                    return val;
                                }
                            }
                        }
                    ]
                },
                grid: {
                    borderColor: '#f1f1f1',
                }
            };

            var lineChart = new ApexCharts(document.querySelector("#line-chart"), lineChartOptions);
            lineChart.render();

            // Business, Intellectual Property and Journal Counts
            document.getElementById('business-count').innerHTML = data.businessCount;
            document.getElementById('patent-count').innerHTML = data.patentCount;
            document.getElementById('journal-count').innerHTML = data.journalCount;
            console.log(data.patentCountByYears);
            console.log(data.journalCountByYears);

            lineChart.updateOptions({
                series: [{
                        name: "Business",
                        data: data.businessCountByYears
                    },
                    {
                        name: "Intellectual property",
                        data: data.patentCountByYears
                    },
                    {
                        name: 'Journal',
                        data: data.journalCountByYears
                    }
                ],
                xaxis: {
                    categories: data.lineChartYears
                }
            });

            // Forcast
            // var forcastChartOptions = {
            //     series: [{
            //             name: 'Business Forcast',
            //             // data: [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 17, 2, 7, 5]
            //             data: data.forcastData
            //         },
            //         {
            //             name: 'Patent Forcast',
            //             // data: [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 17, 2, 7, 5]
            //             data: data.forcastData
            //         }
            //     ],
            //     chart: {
            //         id: "forcast-chart",
            //         height: 350,
            //         type: 'line',
            //         foreColor: '#fff',
            //     },
            //     forecastDataPoints: {
            //         count: data.forecastedFrom
            //     },
            //     stroke: {
            //         width: 5,
            //         curve: 'smooth'
            //     },
            //     xaxis: {
            //         // type: 'datetime',
            //         categories: data.forcastDates,
            //         tickAmount: 10,
            //         // labels: {
            //         //     formatter: function(value, timestamp, opts) {
            //         //         console.log(timestamp);
            //         //         return opts.dateFormatter(new Date(timestamp), 'dd MMM')
            //         //     }
            //         // }
            //     },
            //     title: {
            //         text: 'Business Forecast',
            //         align: 'left',
            //         style: {
            //             fontSize: "16px",
            //             color: '#fff'
            //         }
            //     },
            //     colors: ['#b71c1c', '#ffd600', '#01579b'],
            //     // fill: {
            //     //     type: 'gradient',
            //     //     gradient: {
            //     //         shade: 'dark',
            //     //         gradientToColors: ['#FDD835'],
            //     //         shadeIntensity: 1,
            //     //         type: 'horizontal',
            //     //         opacityFrom: 1,
            //     //         opacityTo: 1,
            //     //         stops: [0, 100, 100, 100]
            //     //     },
            //     // },
            //     yaxis: {
            //         min: 0,
            //         max: 60000
            //     }
            // };

            // var forcastChart = new ApexCharts(document.querySelector("#forcast-chart"), forcastChartOptions);
            // forcastChart.render();

            var emergingData = data.emergingBusiness.sort(function(x, y) {
                return y.value - x.value;
            });
            addEmergingData(emergingData);

            var emergingPatentData = data.emergingPatents.sort(function(x, y) {
                return y.value - x.value;
            });
            addEmergingPatentData(emergingPatentData);
        });

        function addEmergingData(data) {
            $("#business-emerging tbody tr").remove();

            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                var myHtmlContent =
                    `
                    <td>${index+1}</td>
                    <td>${element.key}</td>
                    <td>${element.value}</td>
                `;
                var tableRef = document.getElementById('business-emerging').getElementsByTagName('tbody')[0];
                var newRow = tableRef.insertRow(tableRef.rows.length);
                newRow.innerHTML = myHtmlContent;
            }
        }

        function addEmergingPatentData(data) {
            $("#patent-emerging tbody tr").remove();

            for (let index = 0; index < data.length; index++) {
                const element = data[index];
                var myHtmlContent =
                    `
                    <td>${index+1}</td>
                    <td>${element.key}</td>
                    <td>${element.value}</td>
                `;
                var tableRef = document.getElementById('patent-emerging').getElementsByTagName('tbody')[0];
                var newRow = tableRef.insertRow(tableRef.rows.length);
                newRow.innerHTML = myHtmlContent;
            }
        }

        Livewire.on('reportsUpdated', (data) => {
            // ApexCharts.exec('forcast-chart', 'updateOptions', {
            //     xaxis: {
            //         categories: data.forcastDates,
            //         tickAmount: 10,
            //     },
            // }, false, true);

            // ApexCharts.exec('forcast-chart', 'updateSeries', [{
            //     data: data.forcastData
            // }], true);
            // var emergingData = data.emergingBusiness.sort(function(x, y) {
            //     return y.value - x.value;
            // });
            // addEmergingData(emergingData);
        });

        Livewire.on('updateTopBusiness', (data) => {
            var emergingData = data.emergingBusiness.sort(function(x, y) {
                return y.value - x.value;
            });
            addEmergingData(emergingData);
        });

        Livewire.on('updateTopPatent', (data) => {
            var emergingPatentData = data.emergingPatents.sort(function(x, y) {
                return y.value - x.value;
            });
            addEmergingPatentData(emergingPatentData);
        });
    </script>
@endpush
