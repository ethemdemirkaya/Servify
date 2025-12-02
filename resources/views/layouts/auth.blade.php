<!DOCTYPE html>
<html lang="tr" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- 1. DİNAMİK TITLE: Sayfa başlığı varsa onu, yoksa Site Adını yazar -->
    <title>@yield('title', '') | {{setting('site_name', 'Laravel Restoran')}}</title>

    <meta name="Description" content="{{ setting('site_description', 'Restoran Yönetim Sistemi') }}">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="dashboard template,bootstrap admin,pos system">

    <!-- 2. DİNAMİK FAVICON -->
    <link rel="icon" href="{{ asset(setting('favicon', 'assets/images/brand-logos/favicon.ico')) }}" type="image/x-icon">

    <!-- Main Theme Js -->
    <script src="{{ asset('assets/js/authentication-main.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" >

    <!-- Style Css -->
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet" >

    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" >

</head>

<body class="authentication-background">
<div class="authentication-basic-background">
    <!-- Arka plan resmi -->
    <img src="{{ asset('assets/images/media/backgrounds/9.png') }}" alt="">
</div>

<div class="container">

    @yield('content')
</div>


<!-- Bootstrap JS -->
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Show Password JS -->
<script src="{{ asset('assets/js/show-password.js') }}"></script>

</body>
</html>
