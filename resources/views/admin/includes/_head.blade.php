        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ASEAN | @yield("title")</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
        <!-- IonIcons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('admin/dist/css/adminlte.min.css')}}">
        <!-- Toastr -->
        <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

        <style>
                .elevation-2{box-shadow:0 0px 6px rgba(0,0,0,0.16),0 3px 3px rgba(0,0,0,.23)!important;}
                .pull-right{float:right;}
                .clear-fix{content:'';display:table;clear:both;}
                .error{color:#bd2130;}
                .blockquote{font-size: 15px;border-left:0.2rem solid#17a2b8;margin:0.5em 0rem;padding:0.5em 0.7rem;}
                .strong-code{font-size:12px;display:block;}
                .nav-header {font-size:12px!important;font-weight:600;color:#fff!important;border-top:1px solid #707070;}
                [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link {color: #ffffff;background-color: rgba(255,255,255,.1);}
                [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link:hover {background-color: #607d8b;color: #fff;}
                .navbar-dark {background-color: #3f51b5!important;border-color: #0087ff!important;}
                .layout-navbar-fixed .wrapper .sidebar-dark-success .brand-link:not([class*=navbar]) {background-color: #3f51b5!important;}
                .info a {color: #8bc34a!important;}
                .sidebar::-webkit-scrollbar-track
                {
                        background-color: hsl(210deg 10% 23%);
                }

                .sidebar::-webkit-scrollbar
                {
                        width: 6px;
                        background-color: #F5F5F5;
                }

                .sidebar::-webkit-scrollbar-thumb
                {
                        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.1);
                        background-color: hsl(0deg 0% 35%);
                }
        </style>

        @livewireStyles

        @stack('extra-styles')