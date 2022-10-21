<div>
    <div class="content">
        <div class="container-fluid">
            <div class="row" style="margin-top: 60px;">
                <div class="col-12 col-sm-12 p-3">
                    <h3>{{ GoogleTranslate::trans('Current Report', app()->getLocale()) }}</h3>
                </div>
            </div>
            <div class="row">

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-12 col-sm-12" wire:ignore>
                        <div class="card">
                            <div class="card-body">
                                <div id="line-chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12" wire:ignore>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ GoogleTranslate::trans('Business Manpower', app()->getLocale()) }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>{{ GoogleTranslate::trans('Skill Type', app()->getLocale()) }}:</label>
                                            <select class="form-control">
                                                <option hidden>{{ GoogleTranslate::trans('Choose Skill Type', app()->getLocale()) }}</option>
                                                <option value="1">{{ GoogleTranslate::trans('Professional', app()->getLocale()) }}</option>
                                                <option value="2">{{ GoogleTranslate::trans('Tradesman', app()->getLocale()) }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>{{ GoogleTranslate::trans('Sort by Classifications', app()->getLocale()) }}:</label>
                                            <select class="form-control">
                                                <option hidden>{{ GoogleTranslate::trans('Choose Industry', app()->getLocale()) }}</option>
                                                <option value="">{{ GoogleTranslate::trans('All', app()->getLocale()) }}</option>
                                                @foreach ($classifications as $classification)
                                                    <option value="{{ $classification->id }}">
                                                        {{ $classification->classifications }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Sub Classifications</label>
                                            <select class="form-control" style="width: 100%;" wire:model="perPage">
                                                <option hidden>Select Sub-Classifications</option>
                                            </select>
                                        </div>
                                    </div> --}}
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
                                                <option hidden>{{ GoogleTranslate::trans('Choose Countries', app()->getLocale()) }}</option>
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
                                                <option hidden>{{ GoogleTranslate::trans('Choose Classifications', app()->getLocale()) }}</option>
                                                <option value="">{{ GoogleTranslate::trans('All', app()->getLocale()) }}</option>
                                                @foreach ($classifications as $classification)
                                                    <option value="{{ $classification->id }}">
                                                        {{ $classification->classifications }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Sub Classifications</label>
                                            <select class="form-control" style="width: 100%;" wire:model="perPage">
                                                <option hidden>Select Sub-Classifications</option>
                                            </select>
                                        </div>
                                    </div> --}}
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
                    <div class="col-md-12 col-sm-12" wire:ignore>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ GoogleTranslate::trans('Popular Business', app()->getLocale()) }}</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover" id="business-emerging">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ GoogleTranslate::trans('Year', app()->getLocale()) }}</th>
                                            <th>{{ GoogleTranslate::trans('No. Business Data', app()->getLocale()) }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($allData as $key => $value)
                                            <tr data-widget="expandable-table" aria-expanded="false">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $key }}</td>
                                                <td>{{ $value }}</td>
                                            </tr> --}}
                                        {{-- <tr class="expandable-body d-none">
                                            <td colspan="8">
                                                <ul class="products-list product-list-in-card pl-2 pr-2">
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Professional Manpower
                                                            </div>
                                                            <span class="badge badge-info badge-xs">40 Software Engineer</span>
                                                            <span class="badge badge-info badge-xs">3 Quality Assurance</span>
                                                            <span class="badge badge-info badge-xs">6 Human Resources</span>
                                                        </div>
                                                    </li>
                                                    <li class="item">
                                                        <div class="product-info">
                                                            <div class="product-title">
                                                                Skill Manpower
                                                            </div>
                                                            <span class="badge badge-primary badge-xs">2 Cook</span>
                                                            <span class="badge badge-primary badge-xs">2 Cleaner</span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr> --}}
                                        {{-- @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-md-12 col-sm-12" wire:ignore>
                    <div class="card">
                        <div class="card-body">
                            <div id="country-wise-chart"></div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>

@push('extra-styles')
    <style>
        body {
            overflow: auto !important;
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
                        name: "Patent",
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
                    text: 'Total Reporting of Businesses, Patents and Journals',
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

            // Replace data to existing Chart
            // lineChart.updateSeries([
            //     {
            //         name: "Business",
            //         data: data.businessCountByYears
            //     },
            //     {
            //         name: "Patent",
            //         data: data.patentCountByYears
            //     },
            //     {
            //         name: 'Journal',
            //         data: []
            //     }
            // ]);

            console.log(data.forcastData);
            console.log(data.forcastDates);

            lineChart.updateOptions({
                series: [{
                        name: "Business",
                        data: data.businessCountByYears
                    },
                    {
                        name: "Patent",
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
            var forcastChartOptions = {
                series: [{
                    name: 'Business Forcast',
                    // data: [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 17, 2, 7, 5]
                    data: data.forcastData
                }],
                chart: {
                    id: "forcast-chart",
                    height: 350,
                    type: 'line',
                    foreColor: '#fff',
                },
                forecastDataPoints: {
                    count: data.forecastedFrom
                },
                stroke: {
                    width: 5,
                    curve: 'smooth'
                },
                xaxis: {
                    // type: 'datetime',
                    categories: data.forcastDates,
                    tickAmount: 10,
                    // labels: {
                    //     formatter: function(value, timestamp, opts) {
                    //         console.log(timestamp);
                    //         return opts.dateFormatter(new Date(timestamp), 'dd MMM')
                    //     }
                    // }
                },
                title: {
                    text: 'Business Forecast',
                    align: 'left',
                    style: {
                        fontSize: "16px",
                        color: '#fff'
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        gradientToColors: ['#FDD835'],
                        shadeIntensity: 1,
                        type: 'horizontal',
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                    },
                },
                yaxis: {
                    min: 0,
                    max: 60000
                }
            };

            var forcastChart = new ApexCharts(document.querySelector("#forcast-chart"), forcastChartOptions);
            forcastChart.render();

            // Country Wise
            //     var CountryWiseChartOptions = {
            //         series: [{
            //             name: 'Business',
            //             data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
            //             // data: data.businessCountByYears
            //         }, {
            //             name: 'Patent',
            //             data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
            //             // data: data.patentCountByYears
            //         }, {
            //             name: 'Journal',
            //             data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
            //             // data: ['1']
            //         }],
            //         chart: {
            //             type: 'bar',
            //             foreColor: '#fff',
            //             height: 350
            //         },
            //         plotOptions: {
            //             bar: {
            //                 horizontal: false,
            //                 columnWidth: '55%',
            //                 endingShape: 'rounded'
            //             },
            //         },
            //         dataLabels: {
            //             enabled: false
            //         },
            //         stroke: {
            //             show: true,
            //             width: 2,
            //             colors: ['transparent']
            //         },
            //         xaxis: {
            //             categories: ['2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021',
            //                 '2022'],
            //             // categories: data.lineChartYears,
            //         },
            //         yaxis: {
            //             title: {
            //                 text: 'Yearly Growth',
            //                 color: '#fff'
            //             }
            //         },
            //         fill: {
            //             opacity: 1
            //         },
            //         colors: ['#ffd600', '#b71c1c', '#01579b'],
            //         tooltip: {
            //             y: {
            //                 formatter: function(val) {
            //                     return "$ " + val + " thousands"
            //                 }
            //             }
            //         }
            //     };

            //     var CountryWiseChart = new ApexCharts(document.querySelector("#country-wise-chart"),
            //         CountryWiseChartOptions);
            //     CountryWiseChart.render();
            var emergingData = data.emergingBusiness.sort(function(x, y) {
                return y.value - x.value;
            });
            addEmergingData(emergingData);
        });

        function addEmergingData(data) {
            $("#business-emerging tr").remove();

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

        Livewire.on('reportsUpdated', (data) => {
            ApexCharts.exec('forcast-chart', 'updateOptions', {
                xaxis: {
                    categories: data.forcastDates,
                    tickAmount: 10,
                },
            }, false, true);

            ApexCharts.exec('forcast-chart', 'updateSeries', [{
                data: data.forcastData
            }], true);
            // var emergingData = data.emergingBusiness.sort(function(x, y) {
            //     return y.value - x.value;
            // });
            // addEmergingData(emergingData);
        });
    </script>
@endpush
