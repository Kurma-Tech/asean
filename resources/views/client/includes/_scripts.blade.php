<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('client/dist/js/adminlte.js')}}"></script>

<script src="{{ asset('client/dist/js/apexcharts.min.js') }}"></script>

<script>
    // Close menu
    $("#close-filter").click(function(e) {
        e.preventDefault();
        $("#filter-wrapper").toggleClass("active");
    });
    // Open menu
    $("#filter-toggle").click(function(e) {
        e.preventDefault();
        $("#filter-wrapper").toggleClass("active");
    });
    // Scroll To
    $("#view-report-element").click(function() {
        enableScroll(); // enable scroll
        $('html, body').animate({
            scrollTop: $("#reportSection").offset().top
        }, 1000);
        disableScroll(); // disable scroll
    });

    $("#view-map-element").click(function() {
        enableScroll(); // enable scroll
        $('html, body').animate({
            scrollTop: $("#mapSection").offset().top-45
        }, 1000);
        disableScroll(); // disable scroll
    });

    function disableScroll() { 
        document.body.classList.add("remove-scrolling"); 
    } 

    function enableScroll() { 
        document.body.classList.remove("remove-scrolling"); 
    }
</script>

@livewireScripts

@stack('extra-scripts')