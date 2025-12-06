@extends('layouts.dashboard')
@section('title', 'Profil Ayarları')

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- Başlık -->
        <div class="page-header-breadcrumb mb-3">
            <div class="d-flex align-center justify-content-between flex-wrap">
                <h1 class="page-title fw-medium fs-18 mb-0">Profil Ayarları</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profilim</li>
                </ol>
            </div>
        </div>

        <!-- Hata/Başarı Mesajları -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mb-5">
            <!-- Sol Taraf: Kart -->
            <div class="col-xl-3">
                <div class="card custom-card">
                    <div class="card-body text-center p-4">
                    <span class="avatar avatar-xxl avatar-rounded bg-primary-transparent fs-24">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                        <h6 class="fw-semibold mt-3 mb-1">{{ $user->name }}</h6>
                        <span class="d-block fs-13 text-muted">{{ $user->email }}</span>
                        <div class="mt-3">
                        <span class="badge bg-primary-transparent">
                            @if($user->role == 'admin') Yönetici
                            @elseif($user->role == 'waiter') Garson
                            @elseif($user->role == 'chef') Şef
                            @elseif($user->role == 'cashier') Kasiyer
                            @endif
                        </span>
                        </div>
                    </div>
                </div>

                <!-- Menü -->
                <div class="card custom-card">
                    <div class="card-body">
                        <ul class="nav nav-tabs flex-column nav-tabs-header mb-0 mail-settings-tab" role="tablist">
                            <li class="nav-item m-1">
                                <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page" href="#personal-info" aria-selected="true">
                                    <i class="ti ti-user-circle me-2 fs-18"></i> Kişisel Bilgiler
                                </a>
                            </li>
                            <li class="nav-item m-1">
                                <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#security" aria-selected="false">
                                    <i class="ti ti-lock me-2 fs-18"></i> Güvenlik & Şifre
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sağ Taraf: Formlar -->
            <div class="col-xl-9">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="tab-content">

                            <!-- TAB 1: Kişisel Bilgiler -->
                            <div class="tab-pane show active p-0 border-0" id="personal-info" role="tabpanel">
                                <form action="{{ route('profile.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <h6 class="fw-medium mb-3">Hesap Bilgileri:</h6>
                                    <div class="row gy-4 mb-4">
                                        <div class="col-xl-6">
                                            <label for="name" class="form-label">Ad Soyad</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="email" class="form-label">E-Posta Adresi</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        </div>

                                        <!-- Veritabanında bio/telefon olmadığı için bu alanları pasif veya görsel amaçlı tuttum, isterseniz kaldırabilirsiniz -->
                                        <div class="col-xl-12">
                                            <div class="alert alert-light border-start border-primary" role="alert">
                                                <i class="ti ti-info-circle me-1"></i> Rolünüz:
                                                <strong>{{ ucfirst($user->role) }}</strong>. Rol değişikliği için yönetici ile iletişime geçiniz.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="float-end">
                                        <button type="submit" class="btn btn-primary">
                                            Bilgileri Güncelle
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- TAB 2: Güvenlik (Şifre) -->
                            <div class="tab-pane p-0 border-0" id="security" role="tabpanel">
                                <form action="{{ route('profile.password') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <h6 class="fw-medium mb-3">Şifre Değiştir:</h6>
                                    <div class="row gy-3">
                                        <div class="col-xl-12">
                                            <div class="alert alert-warning-transparent" role="alert">
                                                <i class="ti ti-alert-triangle me-1"></i> Şifreniz en az 6 karakter olmalıdır.
                                            </div>
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="current_password" class="form-label">Mevcut Şifre</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="password" class="form-label">Yeni Şifre</label>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                        </div>

                                        <div class="col-xl-4">
                                            <label for="password_confirmation" class="form-label">Yeni Şifre (Tekrar)</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                        </div>
                                    </div>

                                    <div class="float-end mt-4">
                                        <button type="submit" class="btn btn-danger">
                                            Şifreyi Değiştir
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
