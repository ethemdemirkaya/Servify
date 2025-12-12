@extends('layouts.dashboard')
@section('title', 'Yazıcı Ayarları')

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- Header -->
        <div class="page-header-breadcrumb mb-4">
            <div class="d-flex align-center justify-content-between flex-wrap">
                <div>
                    <h1 class="page-title fw-medium fs-18 mb-0 d-flex align-items-center">
                        <i class="ri-printer-cloud-line me-2 text-primary"></i>
                        Yazıcı Yönetimi
                    </h1>
                    <p class="fs-12 text-muted mb-0">Mutfak, Bar ve Kasa yazıcılarını buradan tanımlayın.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-wave" onclick="openModal('add')">
                        <i class="ri-add-line align-middle me-1"></i> Yeni Yazıcı Ekle
                    </button>
                </div>
            </div>
        </div>

        <!-- Yazıcı Listesi -->
        <div class="row">
            @forelse($printers as $printer)
                <div class="col-xl-4 col-md-6">
                    <div class="card custom-card border-top border-4 border-{{ $printer->type == 'network' ? 'info' : 'secondary' }}">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                <span class="avatar avatar-lg bg-{{ $printer->type == 'network' ? 'info' : 'secondary' }} bg-opacity-10 text-{{ $printer->type == 'network' ? 'info' : 'secondary' }} rounded-circle">
                                    <i class="ri-{{ $printer->type == 'network' ? 'router-line' : 'usb-line' }} fs-24"></i>
                                </span>
                                    <div>
                                        <h6 class="fw-semibold mb-0">{{ $printer->name }}</h6>
                                        <span class="fs-12 text-muted text-uppercase">{{ $printer->type }} Yazıcı</span>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-icon btn-sm btn-light rounded-pill" type="button" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="editPrinter({{ $printer }})">
                                                <i class="ri-edit-line me-2"></i> Düzenle
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('printers.destroy', $printer->id) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="ri-delete-bin-line me-2"></i> Sil
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded border mb-3">
                                @if($printer->type == 'network')
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted fs-12">IP Adresi:</span>
                                        <span class="fw-semibold fs-12 font-monospace">{{ $printer->ip_address }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted fs-12">Port:</span>
                                        <span class="fw-semibold fs-12 font-monospace">{{ $printer->port }}</span>
                                    </div>
                                @else
                                    <div class="text-center text-muted fs-12 py-2">
                                        <i class="ri-usb-line me-1"></i> Doğrudan USB Bağlantısı
                                    </div>
                                @endif
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-light btn-sm" onclick="testConnection({{ $printer->id }}, '{{ $printer->type }}')">
                                    <i class="ri-wifi-line me-1"></i> Bağlantıyı Test Et
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card custom-card">
                        <div class="card-body text-center py-5">
                        <span class="avatar avatar-xl bg-light text-muted mb-3 rounded-circle">
                            <i class="ri-printer-line fs-36"></i>
                        </span>
                            <h4>Henüz Yazıcı Eklenmemiş</h4>
                            <p class="text-muted">Adisyon ve mutfak çıktıları için bir yazıcı ekleyin.</p>
                            <button class="btn btn-primary btn-wave" onclick="openModal('add')">
                                <i class="ri-add-line me-1"></i> İlk Yazıcıyı Ekle
                            </button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

    </div>

    <!-- Ekle/Düzenle Modal -->
    <div class="modal fade" id="printerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modalTitle">Yeni Yazıcı Ekle</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="printerForm" action="{{ route('printers.store') }}" method="POST">
                    @csrf
                    <div id="methodField"></div> <!-- PUT methodu için -->

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Yazıcı Adı <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="p_name" placeholder="Örn: Mutfak Yazıcısı" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bağlantı Tipi</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_network" value="network" checked onchange="toggleFields()">
                                    <label class="form-check-label" for="type_network">Network (IP)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_usb" value="usb" onchange="toggleFields()">
                                    <label class="form-check-label" for="type_usb">USB / Seri</label>
                                </div>
                            </div>
                        </div>

                        <div id="networkFields">
                            <div class="row">
                                <div class="col-8">
                                    <div class="mb-3">
                                        <label class="form-label">IP Adresi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control font-monospace" name="ip_address" id="p_ip" placeholder="192.168.1.200">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label">Port</label>
                                        <input type="number" class="form-control font-monospace" name="port" id="p_port" value="9100">
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info-transparent fs-12">
                                <i class="ri-information-line me-1"></i> Standart adisyon yazıcıları (Epson, Bixolon vb.) genellikle <strong>9100</strong> portunu kullanır.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const modal = new bootstrap.Modal(document.getElementById('printerModal'));

        function toggleFields() {
            const isNetwork = document.getElementById('type_network').checked;
            const netFields = document.getElementById('networkFields');

            if(isNetwork) {
                netFields.style.display = 'block';
                document.getElementById('p_ip').required = true;
                document.getElementById('p_port').required = true;
            } else {
                netFields.style.display = 'none';
                document.getElementById('p_ip').required = false;
                document.getElementById('p_port').required = false;
            }
        }

        function openModal(mode) {
            document.getElementById('printerForm').reset();
            document.getElementById('methodField').innerHTML = '';

            if(mode === 'add') {
                document.getElementById('modalTitle').innerText = 'Yeni Yazıcı Ekle';
                document.getElementById('printerForm').action = "{{ route('printers.store') }}";
                document.getElementById('type_network').checked = true;
            }

            toggleFields();
            modal.show();
        }

        function editPrinter(printer) {
            document.getElementById('modalTitle').innerText = 'Yazıcıyı Düzenle';
            let url = "{{ route('printers.update', ':id') }}";
            url = url.replace(':id', printer.id);

            document.getElementById('printerForm').action = url;
            document.getElementById('methodField').innerHTML = '@method("PUT")';

            document.getElementById('p_name').value = printer.name;

            if(printer.type === 'network') {
                document.getElementById('type_network').checked = true;
                document.getElementById('p_ip').value = printer.ip_address;
                document.getElementById('p_port').value = printer.port;
            } else {
                document.getElementById('type_usb').checked = true;
            }

            toggleFields();
            modal.show();
        }

        function testConnection(id, type) {
            if(type === 'usb') {
                Swal.fire({
                    icon: 'info',
                    title: 'USB Yazıcı',
                    text: 'USB yazıcılar web arayüzünden test edilemez. İşletim sistemi üzerinden test sayfası yazdırınız.'
                });
                return;
            }

            Swal.fire({
                title: 'Bağlanıyor...',
                text: 'Yazıcıya erişim deneniyor, lütfen bekleyin.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            let url = "{{ route('printers.test', ':id') }}";
            url = url.replace(':id', id);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: data.status,
                        title: data.status === 'success' ? 'Başarılı' : 'Başarısız',
                        text: data.message
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata',
                        text: 'Sunucu ile iletişim kurulamadı.'
                    });
                });
        }

        // Server-side success message
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Başarılı',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false,
            customClass: { popup: 'colored-toast' }
        });
        @endif
    </script>
@endpush
