@extends('layouts.dashboard')
@section('title', 'Mutfak Ekranı')

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- Header -->
        <div class="page-header-breadcrumb mb-4">
            <div class="d-flex align-center justify-content-between flex-wrap">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-0 d-flex align-items-center">
                        <i class="ri-restaurant-2-line me-2 text-primary"></i>
                        Mutfak Ekranı (KDS)
                    </h1>
                    <p class="fs-12 text-muted mb-0">Siparişleri anlık takip edin ve durumlarını güncelleyin.</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="input-group">
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="all">Tüm Siparişler</option>
                            <option value="pending">Bekleyen</option>
                            <option value="preparing">Hazırlanan</option>
                            <option value="ready">Hazır</option>
                        </select>
                    </div>
                    <a href="{{ route('orders.kitchen') }}" class="btn btn-primary-light btn-wave">
                        <i class="ri-refresh-line align-middle me-1"></i> Yenile
                    </a>
                </div>
            </div>
        </div>

        <!-- Durum Özet Kartları -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card custom-card bg-warning-transparent border-warning border border-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold">Bekleyen</h6>
                                <h3 class="mb-0 fw-bold mt-1">{{ $orders->where('status', 'pending')->count() }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-warning bg-opacity-10 rounded-circle">
                                <i class="ri-time-line fs-24 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card custom-card bg-primary-transparent border-primary border border-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold">Hazırlanan</h6>
                                <h3 class="mb-0 fw-bold mt-1">{{ $orders->where('status', 'preparing')->count() }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-primary bg-opacity-10 rounded-circle">
                                <i class="ri-fire-line fs-24 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card custom-card bg-success-transparent border-success border border-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold">Hazır</h6>
                                <h3 class="mb-0 fw-bold mt-1">{{ $orders->where('status', 'ready')->count() }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-success bg-opacity-10 rounded-circle">
                                <i class="ri-notification-3-line fs-24 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card custom-card bg-info-transparent border-info border border-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold">Toplam</h6>
                                <h3 class="mb-0 fw-bold mt-1">{{ $orders->count() }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-info bg-opacity-10 rounded-circle">
                                <i class="ri-restaurant-line fs-24 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sipariş Kartları -->
        <div class="row" id="ordersContainer">
            @forelse($orders as $order)
                @php
                    // Duruma göre renk ve kart stili belirleme
                    $cardColor = match($order->status) {
                        'pending' => 'warning',   // Bekliyor - Sarı
                        'preparing' => 'primary', // Hazırlanıyor - Mavi
                        'ready' => 'success',     // Hazır - Yeşil
                        default => 'secondary'
                    };

                    // Duruma göre ikon
                    $statusIcon = match($order->status) {
                        'pending' => 'ri-time-line',   // Bekliyor
                        'preparing' => 'ri-fire-line', // Hazırlanıyor
                        'ready' => 'ri-notification-3-line', // Hazır
                        default => 'ri-question-line'
                    };

                    // DÜZELTME BURADA YAPILDI: intval() ile tam sayıya çevrildi
                    $minutes = intval($order->created_at->diffInMinutes(now()));
                    $isUrgent = $minutes > 20;
                @endphp

                <div class="col-xl-3 col-lg-4 col-md-6 order-card" data-status="{{ $order->status }}">
                    <!-- Vyzor Widget Card Style -->
                    <div class="card custom-card border-top border-3 border-{{ $cardColor }} shadow-sm {{ $order->status == 'ready' ? 'bg-success-transparent' : '' }} h-100">

                        <!-- Kart Başlığı: Masa ve Süre -->
                        <div class="card-header justify-content-between align-items-center">
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-{{ $cardColor }}-transparent fs-14 fw-semibold d-flex align-items-center gap-1">
                                        <i class="{{ $statusIcon }}"></i>
                                        {{ $order->table->name ?? 'Paket Sipariş' }}
                                    </span>
                                    @if($isUrgent)
                                        <span class="badge bg-danger-transparent animate-pulse">
                                            <i class="ri-alarm-warning-line"></i> Acil
                                        </span>
                                    @endif
                                </div>
                                <div class="fs-11 text-muted mt-1">#{{ $order->id }} - {{ $order->created_at->format('H:i') }}</div>
                            </div>
                            <div class="text-end">
                                <div class="d-flex align-items-center gap-1 text-{{ $isUrgent ? 'danger' : 'muted' }}">
                                    <i class="ri-timer-2-line"></i>
                                    {{-- Düzeltilen dakika burada tam sayı olarak gösteriliyor --}}
                                    <span class="fw-bold">{{ $minutes }} dk</span>
                                </div>
                            </div>
                        </div>

                        <!-- Müşteri Notu Varsa -->
                        @if($order->note)
                            <div class="px-3 pt-2">
                                <div class="alert alert-warning-transparent p-2 fs-11 mb-0 d-flex align-items-start gap-2">
                                    <i class="ri-information-line mt-0"></i>
                                    <div>
                                        <strong>Not:</strong> {{ $order->note }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Ürün Listesi (Vyzor Recent Activity Style) -->
                        <div class="card-body">
                            <ul class="list-unstyled mb-0 ecommerce-recent-activity">
                                @foreach($order->items as $item)
                                    <li class="ecommerce-recent-activity-content pb-2 mb-2 border-bottom border-block-end-dashed {{ $item->status == 'ready' ? 'opacity-50' : '' }}">
                                        <div class="d-flex align-items-start">
                                            <div class="me-2">
                                                <!-- Miktar -->
                                                <span class="avatar avatar-sm avatar-rounded bg-{{ $item->status == 'ready' ? 'success' : 'light' }} text-dark fw-bold border">
                                                    {{ $item->quantity }}x
                                                </span>
                                            </div>
                                            <div class="activity-content flex-fill">
                                                <span class="d-block fw-medium text-dark {{ $item->status == 'ready' ? 'text-decoration-line-through' : '' }}">
                                                    {{ $item->product->name }}
                                                </span>

                                                <!-- Varyasyonlar -->
                                                @if($item->variations->count() > 0)
                                                    <div class="fs-11 text-danger mt-1">
                                                        @foreach($item->variations as $var)
                                                            <span class="badge bg-danger-transparent p-1 me-1">{{ $var->variation_name }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <!-- Ürün Notu -->
                                                @if($item->note)
                                                    <span class="d-block fs-11 text-muted mt-1 fst-italic">
                                                        <i class="ri-message-2-line"></i> "{{ $item->note }}"
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Tekil Ürün Tikleme (Opsiyonel) -->
                                            <div>
                                                <form action="{{ route('orders.kitchen.item.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-icon btn-sm {{ $item->status == 'ready' ? 'btn-success' : 'btn-light' }} rounded-pill">
                                                        <i class="ri-check-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Kart Altı: Aksiyon Butonları -->
                        <div class="card-footer">
                            <div class="d-grid gap-2">
                                @if($order->status == 'pending')
                                    <form action="{{ route('orders.kitchen.update', $order->id) }}" method="POST" class="d-grid">
                                        @csrf
                                        <input type="hidden" name="status" value="preparing">
                                        <button type="submit" class="btn btn-primary btn-wave">
                                            <i class="ri-fire-line me-1"></i> Hazırlamaya Başla
                                        </button>
                                    </form>
                                @elseif($order->status == 'preparing')
                                    <form action="{{ route('orders.kitchen.update', $order->id) }}" method="POST" class="d-grid">
                                        @csrf
                                        <input type="hidden" name="status" value="ready">
                                        <button type="submit" class="btn btn-success btn-wave">
                                            <i class="ri-notification-3-line me-1"></i> Hazırlandı
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-light disabled text-success fw-bold">
                                        <i class="ri-check-double-line me-1"></i> Servis Bekliyor
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <span class="avatar avatar-xxl avatar-rounded bg-light text-muted">
                                    <i class="ri-restaurant-line fs-1"></i>
                                </span>
                            </div>
                            <h4 class="fw-semibold">Bekleyen Sipariş Yok</h4>
                            <p class="text-muted mb-4">Mutfak şu an sakin. Yeni siparişler buraya düşecektir.</p>
                            <a href="{{ route('orders.index') }}" class="btn btn-primary-light">
                                <i class="ri-list-check me-1"></i> Tüm Siparişleri Gör
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // Sayfayı her 30 saniyede bir otomatik yenile
        let refreshInterval = setInterval(function() {
            window.location.reload();
        }, 30000);

        // Sipariş durumuna göre filtreleme
        document.getElementById('statusFilter').addEventListener('change', function() {
            const status = this.value;
            const orderCards = document.querySelectorAll('.order-card');

            orderCards.forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Yenile butonuna tıklandığında otomatik yenilemeyi sıfırla
        document.querySelector('.btn-primary-light').addEventListener('click', function() {
            clearInterval(refreshInterval);
            refreshInterval = setInterval(function() {
                window.location.reload();
            }, 30000);
        });

        // Form gönderimlerinde otomatik yenilemeyi sıfırla
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                clearInterval(refreshInterval);
            });
        });

        // Sipariş kartlarına hover efekti
        document.querySelectorAll('.order-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.querySelector('.card').classList.add('shadow-lg');
            });

            card.addEventListener('mouseleave', function() {
                this.querySelector('.card').classList.remove('shadow-lg');
            });
        });
    </script>
@endpush
