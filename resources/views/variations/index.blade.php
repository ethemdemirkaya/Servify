@extends('layouts.dashboard')
@section('title', 'Varyasyon Yönetimi')

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
                <h1 class="page-title fw-medium fs-20 mb-0">Varyasyon Yönetimi</h1>
                <p class="fs-12 text-muted mb-0">Ürünlere ait varyasyonları (Büyük Boy, Ekstra Peynir vb.) yönetin.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#createVariationModal">
                    <i class="ti ti-plus me-1"></i> Yeni Varyasyon
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

        <!-- VARYASYON LİSTESİ -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Varyasyon Listesi</div>
                        <span class="badge bg-light text-dark border">Toplam: {{ $variations->total() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover border table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th scope="col">Bağlı Olduğu Ürün</th>
                                    <th scope="col">Varyasyon Adı</th>
                                    <th scope="col">Fiyat Farkı (+/-)</th>
                                    <th scope="col" class="text-end">İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($variations as $variation)
                                    <tr>
                                        <td>
                                            @if($variation->product)
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-sm bg-light me-2">
                                                        <img src="{{ asset($variation->product->image ?? 'assets/images/ecommerce/png/1.png') }}" alt="img">
                                                    </span>
                                                    <div>
                                                        <div class="fw-medium">{{ $variation->product->name }}</div>
                                                        <span class="fs-11 text-muted">{{ number_format($variation->product->price, 2) }} ₺ (Baz Fiyat)</span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-danger">Ürün Silinmiş</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-medium">{{ $variation->name }}</span>
                                        </td>
                                        <td>
                                            @if($variation->price_adjustment > 0)
                                                <span class="badge bg-success-transparent fs-13">
                                                    +{{ number_format($variation->price_adjustment, 2) }} ₺
                                                </span>
                                            @elseif($variation->price_adjustment < 0)
                                                <span class="badge bg-danger-transparent fs-13">
                                                    {{ number_format($variation->price_adjustment, 2) }} ₺
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark border fs-13">Fiyat Farkı Yok</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button class="btn btn-sm btn-icon btn-info-light"
                                                        onclick="editVariation(
                                                            {{ $variation->id }},
                                                            '{{ $variation->product_id }}',
                                                            '{{ addslashes($variation->name) }}',
                                                            '{{ $variation->price_adjustment }}'
                                                        )">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-icon btn-danger-light" onclick="deleteVariation({{ $variation->id }})">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ti ti-tags fs-1"></i>
                                                <p class="mt-2">Henüz varyasyon eklenmemiş.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- PAGINATION ALANI --}}
                    @if($variations->hasPages())
                        <div class="card-footer border-top-0">
                            <div class="d-flex align-items-center flex-wrap overflow-auto">
                                <div class="mb-2 mb-sm-0 me-auto">
                                <span class="text-muted fs-12">
                                    Gösterilen: <b>{{ $variations->firstItem() }}</b> - <b>{{ $variations->lastItem() }}</b> / Toplam: <b>{{ $variations->total() }}</b>
                                </span>
                                </div>
                                <div>
                                    {{ $variations->links('pagination.vyzor-style') }}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <!-- CREATE MODAL -->
    <div class="modal fade" id="createVariationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('variations.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Yeni Varyasyon Ekle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Ürün Seçin <span class="text-danger">*</span></label>
                            <select class="form-select js-example-placeholder-single" name="product_id" id="create_product_select" required style="width:100%">
                                <option></option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ number_format($product->price, 2) }} ₺)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Varyasyon Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required placeholder="Örn: Büyük Boy, Acılı, Ekstra Peynir">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fiyat Farkı (₺) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="price_adjustment" required placeholder="Örn: 15.00 veya -5.00">
                            <div class="form-text fs-11">Ürün baz fiyatına eklenecek tutar. (İndirim için negatif değer girin örn: -10)</div>
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
    <div class="modal fade" id="editVariationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editVariationForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Varyasyonu Düzenle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Ürün Seçin</label>
                            <select class="form-select js-example-placeholder-single" name="product_id" id="edit_product_id" required style="width:100%">
                                <option></option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ number_format($product->price, 2) }} ₺)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Varyasyon Adı</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fiyat Farkı (₺)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_price_adjustment" name="price_adjustment" required>
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
    <form id="deleteVariationForm" action="" method="POST" class="d-none">
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
            $('#create_product_select').select2({ placeholder: "Ürün Seçiniz", dropdownParent: $('#createVariationModal') });
            $('#edit_product_id').select2({ placeholder: "Ürün Seçiniz", dropdownParent: $('#editVariationModal') });
        });

        // EDIT MODAL DOLDURMA
        window.editVariation = function(id, productId, name, priceAdjustment) {
            const form = document.getElementById('editVariationForm');
            form.action = `/variations/${id}`;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price_adjustment').value = priceAdjustment;

            // Select2 Güncelle
            $('#edit_product_id').val(productId).trigger('change');

            const modal = new bootstrap.Modal(document.getElementById('editVariationModal'));
            modal.show();
        };

        // SİLME İŞLEMİ
        window.deleteVariation = function(id) {
            Swal.fire({
                title: 'Varyasyon Silinecek!',
                text: "Bu işlem geri alınamaz.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, Sil',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteVariationForm');
                    form.action = `/variations/${id}`;
                    form.submit();
                }
            });
        };
    </script>
@endpush
