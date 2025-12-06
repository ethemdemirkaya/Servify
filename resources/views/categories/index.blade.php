@extends('layouts.dashboard')
@section('title', 'Kategori Yönetimi')

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        .select2-container { width: 100% !important; z-index: 9999; }
        .select2-dropdown { z-index: 1056; }
        .cat-img-preview { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
    </style>
@endpush

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-20 mb-0">Kategori Yönetimi</h1>
                <p class="fs-12 text-muted mb-0">Ürün kategorilerini düzenleyin, yeni kategori ekleyin.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                    <i class="ti ti-plus me-1"></i> Yeni Kategori
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
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <!-- KATEGORİ LİSTESİ -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Kategori Listesi</div>
                        {{-- total() metodu pagination ile gelen toplam sayıyı verir --}}
                        <span class="badge bg-light text-dark border">Toplam: {{ $categories->total() }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-hover border table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th scope="col" width="60">Görsel</th>
                                    <th scope="col">Kategori Adı</th>
                                    <th scope="col">Yazıcı Hedefi</th>
                                    <th scope="col">Ürün Sayısı</th>
                                    <th scope="col" class="text-end">İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($categories as $cat)
                                    <tr>
                                        <td>
                                            @if($cat->image)
                                                <img src="{{ asset($cat->image) }}" alt="img" class="cat-img-preview border">
                                            @else
                                                <span class="avatar avatar-sm bg-light text-muted rounded-circle border">
                                                    <i class="ti ti-photo-off"></i>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $cat->name }}</div>
                                            <span class="fs-11 text-muted">/{{ $cat->slug }}</span>
                                        </td>
                                        <td>
                                            @if($cat->printer)
                                                <span class="badge bg-info-transparent"><i class="ti ti-printer me-1"></i> {{ $cat->printer->name }}</span>
                                            @else
                                                <span class="text-muted fs-12">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-transparent rounded-pill">{{ $cat->products_count }} Ürün</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button class="btn btn-sm btn-icon btn-info-light"
                                                        onclick="editCategory(
                                                            {{ $cat->id }},
                                                            '{{ addslashes($cat->name) }}',
                                                            '{{ $cat->printer_id }}'
                                                        )">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-icon btn-danger-light" onclick="deleteCategory({{ $cat->id }})">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ti ti-folder-off fs-1"></i>
                                                <p class="mt-2">Henüz kategori eklenmemiş.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- PAGINATION ALANI --}}
                    @if($categories->hasPages())
                        <div class="card-footer border-top-0">
                            <div class="d-flex align-items-center flex-wrap overflow-auto">
                                <div class="mb-2 mb-sm-0 me-auto">
                                <span class="text-muted fs-12">
                                    Gösterilen: <b>{{ $categories->firstItem() }}</b> - <b>{{ $categories->lastItem() }}</b> / Toplam: <b>{{ $categories->total() }}</b>
                                </span>
                                </div>
                                <div>
                                    {{-- Özel Pagination View Çağrısı --}}
                                    {{ $categories->links('pagination.vyzor-style') }}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <!-- CREATE MODAL -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Yeni Kategori Ekle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kategori Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required placeholder="Örn: İçecekler">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mutfak Yazıcısı (Opsiyonel)</label>
                            <select class="form-select js-example-placeholder-single" name="printer_id" id="create_printer_select" style="width:100%">
                                <option value="">Seçiniz (Varsayılan)</option>
                                @foreach($printers as $printer)
                                    <option value="{{ $printer->id }}">{{ $printer->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text fs-11">Sipariş fişlerinin hangi yazıcıdan çıkacağını belirler.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori Görseli</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
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
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editCategoryForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Kategoriyi Düzenle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kategori Adı</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mutfak Yazıcısı</label>
                            <select class="form-select js-example-placeholder-single" name="printer_id" id="edit_printer_id" style="width:100%">
                                <option value="">Seçiniz (Varsayılan)</option>
                                @foreach($printers as $printer)
                                    <option value="{{ $printer->id }}">{{ $printer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Görsel Değiştir</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <div class="form-text fs-11 text-warning">Sadece değiştirmek istiyorsanız dosya seçin.</div>
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
    <form id="deleteCategoryForm" action="" method="POST" class="d-none">
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
            $('#create_printer_select').select2({ placeholder: "Yazıcı Seçiniz", allowClear: true, dropdownParent: $('#createCategoryModal') });
            $('#edit_printer_id').select2({ placeholder: "Yazıcı Seçiniz", allowClear: true, dropdownParent: $('#editCategoryModal') });
        });

        // EDIT MODAL DOLDURMA
        window.editCategory = function(id, name, printerId) {
            const form = document.getElementById('editCategoryForm');
            form.action = `/categories/${id}`;

            document.getElementById('edit_name').value = name;

            // Select2 Güncelle
            $('#edit_printer_id').val(printerId).trigger('change');

            const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
            modal.show();
        };

        // SİLME İŞLEMİ
        window.deleteCategory = function(id) {
            Swal.fire({
                title: 'Kategori Silinecek!',
                text: "Kategoriye bağlı ürünler varsa silme işlemi başarısız olabilir.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, Sil',
                cancelButtonText: 'Vazgeç'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteCategoryForm');
                    form.action = `/categories/${id}`;
                    form.submit();
                }
            });
        };
    </script>
@endpush
