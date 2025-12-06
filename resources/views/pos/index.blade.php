@extends('layouts.dashboard')
@section('title', 'POS Terminali')

@section('content')
    <div class="container-fluid page-container main-body-container p-4">
        <!-- Başlık -->
        <div class="page-header-breadcrumb mb-3">
            <div class="d-flex align-center justify-content-between flex-wrap">
                <h1 class="page-title fw-medium fs-18 mb-0">POS Satış Ekranı</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-danger btn-sm">
                        <i class="ti ti-logout me-1"></i> Çıkış
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- SOL TARA: Ürünler ve Kategoriler -->
            <div class="col-xxl-9">
                <div class="row">
                    <!-- Arama ve Kategoriler -->
                    <div class="col-xl-12">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <div class="input-group w-50">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-search"></i></span>
                                <input type="text" class="form-control border-start-0 bg-light" id="searchProduct" placeholder="Ürün ara..." autocomplete="off">
                            </div>
                            <div class="fs-13 text-muted">Toplam <span class="badge bg-primary ms-1 rounded-pill">{{ $categories->count() }} Kategori</span></div>
                        </div>

                        <!-- Kategori Filtreleri (Vyzor NFT Tag Stili) -->
                        <div class="d-flex align-items-center gap-2 mb-4 flex-wrap pos-category pos-categories-list" id="category-tabs">
                            <div class="nft-tag nft-tag-primary active" role="button" data-filter="all">
                                <span class="nft-tag-icon"><i class="ti ti-apps fs-20"></i></span>
                                <span class="nft-tag-text">Tümü</span>
                            </div>
                            @foreach($categories as $category)
                                <div class="nft-tag nft-tag-secondary" role="button" data-filter="{{ $category->id }}">
                                    <!-- Kategori resmi yoksa ikon göster -->
                                    <span class="nft-tag-icon">
                                        @if($category->image)
                                            <img src="{{ asset($category->image) }}" alt="" style="width:24px; height:24px; object-fit:cover; border-radius:50%;">
                                        @else
                                            <i class="ti ti-tag"></i>
                                        @endif
                                    </span>
                                    <span class="nft-tag-text">{{ $category->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ürün Listesi Grid -->
                    <div class="col-xl-12">
                        <div class="row list-wrapper" id="products-container">
                            @foreach($categories as $category)
                                @foreach($category->products as $product)
                                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 product-item animate__animated animate__fadeIn"
                                         data-category="{{ $category->id }}"
                                         data-name="{{ strtolower($product->name) }}">

                                        <div class="card custom-card h-100">
                                            <!-- Ürün Görseli -->
                                            <div class="position-relative">
                                                <img src="{{ asset($product->image ?? 'assets/images/ecommerce/png/1.png') }}" class="card-img-top bg-light p-4" alt="{{ $product->name }}" style="height: 160px; object-fit: contain;">
                                            </div>

                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <a href="javascript:void(0);" class="fw-medium fs-16 text-dark">{{ $product->name }}</a>
                                                </div>
                                                <div class="d-flex align-items-end justify-content-between flex-wrap gap-2 mt-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <h5 class="fw-semibold mb-0 text-primary">{{ number_format($product->price, 2) }} ₺</h5>
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-primary btn-sm btn-wave d-inline-flex align-items-center justify-content-center"
                                                                onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ asset($product->image ?? 'assets/images/ecommerce/png/1.png') }}')">
                                                            Ekle <i class="ti ti-plus ms-1"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- SAĞ TARA: Sepet ve Ödeme -->
            <div class="col-xxl-3">
                <div class="card custom-card h-100">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Sipariş Detayı</div>
                        <span class="badge bg-primary-transparent" id="cart-count-badge">0 Ürün</span>
                    </div>

                    <div class="card-body p-3">
                        <!-- Müşteri ve Masa Seçimi (Input Grubu) -->
                        <div class="mb-3">
                            <label class="fs-13 text-muted mb-1">Masa / Sipariş Türü</label>
                            <select class="form-select form-select-sm mb-2" id="dining_table_id">
                                <option value="" data-status="empty" selected>📦 Paket Servis</option>
                                @foreach($tables as $table)
                                    <!--
           DÜZELTME BURADA:
           data-name özelliğini ekledik. Bu sayede JS tarafında metin parçalamakla uğraşmayacağız.
        -->
                                    <option value="{{ $table->id }}"
                                            data-status="{{ $table->status }}"
                                            data-name="{{ $table->name }} ({{ $table->capacity }} Kişilik)">
                                        {{ $table->name }} ({{ $table->capacity }} Kişilik)
                                        @if($table->status == 'occupied')
                                            🔴 (DOLU - Ödeme Al)
                                        @else
                                            🟢 (BOŞ)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" id="current_order_id" value="">
                            <input type="text" class="form-control form-control-sm" id="customer_name" placeholder="Müşteri Adı (Opsiyonel)">
                        </div>

                        <!-- Sepet Listesi (Scrollable Alan) -->
                        <div class="overflow-auto" style="max-height: 400px; min-height: 200px;" id="cart-scroll-area">
                            <ul class="list-unstyled pos-system-orders-list" id="cart-items-container">
                                <!-- JS ile Doldurulacak -->
                                <li class="text-center p-5 text-muted empty-cart-msg">
                                    <i class="ti ti-shopping-cart fs-40 mb-2 opacity-50"></i>
                                    <p class="fs-13">Sepetiniz boş.</p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Alt Bilgiler ve Toplam -->
                    <div class="card-footer p-0 border-top">
                        <div class="p-3 border-bottom border-bottom-dashed bg-light">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 fw-semibold mb-1">
                                <span class="text-muted fs-13">Ara Toplam</span>
                                <span class="text-dark" id="sub-total">0.00 ₺</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 text-muted mb-0">
                                <span class="fs-13">KDV (%10)</span>
                                <span id="tax-amount">0.00 ₺</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 text-primary px-3 py-3 fw-bold fs-20 border-bottom border-bottom-dashed bg-white">
                            <span>TOPLAM:</span>
                            <span id="grand-total">0.00 ₺</span>
                        </div>

                        <div class="p-3">
                            <h6 class="fw-semibold mb-3 fs-13 text-muted text-uppercase">Ödeme Yöntemi:</h6>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay_cash" value="cash" checked>
                                    <label class="btn btn-outline-success w-100 p-2" for="pay_cash">
                                        <i class="ti ti-cash fs-18 d-block mb-1"></i>
                                        <span class="fs-12">Nakit</span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay_card" value="credit_card">
                                    <label class="btn btn-outline-info w-100 p-2" for="pay_card">
                                        <i class="ti ti-credit-card fs-18 d-block mb-1"></i>
                                        <span class="fs-12">Kart</span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <button class="btn btn-outline-danger w-100 h-100 p-0 d-flex flex-column align-items-center justify-content-center" onclick="clearCart()">
                                        <i class="ti ti-trash fs-18 mb-1"></i>
                                        <span class="fs-12">İptal</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-grid p-3 border-0">
                            <div class="row g-2">
                                <div class="col-12">
                                    <button class="btn btn-warning w-100 fw-bold mb-2" onclick="processPayment('pending')">
                                        <i class="ti ti-chef-hat me-1"></i> MUTFAĞA GÖNDER
                                    </button>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary btn-lg w-100 fw-bold" onclick="submitOrder()">
                                        SİPARİŞİ TAMAMLA
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Select2 Başlatma
            $('#dining_table_id').select2({ placeholder: 'Masa Seçiniz', width: '100%' });

            // --- DEĞİŞKENLER ---
            let cart = [];
            const taxRate = 0.10;

            // --- KATEGORİ FİLTRELEME ---
            $('#category-tabs .nft-tag').click(function() {
                $('#category-tabs .nft-tag').removeClass('active nft-tag-primary').addClass('nft-tag-secondary');
                $(this).removeClass('nft-tag-secondary').addClass('active nft-tag-primary');

                const filter = $(this).data('filter');

                $('.product-item').each(function() {
                    if (filter === 'all' || $(this).data('category') == filter) {
                        $(this).removeClass('d-none').addClass('animate__fadeIn');
                    } else {
                        $(this).addClass('d-none').removeClass('animate__fadeIn');
                    }
                });
            });

            // --- ARAMA ---
            $('#searchProduct').on('keyup', function() {
                const val = $(this).val().toLowerCase();
                $('.product-item').each(function() {
                    const name = $(this).data('name');
                    if (name.indexOf(val) > -1) {
                        $(this).removeClass('d-none');
                    } else {
                        $(this).addClass('d-none');
                    }
                });
            });

            // --- SEPET İŞLEMLERİ ---
            window.addToCart = function(id, name, price, image) {
                const existingItem = cart.find(item => item.id === id);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    cart.push({ id, name, price: parseFloat(price), image, quantity: 1 });
                }
                renderCart();
            };

            window.updateQuantity = function(id, change) {
                const item = cart.find(item => item.id === id);
                if (item) {
                    item.quantity += change;
                    if (item.quantity <= 0) {
                        cart = cart.filter(i => i.id !== id);
                    }
                    renderCart();
                }
            };

            window.removeFromCart = function(id) {
                cart = cart.filter(i => i.id !== id);
                renderCart();
            };

            window.clearCart = function() {
                if(cart.length === 0) return;
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: "Sepet tamamen temizlenecek!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Evet, Sil',
                    cancelButtonText: 'Vazgeç'
                }).then((result) => {
                    if (result.isConfirmed) {
                        cart = [];
                        renderCart();
                    }
                });
            };

            function renderCart() {
                const container = $('#cart-items-container');
                container.empty();

                if (cart.length === 0) {
                    container.html(`
                        <li class="text-center p-5 text-muted empty-cart-msg">
                            <i class="ti ti-shopping-cart fs-40 mb-2 opacity-50"></i>
                            <p class="fs-13">Sepetiniz boş.</p>
                        </li>
                    `);
                    resetTotals();
                    return;
                }

                let subTotal = 0;
                let totalCount = 0;

                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    subTotal += itemTotal;
                    totalCount += item.quantity;

                    const html = `
                        <li class="mb-3 border-bottom border-bottom-dashed pb-3">
                            <div class="d-flex align-items-start gap-2 flex-wrap">
                                <div class="lh-1">
                                    <span class="avatar avatar-lg bg-light border">
                                        <img src="${item.image}" alt="img">
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-end mb-1 flex-wrap justify-content-between">
                                        <div class="fw-semibold text-truncate flex-fill" style="max-width: 140px;" title="${item.name}">${item.name}</div>
                                        <div class="d-flex align-items-center order-qnt gap-2">
                                            <a href="javascript:void(0);" onclick="updateQuantity(${item.id}, -1)" class="badge bg-white p-1 border text-muted fs-13"><i class="ti ti-minus"></i></a>
                                            <input type="text" class="form-control form-control-sm border-0 text-center p-0" style="width: 30px;" value="${item.quantity}" readonly>
                                            <a href="javascript:void(0);" onclick="updateQuantity(${item.id}, 1)" class="badge bg-white p-1 border text-muted fs-13"><i class="ti ti-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div class="flex-grow-1 mb-0 text-primary fw-bold">${itemTotal.toFixed(2)} ₺</div>
                                        <div class="lh-1">
                                            <a href="javascript:void(0);" onclick="removeFromCart(${item.id})" class="text-danger fs-12 text-decoration-underline">Sil</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    `;
                    container.append(html);
                });

                const tax = subTotal * taxRate;
                const grandTotal = subTotal + tax;

                $('#sub-total').text(subTotal.toFixed(2) + ' ₺');
                $('#tax-amount').text(tax.toFixed(2) + ' ₺');
                $('#grand-total').text(grandTotal.toFixed(2) + ' ₺');
                $('#cart-count-badge').text(totalCount + ' Ürün');

                const scrollArea = document.getElementById('cart-scroll-area');
                scrollArea.scrollTop = scrollArea.scrollHeight;
            }

            function resetTotals() {
                $('#sub-total').text('0.00 ₺');
                $('#tax-amount').text('0.00 ₺');
                $('#grand-total').text('0.00 ₺');
                $('#cart-count-badge').text('0 Ürün');
            }

            window.submitOrder = function() {
                const method = $('input[name="payment_method"]:checked').val();
                processPayment(method);
            }

            // --- ÖDEME VE DURUM GÜNCELLEME ---
            window.processPayment = function(method) {
                if (cart.length === 0) {
                    Swal.fire({ icon: 'warning', title: 'Sepet Boş', text: 'Lütfen ürün ekleyin.', timer: 1500, showConfirmButton: false });
                    return;
                }

                const tableId = $('#dining_table_id').val();
                const customerName = $('#customer_name').val();
                let methodTitle = method === 'pending' ? 'Mutfağa Gönderiliyor' : (method === 'cash' ? 'Nakit Ödeme Alınıyor' : 'Kart ile Ödeme Alınıyor');

                Swal.fire({
                    title: methodTitle,
                    html: `<div class="fs-18">Toplam Tutar: <b>${$('#grand-total').text()}</b></div>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Onayla',
                    cancelButtonText: 'İptal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return $.ajax({
                            url: "{{ route('pos.store') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                cart: cart,
                                dining_table_id: tableId,
                                customer_name: customerName,
                                payment_method: method,
                                order_id: $('#current_order_id').val()
                            }
                        }).fail(error => {
                            Swal.showValidationMessage(`Hata: ${error.responseJSON?.message || 'Bir sorun oluştu'}`);
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı!',
                            text: method === 'pending' ? 'Sipariş mutfağa iletildi.' : 'İşlem tamamlandı.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // ----------------------------------------------------
                        // [GÜNCELLENMİŞ] Masa Durumunu Anlık Değiştirme
                        // ----------------------------------------------------
                        if (tableId) {
                            // İlgili option elementini seç
                            let $selectElement = $('#dining_table_id');
                            let $option = $selectElement.find('option[value="' + tableId + '"]');

                            // Blade tarafında eklediğimiz data-name'i al (Regex hatalarını önler)
                            // Eğer data-name yoksa (eski kod varsa) manuel temizleme dener
                            let cleanName = $option.data('name');
                            if(!cleanName) {
                                cleanName = $option.text().split('🔴')[0].split('🟢')[0].trim();
                            }

                            if (method === 'pending') {
                                // Durum: DOLU
                                $option.attr('data-status', 'occupied');
                                $option.text(cleanName + ' 🔴 (DOLU - Ödeme Al)');
                            } else {
                                // Durum: BOŞ (Ödeme alındı)
                                $option.attr('data-status', 'empty');
                                $option.text(cleanName + ' 🟢 (BOŞ)');
                            }

                            // ÖNEMLİ: Select2'ye değişikliği haber ver (Dropdown listesini yenilemesi için)
                            // Önce seçimi kaldırıyoruz, sonra change eventini tetikliyoruz.
                            $selectElement.val(null).trigger('change.select2');

                            // Bu komut, Select2'nin render edilmiş HTML'ini günceller
                            $selectElement.trigger('change');
                        }
                        // ----------------------------------------------------

                        // Formu ve Sepeti Temizle
                        cart = [];
                        renderCart();
                        $('#customer_name').val('');
                        $('#current_order_id').val('');

                        // Son olarak seçimi null yapıp tetiklemek
                        $('#dining_table_id').val(null).trigger('change');
                    }
                });
            };

            // --- MASA SEÇİMİ ---
            $('#dining_table_id').on('change', function() {
                var tableId = $(this).val();

                // Seçilen option'un güncel data-status değerini al
                var $option = $(this).find('option[value="' + tableId + '"]');
                var status = $option.attr('data-status');

                // Paket Servis (Boş value) veya Boş Masa seçildiyse
                if (!tableId || status === 'empty') {
                    cart = [];
                    $('#current_order_id').val('');
                    $('#customer_name').val('');
                    renderCart();
                    return;
                }

                // Masa DOLU ise siparişi getir
                if (status === 'occupied') {
                    Swal.fire({
                        title: 'Masa Dolu!',
                        text: "Sipariş detayları getiriliyor...",
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    var url = "{{ route('pos.get-order', ':id') }}";
                    url = url.replace(':id', tableId);

                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(response) {
                            Swal.close();

                            if (response.success) {
                                cart = response.cart;
                                $('#current_order_id').val(response.order_id);
                                $('#customer_name').val(response.customer_name || '');
                                renderCart();

                                // TOAST Bildirimi
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                });

                                Toast.fire({
                                    icon: 'success',
                                    title: 'Sipariş Yüklendi',
                                    text: 'Masa siparişi ekrana getirildi.'
                                });

                            } else {
                                Swal.fire('Hata', response.message || 'Sipariş detayları alınamadı.', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata',
                                text: 'Sipariş getirilemedi.'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
