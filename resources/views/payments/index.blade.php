@extends('layouts.dashboard')
@section('title', 'Ödeme Geçmişi')

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- Header -->
        <div class="page-header-breadcrumb mb-4">
            <div class="d-flex align-center justify-content-between flex-wrap">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-0 d-flex align-items-center">
                        <i class="ri-hand-coin-line me-2 text-success"></i>
                        Kasa & Ödemeler
                    </h1>
                    <p class="fs-12 text-muted mb-0">Tüm ödeme hareketleri ve ciro takibi.</p>
                </div>
                <div class="d-flex gap-2">
                    <!-- Manuel Ödeme Ekleme (Gerekirse) -->
                    <button type="button" class="btn btn-success btn-wave" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                        <i class="ri-add-line align-middle me-1"></i> Manuel Ödeme Ekle
                    </button>
                </div>
            </div>
        </div>

        <!-- İstatistik Kartları -->
        <div class="row mb-4">
            <!-- Günlük Ciro -->
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card bg-success-transparent border-success border border-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold text-success">Bugünkü Ciro</h6>
                                <h3 class="mb-0 fw-bold mt-1">₺{{ number_format($todayRevenue, 2) }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-success bg-opacity-10 rounded-circle">
                                <i class="ri-money-dollar-box-line fs-24 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nakit Toplam -->
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card bg-warning-transparent border-warning border border-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold text-warning">Nakit Kasa</h6>
                                <h3 class="mb-0 fw-bold mt-1">₺{{ number_format($cashTotal, 2) }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-warning bg-opacity-10 rounded-circle">
                                <i class="ri-cash-line fs-24 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kredi Kartı Toplam -->
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card bg-primary-transparent border-primary border border-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold text-primary">Kredi Kartı</h6>
                                <h3 class="mb-0 fw-bold mt-1">₺{{ number_format($creditCardTotal, 2) }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-primary bg-opacity-10 rounded-circle">
                                <i class="ri-bank-card-line fs-24 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Genel Toplam -->
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card bg-info-transparent border-info border border-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold text-info">Toplam Ciro</h6>
                                <h3 class="mb-0 fw-bold mt-1">₺{{ number_format($totalRevenue, 2) }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-info bg-opacity-10 rounded-circle">
                                <i class="ri-safe-2-line fs-24 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ödemeler Tablosu -->
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Ödeme Hareketleri</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="paymentsTable" class="table table-bordered text-nowrap w-100">
                                <thead>
                                <tr>
                                    <th width="5%">#ID</th>
                                    <th>Sipariş No</th>
                                    <th>Masa / Müşteri</th>
                                    <th>Ödeme Yöntemi</th>
                                    <th>Tutar</th>
                                    <th>Tarih</th>
                                    <th width="5%">İşlem</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($payments as $payment)
                                    @php
                                        // Ödeme yöntemine göre badge rengi ve ikon
                                        $methodInfo = match($payment->payment_method) {
                                            'cash' => ['class' => 'warning', 'text' => 'Nakit', 'icon' => 'ri-cash-line'],
                                            'credit_card' => ['class' => 'primary', 'text' => 'Kredi Kartı', 'icon' => 'ri-bank-card-line'],
                                            'online' => ['class' => 'info', 'text' => 'Online', 'icon' => 'ri-global-line'],
                                            default => ['class' => 'secondary', 'text' => 'Diğer', 'icon' => 'ri-question-line'],
                                        };
                                    @endphp
                                    <tr>
                                        <td>{{ $payment->id }}</td>
                                        <td>
                                            @if($payment->order)
                                                <a href="#" class="fw-semibold text-primary">#{{ $payment->order_id }}</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->order && $payment->order->table)
                                                <span class="badge bg-light text-dark border">
                                                    {{ $payment->order->table->name }}
                                                </span>
                                            @elseif($payment->order && $payment->order->customer_name)
                                                <span>{{ $payment->order->customer_name }}</span>
                                            @else
                                                <span class="text-muted">Bilinmiyor</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $methodInfo['class'] }}-transparent text-{{ $methodInfo['class'] }} fs-12 px-2 py-1">
                                                <i class="{{ $methodInfo['icon'] }} me-1"></i>
                                                {{ $methodInfo['text'] }}
                                            </span>
                                        </td>
                                        <td class="fw-bold text-success">₺{{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ $payment->created_at->format('d.m.Y') }}</span>
                                                <span class="fs-11 text-muted">{{ $payment->created_at->format('H:i:s') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <form id="delete-form-{{ $payment->id }}" action="{{ route('payments.destroy', $payment->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" onclick="confirmDelete({{ $payment->id }})" class="btn btn-sm btn-icon btn-danger-light rounded-pill btn-wave" data-bs-toggle="tooltip" title="Kaydı Sil">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Manuel Ödeme Ekle Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="addPaymentModalLabel">Manuel Ödeme Ekle</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('payments.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info-transparent fs-12">
                            <i class="ri-information-line me-1"></i> Bu alan genellikle hata düzeltmek veya sisteme manuel ödeme girmek için kullanılır.
                        </div>

                        <div class="mb-3">
                            <label for="order_id" class="form-label">Sipariş ID <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="order_id" name="order_id" placeholder="Örn: 15" required>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Tutar (₺) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" placeholder="0.00" required>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Ödeme Yöntemi <span class="text-danger">*</span></label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="cash">Nakit</option>
                                <option value="credit_card">Kredi Kartı</option>
                                <option value="online">Online</option>
                                <option value="other">Diğer</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-success">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // DataTables Başlatma
            $('#paymentsTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/tr.json'
                },
                responsive: true,
                order: [[ 0, "desc" ]], // ID'ye göre tersten sırala (En son eklenen en üstte)
                pageLength: 25
            });

            // Başarılı İşlem Bildirimi
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Başarılı!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: { popup: 'colored-toast' }
            });
            @endif

            // Hata Bildirimi (Validasyon)
            @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                html: `
                    <ul class="text-start text-danger" style="list-style: none;">
                        @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
                        @endforeach
                </ul>
`
            });
            var myModal = new bootstrap.Modal(document.getElementById('addPaymentModal'));
            myModal.show();
            @endif
        });

        // Silme Onayı
        function confirmDelete(id) {
            Swal.fire({
                title: 'Ödemeyi Sil?',
                text: "Bu ödeme kaydı silinecek! Bu işlem kasa raporlarını etkiler.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, Sil!',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endpush
