<div>
    <div class="row">
        <div class="col-12 col-sm-12 p-3">
            <h3>Current Report</h3>
        </div>
    </div>
    <div class="row">
        {{-- <div class="col-md-6 col-sm-12" wire:ignore>
            <div class="card">
                <div class="card-body">
                    <div id="forcast-chart"></div>
                </div>
            </div>
        </div> --}}
        <div class="col-md-6 col-sm-12" wire:ignore>
            <div class="card">
                <div class="card-body">
                    <div id="line-chart"></div>
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
        <div class="col-md-6 col-sm-12" wire:ignore>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Business Statistics</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Year</th>
                                <th>No. Business Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-widget="expandable-table" aria-expanded="false">
                                <td>1</td>
                                <td>2011</td>
                                <td>11033</td>
                            </tr>
                            <tr class="expandable-body d-none">
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('extra-styles')
@endpush

@push('extra-scripts')
    <script>
        Livewire.on('reportsUpdated', (data) => {
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
                    text: 'Page Statistics',
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
                colors: ['#ffd600', '#b71c1c', '#01579b'],
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

            lineChart.updateOptions({
                series: [
                    {
                        name: "Business",
                        data: data.businessCountByYears
                    },
                    {
                        name: "Patent",
                        data: data.patentCountByYears
                    },
                    {
                        name: 'Journal',
                        data: []
                    }
                ],
                xaxis: {
                    categories: data.lineChartYears
                }
            })

            // Country Wise
            // var CountryWiseChartOptions = {
            //     series: [{
            //         name: 'Business',
            //         // data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
            //         data: data.businessCountByYears
            //     }, {
            //         name: 'Patent',
            //         // data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
            //         data: data.patentCountByYears
            //     }, {
            //         name: 'Journal',
            //         // data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
            //         data: ['1']
            //     }],
            //     chart: {
            //         type: 'bar',
            //         foreColor: '#fff',
            //         height: 350
            //     },
            //     plotOptions: {
            //         bar: {
            //             horizontal: false,
            //             columnWidth: '55%',
            //             endingShape: 'rounded'
            //         },
            //     },
            //     dataLabels: {
            //         enabled: false
            //     },
            //     stroke: {
            //         show: true,
            //         width: 2,
            //         colors: ['transparent']
            //     },
            //     xaxis: {
            //         // categories: ['2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022'],
            //         categories: data.lineChartYears,
            //     },
            //     yaxis: {
            //         title: {
            //             text: 'Yearly Growth',
            //             color: '#fff'
            //         }
            //     },
            //     fill: {
            //         opacity: 1
            //     },
            //     colors: ['#ffd600', '#b71c1c', '#01579b'],
            //     tooltip: {
            //         y: {
            //             formatter: function(val) {
            //                 return "$ " + val + " thousands"
            //             }
            //         }
            //     }
            // };

            // var CountryWiseChart = new ApexCharts(document.querySelector("#country-wise-chart"), CountryWiseChartOptions);
            // CountryWiseChart.render();

        });

        // Forcast
        // var forcastChartOptions = {
        //     series: [{
        //         name: 'Data Forcast',
        //         // data: [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5]
        //         data: []
        //     }],
        //     chart: {
        //         height: 350,
        //         type: 'line',
        //         foreColor: '#fff',
        //     },
        //     forecastDataPoints: {
        //         count: 7
        //     },
        //     stroke: {
        //         width: 5,
        //         curve: 'smooth'
        //     },
        //     xaxis: {
        //         type: 'datetime',
        //         categories: ['1/11/2022', '2/11/2000', '3/11/2000', '4/11/2000', '5/11/2000', '6/11/2000', '7/11/2000',
        //             '8/11/2000', '9/11/2000', '10/11/2000', '11/11/2000', '12/11/2000', '1/11/2001', '2/11/2001',
        //             '3/11/2001', '4/11/2001', '5/11/2001', '6/11/2001'
        //         ],
        //         tickAmount: 10,
        //         labels: {
        //             formatter: function(value, timestamp, opts) {
        //                 return opts.dateFormatter(new Date(timestamp), 'dd MMM')
        //             }
        //         }
        //     },
        //     title: {
        //         text: 'Forecast',
        //         align: 'left',
        //         style: {
        //             fontSize: "16px",
        //             color: '#fff'
        //         }
        //     },
        //     fill: {
        //         type: 'gradient',
        //         gradient: {
        //             shade: 'dark',
        //             gradientToColors: ['#FDD835'],
        //             shadeIntensity: 1,
        //             type: 'horizontal',
        //             opacityFrom: 1,
        //             opacityTo: 1,
        //             stops: [0, 100, 100, 100]
        //         },
        //     },
        //     yaxis: {
        //         min: -10,
        //         max: 40
        //     }
        // };

        // var forcastChart = new ApexCharts(document.querySelector("#forcast-chart"), forcastChartOptions);
        // forcastChart.render();
    </script>
@endpush
