<!DOCTYPE html>
<html lang="en">
<head>
  @include('client/includes/_head')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{asset('client/dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
        </div>

        <!-- Navbar -->
        @include('client/includes/_navigation')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('client/includes/_sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper p-3">
            {{$slot}}
        </div>
        <!-- /.content-wrapper -->

        @include('client/includes/_footer')
    </div>
    <!-- ./wrapper -->

    @include('client/includes/_scripts')

</body>
</html>

