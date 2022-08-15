<div>
    @include('client/includes/_sidebar')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper p-3">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-sm-12">
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
                                        Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam.
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
