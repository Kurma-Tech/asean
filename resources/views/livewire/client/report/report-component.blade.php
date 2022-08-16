<div>
    <div class="row">
        <div class="col-6 col-sm-6">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Report Data</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Year</th>
                                    <th>Total Business</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reportData as $key => $value)
                                <tr>
                                    <td>{{$key}}</td>
                                    <td>{{$value}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>

        </div>
        <div class="col-6 col-sm-6" wire:ignore>
            <!-- solid sales graph -->
            <div class="card bg-gradient-info">
                <div class="card-header border-0">
                    <h3 class="card-title">
                        <i class="fas fa-th mr-1"></i>
                        Market Graph
                    </h3>
                </div>
                <div class="card-body">
                    <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
                <div class="card-footer bg-transparent">
                    <div class="row">
                        <div class="col-4 text-center">
                            <input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60" data-fgColor="#39CCCC">

                            <div class="text-white">Business</div>
                        </div>
                        <!-- ./col -->
                        <div class="col-4 text-center">
                            <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60" data-fgColor="#39CCCC">

                            <div class="text-white">Patient</div>
                        </div>
                        <!-- ./col -->
                        <div class="col-4 text-center">
                            <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60" data-fgColor="#39CCCC">

                            <div class="text-white">Journals</div>
                        </div>
                        <!-- ./col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

@push('extra-scripts')
    <!-- ChartJS -->
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    {{-- <script src="{{asset('client/dist/js/home.js')}}"></script> --}}
    <script>
        $(function () {
            var record = @json($records);

            'use strict'
            /* jQueryKnob */
            $('.knob').knob()

            // Sales graph chart
            var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d')
            // $('#revenue-chart').get(0).getContext('2d');

            var salesGraphChartData = {
                labels: record.label,
                datasets: [
                {
                    label: 'Business',
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
                    spanGaps: true,
                    borderColor: '#efefef',
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointColor: '#efefef',
                    pointBackgroundColor: '#efefef',
                    data: record.data
                }
                ]
            }

            var salesGraphChartOptions = {
                maintainAspectRatio: false,
                responsive: true,
                legend: {
                display: false
                },
                scales: {
                xAxes: [{
                    ticks: {
                    fontColor: '#efefef'
                    },
                    gridLines: {
                    display: true,
                    color: '#efefef',
                    drawBorder: false
                    }
                }],
                yAxes: [{
                    ticks: {
                    stepSize: 500,
                    fontColor: '#efefef'
                    },
                    gridLines: {
                    display: true,
                    color: '#efefef',
                    drawBorder: false
                    }
                }]
                }
            }

            // This will get the first returned node in the jQuery collection.
            // eslint-disable-next-line no-unused-vars
            var salesGraphChart = new Chart(salesGraphChartCanvas, { // lgtm[js/unused-local-variable]
                type: 'line',
                data: salesGraphChartData,
                options: salesGraphChartOptions
            })
        })
    </script>
@endpush
