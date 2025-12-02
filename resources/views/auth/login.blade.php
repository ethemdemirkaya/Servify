@extends('layouts.auth')

@section('title','Giriş Yap')

@section('content')
    <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
        <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-6 col-sm-8 col-12">
            <div class="card custom-card border-0 my-4">
                <div class="card-body p-5">

                    <!-- Logo Kısmı -->
                    <div class="mb-4">
                        <a href="{{ url('/') }}">
                            <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="desktop-dark">
                        </a>
                    </div>

                    <!-- Başlık Kısmı -->
                    <div>
                        <h4 class="mb-1 fw-semibold">Merhaba, Tekrar Hoşgeldiniz!</h4>
                        <p class="mb-4 text-muted fw-normal">Lütfen giriş bilgilerinizi giriniz.</p>
                    </div>

                    <!-- Laravel Form Başlangıcı -->
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf <!-- Güvenlik Tokeni -->

                        <div class="row gy-3">
                            <!-- Email Input -->
                            <div class="col-xl-12">
                                <label for="signin-email" class="form-label text-default">E-Posta</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="signin-email" name="email" placeholder="E-Posta Giriniz"
                                       value="{{ old('email') }}"> <!-- Hata olursa eski değeri korur -->

                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Şifre Input -->
                            <div class="col-xl-12 mb-2">
                                <label for="signin-password" class="form-label text-default d-block">Şifre</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="signin-password" name="password" placeholder="Şifre Giriniz">

                                    <a href="javascript:void(0);" class="show-password-button text-muted" onclick="createpassword('signin-password',this)" id="button-addon2">
                                        <i class="ri-eye-off-line align-middle"></i>
                                    </a>

                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Beni Hatırla & Şifremi Unuttum -->
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="defaultCheck1">
                                        <label class="form-check-label" for="defaultCheck1">
                                            Beni Hatırla
                                        </label>
                                        <a href="reset-password-basic.html" class="float-end link-danger fw-medium fs-12">Şifremi Unuttum?</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Giriş Butonu -->
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary">Giriş Yap</button>
                        </div>
                    </form>
                    <!-- Form Bitişi -->



                    <div class="text-center mt-3 fw-medium">
                        Hesabınız yok mu? <a href="sign-up-basic.html" class="text-primary">Kayıt Olun</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
