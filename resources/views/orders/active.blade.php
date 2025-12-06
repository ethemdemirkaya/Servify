@extends('layouts.dashboard')
@section('title', 'Aktif Siparişler')

@push('styles')
    <!-- Grid.js CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
    <style>

        /* Buton Grubu Stili */
        .btn-action-group {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        /* Grid.js Yükseklik Ayarı */
        .gridjs-wrapper {
            min-height: 300px;
        }

        /* Tablo Başlıkları */
        .gridjs-th {
            font-size: 13px;
            color: #6c757d;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-20 mb-0">Aktif Siparişler</h1>
                <p class="fs-12 text-muted mb-0">
                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y, l H:i') }} itibariyle açık adisyonlar.
                </p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="/pos" class="btn btn-primary btn-wave waves-effect waves-light">
                    <i class="ti ti-plus me-1"></i> Yeni Sipariş
                </a>
            </div>
        </div>
        <!-- END HEADER -->

        <!-- ÖZET KARTLAR -->
        <div class="row mb-3">
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card border-start border-primary border-3 hover-lift">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted fw-medium mb-1">Toplam Aktif</p>
                                <h4 class="fw-semibold mb-0">{{ $stats['total_active'] }}</h4>
                            </div>
                            <div class="avatar avatar-md bg-primary-transparent">
                                <i class="ti ti-activity fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card border-start border-warning border-3 hover-lift">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted fw-medium mb-1">Onay Bekleyen</p>
                                <h4 class="fw-semibold mb-0">{{ $stats['pending'] }}</h4>
                            </div>
                            <div class="avatar avatar-md bg-warning-transparent">
                                <i class="ti ti-clock fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card border-start border-info border-3 hover-lift">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted fw-medium mb-1">Mutfakta</p>
                                <h4 class="fw-semibold mb-0">{{ $stats['kitchen'] }}</h4>
                            </div>
                            <div class="avatar avatar-md bg-info-transparent">
                                <i class="ti ti-chef-hat fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card border-start border-success border-3 hover-lift">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted fw-medium mb-1">Servis Edildi</p>
                                <h4 class="fw-semibold mb-0">{{ $stats['served'] }}</h4>
                            </div>
                            <div class="avatar avatar-md bg-success-transparent">
                                <i class="ti ti-check fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END ÖZET KARTLAR -->

        <!-- TABLO ALANI -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Sipariş Listesi
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-light border" onclick="window.location.reload()">
                                <i class="ri-refresh-line"></i> Yenile
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="active-orders-grid"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <!-- Grid.js JS -->
    <script src="{{ asset('assets/libs/gridjs/gridjs.umd.js') }}"></script>

    <script>
        // Veriyi Hazırlama
        const activeOrdersData = [
                @foreach($orders as $order)
                @php
                    // SÜRE HESAPLAMA DÜZELTMESİ
                    // abs() fonksiyonu ile negatif değerleri pozitife çeviriyoruz.
                    // now() ve created_at arasındaki farkı alıyoruz.
                    $minutes = abs(now()->diffInMinutes($order->created_at));
                @endphp
            [
                "{{ $order->id }}",
                "{{ $order->diningTable ? $order->diningTable->name : null }}", // Masa Adı
                "{{ $order->customer_name }}",
                "{{ $order->total_amount }}",
                "{{ $order->user ? $order->user->name : 'Sistem' }}",
                "{{ $minutes }}", // PHP Tarafından hesaplanmış dakika (örn: 5)
                "{{ $order->status }}",
                null
            ],
            @endforeach
        ];

        new gridjs.Grid({
            search: {
                placeholder: 'Masa, Müşteri veya ID Ara...'
            },
            pagination: {
                limit: 10,
                summary: true
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
                'noRecordsFound': 'Aktif sipariş bulunamadı.'
            },
            className: {
                table: 'table table-hover text-nowrap',
                th: 'text-muted fw-medium bg-light',
                td: 'align-middle'
            },
            style: {
                table: { border: '1px solid #f0f0f0', borderRadius: '8px' }
            },
            columns: [
                {
                    name: 'ID',
                    width: '80px',
                    formatter: (cell) => gridjs.html(`<span class="fw-bold">#${cell}</span>`)
                },
                {
                    name: 'Masa / Konum',
                    formatter: (cell) => gridjs.html(
                        cell ? `<span class="badge bg-primary-transparent fs-13 px-3 py-2"><i class="ti ti-armchair me-1"></i> ${cell}</span>`
                            : `<span class="badge bg-warning-transparent fs-13 px-3 py-2"><i class="ti ti-package me-1"></i> Paket Servis</span>`
                    )
                },
                {
                    name: 'Müşteri',
                    formatter: (cell) => gridjs.html(`
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-xs bg-light rounded-circle text-muted me-2">
                                <i class="ti ti-user"></i>
                            </span>
                            <span class="fw-medium text-dark">${cell || 'Misafir'}</span>
                        </div>
                    `)
                },
                {
                    name: 'Tutar',
                    formatter: (cell) => gridjs.html(`<span class="fw-bold fs-15 text-dark">${parseFloat(cell).toFixed(2)} ₺</span>`)
                },
                {
                    name: 'Garson',
                    hidden: true // Mobilde yer kaplamasın diye gizli
                },
                {
                    name: 'Süre',
                    formatter: (cell) => {
                        const diffMins = parseInt(cell);
                        let colorClass = 'text-muted';
                        let icon = 'clock';

                        if(diffMins > 45) { colorClass = 'text-danger fw-bold'; icon = 'alert-circle'; }
                        else if(diffMins > 30) { colorClass = 'text-warning fw-bold'; icon = 'alert-triangle'; }

                        return gridjs.html(`<span class="${colorClass}"><i class="ti ti-${icon} me-1"></i> ${diffMins} dk</span>`);
                    }
                },
                {
                    name: 'Durum',
                    formatter: (cell) => {
                        let color = 'secondary';
                        let icon = 'circle';
                        let text = cell;

                        if(cell === 'pending') { color = 'warning'; icon = 'clock'; text = 'Onay Bekliyor'; }
                        else if(cell === 'preparing') { color = 'info'; icon = 'flame'; text = 'Hazırlanıyor'; }
                        else if(cell === 'ready') { color = 'primary'; icon = 'bell-ringing'; text = 'Servise Hazır'; }
                        else if(cell === 'served') { color = 'success'; icon = 'check'; text = 'Servis Edildi'; }

                        return gridjs.html(`<span class="badge bg-${color}-transparent"><i class="ti ti-${icon} me-1"></i>${text.toUpperCase()}</span>`);
                    }
                },
                {
                    name: 'İşlemler',
                    width: '160px',
                    sort: false,
                    formatter: (cell, row) => {
                        // POPUP SORUNU ÇÖZÜMÜ:
                        // Dropdown yerine yan yana butonlar kullanıyoruz.
                        const orderId = row.cells[0].data;
                        return gridjs.html(`
                            <div class="btn-action-group">
                                <a href="/pos?order=${orderId}" class="btn btn-sm btn-icon btn-primary-light" title="Düzenle">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <a href="/orders/${orderId}" class="btn btn-sm btn-icon btn-info-light" title="Detay">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <a href="/payment/${orderId}" class="btn btn-sm btn-success-light px-2" title="Ödeme Al">
                                    <i class="ti ti-cash me-1"></i> Öde
                                </a>
                            </div>
                        `);
                    }
                }
            ],
            data: activeOrdersData
        }).render(document.getElementById("active-orders-grid"));
    </script>
@endpush
