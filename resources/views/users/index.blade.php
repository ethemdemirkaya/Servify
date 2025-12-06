@extends('layouts.dashboard')
@section('title', 'Personel Yönetimi')

@push('styles')
    <!-- Grid.js ve SweetAlert2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/gridjs/theme/mermaid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="container-fluid page-container main-body-container">

        <!-- HEADER -->
        <div class="d-flex align-items-center justify-content-between mb-4 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-20 mb-0">Personel Yönetimi</h1>
                <p class="fs-12 text-muted mb-0">Sisteme kayıtlı kullanıcıları yönetin, düzenleyin veya yeni personel ekleyin.</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Yeni Kullanıcı Butonu -->
                <button type="button" class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="ti ti-user-plus me-1"></i> Yeni Personel Ekle
                </button>
            </div>
        </div>

        <!-- HATA/BAŞARI MESAJLARI (Backend'den dönen) -->
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
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- TABLO KARTI -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Kullanıcı Listesi</div>
                    </div>
                    <div class="card-body">
                        <div id="users-grid"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- CREATE USER MODAL -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="createUserModalLabel">Yeni Personel Ekle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Ad Soyad</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="Örn: Ahmet Yılmaz">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-Posta Adresi</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="ornek@servify.com">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Şifre</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Yetki (Rol)</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" selected disabled>Seçiniz...</option>
                                <option value="waiter">Garson</option>
                                <option value="chef">Şef (Mutfak)</option>
                                <option value="cashier">Kasiyer</option>
                                <option value="admin">Yönetici</option>
                            </select>
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

    <!-- EDIT USER MODAL -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editUserForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="editUserModalLabel">Personel Düzenle</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">

                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Ad Soyad</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">E-Posta</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_role" class="form-label">Yetki</label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="waiter">Garson</option>
                                <option value="chef">Şef</option>
                                <option value="cashier">Kasiyer</option>
                                <option value="admin">Yönetici</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">Yeni Şifre (Boş bırakırsanız değişmez)</label>
                            <input type="password" class="form-control" id="edit_password" name="password" minlength="6">
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="edit_is_active" name="is_active" value="1">
                            <label class="form-check-label" for="edit_is_active">Hesap Aktif</label>
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

    <!-- DELETE FORM (Hidden) -->
    <form id="deleteUserForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

@endsection

@push('scripts')
    <!-- Grid.js ve SweetAlert2 JS -->
    <script src="{{ asset('assets/libs/gridjs/gridjs.umd.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        // Kullanıcı verileri (PHP'den JS'e)
        const usersData = [
                @foreach($users as $user)
            {
                id: "{{ $user->id }}",
                name: "{{ $user->name }}",
                email: "{{ $user->email }}",
                role: "{{ $user->role }}",
                is_active: {{ $user->is_active ? 'true' : 'false' }},
                created_at: "{{ $user->created_at->format('d.m.Y') }}"
            },
            @endforeach
        ];

        // Grid.js Yapılandırması
        new gridjs.Grid({
            search: true,
            pagination: { limit: 10 },
            sort: true,
            language: {
                'search': { 'placeholder': 'Ara...' },
                'pagination': {
                    'previous': 'Önceki', 'next': 'Sonraki', 'showing': 'Gösterilen', 'results': () => 'Kayıt'
                }
            },
            className: {
                table: 'table table-hover text-nowrap'
            },
            columns: [
                {
                    name: 'ID',
                    width: '60px',
                    formatter: (cell) => gridjs.html(`<span class="fw-bold">#${cell}</span>`)
                },
                {
                    name: 'Personel',
                    formatter: (cell, row) => gridjs.html(`
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm bg-primary-transparent rounded-circle me-2">
                                ${cell.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <span class="d-block fw-semibold text-dark">${cell}</span>
                                <span class="fs-12 text-muted">${row.cells[2].data}</span>
                            </div>
                        </div>
                    `)
                },
                { name: 'Email', hidden: true },
                {
                    name: 'Rol',
                    formatter: (cell) => {
                        let color = 'primary';
                        if(cell === 'admin') color = 'dark';
                        if(cell === 'chef') color = 'danger';
                        if(cell === 'waiter') color = 'warning';
                        if(cell === 'cashier') color = 'info';

                        const roleNames = { 'admin': 'Yönetici', 'chef': 'Şef', 'waiter': 'Garson', 'cashier': 'Kasiyer' };

                        return gridjs.html(`<span class="badge bg-${color}-transparent">${roleNames[cell] || cell}</span>`);
                    }
                },
                {
                    name: 'Durum',
                    formatter: (cell) => cell
                        ? gridjs.html('<span class="badge bg-success-transparent"><i class="ti ti-check me-1"></i>Aktif</span>')
                        : gridjs.html('<span class="badge bg-danger-transparent"><i class="ti ti-ban me-1"></i>Pasif</span>')
                },
                {
                    name: 'Kayıt Tarihi',
                    width: '120px'
                },
                {
                    name: 'İşlem',
                    width: '120px',
                    sort: false,
                    formatter: (cell, row) => {
                        const userId = row.cells[0].data;
                        return gridjs.html(`
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-icon btn-info-light" onclick="openEditModal('${userId}')" data-bs-toggle="tooltip" title="Düzenle">
                                    <i class="ti ti-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-danger-light" onclick="confirmDelete('${userId}')" data-bs-toggle="tooltip" title="Sil">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        `);
                    }
                }
            ],
            data: usersData.map(user => [ user.id, user.name, user.email, user.role, user.is_active, user.created_at ])
        }).render(document.getElementById("users-grid"));

        // EDIT MODAL
        window.openEditModal = function(id) {
            const user = usersData.find(u => u.id == id);
            if(!user) return;

            const form = document.getElementById('editUserForm');
            form.action = `/users/${id}`;

            document.getElementById('edit_id').value = user.id;
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_role').value = user.role;
            document.getElementById('edit_is_active').checked = user.is_active;
            document.getElementById('edit_password').value = '';

            const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
            modal.show();
        }

        // SWEETALERT İLE SİLME ONAYI
        window.confirmDelete = function(id) {
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu kullanıcıyı silmek istediğinize emin misiniz? Bu işlem geri alınamaz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteUserForm');
                    form.action = `/users/${id}`;
                    form.submit();
                }
            });
        }
    </script>
@endpush
