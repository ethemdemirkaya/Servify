@extends('layouts.dashboard')
@section('title', 'Ürün Yönetimi')

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .select2-container { width: 100% !important; z-index: 9999; }
        .select2-dropdown { z-index: 1056; }
        .product-img-preview {
            width: 50px; height: 50px; object-fit: cover; border-radius: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-20 mb-0">Ürün Yönetimi</h1>
                <p class="fs-12 text-muted mb-0">Menüdeki ürünleri ekleyin, fiyatlarını düzenleyin veya kaldırın.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#createProductModal">
                    <i class="ti ti-plus me-1"></i> Yeni Ürün Ekle
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

        <!-- ÜRÜN LİSTESİ -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Ürün Listesi</div>
                        <div class="d-flex gap-2">
                            {{-- total() metodu pagination ile gelen toplam kayıt sayısını verir --}}
                            <span class="badge bg-light text-dark border">Toplam: {{ $products->total() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover border table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th scope="col" width="80">Görsel</th>
                                    <th scope="col">Ürün Adı</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Fiyat</th>
                                    <th scope="col">Durum</th>
                                    <th scope="col" class="text-end">İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                            <span class="avatar avatar-lg bg-light">
                                                <img src="{{ asset($product->image ?? 'assets/images/ecommerce/png/1.png') }}" alt="img" class="product-img-preview">
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $product->name }}</div>
                                            <small class="text-muted text-truncate d-block" style="max-width: 200px;">{{ $product->description }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-transparent">{{ $product->category->name ?? 'Kategorisiz' }}</span>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 fw-semibold text-primary">{{ number_format($product->price, 2) }} ₺</h6>
                                        </td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge bg-success-transparent"><i class="ti ti-check me-1"></i> Aktif</span>
                                            @else
                                                <span class="badge bg-danger-transparent"><i class="ti ti-x me-1"></i> Pasif</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button class="btn btn-sm btn-icon btn-info-light"
                                                        onclick="editProduct(
                                                            {{ $product->id }},
                                                            '{{ addslashes($product->name) }}',
                                                            '{{ $product->category_id }}',
                                                            '{{ $product->price }}',
                                                            '{{ addslashes($product->description) }}',
                                                            {{ $product->is_active }}
                                                        )">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-icon btn-danger-light" onclick="deleteProduct({{ $product->id }})">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ti ti-box-off fs-1"></i>
                                                <p class="mt-2">Henüz ürün eklenmemiş.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- PAGINATION ALANI (Kartın Alt Kısmı) --}}
                    @if($products->hasPages())
                        <div class="card-footer border-top-0">
                            <div class="d-flex align-items-center flex-wrap overflow-auto">
                                <div class="mb-2 mb-sm-0 me-auto">
                                <span class="text-muted fs-12">
                                    Gösterilen: <b>{{ $products->firstItem() }}</b> - <b>{{ $products->lastItem() }}</b> / Toplam: <b>{{ $products->total() }}</b>
                                </span>
                                </div>
                                <div>
                                    {{-- Özel Pagination View Çağrısı --}}
                                    {{ $products->links('pagination.vyzor-style') }}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <!-- CREATE MODAL -->
    <div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Yeni Ürün Ekle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Ürün Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required placeholder="Örn: Adana Kebap">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select js-example-placeholder-single" name="category_id" id="create_category_select" required style="width:100%">
                                    <option></option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fiyat (₺) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="price" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="İçindekiler vb..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ürün Görseli</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <div class="form-text fs-11">Desteklenen: jpg, png, jpeg. Maks: 2MB</div>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" checked>
                            <label class="form-check-label">Ürün Aktif (Menüde görünsün)</label>
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
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editProductForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Ürünü Düzenle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select class="form-select js-example-placeholder-single" name="category_id" id="edit_category_id" required style="width:100%">
                                    <option></option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fiyat (₺)</label>
                                <input type="number" step="0.01" class="form-control" id="edit_price" name="price" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Açıklama</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Görsel Değiştir (Opsiyonel)</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <div class="form-text fs-11 text-warning">Sadece değiştirmek istiyorsanız dosya seçin.</div>
                        </div>

                        <div class="form-check form-switch">
                            <!-- Hidden input checkbox unchecked durumunu handle etmek için -->
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" id="edit_is_active" name="is_active" value="1">
                            <label class="form-check-label">Ürün Aktif</label>
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
    <form id="deleteProductForm" action="" method="POST" class="d-none">
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
            $('#create_category_select').select2({ placeholder: "Kategori Seçiniz", dropdownParent: $('#createProductModal') });
            $('#edit_category_id').select2({ placeholder: "Kategori Seçiniz", dropdownParent: $('#editProductModal') });
        });

        // EDIT MODAL DOLDURMA
        window.editProduct = function(id, name, catId, price, desc, isActive) {
            const form = document.getElementById('editProductForm');
            form.action = `/products/${id}`;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_description').value = desc || ''; // Null ise boş string

            // Switch Checkbox Kontrolü
            document.getElementById('edit_is_active').checked = (isActive == 1);

            // Select2 Güncelle
            $('#edit_category_id').val(catId).trigger('change');

            const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
            modal.show();
        };

        // SİLME İŞLEMİ
        window.deleteProduct = function(id) {
            Swal.fire({
                title: 'Ürün Silinecek!',
                text: "Bu işlem geri alınamaz. Devam etmek istiyor musunuz?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, Sil',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteProductForm');
                    form.action = `/products/${id}`;
                    form.submit();
                }
            });
        };
    </script>
@endpush
