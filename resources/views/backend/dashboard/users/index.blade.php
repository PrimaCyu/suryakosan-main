@extends('backend.dashboard.main')

@section('content')
  <!-- Page Header -->
  <div class="page-header-box mb-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
      <div>
        <h2 class="page-title mb-1">Manajemen Admin & Penugasan Kos</h2>
        <p class="page-subtitle mb-0">Kelola akun administrator dan tentukan cabang kos yang menjadi tanggung jawab masing-masing admin.</p>
      </div>
      <div>
        <button type="button" class="btn btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
          <i class="bi bi-person-plus-fill me-1"></i> Tambah Admin Baru
        </button>
      </div>
    </div>
  </div>

<!-- Main Content -->
<div class="app-content">
  <div class="container-fluid">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if(session('failed'))
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
        <i class="bi bi-x-circle-fill me-2"></i>{{ session('failed') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan:</div>
        <ul class="mb-0 ps-3">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Card Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
        <h3 class="section-title mb-0">Daftar Akun Pengelola</h3>

        <!-- Form Pencarian -->
        <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2" style="max-width: 320px;">
          <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama / email...">
          <button type="submit" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-search"></i>
          </button>
          @if(request('search'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
          @endif
        </form>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4" style="width: 60px;">No</th>
              <th>Nama & Email</th>
              <th>Peran (Role)</th>
              <th>Tanggung Jawab Cabang Kos</th>
              <th>Terdaftar</th>
              <th class="text-end pe-4" style="width: 140px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $index => $u)
              <tr>
                <td class="ps-4 text-muted">{{ $users->firstItem() + $index }}</td>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <div class="user-avatar-badge" style="width: 38px; height: 38px; font-size: 0.95rem;">
                      {{ strtoupper(substr($u->name, 0, 1)) }}
                    </div>
                    <div>
                      <div class="table-cell-title">{{ $u->name }}</div>
                      <div class="table-cell-sub">{{ $u->email }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  @if($u->isSuperAdmin())
                    <span class="badge bg-dark-subtle text-dark border px-2.5 py-1 fw-semibold">
                      <i class="bi bi-shield-check me-1"></i> Super Admin
                    </span>
                  @else
                    <span class="badge bg-light text-secondary border px-2.5 py-1 fw-semibold">
                      <i class="bi bi-person-badge me-1"></i> Admin Cabang
                    </span>
                  @endif
                </td>
                <td>
                  @if($u->isSuperAdmin())
                    <span class="badge bg-light text-secondary border px-2 py-1">
                      <i class="bi bi-globe me-1"></i> Seluruh Cabang Kos (Full Access)
                    </span>
                  @else
                    @if($u->kosans->count() > 0)
                      <div class="d-flex flex-wrap gap-1">
                        @foreach($u->kosans as $kos)
                          <span class="badge bg-light text-dark border px-2 py-1 fs-8">
                            <i class="bi bi-house-door me-1"></i>{{ $kos->title }} ({{ $kos->wilayah ?? '-' }})
                          </span>
                        @endforeach
                      </div>
                    @else
                      <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fs-8">
                        <i class="bi bi-exclamation-circle me-1"></i> Belum Ada Tugas Kos
                      </span>
                    @endif
                  @endif
                </td>
                <td class="text-muted fs-7">
                  {{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '-' }}
                </td>
                <td class="text-end pe-4">
                  <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEditAdmin{{ $u->id }}" title="Edit Admin">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    @if($u->id !== auth()->id())
                      <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin ini? Seluruh riwayat penugasan akan dihapus.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" title="Hapus Admin">
                          <i class="bi bi-trash-fill"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>

              <!-- MODAL EDIT ADMIN -->
              <div class="modal fade" id="modalEditAdmin{{ $u->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                  <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-bottom py-3">
                      <h5 class="modal-title fs-5" style="font-family: var(--dash-font-serif); font-weight: 600; color: var(--dash-navy);">
                        Edit Data Admin: {{ $u->name }}
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.users.update', $u->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-body p-4">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $u->name) }}" required>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $u->email) }}" required>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah">
                            <div class="form-text fs-8 text-muted">Minimal 8 karakter jika diisi.</div>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label fw-semibold">Peran (Role) <span class="text-danger">*</span></label>
                            <select name="role" class="form-select select-role-edit" data-id="{{ $u->id }}" required>
                              <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin Cabang (Kelola Kos Tertentu)</option>
                              <option value="super_admin" {{ $u->role === 'super_admin' ? 'selected' : '' }}>Super Admin (Akses Penuh Seluruh Cabang)</option>
                            </select>
                          </div>

                          <div class="col-12 kosan-assignment-edit-{{ $u->id }} {{ $u->isSuperAdmin() ? 'd-none' : '' }}">
                            <label class="form-label fw-semibold mb-2">
                              <i class="bi bi-check2-square text-primary me-1"></i> Penugasan Properti Kos:
                            </label>
                            <div class="p-3 bg-light rounded-3 border">
                              @php
                                $assignedKosanIds = $u->kosans->pluck('id')->toArray();
                              @endphp
                              @forelse($allKosans as $kos)
                                <div class="form-check mb-2">
                                  <input class="form-check-input" type="checkbox" name="kosan_ids[]" value="{{ $kos->id }}" id="edit_kos_{{ $u->id }}_{{ $kos->id }}" {{ in_array($kos->id, $assignedKosanIds) ? 'checked' : '' }}>
                                  <label class="form-check-label fw-medium text-dark" for="edit_kos_{{ $u->id }}_{{ $kos->id }}">
                                    <strong>{{ $kos->title }}</strong> — <span class="text-muted fs-7">{{ $kos->wilayah ?? 'Bali' }}</span>
                                  </label>
                                </div>
                              @empty
                                <p class="text-muted fs-7 mb-0">Belum ada properti kos yang terdaftar di sistem.</p>
                              @endforelse
                            </div>
                            <div class="form-text fs-8 text-muted mt-1">Admin hanya dapat melihat dan mengelola kosan yang dicentang.</div>
                          </div>

                        </div>
                      </div>
                      <div class="modal-footer border-top py-2.5">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-semibold">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

            @empty
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                  <i class="bi bi-people fs-1 d-block mb-2 text-secondary opacity-50"></i>
                  Belum ada data admin yang terdaftar.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($users->hasPages())
        <div class="card-footer bg-white border-top py-3">
          {{ $users->links() }}
        </div>
      @endif
    </div>

  </div>
</div>

<!-- MODAL TAMBAH ADMIN BARU -->
<div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom py-3">
        <h5 class="modal-title fs-5" style="font-family: var(--dash-font-serif); font-weight: 600; color: var(--dash-navy);">
          Tambah Administrator Baru
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" placeholder="budi@sinarcitralestari.com" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Peran (Role) <span class="text-danger">*</span></label>
              <select name="role" id="tambah-role" class="form-select" required>
                <option value="admin" selected>Admin Cabang (Kelola Kos Tertentu)</option>
                <option value="super_admin">Super Admin (Akses Penuh Seluruh Cabang)</option>
              </select>
            </div>

            <!-- CHECKBOX KOSAN ASSIGNMENT -->
            <div class="col-12" id="tambah-kosan-wrapper">
              <label class="form-label fw-semibold mb-2">
                <i class="bi bi-check2-square text-primary me-1"></i> Penugasan Properti Kos:
              </label>
              <div class="p-3 bg-light rounded-3 border">
                @forelse($allKosans as $kos)
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="kosan_ids[]" value="{{ $kos->id }}" id="tambah_kos_{{ $kos->id }}">
                    <label class="form-check-label fw-medium text-dark" for="tambah_kos_{{ $kos->id }}">
                      <strong>{{ $kos->title }}</strong> — <span class="text-muted fs-7">{{ $kos->wilayah ?? 'Bali' }}</span>
                    </label>
                  </div>
                @empty
                  <p class="text-muted fs-7 mb-0">Belum ada properti kos yang dibuat. Buat kosan terlebih dahulu.</p>
                @endforelse
              </div>
              <div class="form-text fs-8 text-muted mt-1">Centang kosan yang menjadi tanggung jawab admin ini.</div>
            </div>

          </div>
        </div>
        <div class="modal-footer border-top py-2.5">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary fw-semibold">
            <i class="bi bi-save me-1"></i> Simpan Admin
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('tambah-role');
    const kosanWrapper = document.getElementById('tambah-kosan-wrapper');

    if (roleSelect && kosanWrapper) {
      roleSelect.addEventListener('change', function() {
        if (this.value === 'super_admin') {
          kosanWrapper.classList.add('d-none');
        } else {
          kosanWrapper.classList.remove('d-none');
        }
      });
    }

    document.querySelectorAll('.select-role-edit').forEach(sel => {
      sel.addEventListener('change', function() {
        const uid = this.getAttribute('data-id');
        const wrap = document.querySelector('.kosan-assignment-edit-' + uid);
        if (wrap) {
          if (this.value === 'super_admin') {
            wrap.classList.add('d-none');
          } else {
            wrap.classList.remove('d-none');
          }
        }
      });
    });
  });
</script>
@endsection
