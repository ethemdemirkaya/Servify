@extends('layouts.dashboard')
@section('title', 'Genel Ayarlar')

@push('styles')
    <style>
        .setting-img-preview {
            max-width: 150px;
            max-height: 80px;
            border: 1px dashed #ccc;
            padding: 5px;
            border-radius: 8px;
            margin-top: 10px;
            background-color: #f9f9f9;
        }
        .nav-tabs .nav-link.active {
            background-color: var(--primary-color);
            color: #fff !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-20 mb-0">Genel Ayarlar</h1>
                <p class="fs-12 text-muted mb-0">Site kimliği, logolar ve iletişim bilgilerini buradan yönetebilirsiniz.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Buraya gerekirse ek buton konulabilir -->
            </div>
        </div>

        <!-- ALERT MESAJLARI -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-circle-check me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Ayarları Düzenle</div>
                    </div>
                    <div class="card-body">

                        <!-- TAB MENÜSÜ -->
                        <ul class="nav nav-tabs mb-4" id="settingsTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-tab-pane" type="button" role="tab"><i class="ti ti-settings me-1"></i> Genel Bilgiler</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="visual-tab" data-bs-toggle="tab" data-bs-target="#visual-tab-pane" type="button" role="tab"><i class="ti ti-photo me-1"></i> Logolar & Görseller</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab"><i class="ti ti-phone me-1"></i> İletişim</button>
                            </li>
                        </ul>

                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="tab-content" id="settingsTabContent">

                                <!-- GENEL TAB -->
                                <div class="tab-pane fade show active" id="general-tab-pane" role="tabpanel">
                                    <div class="row gy-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Site Adı (Restoran Adı)</label>
                                            <input type="text" class="form-control" name="site_name" value="{{ $settings['site_name'] ?? '' }}" placeholder="Örn: Servify Restoran">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Para Birimi Sembolü</label>
                                            <input type="text" class="form-control" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? '₺' }}" placeholder="₺, $, €">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Site Açıklaması (Slogan)</label>
                                            <textarea class="form-control" name="site_description" rows="3">{{ $settings['site_description'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- GÖRSEL TAB -->
                                <div class="tab-pane fade" id="visual-tab-pane" role="tabpanel">
                                    <div class="row gy-4">
                                        <!-- Varsayılan Logo -->
                                        <div class="col-md-6">
                                            <label class="form-label">Site Logo (Varsayılan)</label>
                                            <input type="file" class="form-control" name="site_logo" onchange="previewImage(this, 'preview_site_logo')">
                                            <div class="mt-2">
                                                @if(isset($settings['site_logo']))
                                                    <img src="{{ asset($settings['site_logo']) }}" id="preview_site_logo" class="setting-img-preview" alt="Logo">
                                                @else
                                                    <img src="" id="preview_site_logo" class="setting-img-preview d-none" alt="Logo">
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Dark Logo -->
                                        <div class="col-md-6">
                                            <label class="form-label">Site Logo (Koyu Tema)</label>
                                            <input type="file" class="form-control" name="site_dark_logo" onchange="previewImage(this, 'preview_dark_logo')">
                                            <div class="mt-2">
                                                @if(isset($settings['site_dark_logo']))
                                                    <img src="{{ asset($settings['site_dark_logo']) }}" id="preview_dark_logo" class="setting-img-preview" style="background:#333;" alt="Dark Logo">
                                                @else
                                                    <img src="" id="preview_dark_logo" class="setting-img-preview d-none" alt="Dark Logo">
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Light Logo -->
                                        <div class="col-md-6">
                                            <label class="form-label">Site Logo (Açık Tema)</label>
                                            <input type="file" class="form-control" name="site_light_logo" onchange="previewImage(this, 'preview_light_logo')">
                                            <div class="mt-2">
                                                @if(isset($settings['site_light_logo']))
                                                    <img src="{{ asset($settings['site_light_logo']) }}" id="preview_light_logo" class="setting-img-preview" alt="Light Logo">
                                                @else
                                                    <img src="" id="preview_light_logo" class="setting-img-preview d-none" alt="Light Logo">
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Favicon -->
                                        <div class="col-md-6">
                                            <label class="form-label">Favicon (Tarayıcı İkonu)</label>
                                            <input type="file" class="form-control" name="favicon" onchange="previewImage(this, 'preview_favicon')">
                                            <div class="mt-2">
                                                @if(isset($settings['favicon']))
                                                    <img src="{{ asset($settings['favicon']) }}" id="preview_favicon" class="setting-img-preview" style="width:32px; height:32px;" alt="Favicon">
                                                @else
                                                    <img src="" id="preview_favicon" class="setting-img-preview d-none" alt="Favicon">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- İLETİŞİM TAB -->
                                <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel">
                                    <div class="row gy-4">
                                        <div class="col-md-6">
                                            <label class="form-label">İletişim E-Posta</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                                <input type="email" class="form-control" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">İletişim Telefon</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ti ti-phone"></i></span>
                                                <input type="text" class="form-control" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer d-flex justify-content-end mt-4 border-top-0 px-0 pb-0">
                                <button type="submit" class="btn btn-primary btn-wave">
                                    <i class="ti ti-device-floppy me-1"></i> Değişiklikleri Kaydet
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        // Görsel Önizleme Fonksiyonu
        function previewImage(input, targetId) {
            const target = document.getElementById(targetId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    target.src = e.target.result;
                    target.classList.remove('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
