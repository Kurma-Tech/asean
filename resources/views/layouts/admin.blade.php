<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.includes._head')
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('admin.includes._navigation')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('admin.includes._sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper p-3">

            @include('admin.includes._breadcrumbs')

            {{$slot}}
            
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        @include('admin.includes._footer')
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    @include('admin.includes._scripts')
</body>
</html>
