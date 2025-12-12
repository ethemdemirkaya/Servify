@extends('layouts.dashboard')
@section('title', ucfirst($role) . ' Paneli')

@push('styles')
    <!-- Grid.js CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-20 mb-0">
                    Hoşgeldin, {{ auth()->user()->name }}
                    <span class="badge bg-primary-transparent ms-2">{{ ucfirst($role) }}</span>
                </h1>
                <p class="fs-12 text-muted mb-0">
                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y, l') }} - Güncel durum özeti.
                </p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="btn-list">
                    <button class="btn btn-icon btn-primary btn-wave waves-effect waves-light" onclick="window.location.reload()">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- END HEADER -->

        <!-- ROW 1: İSTATİSTİK KARTLARI (Role Göre Dinamik) -->
        <div class="row">
            @if($role == 'admin')
                <!-- ADMIN STATS -->
                <!-- KART 1: TOPLAM GELİR -->
                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden primary hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Toplam Gelir</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ number_format($data['total_revenue'] ?? 0, 2) }} ₺</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        @php $revGrowth = $data['revenue_growth'] ?? 0; @endphp
                                        <span class="{{ $revGrowth >= 0 ? 'text-success' : 'text-danger' }} fs-12">
                                            <i class="{{ $revGrowth >= 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line' }}"></i>
                                            {{ abs($revGrowth) }}%
                                        </span>
                                        <span class="fs-12 text-muted">geçen aydan</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-primary-transparent">
                                    <i class="ti ti-wallet fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KART 2: GÜNLÜK CİRO -->
                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden secondary hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Günlük Ciro</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ number_format($data['daily_revenue'] ?? 0, 2) }} ₺</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        @php $dailyGrowth = $data['daily_growth'] ?? 0; @endphp
                                        <span class="{{ $dailyGrowth >= 0 ? 'text-success' : 'text-danger' }} fs-12">
                                            <i class="{{ $dailyGrowth >= 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line' }}"></i>
                                            {{ abs($dailyGrowth) }}%
                                        </span>
                                        <span class="fs-12 text-muted">dünden</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-secondary-transparent">
                                    <i class="ti ti-chart-pie-2 fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-secondary" role="progressbar" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KART 3: TOPLAM SİPARİŞ -->
                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden warning hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Toplam Sipariş</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ $data['total_orders'] ?? 0 }}</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        @php $orderGrowth = $data['orders_growth'] ?? 0; @endphp
                                        <span class="{{ $orderGrowth >= 0 ? 'text-success' : 'text-danger' }} fs-12">
                                            <i class="{{ $orderGrowth >= 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line' }}"></i>
                                            {{ abs($orderGrowth) }}%
                                        </span>
                                        <span class="fs-12 text-muted">geçen haftadan</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-warning-transparent">
                                    <i class="ti ti-shopping-cart fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KART 4: KULLANICILAR -->
                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden success hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Kullanıcılar</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ $data['total_users'] ?? 0 }}</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        @php $userGrowth = $data['users_growth'] ?? 0; @endphp
                                        <span class="{{ $userGrowth >= 0 ? 'text-success' : 'text-danger' }} fs-12">
                                            <i class="{{ $userGrowth >= 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line' }}"></i>
                                            {{ abs($userGrowth) }}%
                                        </span>
                                        <span class="fs-12 text-muted">bu ay</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-success-transparent">
                                    <i class="ti ti-users fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($role == 'waiter')
                <!-- WAITER STATS -->
                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden success hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Boş Masalar</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ $data['empty_tables'] ?? 0 }}</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="text-success fs-12"><i class="ri-arrow-up-line"></i> {{ $data['new_empty_last_hour'] ?? 0 }} yeni</span>
                                        <span class="fs-12 text-muted">son saatte</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-success-transparent">
                                    <i class="ti ti-armchair fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden danger hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Dolu Masalar</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ $data['active_tables'] ?? 0 }}</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="text-danger fs-12"><i class="ri-arrow-up-line"></i> {{ $data['new_occupied_last_30min'] ?? 0 }} yeni</span>
                                        <span class="fs-12 text-muted">son 30 dk</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-danger-transparent">
                                    <i class="ti ti-users fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden warning hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Bekleyen Sipariş</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ $data['pending_orders'] ?? 0 }}</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="text-warning fs-12"><i class="ri-arrow-up-line"></i> {{ $data['new_orders_last_15min'] ?? 0 }} yeni</span>
                                        <span class="fs-12 text-muted">son 15 dk</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-warning-transparent">
                                    <i class="ti ti-clock fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden primary hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Servise Hazır</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ $data['ready_orders'] ?? 0 }}</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="text-primary fs-12"><i class="ri-arrow-up-line"></i> {{ $data['new_ready_last_20min'] ?? 0 }} yeni</span>
                                        <span class="fs-12 text-muted">son 20 dk</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-primary-transparent">
                                    <i class="ti ti-bell-ringing fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 55%"></div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($role == 'chef')
                <!-- CHEF STATS -->
                <div class="col-xl-6 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden danger hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Hazırlanacak Ürünler</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ $data['pending_items'] ?? 0 }}</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="text-danger fs-12"><i class="ri-arrow-up-line"></i> {{ $data['new_items_last_30min'] ?? 0 }} yeni</span>
                                        <span class="fs-12 text-muted">son 30 dk</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-danger-transparent">
                                    <i class="ti ti-flame fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden warning hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Kritik Stok Uyarısı</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ isset($data['low_stock_ingredients']) ? count($data['low_stock_ingredients']) : 0 }}</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="text-warning fs-12"><i class="ri-alert-triangle-fill"></i> Tükenebilir</span>
                                        <span class="fs-12 text-muted">malzeme var</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-warning-transparent">
                                    <i class="ti ti-alert-triangle fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 30%"></div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($role == 'cashier')
                <!-- CASHIER STATS -->
                <div class="col-xl-4 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden success hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Günlük Nakit</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ number_format($data['today_cash'] ?? 0, 2) }} ₺</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        @php $cashGrowth = $data['cash_growth'] ?? 0; @endphp
                                        <span class="{{ $cashGrowth >= 0 ? 'text-success' : 'text-danger' }} fs-12">
                                            <i class="{{ $cashGrowth >= 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line' }}"></i>
                                            {{ abs($cashGrowth) }}%
                                        </span>
                                        <span class="fs-12 text-muted">dünden</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-success-transparent">
                                    <i class="ti ti-cash fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 65%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden info hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Günlük Kredi Kartı</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ number_format($data['today_card'] ?? 0, 2) }} ₺</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        @php $cardGrowth = $data['card_growth'] ?? 0; @endphp
                                        <span class="{{ $cardGrowth >= 0 ? 'text-success' : 'text-danger' }} fs-12">
                                            <i class="{{ $cardGrowth >= 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line' }}"></i>
                                            {{ abs($cardGrowth) }}%
                                        </span>
                                        <span class="fs-12 text-muted">dünden</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-info-transparent">
                                    <i class="ti ti-credit-card fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card custom-card dashboard-main-card overflow-hidden warning hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-fill">
                                    <span class="fs-13 fw-medium">Bekleyen Ödemeler</span>
                                    <h4 class="fw-semibold my-2 lh-1">{{ $data['pending_payments'] ?? 0 }}</h4>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="text-warning fs-12"><i class="ri-arrow-up-line"></i> {{ $data['new_pending_last_hour'] ?? 0 }} yeni</span>
                                        <span class="fs-12 text-muted">son saatte</span>
                                    </div>
                                </div>
                                <div class="avatar avatar-md bg-warning-transparent">
                                    <i class="ti ti-receipt fs-24"></i>
                                </div>
                            </div>
                            <div class="progress progress-sm mt-3">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 35%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- END ROW 1 -->

        <!-- ROW 2: DETAYLI TABLOLAR VE İÇERİKLER -->
        <div class="row mt-3">

            @if($role == 'admin')
                <!-- ADMIN GRID -->
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">Son Siparişler</div>
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-light" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="javascript:void(0);">Tümünü Gör</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Grid.js Container -->
                            <div id="admin-orders-grid"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">Çok Satanlar</div>
                            <a href="javascript:void(0);" class="text-primary fs-12">Rapor</a>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($data['top_products'] ?? [] as $product)
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="lh-1">
                                                <span class="avatar avatar-lg bg-light border border-dashed p-1">
                                                     <i class="ti ti-box fs-20"></i>
                                                </span>
                                            </div>
                                            <div class="flex-fill">
                                                <span class="fw-semibold mb-1 d-block">{{ $product->name ?? 'Bilinmeyen Ürün' }}</span>
                                                <div class="d-flex align-items-center gap-2 fw-medium">
                                                    <span class="text-success fs-12"><i class="ri-circle-fill me-1 fs-7 align-middle"></i>Popüler</span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <span class="d-block fw-semibold">{{ $product->total ?? 0 }} Adet</span>
                                                <div class="progress progress-xs mt-1" style="width: 60px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(($product->total / 50) * 100, 100) }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">Henüz veri yok.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            @elseif($role == 'chef')
                <!-- CHEF GRID -->
                <div class="col-xl-8">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">Mutfak Ekranı</div>
                            <div class="d-flex gap-2">
                                <span class="badge bg-warning-transparent">Bekleyen: {{ $data['pending_items'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Grid.js Container -->
                            <div id="chef-orders-grid"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title text-danger">Kritik Stoklar</div>
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger-light">Talep Oluştur</a>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($data['low_stock_ingredients'] ?? [] as $ing)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-medium">{{ $ing->name }}</span>
                                            <div class="fs-11 text-muted">Kalan: <span class="text-danger fw-bold">{{ $ing->stock_quantity }} {{ $ing->unit }}</span></div>
                                        </div>
                                        <span class="badge bg-danger rounded-pill">Kritik</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-success py-3">
                                        <i class="ti ti-check fs-18 mb-1 d-block"></i>
                                        Tüm stoklar yeterli.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            @elseif($role == 'waiter')
                <!-- WAITER VISUAL GRID (Garson için görsel masa düzeni daha iyi) -->
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">Masa Durumları</div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-success-light waves-effect waves-light" id="refresh-tables">
                                    <i class="ri-refresh-line me-1"></i>Yenile
                                </button>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-filter-3-line me-1"></i>Filtrele
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item filter-tables" href="javascript:void(0);" data-filter="all">Tümü</a></li>
                                        <li><a class="dropdown-item filter-tables" href="javascript:void(0);" data-filter="empty">Boş</a></li>
                                        <li><a class="dropdown-item filter-tables" href="javascript:void(0);" data-filter="occupied">Dolu</a></li>
                                        <li><a class="dropdown-item filter-tables" href="javascript:void(0);" data-filter="reserved">Rezerve</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row" id="tables-container">
                                @forelse($data['tables'] ?? [] as $table)
                                    <div class="col-xl-2 col-md-3 col-sm-4 col-6 mb-3 table-item" data-status="{{ $table->status }}">
                                        <div class="card text-center p-3 border shadow-none hover-lift
                                            @if($table->status == 'occupied') border-danger bg-danger-transparent
                                            @elseif($table->status == 'reserved') border-warning bg-warning-transparent
                                            @else border-success bg-success-transparent @endif">

                                            <h5 class="mb-1 fw-bold">{{ $table->name }}</h5>
                                            <span class="fs-12 text-uppercase fw-semibold mb-2 d-block">
                                                @if($table->status == 'occupied') DOLU
                                                @elseif($table->status == 'reserved') REZERVE
                                                @else BOŞ @endif
                                            </span>
                                            <div class="avatar avatar-lg bg-transparent">
                                                <i class="ti ti-armchair fs-24 @if($table->status == 'occupied') text-danger @else text-success @endif"></i>
                                            </div>
                                            @if($table->status == 'occupied')
                                                <div class="mt-2">
                                                    <button class="btn btn-sm btn-danger-light waves-effect waves-light">Sipariş Detay</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted">Kayıtlı masa bulunamadı.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($role == 'cashier')
                <!-- CASHIER GRID -->
                <div class="col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header justify-content-between">
                            <div class="card-title">Ödeme Bekleyen Masalar</div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-primary-light waves-effect waves-light">
                                    <i class="ri-history-line me-1"></i>Geçmiş İşlemler
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Grid.js Container -->
                            <div id="cashier-orders-grid"></div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <!-- END ROW 2 -->
    </div>
@endsection

@push('scripts')
    <!-- Grid.js JS -->
    <script src="{{ asset('assets/libs/gridjs/gridjs.umd.js') }}"></script>

    <script>
        // --- ORTAK YAPILANDIRMA ---
        const commonGridConfig = {
            search: {
                placeholder: 'Ara...'
            },
            pagination: {
                limit: 5,
                summary: false
            },
            sort: true,
            language: {
                'search': { 'placeholder': 'Ara...' },
                'pagination': {
                    'previous': 'Önceki',
                    'next': 'Sonraki',
                    'showing': 'Gösterilen',
                    'results': () => 'Kayıt'
                },
                'loading': 'Yükleniyor...',
                'noRecordsFound': 'Kayıt bulunamadı'
            },
            className: {
                table: 'table table-hover text-nowrap',
                th: 'text-muted fw-medium bg-light',
                td: 'align-middle'
            },
            style: {
                table: {
                    border: '1px solid #f0f0f0',
                    borderRadius: '8px'
                }
            }
        };

        // --- ADMIN ROLE ---
        @if($role == 'admin')
        const adminData = @json($data['recent_orders'] ?? []);

        if(document.getElementById("admin-orders-grid")) {
            new gridjs.Grid({
                ...commonGridConfig,
                columns: [
                    {
                        name: 'ID',
                        width: '80px',
                        formatter: (cell) => gridjs.html(`<span class="fw-bold">#${cell}</span>`)
                    },
                    {
                        name: 'Müşteri',
                        formatter: (cell) => gridjs.html(`
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xs me-2 bg-primary-transparent rounded-circle">
                                    ${cell ? cell.charAt(0).toUpperCase() : 'M'}
                                </div>
                                <span class="fw-medium">${cell || 'Misafir'}</span>
                            </div>
                        `)
                    },
                    {
                        name: 'Konum',
                        width: '120px',
                        formatter: (cell) => gridjs.html(cell ? `<span class="badge bg-info-transparent">Masa ${cell}</span>` : `<span class="badge bg-warning-transparent">Paket</span>`)
                    },
                    {
                        name: 'Tutar',
                        formatter: (cell) => gridjs.html(`<span class="fw-bold text-dark">${parseFloat(cell).toFixed(2)} ₺</span>`)
                    },
                    {
                        name: 'Durum',
                        formatter: (cell) => {
                            let color = 'primary';
                            let text = cell;
                            if(cell === 'completed') { color = 'success'; text = 'Tamamlandı'; }
                            else if(cell === 'pending') { color = 'warning'; text = 'Beklemede'; }
                            else if(cell === 'preparing') { color = 'primary'; text = 'Hazırlanıyor'; }
                            else if(cell === 'served') { color = 'info'; text = 'Servis Edildi'; }
                            else if(cell === 'cancelled') { color = 'danger'; text = 'İptal'; }

                            return gridjs.html(`<span class="badge bg-${color}-transparent">${text.toUpperCase()}</span>`);
                        }
                    },
                    {
                        name: 'Ödeme',
                        formatter: (cell) => {
                            const isPaid = cell === 'paid';
                            return gridjs.html(`<span class="badge bg-${isPaid ? 'success' : 'danger'}">${isPaid ? 'ÖDENDİ' : 'ÖDENMEDİ'}</span>`);
                        }
                    },
                    {
                        name: 'İşlem',
                        width: '100px',
                        sort: false,
                        formatter: (cell, row) => gridjs.html(`
                            <button class="btn btn-sm btn-icon btn-light rounded-circle">
                                <i class="ti ti-eye"></i>
                            </button>
                        `)
                    }
                ],
                data: adminData.map(order => [
                    order.id,
                    order.customer_name,
                    order.dining_table_id,
                    order.total_amount,
                    order.status,
                    order.payment_status,
                    null
                ])
            }).render(document.getElementById("admin-orders-grid"));
        }
        @endif

        // --- CHEF ROLE ---
        @if($role == 'chef')
        const chefData = @json($data['kitchen_orders'] ?? []);

        if(document.getElementById("chef-orders-grid")) {
            new gridjs.Grid({
                ...commonGridConfig,
                columns: [
                    {
                        name: 'Masa',
                        width: '100px',
                        formatter: (cell) => gridjs.html(`<span class="fw-bold fs-15">${cell || 'Paket'}</span>`)
                    },
                    { name: 'Ürün', width: '200px' },
                    {
                        name: 'Adet',
                        width: '80px',
                        formatter: (cell) => gridjs.html(`<span class="badge bg-secondary fs-13">${cell}</span>`)
                    },
                    {
                        name: 'Not',
                        formatter: (cell) => gridjs.html(cell ? `<span class="text-danger fw-medium"><i class="ti ti-note me-1"></i>${cell}</span>` : '<span class="text-muted">-</span>')
                    },
                    {
                        name: 'Durum',
                        formatter: (cell) => {
                            const isWaiting = cell === 'waiting';
                            return gridjs.html(`<span class="badge bg-${isWaiting ? 'warning' : 'info'}">${isWaiting ? 'BEKLİYOR' : 'PİŞİYOR'}</span>`);
                        }
                    },
                    {
                        name: 'İşlem',
                        width: '120px',
                        sort: false,
                        formatter: (cell, row) => gridjs.html(`
                            <button class="btn btn-sm btn-success-light w-100">Hazır</button>
                        `)
                    }
                ],
                data: chefData.map(item => [
                    item.table_name,
                    item.product_name,
                    item.quantity,
                    item.note,
                    item.status,
                    null
                ])
            }).render(document.getElementById("chef-orders-grid"));
        }
        @endif

        // --- CASHIER ROLE ---
        @if($role == 'cashier')
        const cashierData = @json($data['unpaid_orders'] ?? []);

        if(document.getElementById("cashier-orders-grid")) {
            new gridjs.Grid({
                ...commonGridConfig,
                columns: [
                    {
                        name: 'Sipariş ID',
                        width: '100px',
                        formatter: (cell) => gridjs.html(`<span class="fw-bold">#${cell}</span>`)
                    },
                    {
                        name: 'Masa',
                        formatter: (cell) => gridjs.html(`<span class="badge bg-info-transparent fs-13">${cell || 'Paket'}</span>`)
                    },
                    {
                        name: 'Toplam Tutar',
                        formatter: (cell) => gridjs.html(`<span class="fw-bold text-dark fs-15">${parseFloat(cell).toFixed(2)} ₺</span>`)
                    },
                    {
                        name: 'Durum',
                        formatter: (cell) => gridjs.html(`<span class="badge bg-danger-transparent">ÖDENMEDİ</span>`)
                    },
                    {
                        name: 'İşlem',
                        width: '180px',
                        sort: false,
                        formatter: (cell, row) => gridjs.html(`
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-success-light flex-fill">
                                    <i class="ti ti-cash me-1"></i>Al
                                </button>
                                <button class="btn btn-sm btn-light flex-fill">
                                    <i class="ti ti-printer"></i>
                                </button>
                            </div>
                        `)
                    }
                ],
                data: cashierData.map(order => [
                    order.id,
                    order.dining_table ? order.dining_table.name : 'Paket',
                    order.total_amount,
                    'unpaid',
                    null
                ])
            }).render(document.getElementById("cashier-orders-grid"));
        }
        @endif

        // --- WAITER ROLE EVENTS ---
        @if($role == 'waiter')
        document.addEventListener('DOMContentLoaded', function() {
            // Filtreleme
            document.querySelectorAll('.filter-tables').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const filter = this.getAttribute('data-filter');
                    document.querySelectorAll('.table-item').forEach(item => {
                        item.style.display = (filter === 'all' || item.getAttribute('data-status') === filter) ? 'block' : 'none';
                    });
                });
            });

            // Yenileme Animasyonu
            const refreshBtn = document.getElementById('refresh-tables');
            if(refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    icon.classList.add('spin');
                    this.disabled = true;
                    // Simüle edilmiş yenileme
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                });
            }
        });
        @endif
    </script>
@endpush
