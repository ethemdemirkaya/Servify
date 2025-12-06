@extends('layouts.dashboard')
@section('title', 'Stok & Malzeme Yönetimi')

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
                <h1 class="page-title fw-medium fs-20 mb-0">Stok & Malzeme Yönetimi</h1>
                <p class="fs-12 text-muted mb-0">Mutfak malzemelerini, stok durumlarını ve maliyetlerini yönetin.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#createIngredientModal">
                    <i class="ti ti-plus me-1"></i> Yeni Malzeme
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

        <!-- MALZEME LİSTESİ -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Malzeme Listesi</div>
                        <span class="badge bg-light text-dark border">Toplam: {{ $ingredients->total() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover border table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th scope="col">Malzeme Adı</th>
                                    <th scope="col">Birim</th>
                                    <th scope="col">Mevcut Stok</th>
                                    <th scope="col">Kritik Limit</th>
                                    <th scope="col">Birim Maliyet</th>
                                    <th scope="col" class="text-end">İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($ingredients as $ingredient)
                                    <tr class="{{ $ingredient->stock_quantity <= $ingredient->alert_limit ? 'bg-danger-transparent' : '' }}">
                                        <td>
                                            <div class="fw-medium">{{ $ingredient->name }}</div>
                                        </td>
                                        <td>
                                            @php
                                                $units = [
                                                    'kg' => 'Kilogram (kg)',
                                                    'g' => 'Gram (g)',
                                                    'l' => 'Litre (L)',
                                                    'ml' => 'Mililitre (ml)',
                                                    'piece' => 'Adet'
                                                ];
                                            @endphp
                                            <span class="badge bg-info-transparent">{{ $units[$ingredient->unit] ?? $ingredient->unit }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold fs-14">{{ number_format($ingredient->stock_quantity, 3) }}</span>

                                            @if($ingredient->stock_quantity <= $ingredient->alert_limit)
                                                <span class="badge bg-danger ms-2"><i class="ti ti-alert-circle"></i> Kritik</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ number_format($ingredient->alert_limit, 3) }}
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ number_format($ingredient->cost_price, 2) }} ₺</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button class="btn btn-sm btn-icon btn-info-light"
                                                        onclick="editIngredient(
                                                            {{ $ingredient->id }},
                                                            '{{ addslashes($ingredient->name) }}',
                                                            '{{ $ingredient->unit }}',
                                                            '{{ $ingredient->stock_quantity }}',
                                                            '{{ $ingredient->alert_limit }}',
                                                            '{{ $ingredient->cost_price }}'
                                                        )">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-icon btn-danger-light" onclick="deleteIngredient({{ $ingredient->id }})">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ti ti-package fs-1"></i>
                                                <p class="mt-2">Henüz malzeme eklenmemiş.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- PAGINATION ALANI --}}
                    @if($ingredients->hasPages())
                        <div class="card-footer border-top-0">
                            <div class="d-flex align-items-center flex-wrap overflow-auto">
                                <div class="mb-2 mb-sm-0 me-auto">
                                <span class="text-muted fs-12">
                                    Gösterilen: <b>{{ $ingredients->firstItem() }}</b> - <b>{{ $ingredients->lastItem() }}</b> / Toplam: <b>{{ $ingredients->total() }}</b>
                                </span>
                                </div>
                                <div>
                                    {{ $ingredients->links('pagination.vyzor-style') }}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <!-- CREATE MODAL -->
    <div class="modal fade" id="createIngredientModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('ingredients.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Yeni Malzeme Ekle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Malzeme Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required placeholder="Örn: Un, Domates, Süt">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Birim <span class="text-danger">*</span></label>
                            <select class="form-select js-example-placeholder-single" name="unit" id="create_unit_select" required style="width:100%">
                                <option></option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="g">Gram (g)</option>
                                <option value="l">Litre (L)</option>
                                <option value="ml">Mililitre (ml)</option>
                                <option value="piece">Adet</option>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Başlangıç Stoğu</label>
                                <input type="number" step="0.001" class="form-control" name="stock_quantity" required value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kritik Stok Limiti</label>
                                <input type="number" step="0.001" class="form-control" name="alert_limit" required value="1">
                                <div class="form-text fs-11">Bu seviyenin altına düşünce uyarı verilir.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Birim Maliyet (₺)</label>
                            <input type="number" step="0.01" class="form-control" name="cost_price" required value="0.00">
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
    <div class="modal fade" id="editIngredientModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editIngredientForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Malzemeyi Düzenle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Malzeme Adı</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Birim</label>
                            <select class="form-select js-example-placeholder-single" name="unit" id="edit_unit" required style="width:100%">
                                <option></option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="g">Gram (g)</option>
                                <option value="l">Litre (L)</option>
                                <option value="ml">Mililitre (ml)</option>
                                <option value="piece">Adet</option>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Mevcut Stok</label>
                                <input type="number" step="0.001" class="form-control" id="edit_stock_quantity" name="stock_quantity" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kritik Stok Limiti</label>
                                <input type="number" step="0.001" class="form-control" id="edit_alert_limit" name="alert_limit" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Birim Maliyet (₺)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_cost_price" name="cost_price" required>
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
    <form id="deleteIngredientForm" action="" method="POST" class="d-none">
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
            $('#create_unit_select').select2({ placeholder: "Birim Seçiniz", dropdownParent: $('#createIngredientModal') });
            $('#edit_unit').select2({ placeholder: "Birim Seçiniz", dropdownParent: $('#editIngredientModal') });
        });

        // EDIT MODAL DOLDURMA
        window.editIngredient = function(id, name, unit, stock, alert, cost) {
            const form = document.getElementById('editIngredientForm');
            form.action = `/ingredients/${id}`;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_stock_quantity').value = stock;
            document.getElementById('edit_alert_limit').value = alert;
            document.getElementById('edit_cost_price').value = cost;

            // Select2 Güncelle
            $('#edit_unit').val(unit).trigger('change');

            const modal = new bootstrap.Modal(document.getElementById('editIngredientModal'));
            modal.show();
        };

        // SİLME İŞLEMİ
        window.deleteIngredient = function(id) {
            Swal.fire({
                title: 'Malzeme Silinecek!',
                text: "Bu işlem geri alınamaz. Ürün reçetelerinde kullanılıyorsa sorun çıkabilir.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, Sil',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteIngredientForm');
                    form.action = `/ingredients/${id}`;
                    form.submit();
                }
            });
        };
    </script>
@endpush
