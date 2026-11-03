@extends('backend.dashboard.main')

@section('content')
<div class="app-content-header">
  <div class="container-fluid">
    <div class="page-header-box">
      <div>
        <h3 class="mb-0">Kelola Admin & Cabang</h3>
        <p class="text-muted">Atur akun pengelola dan tentukan cabang kos yang menjadi tanggung jawab masing-masing admin.</p>
      </div>
      <div class="page-header-actions">
        <button type="button" class="btn btn-primary px-3" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
          <i class="bi bi-person-plus"></i>
          <span>Tambah Admin</span>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="app-content">
  <div class="container-fluid">

    @include('backend.dashboard.kosan.kamar.partials.alerts')

    <!-- Card Table -->
    <div class="card mb-4">
      <div class="card-header d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2.5">
        <div>
          <h5 class="card-title mb-0">Daftar Akun Pengelola</h5>
          <span class="fs-8 text-muted">Menampilkan seluruh akun administrator sistem</span>
        </div>

        <!-- Form Pencarian -->
        <div class="search-box-responsive">
          <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-1.5">
            <div class="search-filter-box flex-grow-1">
              <i class="bi bi-search"></i>
              <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama atau email...">
            </div>
            <button type="submit" class="btn btn-sm btn-light border px-2.5">Cari</button>
            @if(request('search'))
              <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary px-2" title="Reset Pencarian">
                <i class="bi bi-x-lg"></i>
              </a>
            @endif
          </form>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table align-middle mb-0 table-min-md">
            <thead>
              <tr>
                <th class="ps-4" style="width: 56px;">No</th>
                <th>Nama & Email</th>
                <th>Peran (Role)</th>
                <th>Tanggung Jawab Cabang Kos</th>
                <th>Terdaftar</th>
                <th class="text-end pe-4" style="width: 130px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $index => $u)
                <tr>
                  <td class="ps-4 text-muted tabular-nums">{{ $users->firstItem() + $index }}</td>
                  <td>
                    <div class="d-flex align-items-center gap-2.5">
                      <div class="user-avatar-badge">
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
                      <span class="badge badge-subtle-primary">
                        <i class="bi bi-shield-check me-1"></i>Super Admin
                      </span>
                    @else
                      <span class="badge badge-subtle-secondary">
                        <i class="bi bi-person-badge me-1"></i>Admin Cabang
                      </span>
                    @endif
                  </td>
                  <td>
                    @if($u->isSuperAdmin())
                      <span class="badge badge-subtle-secondary">
                        <i class="bi bi-globe2 me-1"></i>Seluruh Cabang (Akses Penuh)
                      </span>
                    @else
                      @if($u->kosans->count() > 0)
                        <div class="d-flex flex-wrap gap-1">
                          @foreach($u->kosans as $kos)
                            <span class="badge bg-light text-dark border px-2 py-1 fs-8 fw-medium">
                              <i class="bi bi-buildings text-muted me-1"></i>{{ $kos->title }}
                            </span>
                          @endforeach
                        </div>
                      @else
                        <span class="badge badge-subtle-warning">
                          <i class="bi bi-exclamation-circle me-1"></i>Belum Ditugaskan
                        </span>
                      @endif
                    @endif
                  </td>
                  <td class="text-muted fs-8 tabular-nums">
                    {{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '-' }}
                  </td>
                  <td class="text-end pe-4">
                    <div class="action-btn-group">
                      <button type="button" class="btn btn-xs btn-light border px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalEditAdmin{{ $u->id }}" title="Edit Admin">
                        <i class="bi bi-pencil-square text-primary"></i>
                        <span>Edit</span>
                      </button>
                      @if($u->id !== auth()->id())
                        <button type="button" class="btn btn-xs btn-outline-danger px-2 py-1" data-bs-toggle="modal" data-bs-target="#modalDeleteAdmin{{ $u->id }}" title="Hapus Admin">
                          <i class="bi bi-trash3"></i>
                        </button>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-5">
                    <div class="empty-state-box">
                      <div class="empty-state-icon">
                        <i class="bi bi-people"></i>
                      </div>
                      <h6>Belum Ada Data Admin</h6>
                      <p>Tidak ditemukan akun pengelola yang sesuai dengan kriteria pencarian.</p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($users->hasPages())
        <div class="card-footer d-flex justify-content-end">
          {{ $users->links() }}
        </div>
      @endif
    </div>

  </div>
</div>

