<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">@yield('title')</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @foreach($segments = request()->segments() as $index => $segment)
                    <li class="breadcrumb-item" style="text-transform: capitalize;">
                        @if($segment === 'admin')
                        <a href="{{route('admin.dashboard')}}">
                        @endif
                            {{ ($segment === 'admin') ? 'Dashboard': $segment }}
                        @if($segment === 'admin')
                        </a>
                        @endif
                    </li>
                    @endforeach
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->