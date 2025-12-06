@extends('layouts.dashboard')
@section('title', 'Ürün Reçeteleri')

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .select2-container { width: 100% !important; z-index: 9999; }
        .select2-dropdown { z-index: 1056; }
        .recipe-prod-img { width: 32px; height: 32px; object-fit: cover; border-radius: 6px; }
    </style>
@endpush

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-20 mb-0">Ürün Reçeteleri</h1>
                <p class="fs-12 text-muted mb-0">Hangi üründe, hangi malzemeden ne kadar kullanıldığını yönetin.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#createRecipeModal">
                    <i class="ti ti-plus me-1"></i> Yeni Reçete Ekle
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

        <!-- REÇETE LİSTESİ -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Reçete Listesi</div>
                        <span class="badge bg-light text-dark border">Toplam: {{ $recipes->total() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover border table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th scope="col">Ürün</th>
                                    <th scope="col">Kullanılan Malzeme</th>
                                    <th scope="col">Miktar</th>
                                    <th scope="col">Tahmini Maliyet</th>
                                    <th scope="col" class="text-end">İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($recipes as $recipe)
                                    <tr>
                                        <td>
                                            @if($recipe->product)
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="{{ asset($recipe->product->image ?? 'assets/images/ecommerce/png/1.png') }}" class="recipe-prod-img border">
                                                    <div>
                                                        <div class="fw-medium">{{ $recipe->product->name }}</div>
                                                        <span class="fs-11 text-muted">{{ $recipe->product->category->name ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-danger">Ürün Silinmiş</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($recipe->ingredient)
                                                <div class="fw-medium">{{ $recipe->ingredient->name }}</div>
                                                <span class="fs-11 text-muted">Birim Maliyet: {{ $recipe->ingredient->cost_price }} ₺</span>
                                            @else
                                                <span class="text-danger">Malzeme Silinmiş</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($recipe->ingredient)
                                                <span class="fw-bold fs-14">{{ number_format($recipe->quantity, 3) }}</span>
                                                <span class="badge bg-light text-dark border">{{ $recipe->ingredient->unit }}</span>
                                            @else
                                                {{ $recipe->quantity }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($recipe->ingredient)
                                                @php
                                                    $cost = $recipe->quantity * $recipe->ingredient->cost_price;
                                                @endphp
                                                <span class="text-success fw-semibold">{{ number_format($cost, 2) }} ₺</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button class="btn btn-sm btn-icon btn-info-light"
                                                        onclick="editRecipe(
                                                            {{ $recipe->id }},
                                                            '{{ $recipe->product_id }}',
                                                            '{{ $recipe->ingredient_id }}',
                                                            '{{ $recipe->quantity }}'
                                                        )">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-icon btn-danger-light" onclick="deleteRecipe({{ $recipe->id }})">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ti ti-clipboard-list fs-1"></i>
                                                <p class="mt-2">Henüz reçete eklenmemiş.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- PAGINATION --}}
                    @if($recipes->hasPages())
                        <div class="card-footer border-top-0">
                            <div class="d-flex align-items-center flex-wrap overflow-auto">
                                <div class="mb-2 mb-sm-0 me-auto">
                                <span class="text-muted fs-12">
                                    Gösterilen: <b>{{ $recipes->firstItem() }}</b> - <b>{{ $recipes->lastItem() }}</b> / Toplam: <b>{{ $recipes->total() }}</b>
                                </span>
                                </div>
                                <div>
                                    {{ $recipes->links('pagination.vyzor-style') }}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <!-- CREATE MODAL -->
    <div class="modal fade" id="createRecipeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('product-recipes.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Yeni Reçete Satırı Ekle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Ürün Seçin <span class="text-danger">*</span></label>
                            <select class="form-select js-example-placeholder-single" name="product_id" id="create_product_id" required style="width:100%">
                                <option></option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Malzeme Seçin <span class="text-danger">*</span></label>
                            <select class="form-select js-example-placeholder-single" name="ingredient_id" id="create_ingredient_id" required style="width:100%">
                                <option></option>
                                @foreach($ingredients as $ing)
                                    <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Miktar <span class="text-danger">*</span></label>
                            <input type="number" step="0.001" class="form-control" name="quantity" required placeholder="Örn: 0.150">
                            <div class="form-text fs-11">Seçilen malzemenin birimine göre giriniz (örn: kg ise 150g için 0.150 yazın).</div>
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
    <div class="modal fade" id="editRecipeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editRecipeForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Reçeteyi Düzenle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Ürün</label>
                            <select class="form-select js-example-placeholder-single" name="product_id" id="edit_product_id" required style="width:100%">
                                <option></option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Malzeme</label>
                            <select class="form-select js-example-placeholder-single" name="ingredient_id" id="edit_ingredient_id" required style="width:100%">
                                <option></option>
                                @foreach($ingredients as $ing)
                                    <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Miktar</label>
                            <input type="number" step="0.001" class="form-control" id="edit_quantity" name="quantity" required>
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
    <form id="deleteRecipeForm" action="" method="POST" class="d-none">
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
            $('#create_product_id').select2({ placeholder: "Ürün Seçiniz", dropdownParent: $('#createRecipeModal') });
            $('#create_ingredient_id').select2({ placeholder: "Malzeme Seçiniz", dropdownParent: $('#createRecipeModal') });

            $('#edit_product_id').select2({ placeholder: "Ürün Seçiniz", dropdownParent: $('#editRecipeModal') });
            $('#edit_ingredient_id').select2({ placeholder: "Malzeme Seçiniz", dropdownParent: $('#editRecipeModal') });
        });

        // EDIT MODAL DOLDURMA
        window.editRecipe = function(id, prodId, ingId, qty) {
            const form = document.getElementById('editRecipeForm');
            form.action = `/product-recipes/${id}`;

            document.getElementById('edit_quantity').value = qty;

            // Select2 Güncelle
            $('#edit_product_id').val(prodId).trigger('change');
            $('#edit_ingredient_id').val(ingId).trigger('change');

            const modal = new bootstrap.Modal(document.getElementById('editRecipeModal'));
            modal.show();
        };

        // SİLME İŞLEMİ
        window.deleteRecipe = function(id) {
            Swal.fire({
                title: 'Reçete Satırı Silinecek!',
                text: "Bu malzeme üründen çıkarılacak. Onaylıyor musunuz?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, Sil',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteRecipeForm');
                    form.action = `/product-recipes/${id}`;
                    form.submit();
                }
            });
        };
    </script>
@endpush
