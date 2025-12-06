
<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="horizontal" data-theme-mode="dark" data-header-styles="transparent"
      data-width="fullwidth" data-menu-styles="transparent" data-page-style="flat" loader="enable"
      data-nav-style="menu-click" jstcache="0" style="" data-toggled="close">
<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', '') | {{setting('site_name', 'Laravel Restoran')}}</title>
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="admin dashboard html,admin html template,admin panel bootstrap template,admin panel html template,admin template html,bootstrap admin panel,bootstrap html template,bootstrap template,bootstrap with html,dashboard html template,dashboards ui,html admin dashboard,html bootstrap,html dashboard template,html template">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

    <!-- Choices JS -->
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- Main Theme Js -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    <!-- Node Waves Css -->
    <link href="{{ asset('assets/libs/node-waves/waves.min.css') }}" rel="stylesheet">

    <!-- Simplebar Css -->
    <link href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet">

    <!-- Color Picker Css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpfickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/%40simonwep/pickr/themes/nano.min.css') }}">

    <!-- Choices Css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}">

    <!-- FlatPickr CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">

    <!-- Auto Complete CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/%40tarekraafat/autocomplete.js/css/autoComplete.css') }}">

    @stack('styles')

</head>

<body>
<div class="progress-top-bar"></div>


<!-- Loader -->
<div id="loader">
    <img src="{{ asset('assets/images/media/loader.svg') }}" alt="">
</div>
<!-- Loader -->

