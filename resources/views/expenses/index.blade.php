@extends('layouts.dashboard')
@section('title', 'Gider Yönetimi')

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- Header -->
        <div class="page-header-breadcrumb mb-4">
            <div class="d-flex align-center justify-content-between flex-wrap">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-0 d-flex align-items-center">
                        <i class="ri-wallet-3-line me-2 text-primary"></i>
                        Gider Yönetimi
                    </h1>
                    <p class="fs-12 text-muted mb-0">İşletme giderlerini takip edin ve yönetin.</p>
                </div>
                <div class="d-flex gap-2">
                    <!-- Yeni Gider Ekle Butonu -->
                    <button type="button" class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                        <i class="ri-add-line align-middle me-1"></i> Yeni Gider Ekle
                    </button>
                </div>
            </div>
        </div>

        <!-- İstatistik Kartları -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6">
                <div class="card custom-card bg-primary-transparent border-primary border border-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold text-primary">Bugünkü Gider</h6>
                                <h3 class="mb-0 fw-bold mt-1">₺{{ number_format($dailyExpense, 2) }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-primary bg-opacity-10 rounded-circle">
                                <i class="ri-calendar-check-line fs-24 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card custom-card bg-warning-transparent border-warning border border-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold text-warning">Bu Ay Toplam</h6>
                                <h3 class="mb-0 fw-bold mt-1">₺{{ number_format($monthlyExpense, 2) }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-warning bg-opacity-10 rounded-circle">
                                <i class="ri-calendar-2-line fs-24 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card custom-card bg-danger-transparent border-danger border border-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold text-danger">Genel Toplam</h6>
                                <h3 class="mb-0 fw-bold mt-1">₺{{ number_format($totalExpense, 2) }}</h3>
                            </div>
                            <div class="avatar avatar-lg bg-danger bg-opacity-10 rounded-circle">
                                <i class="ri-money-dollar-circle-line fs-24 text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gider Listesi Tablosu -->
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Gider Hareketleri</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="expensesTable" class="table table-bordered text-nowrap w-100">

                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Başlık</th>
                                    <th>Açıklama</th>
                                    <th>Tutar</th>
                                    <th>Ekleyen</th>
                                    <th>Tarih</th>
                                    <th width="10%">İşlem</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($expenses as $expense)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fw-semibold">{{ $expense->title }}</td>
                                        <td class="text-muted">{{ Str::limit($expense->description, 50) ?? '-' }}</td>
                                        <td class="text-danger fw-bold">₺{{ number_format($expense->amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                <i class="ri-user-line me-1"></i> {{ $expense->user->name ?? 'Bilinmiyor' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ $expense->created_at->format('d.m.Y') }}</span>
                                                <span class="fs-11 text-muted">{{ $expense->created_at->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <form id="delete-form-{{ $expense->id }}" action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <!-- Butona onclick eklendi -->
                                            <button type="button" onclick="confirmDelete({{ $expense->id }})" class="btn btn-sm btn-icon btn-danger-light rounded-pill btn-wave">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ri-file-list-3-line fs-32 mb-2 d-block"></i>
                                                Henüz gider kaydı bulunmuyor.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Yeni Gider Ekle Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="addExpenseModalLabel">Yeni Gider Ekle</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Gider Başlığı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Örn: Market Alışverişi" required>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Tutar (₺) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="amount" name="amount" placeholder="0.00" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Gider detayları..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <!-- Eğer şablonda yüklü değilse CDN'leri ekliyoruz -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // 1. Tabloyu DataTables ile zenginleştir (Arama ve Sayfalama)
            $('#expensesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/tr.json' // Türkçe Dil Desteği
                },
                responsive: true,
                order: [[ 0, "asc" ]], // İlk sütuna göre sırala
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tümü"]]
            });

            // 2. Başarılı İşlem Bildirimi (SweetAlert2)
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Başarılı!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    popup: 'colored-toast' // Şablona uygun stil
                }
            });
            @endif

            // 3. Validation Hata Bildirimi (Form hataları varsa)
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
            // Hata varsa Modalı tekrar aç ki kullanıcı düzeltsin
            var myModal = new bootstrap.Modal(document.getElementById('addExpenseModal'));
            myModal.show();
            @endif
        });

        // 4. Silme İşlemi için Onay Penceresi
        function confirmDelete(id) {
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu gider kaydı kalıcı olarak silinecektir! Geri alamazsınız.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, Sil!',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Onay verilirse ilgili formu bul ve gönder
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endpush
