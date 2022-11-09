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
            <div class="col-md-12 col-sm-12" wire:ignore>
                <div class="card bg-card-black">
                    <div class="card-body">
                        <div id="forcast-chart"></div>
                    </div>
                </div>
            </div>
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
            var forcastChartOptions = {
                series: [{
                        name: 'Business Forcast - Philippines - Construction Industry',
                        data: [
      53,
      57,
      79,
      34,
      61,
      53,
      63,
      72,
      55,
      56,
      41,
      49,
      54,
      84,
      81,
      53,
      58,
      62,
      70,
      69,
      60,
      67,
      65,
      79,
      88,
      92,
      78,
      77,
      83,
      82,
      109,
      72,
      93,
      94,
      62,
      64,
      90,
      118,
      100,
      82,
      98,
      73,
      117,
      70,
      108,
      76,
      75,
      58,
      96,
      133,
      147,
      118,
      127,
      142,
      164,
      127,
      145,
      136,
      89,
      111,
      138,
      163,
      157,
      125,
      150,
      155,
      198,
      162,
      156,
      150,
      148,
      119,
      204,
      208,
      201,
      119,
      202,
      175,
      199,
      226,
      173,
      178,
      169,
      20,
      0,
      1,
      32,
      128,
      166,
      205,
      277,
      234,
      188,
      193,
      139,
      104,
      115,
      173,
      112,
      206,
      177,
      182,
      261,
      168,
      219,
      168,
      137,
      114,
      584,
      731,
      724,
      543,
      652,
      640,
      804,
      658,
      693,
      672,
      505,
      545,
      720,
      902,
      894,
      669,
      805,
      789,
      992,
      811,
      854,
      829,
      623,
      672,
      888,
      1113,
      1103,
      826,
      993,
      973,
      1225,
      1001,
      1055,
      1023,
      769,
      829,
      1097,
      1375,
      1362,
      1019,
      1226,
      1202,
      1513,
      1236,
      1302,
      1263,
      948,
      1023,
      1354,
      1698,
      1682,
      1258,
      1514,
      1484,
      1870,
      1527,
      1608,
      1561,
      1171,
      1263,
      1673,
      2099,
      2079,
      1554,
      1870,
      1834,
      2311,
      1886,
      1987,
      1928,
      1446,
      1560,
      2067,
      2595,
      2571,
      1920,
      2312,
      2266,
      2858,
      2332,
      2457,
      2384,
      1786,
      1928,
      2556,
      3209,
      3179,
      2373,
      2859,
      2802,
      3535,
      2883,
      3038,
      2948,
      2207,
      2383,
      3161,
      3971,
      3934,
      2935,
      3537,
      3467,
      4376,
      3567,
      3759,
      3647,
      2730,
      2947,
      3911,
      4916,
      4870,
      3631,
      4378,
      4291,
      5418,
      4415,
      4654,
      4514,
      3377,
      3646,
      4842,
      6089,
      6032,
      4494,
      5421,
      5312,
      6711,
      5467,
      5763,
      5590,
      4179,
      4513
    ]
                        // data: data.forcastData
                    },
                    // {
                    //     name: 'Patent Forcast',
                    //     // data: [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 17, 2, 7, 5]
                    //     data: data.forcastData
                    // }
                ],
                chart: {
                    id: "forcast-chart",
                    height: 600,
                    type: 'line',
                    foreColor: '#fff',
                },
                forecastDataPoints: {
                    count: 120
                },
                stroke: {
                    width: 5,
                    curve: 'smooth'
                },
                xaxis: {
                    // type: 'datetime',
                    // categories: data.forcastDates,
                    categories: [
      "2011-01-01",
      "2011-02-01",
      "2011-03-01",
      "2011-04-01",
      "2011-05-01",
      "2011-06-01",
      "2011-07-01",
      "2011-08-01",
      "2011-09-01",
      "2011-10-01",
      "2011-11-01",
      "2011-12-01",
      "2012-01-01",
      "2012-02-01",
      "2012-03-01",
      "2012-04-01",
      "2012-05-01",
      "2012-06-01",
      "2012-07-01",
      "2012-08-01",
      "2012-09-01",
      "2012-10-01",
      "2012-11-01",
      "2012-12-01",
      "2013-01-01",
      "2013-02-01",
      "2013-03-01",
      "2013-04-01",
      "2013-05-01",
      "2013-06-01",
      "2013-07-01",
      "2013-08-01",
      "2013-09-01",
      "2013-10-01",
      "2013-11-01",
      "2013-12-01",
      "2014-01-01",
      "2014-02-01",
      "2014-03-01",
      "2014-04-01",
      "2014-05-01",
      "2014-06-01",
      "2014-07-01",
      "2014-08-01",
      "2014-09-01",
      "2014-10-01",
      "2014-11-01",
      "2014-12-01",
      "2015-01-01",
      "2015-02-01",
      "2015-03-01",
      "2015-04-01",
      "2015-05-01",
      "2015-06-01",
      "2015-07-01",
      "2015-08-01",
      "2015-09-01",
      "2015-10-01",
      "2015-11-01",
      "2015-12-01",
      "2016-01-01",
      "2016-02-01",
      "2016-03-01",
      "2016-04-01",
      "2016-05-01",
      "2016-06-01",
      "2016-07-01",
      "2016-08-01",
      "2016-09-01",
      "2016-10-01",
      "2016-11-01",
      "2016-12-01",
      "2017-01-01",
      "2017-02-01",
      "2017-03-01",
      "2017-04-01",
      "2017-05-01",
      "2017-06-01",
      "2017-07-01",
      "2017-08-01",
      "2017-09-01",
      "2017-10-01",
      "2017-11-01",
      "2017-12-01",
      "2018-01-01",
      "2018-02-01",
      "2018-03-01",
      "2018-04-01",
      "2018-05-01",
      "2018-06-01",
      "2018-07-01",
      "2018-08-01",
      "2018-09-01",
      "2018-10-01",
      "2018-11-01",
      "2018-12-01",
      "2019-01-01",
      "2019-02-01",
      "2019-03-01",
      "2019-04-01",
      "2019-05-01",
      "2019-06-01",
      "2019-07-01",
      "2019-08-01",
      "2019-09-01",
      "2019-10-01",
      "2019-11-01",
      "2019-12-01",
      "2020-01-01",
      "2020-02-01",
      "2020-03-01",
      "2020-04-01",
      "2020-05-01",
      "2020-06-01",
      "2020-07-01",
      "2020-08-01",
      "2020-09-01",
      "2020-10-01",
      "2020-11-01",
      "2020-12-01",
      "2021-01-01",
      "2021-02-01",
      "2021-03-01",
      "2021-04-01",
      "2021-05-01",
      "2021-06-01",
      "2021-07-01",
      "2021-08-01",
      "2021-09-01",
      "2021-10-01",
      "2021-11-01",
      "2021-12-01",
      "2022-01-01",
      "2022-02-01",
      "2022-03-01",
      "2022-04-01",
      "2022-05-01",
      "2022-06-01",
      "2022-07-01",
      "2022-08-01",
      "2022-09-01",
      "2022-10-01",
      "2022-11-01",
      "2022-12-01",
      "2023-01-01",
      "2023-02-01",
      "2023-03-01",
      "2023-04-01",
      "2023-05-01",
      "2023-06-01",
      "2023-07-01",
      "2023-08-01",
      "2023-09-01",
      "2023-10-01",
      "2023-11-01",
      "2023-12-01",
      "2024-01-01",
      "2024-02-01",
      "2024-03-01",
      "2024-04-01",
      "2024-05-01",
      "2024-06-01",
      "2024-07-01",
      "2024-08-01",
      "2024-09-01",
      "2024-10-01",
      "2024-11-01",
      "2024-12-01",
      "2025-01-01",
      "2025-02-01",
      "2025-03-01",
      "2025-04-01",
      "2025-05-01",
      "2025-06-01",
      "2025-07-01",
      "2025-08-01",
      "2025-09-01",
      "2025-10-01",
      "2025-11-01",
      "2025-12-01",
      "2026-01-01",
      "2026-02-01",
      "2026-03-01",
      "2026-04-01",
      "2026-05-01",
      "2026-06-01",
      "2026-07-01",
      "2026-08-01",
      "2026-09-01",
      "2026-10-01",
      "2026-11-01",
      "2026-12-01",
      "2027-01-01",
      "2027-02-01",
      "2027-03-01",
      "2027-04-01",
      "2027-05-01",
      "2027-06-01",
      "2027-07-01",
      "2027-08-01",
      "2027-09-01",
      "2027-10-01",
      "2027-11-01",
      "2027-12-01",
      "2028-01-01",
      "2028-02-01",
      "2028-03-01",
      "2028-04-01",
      "2028-05-01",
      "2028-06-01",
      "2028-07-01",
      "2028-08-01",
      "2028-09-01",
      "2028-10-01",
      "2028-11-01",
      "2028-12-01",
      "2029-01-01",
      "2029-02-01",
      "2029-03-01",
      "2029-04-01",
      "2029-05-01",
      "2029-06-01",
      "2029-07-01",
      "2029-08-01",
      "2029-09-01",
      "2029-10-01",
      "2029-11-01",
      "2029-12-01",
      "2030-01-01",
      "2030-02-01",
      "2030-03-01",
      "2030-04-01",
      "2030-05-01",
      "2030-06-01",
      "2030-07-01",
      "2030-08-01",
      "2030-09-01",
      "2030-10-01",
      "2030-11-01",
      "2030-12-01"
    ],
                    tickAmount: 10,
                    // labels: {
                    //     formatter: function(value, timestamp, opts) {
                    //         console.log(timestamp);
                    //         return opts.dateFormatter(new Date(timestamp), 'dd MMM')
                    //     }
                    // }
                },
                title: {
                    text: 'Business Forcast - Philippines - Construction Industry',
                    align: 'left',
                    style: {
                        fontSize: "16px",
                        color: '#fff'
                    }
                },
                colors: ['#b71c1c', '#ffd600', '#01579b'],
                // fill: {
                //     type: 'gradient',
                //     gradient: {
                //         shade: 'dark',
                //         gradientToColors: ['#FDD835'],
                //         shadeIntensity: 1,
                //         type: 'horizontal',
                //         opacityFrom: 1,
                //         opacityTo: 1,
                //         stops: [0, 100, 100, 100]
                //     },
                // },
                yaxis: {
                    min: 0,
                    max: 6500
                }
            };

            var forcastChart = new ApexCharts(document.querySelector("#forcast-chart"), forcastChartOptions);
            forcastChart.render();

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
