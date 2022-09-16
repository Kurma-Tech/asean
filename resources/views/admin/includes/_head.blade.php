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
                .elevation-2 {box-shadow: 0 0px 6px rgba(0,0,0,0.16),0 3px 3px rgba(0,0,0,.23)!important;}
                .pull-right{float: right;}
                .clear-fix{content: '';display: table;clear: both;}
                .error{color: #bd2130;}
                .blockquote{font-size: 15px;border-left: 0.2rem solid #17a2b8;margin: 0.5em 0rem;padding: 0.5em 0.7rem;}
                .strong-code{font-size: 12px;display:block;}
        </style>

        @livewireStyles

        @stack('extra-styles')