@extends('layouts.dashboard')
@section('title', 'Sipariş Geçmişi')

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <!-- FlatPickr CSS (Vyzor Teması Uyumlu) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .select2-container { width: 100% !important; z-index: 1040; }
        .select2-selection { height: 38px !important; display: flex; align-items: center; border-color: #ebf1f6; }
        .select2-selection__arrow { height: 38px !important; }
        /* Tablo hover efekti */
        .order-row:hover { background-color: #f8f9fa; transition: 0.2s; }
    </style>
@endpush

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-20 mb-0">Sipariş Geçmişi</h1>
                <p class="fs-12 text-muted mb-0">Tamamlanan ve iptal edilen tüm siparişleri buradan inceleyebilirsiniz.</p>
            </div>
        </div>

        <!-- FİLTRELEME KARTI -->
        <div class="row mb-3">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-3">
                        <form action="{{ route('orders.history') }}" method="GET" class="row g-3 align-items-end">

                            <!-- Başlangıç Tarihi -->
                            <div class="col-md-2">
                                <label class="form-label">Başlangıç Tarihi</label>
                                <div class="input-group">
                                    <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                    <input type="text" class="form-control flatpickr-input" id="start_date" name="start_date" value="{{ request('start_date') }}" placeholder="Tarih Seç">
                                    <!-- Tarih Temizleme Butonu -->
                                    @if(request('start_date'))
                                        <button type="button" class="btn btn-light border btn-clear-date" data-target="#start_date" title="Tarihi Temizle">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Bitiş Tarihi -->
                            <div class="col-md-2">
                                <label class="form-label">Bitiş Tarihi</label>
                                <div class="input-group">
                                    <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                    <input type="text" class="form-control flatpickr-input" id="end_date" name="end_date" value="{{ request('end_date') }}" placeholder="Tarih Seç">
                                    <!-- Tarih Temizleme Butonu -->
                                    @if(request('end_date'))
                                        <button type="button" class="btn btn-light border btn-clear-date" data-target="#end_date" title="Tarihi Temizle">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Durum Filtresi -->
                            <div class="col-md-2">
                                <label class="form-label">Durum</label>
                                <select class="form-select js-example-placeholder-single" name="status" id="status_filter">
                                    <option value="">Tümü</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal Edildi</option>
                                    <option value="served" {{ request('status') == 'served' ? 'selected' : '' }}>Servis Edildi</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Bekliyor</option>
                                </select>
                            </div>

                            <!-- Arama -->
                            <div class="col-md-3">
                                <label class="form-label">Arama</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                                    <input type="text" class="form-control" name="search" placeholder="Müşteri Adı veya ID..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Butonlar -->
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-wave w-100">
                                    <i class="ri-filter-3-line me-1"></i> Filtrele
                                </button>
                                <a href="{{ route('orders.history') }}" class="btn btn-light w-100">Sıfırla</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLO -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Sipariş Listesi</div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark border">Toplam: {{ $orders->total() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover border table-bordered align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th scope="col">#ID</th>
                                    <th scope="col">Tarih</th>
                                    <th scope="col">Müşteri / Masa</th>
                                    <th scope="col">Personel</th>
                                    <th scope="col">Tutar</th>
                                    <th scope="col">Ödeme</th>
                                    <th scope="col">Durum</th>
                                    <th scope="col" class="text-end">İşlem</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($orders as $order)
                                    <tr class="order-row">
                                        <td><span class="fw-bold text-primary">#{{ $order->id }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm bg-light text-muted me-2 border">
                                                    <i class="ri-calendar-line fs-16"></i>
                                                </span>
                                                <div>
                                                    <span class="d-block fw-medium">{{ $order->created_at->format('d.m.Y') }}</span>
                                                    <span class="d-block fs-12 text-muted">{{ $order->created_at->format('H:i') }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium">{{ $order->customer_name ?? 'Misafir' }}</span>
                                                <small class="text-muted"><i class="ri-table-line me-1"></i>{{ $order->diningTable->name ?? 'Paket Servis' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $order->user->name ?? '-' }}</td>
                                        <td>
                                            <span class="fw-bold fs-15 text-dark">{{ number_format($order->total_amount, 2) }} ₺</span>
                                        </td>
                                        <td>
                                            @if($order->payment_status == 'paid')
                                                <span class="badge bg-success-transparent"><i class="ri-check-line me-1"></i> Ödendi</span>
                                            @elseif($order->payment_status == 'partial')
                                                <span class="badge bg-warning-transparent">Kısmi</span>
                                            @else
                                                <span class="badge bg-danger-transparent">Ödenmedi</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($order->status) {
                                                    'completed' => 'bg-success-transparent',
                                                    'cancelled' => 'bg-danger-transparent',
                                                    'served' => 'bg-info-transparent',
                                                    'preparing' => 'bg-warning-transparent',
                                                    'ready' => 'bg-primary-transparent',
                                                    default => 'bg-light text-dark'
                                                };
                                                $statusText = match($order->status) {
                                                    'completed' => 'Tamamlandı',
                                                    'cancelled' => 'İptal',
                                                    'served' => 'Servis Edildi',
                                                    'preparing' => 'Hazırlanıyor',
                                                    'ready' => 'Hazır',
                                                    'pending' => 'Bekliyor',
                                                    default => $order->status
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-icon btn-primary-light btn-wave"
                                                    onclick='showOrderDetails({{ json_encode($order->items) }}, "{{ $order->id }}", "{{ $order->total_amount }}")'
                                                    title="Sipariş Detayı">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="avatar avatar-xxl bg-light mb-3">
                                                    <i class="ri-search-2-line fs-1"></i>
                                                </div>
                                                <h6 class="text-muted">Kayıt Bulunamadı</h6>
                                                <p class="fs-12 text-muted mb-0">Seçilen kriterlere uygun sipariş geçmişi yok.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PAGINATION -->
                    @if($orders->hasPages())
                        <div class="card-footer border-top-0">
                            <div class="d-flex align-items-center flex-wrap overflow-auto">
                                <div class="mb-2 mb-sm-0 me-auto">
                                    <span class="text-muted fs-12">
                                        Toplam <b>{{ $orders->total() }}</b> kayıttan <b>{{ $orders->firstItem() }}</b> - <b>{{ $orders->lastItem() }}</b> arası gösteriliyor
                                    </span>
                                </div>
                                <div>
                                    {{ $orders->appends(request()->query())->links('pagination.vyzor-style') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- DETAILS MODAL -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Sipariş Detayı <span id="modalOrderId" class="badge bg-primary ms-2"></span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th>Ürün Adı</th>
                                <th class="text-center">Adet</th>
                                <th class="text-end">Birim Fiyat</th>
                                <th class="text-end">Toplam</th>
                            </tr>
                            </thead>
                            <tbody id="modalOrderItems"></tbody>
                            <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">GENEL TOPLAM:</td>
                                <td class="text-end fw-bold text-primary fs-16" id="modalOrderTotal"></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/tr.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Select2 Başlat
            $('#status_filter').select2({
                placeholder: "Durum Seçiniz",
                allowClear: true,
                minimumResultsForSearch: Infinity
            });

            // 2. Flatpickr Ayarları (Vyzor HTML'indeki gibi)
            const flatpickrConfig = {
                locale: "tr",
                dateFormat: "Y-m-d",
                allowInput: true,
                disableMobile: true
            };

            const startPicker = flatpickr("#start_date", flatpickrConfig);
            const endPicker = flatpickr("#end_date", flatpickrConfig);

            // 3. Tarih Silme Butonu Mantığı
            $('.btn-clear-date').on('click', function() {
                let targetId = $(this).data('target'); // #start_date veya #end_date
                let pickerInstance = document.querySelector(targetId)._flatpickr;

                if(pickerInstance) {
                    pickerInstance.clear(); // Flatpickr'ı temizle
                }

                $(this).hide(); // Butonu gizle (isteğe bağlı, form submit olunca zaten sayfa yenilenir)
            });
        });

        // 4. Modal Gösterme Fonksiyonu
        function showOrderDetails(items, orderId, totalAmount) {
            const tbody = document.getElementById('modalOrderItems');
            tbody.innerHTML = '';

            items.forEach(item => {
                let productName = item.product ? item.product.name : '<span class="text-danger">Silinmiş Ürün</span>';
                let row = `
                    <tr>
                        <td>
                            <span class="fw-medium">${productName}</span>
                            ${item.note ? `<div class="fs-11 text-muted"><i class="ri-sticky-note-line me-1"></i>${item.note}</div>` : ''}
                        </td>
                        <td class="text-center">${item.quantity}</td>
                        <td class="text-end">${parseFloat(item.unit_price).toFixed(2)} ₺</td>
                        <td class="text-end fw-medium">${parseFloat(item.sub_total).toFixed(2)} ₺</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

            document.getElementById('modalOrderId').innerText = '#' + orderId;
            document.getElementById('modalOrderTotal').innerText = parseFloat(totalAmount).toFixed(2) + ' ₺';

            const modal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
            modal.show();
        }
    </script>
@endpush
