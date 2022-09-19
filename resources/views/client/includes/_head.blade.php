<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Asean | Home</title>
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('client/dist/css/adminlte.css')}}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
<link rel="stylesheet" href="{{ asset('client/dist/css/apexcharts.css') }}">
<style>
.warning,.danger,.info { background-color: #dfdfdf; }
.warning.active { background-color: #ffc107; color: antiquewhite !important; }
.danger.active { background-color: #dc3545; color: antiquewhite !important; }
.info.active { background-color: #17a2b8; color: antiquewhite !important; }
body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .content-wrapper, body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer, body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-header {
    transition: margin-left 0.3s ease-in-out;
    margin-left: 0px;
}
</style>

<style>
    #countryChart {
        max-width: 650px;
        margin: 15px 0;
    }
    .overflow-control { overflow: hidden; }
    .map-overlay-box {
        position: absolute;
        top: 0;
        bottom: 0;
        height: 100%;
        overflow-y: auto;
        min-width: 320px;
        max-width: 320px;
        padding: 15px 15px 20px;
        background-color: rgba(0,0,0,.3);
        z-index: 9;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }
    .overlay-scroll::-webkit-scrollbar-track
    {
        background-color: hsl(0deg 0% 2%);
    }

    .overlay-scroll::-webkit-scrollbar
    {
        width: 6px;
        background-color: #F5F5F5;
    }

    .overlay-scroll::-webkit-scrollbar-thumb
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.1);
        background-color: hsl(18deg 3% 6%);
    }

    .map-overlay-box > .overlay-title { font-size: 1.5em; line-height: 1em; font-weight: 600; margin: 0; padding: 0; margin-bottom: .3em; }
    .map-overlay-box > .data-report-title { font-size: 1em; line-break: 1em; font-weight: 500; margin: 0; padding: 0; margin-bottom: .3em; }
    .map-overlay-box > .data-report-count { font-size: 1em; line-break: 1em; font-weight: 400; }


    #filter-wrapper {
        z-index: 8;
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        min-width: 450px;
        height: 100%;
        transform: translateX(450px);
        overflow-y: auto;
        -webkit-transition: all 0.4s ease 0s;
        -moz-transition: all 0.4s ease 0s;
        -ms-transition: all 0.4s ease 0s;
        -o-transition: all 0.4s ease 0s;
        transition: all 0.4s ease 0s;
        background-color: rgba(0,0,0,.3);
        backdrop-filter: blur(5px);
        padding: 15px;
    }
    
    .filter-nav {
        position: absolute;
        top: 0;
        width: 450px;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    
    #filter-toggle {
        z-index: 1;
        position: absolute;
        top: 15px;
        right: 15px;
    }
    
    #filter-wrapper.active {
        right: 450px;
        width: 450px;
        -webkit-transition: all 0.4s ease 0s;
        -moz-transition: all 0.4s ease 0s;
        -ms-transition: all 0.4s ease 0s;
        -o-transition: all 0.4s ease 0s;
        transition: all 0.4s ease 0s;
    }
    .square { display: inline-block; padding: 6px; text-align: right; color: #ffc107;}
    .square:hover { color: #ffc107d3!important; }

    .square-close { display: inline-block; padding: 6px; text-align: right; color: #e91e63;}
    .square-close:hover { color: #c91c56!important; }

    .filter-inputs { margin-top: 20px; }

    .apexcharts-tooltip,
    .apexcharts-menu-item {
        color: rgb(0, 0, 0);
    }
    #reportSection {
        height: 90vh;
        overflow-y: scroll;
    }
    #reportSection::-webkit-scrollbar-track
    {
        background-color: hsl(0deg 0% 2%);
    }

    #reportSection::-webkit-scrollbar
    {
        width: 6px;
        background-color: #F5F5F5;
    }

    #reportSection::-webkit-scrollbar-thumb
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.1);
        background-color: hsl(18deg 3% 6%);
    }

    .remove-scrolling { 
        height: 100%; 
        overflow: hidden; 
    }
    .view-report {
        cursor: pointer;
        border-bottom: 1px dashed;
        color: #8bc34a;
        display: inline-block;
    }
    .search-title {
        font-size: 18px;
        line-height: 35px;
        text-transform: uppercase;
        border-bottom: 1px dashed #8bc34a;
        display: inline-block;
        color: #8bc34a;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .lemongreen {color: #8bc34a;}
    .lemongreen-bg {background: #8bc34a;}

    hr {
        margin-top: 1rem;
        margin-bottom: 1rem;
        border: 0;
        border-top: 1px solid rgb(255 255 255 / 26%);
    }

    .dark-mode .preloader {
        background-color: rgb(69, 77, 85, .3) !important;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }
</style>

@livewireStyles

@stack('extra-styles')
