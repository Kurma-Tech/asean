        <!-- jQuery -->
        <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
        <!-- Bootstrap -->
        <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- Toastr -->
        <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
        <!-- AdminLTE -->
        <script src="{{asset('admin/dist/js/adminlte.js')}}"></script>

        <script>
            $(document).ready(function() {
                toastr.options = {
                    "positionClass": "toast-top-right"
                    , "progressBar": true
                , }

                window.addEventListener('close-modal', event => {
                    $('#modal-default').modal('toggle');
                });

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