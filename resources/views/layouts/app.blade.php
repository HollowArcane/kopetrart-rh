@php
    use App\Models\Notification;
    $notifications = Notification::get_relevant(session('role'));
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GRH - Application</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicons -->

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('app/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fonts/fontawesome-all.min.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/template.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/table.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/formulaire.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/pagination.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/lib/fa/css/all.min.css" />

    <link rel="stylesheet" href="{{ asset('assets/css/layouts-app.css') }}">

    <link rel="stylesheet" type="text/css" href="/lib/toolkit/css/chart.toolkit.css" />
    <link rel="stylesheet" type="text/css" href="/lib/mdb5/css/mdb.min.css" />

    <script type="text/javascript" src="/lib/toolkit/js/chart.umd.js" defer></script>
    <script type="text/javascript" src="/lib/toolkit/js/chart.toolkit.js" defer></script>

    <script type="text/javascript" src="/lib/mdb5/js/mdb.umd.min.js" defer></script>
    @stack('styles')
    <!-- =======================================================
    * Template Name: NiceAdmin - v2.5.0
    * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->

</head>
<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <meta charset="UTF-8">
        <div class="d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
                    <img src="{{ asset('assets/img/logo_sisal.png') }}" alt="">
                    <span class="d-none d-lg-block">GRH Application</span>
                </a>
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div><!-- End Logo -->
            <x-notification.main :notifications="$notifications ?? collect([])"> <i class="fa fa-bell"></i> </x-notification.main>
        </div>
    </header><!-- End Header -->
    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        @include('includes.navbar')
    </aside>

    <main id="main" class="main">
        @yield('content')
    </main><!-- End Main -->

<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
  <div class="copyright">
    &copy; Copyright <strong><span>ETU-2597</span></strong>. All Rights Reserved
  </div>
  <div class="credits">
    <!-- All the links in the footer should remain intact. -->
    <!-- You can delete the links only if you purchased the pro version. -->
    <!-- Licensing information: https://bootstrapmade.com/license/ -->
    <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
    Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
  </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('assets/js/main1.js') }}"></script>
@stack('scripts')

</body>
</html>
