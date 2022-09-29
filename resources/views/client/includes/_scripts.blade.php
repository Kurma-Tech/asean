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

<!-- Toastr -->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

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
    
    $(document).ready(function() {
        toastr.options = {
            "positionClass": "toast-top-right", 
            "progressBar": true,
        }
        
        window.addEventListener('close-auth-modal', event => {
            $('.modal-auth').modal('toggle');
        });

        window.addEventListener('success-message', event => {
            toastr.success(event.detail.message, 'Success!');
        });

        window.addEventListener('error-message', event => {
            toastr.error(event.detail.message, 'Error!');
        });

        window.livewire.onError(statusCode => {
            if (statusCode === 419) {
                alert('Your Session Time Out Please Refresh The Page');
                return false
            }
        });
    });
</script>

@livewireScripts

@stack('extra-scripts')