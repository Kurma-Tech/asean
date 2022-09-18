<!DOCTYPE html>
<html lang="en">
<head>
  @include('client/includes/_head')
</head>
<body class="hold-transition layout-footer-fixed dark-mode layout-navbar-fixed layout-fixed remove-scrolling">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{asset('client/dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- Navbar -->
        @include('client/includes/_navigation')
        <!-- /.navbar -->
        
        {{$slot}}
        <!-- /.content-wrapper -->

        {{-- @include('client/includes/_footer') --}}
    </div>
    <!-- ./wrapper -->
    @include('client/includes/_scripts')
</body>
</html>

