<div>
    @include('client/includes/_sidebar')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper p-3">
        <section class="content">
            <div class="container-fluid">
                @livewire('client.map.map-component')
            </div>
        </section>
    </div>
</div>
