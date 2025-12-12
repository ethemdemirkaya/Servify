@extends('layouts.dashboard')
@section('title', 'Vardiya İşlemleri')

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- Header -->
        <div class="page-header-breadcrumb mb-4">
            <div class="d-flex align-center justify-content-between flex-wrap">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-0 d-flex align-items-center">
                        <i class="ri-history-line me-2 text-primary"></i>
                        Vardiya Yönetimi
                    </h1>
                    <p class="fs-12 text-muted mb-0">Günlük kasa açılış ve kapanış işlemlerini buradan yönetebilirsiniz.</p>
                </div>
                <div class="d-flex gap-2">
                <span class="badge bg-light text-dark border fs-13">
                    <i class="ri-user-line me-1"></i> {{ Auth::user()->name }}
                </span>
                </div>
            </div>
        </div>

        <!-- Aktif Vardiya Durumu Kartı -->
        <div class="row mb-4">
            <div class="col-12">
                @if($activeShift)
                    <!-- AÇIK VARDİYA VARSA -->
                    <div class="card custom-card border-top border-3 border-success shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div>
                                    <h5 class="fw-bold text-success mb-1">
                                        <i class="ri-checkbox-circle-line"></i> Vardiyanız Açık
                                    </h5>
                                    <div class="text-muted fs-13">
                                        Başlangıç: <span class="fw-semibold text-dark">{{ $activeShift->start_time->format('H:i') }}</span> |
                                        Başlangıç Kasası: <span class="fw-semibold text-dark">₺{{ number_format($activeShift->starting_cash, 2) }}</span>
                                    </div>
                                    <div class="mt-2 text-primary fs-14">
                                        <i class="ri-cash-line"></i> Şu ana kadar yapılan Nakit Satış:
                                        <span class="fw-bold">₺{{ number_format($activeShift->cash_sales, 2) }}</span>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-danger btn-wave btn-lg" data-bs-toggle="modal" data-bs-target="#closeShiftModal">
                                        <i class="ri-door-lock-line me-2"></i> Günü Bitir / Vardiyayı Kapat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- VARDİYA KAPALIYSA -->
                    <div class="card custom-card border-top border-3 border-secondary bg-light">
                        <div class="card-body text-center py-4">
                            <div class="mb-3">
                            <span class="avatar avatar-xxl avatar-rounded bg-white text-muted shadow-sm">
                                <i class="ri-store-2-line fs-32"></i>
                            </span>
                            </div>
                            <h4 class="fw-bold text-dark">Vardiyanız Kapalı</h4>
                            <p class="text-muted mb-4">Sipariş almaya başlamak için lütfen vardiyanızı başlatın ve kasadaki parayı girin.</p>
                            <button class="btn btn-primary btn-lg btn-wave px-5" data-bs-toggle="modal" data-bs-target="#startShiftModal">
                                <i class="ri-play-circle-line me-2"></i> Vardiyayı Başlat
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Geçmiş Vardiyalar Tablosu -->
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Vardiya Geçmişi</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="shiftsTable" class="table table-bordered text-nowrap w-100">
                                <thead>
                                <tr>
                                    <th>Personel</th>
                                    <th>Başlangıç</th>
                                    <th>Bitiş</th>
                                    <th>Başlangıç Kasası</th>
                                    <th>Nakit Satış</th>
                                    <th>Beklenen Kasa</th>
                                    <th>Sayılan (Gerçek)</th>
                                    <th>Fark</th>
                                    <th>Durum</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shifts as $shift)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="avatar avatar-xs avatar-rounded bg-primary text-white">
                                                    {{ substr($shift->user->name, 0, 1) }}
                                                </span>
                                                <span class="fw-semibold">{{ $shift->user->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $shift->start_time->format('d.m.Y H:i') }}</td>
                                        <td>
                                            @if($shift->end_time)
                                                {{ $shift->end_time->format('d.m.Y H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">₺{{ number_format($shift->starting_cash, 2) }}</td>

                                        <!-- Nakit Satış -->
                                        <td class="text-info fw-semibold">
                                            + ₺{{ number_format($shift->cash_sales, 2) }}
                                        </td>

                                        <!-- Beklenen -->
                                        <td class="fw-bold">₺{{ number_format($shift->expected_cash, 2) }}</td>

                                        <!-- Sayılan -->
                                        <td>
                                            @if($shift->status == 'closed')
                                                ₺{{ number_format($shift->actual_cash, 2) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <!-- Fark -->
                                        <td>
                                            @if($shift->status == 'closed')
                                                @if($shift->difference < 0)
                                                    <span class="badge bg-danger-transparent text-danger">
                                                        {{ number_format($shift->difference, 2) }} ₺ (Eksik)
                                                    </span>
                                                @elseif($shift->difference > 0)
                                                    <span class="badge bg-success-transparent text-success">
                                                        +{{ number_format($shift->difference, 2) }} ₺ (Fazla)
                                                    </span>
                                                @else
                                                    <span class="badge bg-success-transparent text-success">Tam</span>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if($shift->status == 'open')
                                                <span class="badge bg-success">AÇIK</span>
                                            @else
                                                <span class="badge bg-light text-dark border">KAPALI</span>
                                            @endif
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

    <!-- Vardiya BAŞLAT Modal -->
    <!-- Vardiya BAŞLAT Modal -->
    <div class="modal fade" id="startShiftModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Vardiyayı Başlat</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('shifts.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <i class="ri-sun-line fs-40 text-warning"></i>
                            <p class="mb-0 mt-2 text-muted">Vardiya açılış işlemleri.</p>
                        </div>

                        <!-- ADMIN İSE PERSONEL SEÇİMİ -->
                        @if(Auth::user()->role == 'admin')
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Vardiya Kimin İçin Açılacak?</label>
                                <select class="form-select" name="user_id">
                                    <option value="{{ Auth::id() }}">Kendim ({{ Auth::user()->name }})</option>
                                    @foreach($users as $user)
                                        @if($user->id != Auth::id())
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="form-text text-primary"><i class="ri-admin-line"></i> Yönetici olarak başka personel adına vardiya açabilirsiniz.</div>
                            </div>
                        @else
                            <!-- ADMİN DEĞİLSE GİZLİ INPUT (KENDİ ID'Sİ GİDER AMA CONTROLLER ZATEN KONTROL EDİYOR) -->
                            <div class="alert alert-light border text-center">
                                <span class="fw-bold">{{ Auth::user()->name }}</span> olarak giriş yapıldı.
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kasa Başlangıç Tutarı (₺)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">₺</span>
                                <input type="number" step="0.01" class="form-control form-control-lg" name="starting_cash" placeholder="0.00" required>
                            </div>
                            <div class="form-text">Genelde kasada bozuk para için bırakılan tutar.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary px-4">Başlat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Vardiya KAPAT Modal -->
    @if($activeShift)
        <div class="modal fade" id="closeShiftModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Vardiyayı Kapat</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('shifts.update', $activeShift->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="alert alert-primary-transparent mb-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Başlangıç Kasası:</span>
                                    <span class="fw-bold">₺{{ number_format($activeShift->starting_cash, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Nakit Satışlar:</span>
                                    <span class="fw-bold text-success">+ ₺{{ number_format($activeShift->cash_sales, 2) }}</span>
                                </div>
                                <div class="border-top border-primary my-2 pt-2 d-flex justify-content-between">
                                    <span class="fw-bold">Sistemde Olması Gereken:</span>
                                    <span class="fw-bold fs-16">₺{{ number_format($activeShift->expected_cash, 2) }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Gerçek (Sayılan) Kasa Tutarı</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">₺</span>
                                    <input type="number" step="0.01" class="form-control form-control-lg" name="actual_cash" placeholder="Kasayı sayıp yazınız..." required>
                                </div>
                                <div class="form-text text-muted">Kasayı saydıktan sonra çıkan net tutarı giriniz. Sistem otomatik olarak farkı hesaplayacaktır.</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Vazgeç</button>
                            <button type="submit" class="btn btn-danger px-4">Günü Kapat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Tabloyu zenginleştir
            $('#shiftsTable').DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/tr.json' },
                responsive: true,
                order: [[ 1, "desc" ]] // Başlangıç saatine göre en yeni en üstte
            });

            // Başarılı Mesajı
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Başarılı!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                customClass: { popup: 'colored-toast' }
            });
            @endif

            // Hata Mesajı
            @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: "{{ $errors->first() }}"
            });
            @endif
        });
    </script>
@endpush
