@extends('layouts.dashboard')
@section('title', 'Rezervasyonlar')

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <!-- FlatPickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .select2-container { width: 100% !important; z-index: 9999; }
        .select2-dropdown { z-index: 1056; }
        /* Pasif saatlerin rengini belirginleştir */
        .flatpickr-time .flatpickr-am-pm.flatpickr-disabled,
        .flatpickr-time .numInputWrapper span.arrowUp.flatpickr-disabled,
        .flatpickr-time .numInputWrapper span.arrowDown.flatpickr-disabled,
        .flatpickr-time input.flatpickr-hour.flatpickr-disabled,
        .flatpickr-time input.flatpickr-minute.flatpickr-disabled {
            color: #d6d6d6;
            background: transparent;
            cursor: not-allowed;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid page-container main-body-container">
        <!-- HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-20 mb-0">Rezervasyon Yönetimi</h1>
                <p class="fs-12 text-muted mb-0">Gelen rezervasyonları görüntüleyin, düzenleyin veya yeni rezervasyon ekleyin.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#createReservationModal">
                    <i class="ti ti-calendar-plus me-1"></i> Yeni Rezervasyon
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

        <!-- TABLO -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Rezervasyon Listesi</div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark border">Toplam: {{ $reservations->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover border table-bordered">
                                <thead>
                                <tr>
                                    <th>Tarih & Saat</th>
                                    <th>Müşteri</th>
                                    <th>Masa</th>
                                    <th>Kişi</th>
                                    <th>Telefon</th>
                                    <th>Durum</th>
                                    <th class="text-end">İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($reservations as $res)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-sm bg-primary-transparent me-2"><i class="ti ti-calendar-event fs-16"></i></span>
                                                <div>
                                                    <span class="d-block fw-medium">{{ \Carbon\Carbon::parse($res->reservation_time)->format('d.m.Y') }}</span>
                                                    <span class="d-block fs-12 text-muted">{{ \Carbon\Carbon::parse($res->reservation_time)->format('H:i') }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $res->customer_name }}</td>
                                        <td>
                                            @if($res->diningTable)
                                                <span class="badge bg-outline-secondary">{{ $res->diningTable->name }}</span>
                                            @else
                                                <span class="text-danger fs-12">Masa Silinmiş</span>
                                            @endif
                                        </td>
                                        <td>{{ $res->guests_count }}</td>
                                        <td>{{ $res->phone }}</td>
                                        <td>
                                            @if($res->status == 'pending') <span class="badge bg-warning-transparent">Bekliyor</span>
                                            @elseif($res->status == 'confirmed') <span class="badge bg-primary-transparent">Onaylandı</span>
                                            @elseif($res->status == 'completed') <span class="badge bg-success-transparent">Tamamlandı</span>
                                            @else <span class="badge bg-danger-transparent">İptal</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button class="btn btn-sm btn-icon btn-info-light"
                                                        onclick="editReservation({{ $res->id }}, '{{ addslashes($res->customer_name) }}', '{{ $res->phone }}', '{{ $res->dining_table_id }}', '{{ \Carbon\Carbon::parse($res->reservation_time)->format('Y-m-d H:i') }}', {{ $res->guests_count }}, '{{ $res->status }}')">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-icon btn-danger-light" onclick="deleteReservation({{ $res->id }})">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-5">Kayıt yok.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CREATE MODAL -->
    <div class="modal fade" id="createReservationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('reservations.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Yeni Rezervasyon Ekle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Müşteri Adı <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customer_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telefon</label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                            <!-- ÖNCE MASA SEÇİMİ, SONRA TARİH -->
                            <div class="col-md-6">
                                <label class="form-label">Masa <span class="text-danger">*</span></label>
                                <select class="form-select js-example-placeholder-single" name="dining_table_id" id="create_table_select" required style="width: 100%;">
                                    <option></option>
                                    @foreach($tables as $table)
                                        <option value="{{ $table->id }}">{{ $table->name }} ({{ $table->capacity }} Kişilik)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tarih ve Saat <span class="text-danger">*</span></label>
                                <!-- disabled attribute ekledik, masa seçilince açılacak -->
                                <input type="text" class="form-control" id="create_datetime" name="reservation_time" placeholder="Önce Masa Seçiniz" disabled required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kişi</label>
                                <input type="number" class="form-control" name="guests_count" required min="1" value="2">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Durum</label>
                                <select class="form-select" name="status">
                                    <option value="pending">Bekliyor</option>
                                    <option value="confirmed">Onaylandı</option>
                                </select>
                            </div>
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
    <div class="modal fade" id="editReservationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editReservationForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Düzenle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Müşteri</label>
                                <input type="text" class="form-control" id="edit_customer_name" name="customer_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telefon</label>
                                <input type="text" class="form-control" id="edit_phone" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Masa</label>
                                <select class="form-select js-example-placeholder-single" id="edit_dining_table_id" name="dining_table_id" required style="width: 100%;">
                                    <option></option>
                                    @foreach($tables as $table)
                                        <option value="{{ $table->id }}">{{ $table->name }} ({{ $table->capacity }} Kişilik)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tarih ve Saat</label>
                                <input type="text" class="form-control" id="edit_reservation_time" name="reservation_time" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kişi</label>
                                <input type="number" class="form-control" id="edit_guests_count" name="guests_count" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Durum</label>
                                <select class="form-select" id="edit_status" name="status" required>
                                    <option value="pending">Bekliyor</option>
                                    <option value="confirmed">Onaylandı</option>
                                    <option value="completed">Tamamlandı</option>
                                    <option value="cancelled">İptal</option>
                                </select>
                            </div>
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

    <form id="deleteReservationForm" action="" method="POST" class="d-none">@csrf @method('DELETE')</form>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // --- KONFİGÜRASYON ---
            const flatpickrConfig = {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                disableMobile: true,
                minuteIncrement: 30, // 30 dk aralık
                minDate: "today",
            };

            // Nesneleri oluştur
            const createPicker = flatpickr("#create_datetime", flatpickrConfig);
            const editPicker   = flatpickr("#edit_reservation_time", flatpickrConfig);

            // Select2 Başlat
            $('#create_table_select').select2({ placeholder: "Masa Seçiniz", dropdownParent: $('#createReservationModal') });
            $('#edit_dining_table_id').select2({ placeholder: "Masa Seçiniz", dropdownParent: $('#editReservationModal') });

            // --- CREATE MODAL EVENTLERİ ---
            $('#create_table_select').on('change', function() {
                let tableId = $(this).val();
                if(tableId) {
                    fetchAvailability(tableId, createPicker);
                } else {
                    $(createPicker.element).prop('disabled', true);
                    createPicker.clear();
                }
            });

            // --- EDIT MODAL EVENTLERİ ---
            $('#edit_dining_table_id').on('change', function() {
                let tableId = $(this).val();
                let currentResId = $('#editReservationForm').data('id');
                if(tableId) {
                    fetchAvailability(tableId, editPicker, currentResId);
                }
            });

            // --- DOLULUK KONTROLÜ (AJAX) ---
            function fetchAvailability(tableId, pickerInstance, ignoreId = null) {
                // Input'u geçici olarak kilitle ve placeholder'ı değiştir
                $(pickerInstance.element).prop('disabled', true).attr('placeholder', 'Kontrol ediliyor...');

                let url = `/reservations/check-availability/${tableId}`;
                if(ignoreId) url += `?ignore_id=${ignoreId}`;

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        console.log("Sunucudan Gelen Yasaklı Saatler:", response); // KONSOLU KONTROL ET

                        // Disable özelliğini set et
                        pickerInstance.set('disable', response);

                        // Inputu aç
                        $(pickerInstance.element).prop('disabled', false).attr('placeholder', 'Tarih ve Saat Seçiniz');
                    },
                    error: function(err) {
                        console.error("AJAX Hatası:", err);
                        alert("Müsaitlik durumu alınamadı. Lütfen sayfayı yenileyin.");
                        $(pickerInstance.element).prop('disabled', false);
                    }
                });
            }
        });

        // --- EDIT MODALINI DOLDURMA ---
        window.editReservation = function(id, name, phone, tableId, time, guests, status) {
            const form = document.getElementById('editReservationForm');
            form.action = `/reservations/${id}`;
            $(form).data('id', id);

            document.getElementById('edit_customer_name').value = name;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_guests_count').value = guests;
            document.getElementById('edit_status').value = status;

            // 1. Select2'yi tetikle (Bu otomatik olarak fetchAvailability çağıracak)
            $('#edit_dining_table_id').val(tableId).trigger('change');

            // 2. Tarihi biraz gecikmeli yaz (AJAX cevabı gelip disable seti yapıldıktan sonra yazılması daha sağlıklı)
            setTimeout(() => {
                const editPicker = document.querySelector("#edit_reservation_time")._flatpickr;
                editPicker.setDate(time);
            }, 300);

            const modal = new bootstrap.Modal(document.getElementById('editReservationModal'));
            modal.show();
        };

        // --- SİLME ---
        window.deleteReservation = function(id) {
            Swal.fire({
                title: 'Emin misiniz?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Evet, Sil'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteReservationForm');
                    form.action = `/reservations/${id}`;
                    form.submit();
                }
            });
        };
    </script>
@endpush
