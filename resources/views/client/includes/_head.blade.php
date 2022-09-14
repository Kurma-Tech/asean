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
        min-width: 450px;
        padding: 15px 15px 20px;
        background-color: rgba(0,0,0,.3);
        z-index: 9;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }
    #map-overlay-scroll::-webkit-scrollbar-track
    {
        background-color: hsl(0deg 0% 2%);
    }

    #map-overlay-scroll::-webkit-scrollbar
    {
        width: 6px;
        background-color: #F5F5F5;
    }

    #map-overlay-scroll::-webkit-scrollbar-thumb
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.1);
        background-color: hsl(18deg 3% 6%);
    }

    .map-overlay-box > .overlay-title { font-size: 1.5em; line-height: 1em; font-weight: 600; margin: 0; padding: 0; margin-bottom: .3em; }
    .map-overlay-box > .data-report-title { font-size: 1em; line-break: 1em; font-weight: 500; margin: 0; padding: 0; margin-bottom: .3em; }
    .map-overlay-box > .data-report-count { font-size: 1em; line-break: 1em; font-weight: 400; margin: 0; padding: 0; margin-bottom: .3em; }


    #filter-wrapper {
        z-index: 99;
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        min-width: 300px;
        height: 100%;
        transform: translateX(300px);
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
        width: 250px;
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
        right: 300px;
        width: 300px;
        -webkit-transition: all 0.4s ease 0s;
        -moz-transition: all 0.4s ease 0s;
        -ms-transition: all 0.4s ease 0s;
        -o-transition: all 0.4s ease 0s;
        transition: all 0.4s ease 0s;
    }
    .square { display: inline-block; padding: 6px; text-align: right; color: hsl(340deg 82% 52%);}
    .square:hover { color: hsl(340deg 72% 42%)!important; }

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
        font-size: 17px;
        line-height: 21px;
        color: #e92063;
        display: inline-block;
    }
</style>

@livewireStyles

@stack('extra-styles')