<!-- MODALS EDIT & DELETE ADMIN -->
@foreach($users as $u)
  <!-- Modal Edit Admin -->
  <div class="modal fade" id="modalEditAdmin{{ $u->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Data Admin — {{ $u->name }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.users.update', $u->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $u->name) }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $u->email) }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                <div class="form-text">Minimal 8 karakter apabila ingin mengganti password.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Peran (Role) <span class="text-danger">*</span></label>
                <select name="role" class="form-select select-role-edit" data-id="{{ $u->id }}" required>
                  <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin Cabang (Kelola Kos Tertentu)</option>
                  <option value="super_admin" {{ $u->role === 'super_admin' ? 'selected' : '' }}>Super Admin (Akses Penuh Seluruh Cabang)</option>
                </select>
              </div>

              <div class="col-12 kosan-assignment-edit-{{ $u->id }} {{ $u->isSuperAdmin() ? 'd-none' : '' }}">
                <label class="form-label mb-1.5">Penugasan Properti Kos</label>
                <div class="p-3 bg-light rounded border">
                  @php
                    $assignedKosanIds = $u->kosans->pluck('id')->toArray();
                  @endphp
                  @forelse($allKosans as $kos)
                    <div class="form-check mb-2 last:mb-0">
                      <input class="form-check-input" type="checkbox" name="kosan_ids[]" value="{{ $kos->id }}" id="edit_kos_{{ $u->id }}_{{ $kos->id }}" {{ in_array($kos->id, $assignedKosanIds) ? 'checked' : '' }}>
                      <label class="form-check-label fs-8 text-dark cursor-pointer" for="edit_kos_{{ $u->id }}_{{ $kos->id }}">
                        <span class="fw-semibold">{{ $kos->title }}</span>
                        <span class="text-muted ms-1">({{ $kos->wilayah ?? 'Bali' }})</span>
                      </label>
                    </div>
                  @empty
                    <p class="text-muted fs-8 mb-0">Belum ada properti kos yang terdaftar di sistem.</p>
                  @endforelse
                </div>
                <div class="form-text">Admin cabang hanya dapat melihat dan mengelola properti kos yang dicentang.</div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi Hapus Admin -->
  @if($u->id !== auth()->id())
    <div class="modal fade" id="modalDeleteAdmin{{ $u->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-body text-center p-4">
            <div class="empty-state-icon bg-danger-subtle text-danger mx-auto mb-3">
              <i class="bi bi-trash3"></i>
            </div>
            <h6 class="fw-bold text-dark mb-1">Hapus Akun Admin?</h6>
            <p class="text-muted fs-8 mb-3">
              Akun <strong>{{ $u->name }}</strong> beserta penugasan cabangnya akan dihapus secara permanen.
            </p>
            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="d-flex gap-2 justify-content-center">
              @csrf
              @method('DELETE')
              <button type="button" class="btn btn-sm btn-light border flex-grow-1" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-sm btn-danger flex-grow-1">Ya, Hapus</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  @endif
@endforeach

<!-- MODAL TAMBAH ADMIN BARU -->
<div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Administrator Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" placeholder="budi@sinarcitralestari.com" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Password <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Peran (Role) <span class="text-danger">*</span></label>
              <select name="role" id="tambah-role" class="form-select" required>
                <option value="admin" selected>Admin Cabang (Kelola Kos Tertentu)</option>
                <option value="super_admin">Super Admin (Akses Penuh Seluruh Cabang)</option>
              </select>
            </div>

            <!-- CHECKBOX KOSAN ASSIGNMENT -->
            <div class="col-12" id="tambah-kosan-wrapper">
              <label class="form-label mb-1.5">Penugasan Properti Kos</label>
              <div class="p-3 bg-light rounded border">
                @forelse($allKosans as $kos)
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="kosan_ids[]" value="{{ $kos->id }}" id="tambah_kos_{{ $kos->id }}">
                    <label class="form-check-label fs-8 text-dark cursor-pointer" for="tambah_kos_{{ $kos->id }}">
                      <span class="fw-semibold">{{ $kos->title }}</span>
                      <span class="text-muted ms-1">({{ $kos->wilayah ?? 'Bali' }})</span>
                    </label>
                  </div>
                @empty
                  <p class="text-muted fs-8 mb-0">Belum ada properti kos yang dibuat.</p>
                @endforelse
              </div>
              <div class="form-text">Centang cabang kos yang menjadi tanggung jawab admin ini.</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Admin</button>
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
        kosanWrapper.classList.toggle('d-none', this.value === 'super_admin');
      });
    }

    document.querySelectorAll('.select-role-edit').forEach(sel => {
      sel.addEventListener('change', function() {
        const uid = this.getAttribute('data-id');
        const wrap = document.querySelector('.kosan-assignment-edit-' + uid);
        if (wrap) {
          wrap.classList.toggle('d-none', this.value === 'super_admin');
        }
      });
    });
  });
</script>
@endsection
