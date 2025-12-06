@extends('layouts.dashboard')
@section('title', 'Stok Hareketleri')

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .select2-container { width: 100% !important; z-index: 9999; }
        .select2-dropdown { z-index: 1056; }
    </style>
@endpush

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-20 mb-0">Stok Hareketleri</h1>
                <p class="fs-12 text-muted mb-0">Malzeme giriş, çıkış ve zayi kayıtlarını buradan yönetin.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#createTransactionModal">
                    <i class="ti ti-plus me-1"></i> Yeni Hareket Ekle
                </button>
            </div>
        </div>

        <!-- ALERT MESAJLARI -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <!-- HAREKET LİSTESİ -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Geçmiş Hareketler</div>
                        <span class="badge bg-light text-dark border">Toplam: {{ $transactions->total() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover border table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th scope="col">Tarih</th>
                                    <th scope="col">Malzeme</th>
                                    <th scope="col">İşlem Türü</th>
                                    <th scope="col">Miktar</th>
                                    <th scope="col">Açıklama</th>
                                    <th scope="col">Personel</th>
                                    <th scope="col" class="text-end">İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <span class="fs-12 text-muted">{{ $transaction->created_at->format('d.m.Y H:i') }}</span>
                                        </td>
                                        <td>
                                            @if($transaction->ingredient)
                                                <span class="fw-medium">{{ $transaction->ingredient->name }}</span>
                                            @else
                                                <span class="text-danger">Malzeme Silinmiş</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $badges = [
                                                    'purchase' => ['color' => 'success', 'icon' => 'ti-shopping-cart', 'text' => 'Satın Alma (Giriş)'],
                                                    'sale' => ['color' => 'info', 'icon' => 'ti-currency-lira', 'text' => 'Satış (Çıkış)'],
                                                    'waste' => ['color' => 'danger', 'icon' => 'ti-trash', 'text' => 'Zayi (Çöp)'],
                                                    'adjustment' => ['color' => 'warning', 'icon' => 'ti-adjustments', 'text' => 'Düzeltme'],
                                                ];
                                                $current = $badges[$transaction->type] ?? ['color' => 'secondary', 'icon' => 'ti-help', 'text' => $transaction->type];
                                            @endphp
                                            <span class="badge bg-{{ $current['color'] }}-transparent">
                                                <i class="ti {{ $current['icon'] }} me-1"></i> {{ $current['text'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($transaction->ingredient)
                                                <span class="fw-bold fs-14">{{ number_format($transaction->quantity, 3) }}</span>
                                                <span class="text-muted fs-11">{{ $transaction->ingredient->unit }}</span>
                                            @else
                                                {{ $transaction->quantity }}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted d-block text-truncate" style="max-width: 150px;" title="{{ $transaction->description }}">
                                                {{ $transaction->description ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="avatar avatar-xs me-1 bg-primary-transparent">
                                                {{ substr($transaction->user->name ?? '?', 0, 1) }}
                                            </span>
                                            <span class="fs-12">{{ $transaction->user->name ?? 'Bilinmiyor' }}</span>
                                        </td>
                                        <td class="text-end">
                                            {{-- Stok hareketlerinde genelde düzenleme olmaz, sadece silme olur --}}
                                            <button class="btn btn-sm btn-icon btn-danger-light" onclick="deleteTransaction({{ $transaction->id }})">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ti ti-exchange fs-1"></i>
                                                <p class="mt-2">Henüz stok hareketi kaydedilmemiş.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- PAGINATION --}}
                    @if($transactions->hasPages())
                        <div class="card-footer border-top-0">
                            <div class="d-flex align-items-center flex-wrap overflow-auto">
                                <div class="mb-2 mb-sm-0 me-auto">
                                <span class="text-muted fs-12">
                                    Gösterilen: <b>{{ $transactions->firstItem() }}</b> - <b>{{ $transactions->lastItem() }}</b> / Toplam: <b>{{ $transactions->total() }}</b>
                                </span>
                                </div>
                                <div>
                                    {{ $transactions->links('pagination.vyzor-style') }}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <!-- CREATE MODAL -->
    <div class="modal fade" id="createTransactionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('inventory.transactions.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Yeni Stok Hareketi</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info fs-11">
                            <i class="ti ti-info-circle me-1"></i> Bu işlem seçilen malzemenin stok miktarını otomatik olarak güncelleyecektir.
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Malzeme Seçin <span class="text-danger">*</span></label>
                            <select class="form-select js-example-placeholder-single" name="ingredient_id" id="create_ingredient_id" required style="width:100%">
                                <option></option>
                                @foreach($ingredients as $ing)
                                    <option value="{{ $ing->id }}">{{ $ing->name }} (Mevcut: {{ number_format($ing->stock_quantity, 2) }} {{ $ing->unit }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">İşlem Türü <span class="text-danger">*</span></label>
                            <select class="form-select" name="type" required>
                                <option value="purchase">Satın Alma (Stok Artırır)</option>
                                <option value="waste">Zayi / Çöp (Stok Azaltır)</option>
                                <option value="sale">Manuel Satış (Stok Azaltır)</option>
                                <option value="adjustment">Düzeltme / Sayım Farkı (+/-)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Miktar <span class="text-danger">*</span></label>
                            <input type="number" step="0.001" class="form-control" name="quantity" required placeholder="Örn: 5.0">
                            <div class="form-text fs-11">Düzeltme seçerseniz: Stok eklemek için pozitif, çıkarmak için negatif (örn: -2) girebilirsiniz. Diğerlerinde hep pozitif girin.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Örn: Marketten alındı..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">İşlemi Kaydet</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE FORM -->
    <form id="deleteTransactionForm" action="" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Select2 Başlatma
            $('#create_ingredient_id').select2({ placeholder: "Malzeme Seçiniz", dropdownParent: $('#createTransactionModal') });
        });

        // SİLME İŞLEMİ
        window.deleteTransaction = function(id) {
            Swal.fire({
                title: 'Kayıt Silinecek!',
                text: "Bu kaydı sildiğinizde stok miktarı OTOMATİK OLARAK GERİ ALINMAZ. Manuel düzeltme yapmanız gerekebilir. Devam edilsin mi?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, Sil',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteTransactionForm');
                    // Rota isimlendirmesine dikkat: resource rotasında 'destroy' URL'si '/inventory/transactions/{id}' şeklindedir.
                    form.action = `/inventory/transactions/${id}`;
                    form.submit();
                }
            });
        };
    </script>
@endpush
