<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
    <x-meta />
</head>
<!-- [Head] end -->

<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ Sidebar Menu ] start -->
    @if (!request()->is('login','register','kunjungan/mandiri'))
    <x-sidebar />
    @endif
    <!-- [ Sidebar Menu ] end -->

    <!-- [ Header Topbar ] start -->
    @if (!request()->is('login','register','kunjungan/mandiri'))
    <x-header />
    @endif
    <!-- [ Header ] end -->



    <!-- [ Main Content ] start -->
    @if (!request()->is('login','register','kunjungan/mandiri'))
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <!-- <x-breadcrumb /> -->
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    @else
    <div class="row">
        @yield('content')
    </div>
    @endif


    @if (!request()->is('login','register','kunjungan/mandiri'))
    <x-footer />
    @endif

    <!-- [Page Specific JS] start -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="{{asset('template/dist')}}/assets/js/plugins/apexcharts.min.js"></script>
    <script src="{{asset('template/dist')}}/assets/js/pages/dashboard-default.js"></script>
    <!-- [Page Specific JS] end -->
    <!-- Required Js -->
    <script src="{{asset('template/dist')}}/assets/js/plugins/popper.min.js"></script>
    <script src="{{asset('template/dist')}}/assets/js/plugins/simplebar.min.js"></script>
    <script src="{{asset('template/dist')}}/assets/js/plugins/bootstrap.min.js"></script>
    <script src="{{asset('template/dist')}}/assets/js/fonts/custom-font.js"></script>
    <script src="{{asset('template/dist')}}/assets/js/pcoded.js"></script>
    <script src="{{asset('template/dist')}}/assets/js/plugins/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')

    <script>
        layout_change('light');
    </script>

    <script>
        change_box_container('false');
    </script>

    <script>
        layout_rtl_change('false');
    </script>

    <script>
        preset_change("preset-1");
    </script>

    <script>
        font_change("Public-Sans");
    </script>
    @include('sweetalert::alert')
</body>
<!-- [Body] end -->

</html>