@extends('layouts.dashboard')
@section('title', 'Masa Yönetimi')

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-20 mb-0">Masa Yönetimi</h1>
                <p class="fs-12 text-muted mb-0">Restoran yerleşimini düzenleyin, yeni masa ekleyin veya mevcutları yönetin.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#createTableModal">
                    <i class="ti ti-plus me-1"></i> Yeni Masa Ekle
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
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- VALIDATION ERRORS -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- MASA KARTLARI -->
        <div class="row">
            @forelse($tables as $table)
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 col-sm-12">
                    <div class="card custom-card border-top-card border-top-primary rounded-3">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                <span class="avatar avatar-md bg-light text-primary border">
                                    <i class="ti ti-table fs-18"></i>
                                </span>
                                    <div>
                                        <h6 class="fw-semibold mb-0">{{ $table->name }}</h6>
                                        <span class="fs-12 text-muted">Kapasite: {{ $table->capacity }} Kişi</span>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <!-- Edit Fonksiyonuna QR Kodu da gönderiyoruz -->
                                            <a class="dropdown-item" href="javascript:void(0);"
                                               onclick="editTable({{ $table->id }}, '{{ addslashes($table->name) }}', {{ $table->capacity }}, '{{ $table->status }}', '{{ $table->qr_code }}')">
                                                <i class="ti ti-edit me-1"></i> Düzenle
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteTable({{ $table->id }})">
                                                <i class="ti ti-trash me-1"></i> Sil
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="d-block fs-12 text-muted mb-1">Durum</span>
                                    @if($table->status == 'empty')
                                        <span class="badge bg-success-transparent"><i class="ti ti-circle-check me-1"></i> BOŞ</span>
                                    @elseif($table->status == 'occupied')
                                        <span class="badge bg-danger-transparent"><i class="ti ti-user me-1"></i> DOLU</span>
                                    @else
                                        <span class="badge bg-warning-transparent"><i class="ti ti-clock me-1"></i> REZERVE</span>
                                    @endif
                                </div>
                                <div class="text-end" style="max-width: 50%;">
                                    <span class="d-block fs-12 text-muted mb-1">QR Kod / Link</span>
                                    <span class="fs-13 fw-medium text-truncate d-block" title="{{ $table->qr_code }}">
                                    {{ $table->qr_code ?? '-' }}
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body text-center p-5">
                        <span class="avatar avatar-xl bg-light mb-3">
                            <i class="ti ti-table-off fs-36 text-muted"></i>
                        </span>
                            <h5 class="fw-medium">Henüz Masa Eklenmemiş</h5>
                            <p class="text-muted">Yeni masa eklemek için sağ üstteki butonu kullanın.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

    </div>

    <!-- CREATE MODAL -->
    <div class="modal fade" id="createTableModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('tables.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Yeni Masa Ekle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Masa Adı / No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required placeholder="Örn: Masa 1, Bahçe 5">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kişi Kapasitesi <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="capacity" required min="1" value="4">
                        </div>

                        <!-- YENİ EKLENEN QR ALANI -->
                        <div class="mb-3">
                            <label class="form-label">QR Kod / Link (Opsiyonel)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-qrcode"></i></span>
                                <input type="text" class="form-control" name="qr_code" placeholder="Boş bırakırsanız otomatik oluşturulur">
                            </div>
                            <div class="form-text fs-11 text-muted">Örn: <code>https://menu.sitem.com/m1</code> veya <code>TBL-001</code></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="editTableModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editTableForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Masayı Düzenle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Masa Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kapasite <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_capacity" name="capacity" required min="1">
                        </div>

                        <!-- YENİ EKLENEN QR ALANI -->
                        <div class="mb-3">
                            <label class="form-label">QR Kod / Link</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-qrcode"></i></span>
                                <input type="text" class="form-control" id="edit_qr_code" name="qr_code" placeholder="Mevcut kodu korumak için dokunmayın">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Masa Durumu (Manuel Ayar)</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="empty">Boş</option>
                                <option value="occupied">Dolu</option>
                                <option value="reserved">Rezerve</option>
                            </select>
                            <div class="form-text fs-11 text-muted">Durumu POS sistemi otomatik yönetir, gerekmedikçe değiştirmeyin.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE FORM -->
    <form id="deleteTableForm" action="" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // EDIT MODALINI DOLDUR
        // QR Kodu parametre olarak alıp inputa yazıyoruz
        function editTable(id, name, capacity, status, qr_code) {
            const form = document.getElementById('editTableForm');
            form.action = `/tables/${id}`;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_capacity').value = capacity;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_qr_code').value = qr_code || ''; // Eğer null ise boş string yap

            const modal = new bootstrap.Modal(document.getElementById('editTableModal'));
            modal.show();
        }

        // SİLME İŞLEMİ (SweetAlert)
        function deleteTable(id) {
            Swal.fire({
                title: 'Masa Silinecek!',
                text: "Bu işlem geri alınamaz. Devam etmek istiyor musunuz?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, Sil',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteTableForm');
                    form.action = `/tables/${id}`;
                    form.submit();
                }
            });
        }
    </script>
@endpush
