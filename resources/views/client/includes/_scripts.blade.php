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

<script>
    jQuery().ready(function() { /* Custom select design */ 
    jQuery('.drop-down').append('<div class="button"></div>'); 
    jQuery('.drop-down').append('<ul class="select-list"></ul>'); 
    jQuery('.drop-down select option').each(function() { 
        var bg = jQuery(this).css('background-image'); 
        jQuery('.select-list').append('<li class="clsAnchor"><span value="' + 
        jQuery(this).val() + '" class="' + jQuery(this).attr('class') + '" style=background-image:' + bg + '>' + jQuery(this).text() + '</span></li>'); 
    }); 
    jQuery('.drop-down .button').html('<a href="javascript:void(0);" class="select-list-link"><span style=background-image:' + 
    jQuery('.drop-down select').find(':selected').css('background-image') + '>' + 
    jQuery('.drop-down select').find(':selected').text() + '</span>' + ' <i class="fas fa-caret-down"></i> </a>'); 
    jQuery('.drop-down ul li').each(function() { 
        if (jQuery(this).find('span').text() == jQuery('.drop-down select').find(':selected').text()) { 
            jQuery(this).addClass('active'); 
        } }); 
        jQuery('.drop-down .select-list span').on('click', function() { 
            var dd_text = jQuery(this).text(); 
            var dd_img = jQuery(this).css('background-image'); 
            var dd_val = jQuery(this).attr('value'); 
            $(".changeLang").val(dd_val).change();
            jQuery('.drop-down .button').html('<a href="javascript:void(0);" class="select-list-link"><span style=background-image:' + dd_img + '>' + dd_text + '</span> <i class="fas fa-caret-down"></i> </a>'); 
            jQuery('.drop-down .select-list span').parent().removeClass('active'); 
            jQuery(this).parent().addClass('active'); $('.drop-down select[name=options]').val( dd_val ); $('.drop-down .select-list li').slideUp(); 
        }); 
        jQuery('.drop-down .button').on('click','a.select-list-link', function() { 
            jQuery('.drop-down ul li').slideToggle(); 
        }); /* End */ 
    });
</script>

<script type="text/javascript">
    
    var url = "{{ route('client.changeLang') }}";
    
    $(".changeLang").change(function(){
        window.location.href = url + "?lang="+ $(this).val();
    });
    
</script>

@livewireScripts

@stack('extra-scripts')