<div class="page">
    <!-- app-header -->
    <header class="app-header sticky" id="header">

        <!-- Start::main-header-container -->
        <div class="main-header-container container-fluid">

            <!-- Start::header-content-left -->
            <div class="header-content-left">

                <!-- Start::header-element -->
                <div class="header-element">
                    <div class="horizontal-logo">
                        <a href="index.html" class="header-logo">
                            <img src="../assets/images/brand-logos/desktop-logo.png" alt="logo" class="desktop-logo">
                            <img src="../assets/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
                            <img src="../assets/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
                            <img src="../assets/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
                        </a>
                    </div>
                </div>
                <!-- End::header-element -->

                <!-- Start::header-element -->
                <div class="header-element mx-lg-0 mx-2">
                    <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                </div>
                <!-- End::header-element -->



            </div>
            <!-- End::header-content-left -->

            <!-- Start::header-content-right -->
            <ul class="header-content-right">



                <!-- Start::header-element -->
                <li class="header-element header-theme-mode">
                    <!-- Start::header-link|layout-setting -->
                    <a href="javascript:void(0);" class="header-link layout-setting">
                    <span class="light-layout">
                        <!-- Start::header-link-icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" viewbox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M108.11,28.11A96.09,96.09,0,0,0,227.89,147.89,96,96,0,1,1,108.11,28.11Z" opacity="0.2"></path><path d="M108.11,28.11A96.09,96.09,0,0,0,227.89,147.89,96,96,0,1,1,108.11,28.11Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path></svg>
                        <!-- End::header-link-icon -->
                    </span>
                        <span class="dark-layout">
                        <!-- Start::header-link-icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" viewbox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><circle cx="128" cy="128" r="56" opacity="0.2"></circle><line x1="128" y1="40" x2="128" y2="32" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><circle cx="128" cy="128" r="56" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></circle><line x1="64" y1="64" x2="56" y2="56" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><line x1="64" y1="192" x2="56" y2="200" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><line x1="192" y1="64" x2="200" y2="56" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><line x1="192" y1="192" x2="200" y2="200" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><line x1="40" y1="128" x2="32" y2="128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><line x1="128" y1="216" x2="128" y2="224" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><line x1="216" y1="128" x2="224" y2="128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line></svg>
                            <!-- End::header-link-icon -->
                    </span>
                    </a>
                    <!-- End::header-link|layout-setting -->
                </li>
                <!-- End::header-element -->

                <!-- Start::header-element -->
                <li class="header-element header-fullscreen">
                    <!-- Start::header-link -->
                    <a onclick="openFullscreen();" href="javascript:void(0);" class="header-link">
                        <svg xmlns="http://www.w3.org/2000/svg" class="full-screen-open header-link-icon" viewbox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><rect x="48" y="48" width="160" height="160" opacity="0.2"></rect><polyline points="168 48 208 48 208 88" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><polyline points="88 208 48 208 48 168" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><polyline points="208 168 208 208 168 208" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><polyline points="48 88 48 48 88 48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="full-screen-close header-link-icon d-none" viewbox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><rect x="32" y="32" width="192" height="192" rx="16" opacity="0.2"></rect><polyline points="160 48 208 48 208 96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><line x1="144" y1="112" x2="208" y2="48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><polyline points="96 208 48 208 48 160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline><line x1="112" y1="144" x2="48" y2="208" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line></svg>
                    </a>
                    <!-- End::header-link -->
                </li>
                <!-- End::header-element -->

                <!-- Start::header-element -->
                <li class="header-element dropdown">
                    <!-- Start::header-link|dropdown-toggle -->
                    <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="me-sm-2 me-0">
                                <!-- Avatar Kısmı: User Model'deki 'initials' fonksiyonunu çağırır -->
                                <span class="avatar avatar-ms avatar-rounded bg-primary text-white fw-bold">
                    {{ auth()->user()->initials }}
                </span>
                            </div>

                        </div>
                    </a>
                    <!-- End::header-link|dropdown-toggle -->

                    <div class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                        <div class="p-3 bg-primary text-fixed-white">
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0 fs-16">Profil</p>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="p-3">
                            <div class="d-flex align-items-start gap-2">
                                <div class="lh-1">
                                    <!-- Dropdown içindeki büyük avatar -->
                                    <span class="avatar avatar-md avatar-rounded bg-primary-transparent text-primary fw-bold">
                         {{ auth()->user()->initials }}
                    </span>
                                </div>
                                <div>
                                    <span class="d-block fw-semibold lh-1">{{ auth()->user()->name }}</span>
                                    <span class="text-muted fs-12">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <ul class="list-unstyled mb-0">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="#"><i class="ti ti-user-circle me-2 fs-18"></i>Profili Görüntüle</a>
                            </li>
                            <li>
                                <!-- Çıkış İşlemi -->
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="ti ti-logout me-2 fs-18"></i>Çıkış Yap
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- End::header-element -->


            </ul>
            <!-- End::header-content-right -->

        </div>
        <!-- End::main-header-container -->

    </header>
    <!-- /app-header -->
    <!-- Start::app-sidebar -->
    <!-- Start::app-sidebar -->
    <!-- Start::app-sidebar -->
    <aside class="app-sidebar sticky" id="sidebar">

        <!-- Header -->
        <div class="main-sidebar-header">
            <a href="/" class="header-logo">
                <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
                <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
                <img src="{{ asset('assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
            </a>
        </div>

        @php
            $role = auth()->user()->role;
        @endphp

        <div class="main-sidebar" id="sidebar-scroll">
            <nav class="main-menu-container nav nav-pills flex-column sub-open">
                <div class="slide-left" id="slide-left">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path></svg>
                </div>

                <ul class="main-menu">
                    <!-- Kategori: Ana Menü -->
                    <li class="slide__category"><span class="category-name">Genel Bakış</span></li>

                    <li class="slide">
                        <a href="/dashboard" class="side-menu__item {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="side-menu__icon ti ti-smart-home"></i>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>

                    <!-- POS Sistemi -->
                    @if(in_array($role, ['admin', 'waiter', 'cashier']))
                        <li class="slide">
                            <a href="/pos" class="side-menu__item {{ request()->is('pos') ? 'active' : '' }}">
                                <i class="side-menu__icon ti ti-device-desktop-analytics"></i>
                                <span class="side-menu__label">POS Sistemi</span>
                            </a>
                        </li>
                    @endif

                    <!-- Kategori: Operasyon -->
                    <li class="slide__category"><span class="category-name">Operasyon</span></li>

                    <!-- SİPARİŞLER (Parent: orders/*) -->
                    <li class="slide has-sub {{ request()->is('orders*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ request()->is('orders*') ? 'active' : '' }}">
                            <i class="side-menu__icon ti ti-receipt-2"></i>
                            <span class="side-menu__label">Siparişler</span>
                            <i class="ri-arrow-right-s-line side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            @if(in_array($role, ['admin', 'waiter', 'cashier']))
                                <li class="slide">
                                    <a href="/orders/active" class="side-menu__item {{ request()->is('orders/active') ? 'active' : '' }}">Aktif Siparişler</a>
                                </li>
                            @endif

                            @if(in_array($role, ['admin', 'chef']))
                                <li class="slide">
                                    <a href="/orders/kitchen" class="side-menu__item {{ request()->is('orders/kitchen') ? 'active' : '' }}">Mutfak Ekranı</a>
                                </li>
                            @endif

                            @if(in_array($role, ['admin', 'cashier']))
                                <li class="slide">
                                    <a href="/orders/history" class="side-menu__item {{ request()->is('orders/history') ? 'active' : '' }}">Sipariş Geçmişi</a>
                                </li>
                            @endif
                        </ul>
                    </li>

                    <!-- SALON & REZERVASYON (Parent: tables* veya reservations*) -->
                    @if(in_array($role, ['admin', 'waiter']))
                        <li class="slide has-sub {{ request()->is('tables*', 'reservations*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->is('tables*', 'reservations*') ? 'active' : '' }}">
                                <i class="side-menu__icon ti ti-armchair"></i>
                                <span class="side-menu__label">Salon & Rezervasyon</span>
                                <i class="ri-arrow-right-s-line side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="/tables" class="side-menu__item {{ request()->is('tables*') ? 'active' : '' }}">Masa Düzeni</a>
                                </li>
                                <li class="slide">
                                    <a href="/reservations" class="side-menu__item {{ request()->is('reservations*') ? 'active' : '' }}">Rezervasyonlar</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <!-- Kategori: Menü & Ürünler -->
                    @if($role === 'admin')
                        <li class="slide__category"><span class="category-name">Menü & Ürünler</span></li>

                        <!-- ÜRÜN YÖNETİMİ (Parent: products*, categories*, variations*) -->
                        <li class="slide has-sub {{ request()->is('products*', 'categories*', 'variations*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->is('products*', 'categories*', 'variations*') ? 'active' : '' }}">
                                <i class="side-menu__icon ti ti-tools-kitchen-2"></i>
                                <span class="side-menu__label">Ürün Yönetimi</span>
                                <i class="ri-arrow-right-s-line side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="/products" class="side-menu__item {{ request()->is('products*') ? 'active' : '' }}">Ürün Listesi</a>
                                </li>
                                <li class="slide">
                                    <a href="/categories" class="side-menu__item {{ request()->is('categories*') ? 'active' : '' }}">Kategoriler</a>
                                </li>
                                <li class="slide">
                                    <a href="/variations" class="side-menu__item {{ request()->is('variations*') ? 'active' : '' }}">Varyasyonlar</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <!-- Kategori: Envanter -->
                    @if(in_array($role, ['admin', 'chef']))
                        <li class="slide__category"><span class="category-name">Stok Takibi</span></li>

                        <!-- ENVANTER (Parent: ingredients*, product-recipes*, inventory*) -->
                        <li class="slide has-sub {{ request()->is('ingredients*', 'product-recipes*', 'inventory*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->is('ingredients*', 'product-recipes*', 'inventory*') ? 'active' : '' }}">
                                <i class="side-menu__icon ti ti-package"></i>
                                <span class="side-menu__label">Envanter</span>
                                <i class="ri-arrow-right-s-line side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="/ingredients" class="side-menu__item {{ request()->is('ingredients*') ? 'active' : '' }}">Malzemeler</a>
                                </li>
                                <li class="slide">
                                    <a href="/product-recipes" class="side-menu__item {{ request()->is('product-recipes*') ? 'active' : '' }}">Reçeteler</a>
                                </li>
                                @if($role === 'admin')
                                    <li class="slide">
                                        <a href="/inventory/transactions" class="side-menu__item {{ request()->is('inventory*') ? 'active' : '' }}">Stok Hareketleri</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <!-- Kategori: Finans -->
                    @if(in_array($role, ['admin', 'cashier']))
                        <li class="slide__category"><span class="category-name">Finans & İK</span></li>

                        <!-- FİNANSAL YÖNETİM (Parent: expenses*, shifts*, payments*) -->
                        <li class="slide has-sub {{ request()->is('expenses*', 'shifts*', 'payments*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->is('expenses*', 'shifts*', 'payments*') ? 'active' : '' }}">
                                <i class="side-menu__icon ti ti-wallet"></i>
                                <span class="side-menu__label">Finansal Yönetim</span>
                                <i class="ri-arrow-right-s-line side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="/expenses" class="side-menu__item {{ request()->is('expenses*') ? 'active' : '' }}">Giderler</a>
                                </li>
                                <li class="slide">
                                    <a href="/shifts" class="side-menu__item {{ request()->is('shifts*') ? 'active' : '' }}">Kasa Vardiyaları</a>
                                </li>
                                <li class="slide">
                                    <a href="/payments" class="side-menu__item {{ request()->is('payments*') ? 'active' : '' }}">Ödemeler</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <!-- Personel ve Sistem Ayarları -->
                    @if($role === 'admin')
                        <li class="slide">
                            <a href="/users" class="side-menu__item {{ request()->is('users*') ? 'active' : '' }}">
                                <i class="side-menu__icon ti ti-users"></i>
                                <span class="side-menu__label">Personel</span>
                            </a>
                        </li>

                        <li class="slide__category"><span class="category-name">Sistem</span></li>

                        <!-- AYARLAR (Parent: settings*, printers*) -->
                        <li class="slide has-sub {{ request()->is('settings*', 'printers*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->is('settings*', 'printers*') ? 'active' : '' }}">
                                <i class="side-menu__icon ti ti-settings"></i>
                                <span class="side-menu__label">Ayarlar</span>
                                <i class="ri-arrow-right-s-line side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide">
                                    <a href="/settings" class="side-menu__item {{ request()->is('settings*') ? 'active' : '' }}">Genel Ayarlar</a>
                                </li>
                                <li class="slide">
                                    <a href="/printers" class="side-menu__item {{ request()->is('printers*') ? 'active' : '' }}">Yazıcılar</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                </ul>
                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path></svg></div>
            </nav>
        </div>
    </aside>
    <!-- End::app-sidebar -->
    <!-- End::app-sidebar -->
    <!-- End::app-sidebar -->

    <!-- Start:: Doublemenu Bottom Menu -->
    <!-- End:: Doublemenu Bottom Menu -->

    <!-- Start::app-content -->
    <div class="main-content app-content">
        @yield('content')
    </div>
    <!-- End::app-content -->


    <!-- Footer Start -->
    <footer class="footer mt-auto py-3 text-center">
        <div class="container">
        <span class="text-muted"> Copyright © <span id="year"></span> <a href="javascript:void(0);" class="text-dark fw-medium">Servify</a>.
            Designed with <span class="bi bi-heart-fill text-danger"></span> by <a href="https://ethemdemirkaya.com.tr/" target="_blank">
                <span class="fw-medium text-primary">Ethem Demirkaya</span>
            </a> All
            rights
            reserved
        </span>
        </div>
    </footer>
</div>


<!-- Scroll To Top -->
<div class="scrollToTop">
    <span class="arrow lh-1"><i class="ti ti-arrow-big-up fs-18"></i></span>
</div>
<div id="responsive-overlay"></div>
<!-- Scroll To Top -->

<!-- Popper JS -->
<script src="{{ asset('assets/libs/%40popperjs/core/umd/popper.min.js') }}"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Defaultmenu JS -->
<script src="{{ asset('assets/js/defaultmenu.min.js') }}"></script>

<!-- Node Waves JS-->
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

<!-- Sticky JS -->
<script src="{{ asset('assets/js/sticky.js') }}"></script>

<!-- Simplebar JS -->
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.js') }}"></script>

<!-- Auto Complete JS -->
<script src="{{ asset('assets/libs/%40tarekraafat/autocomplete.js/autoComplete.min.js') }}"></script>

<!-- Color Picker JS -->
<script src="{{ asset('assets/libs/%40simonwep/pickr/pickr.es5.min.js') }}"></script>

<!-- Date & Time Picker JS -->
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/theme.js') }}"></script>
@stack('scripts')
</body>

</html>
