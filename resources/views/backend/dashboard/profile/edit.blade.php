@extends('backend.dashboard.main')

@section('content')
<div class="app-content-header">
  <div class="container-fluid">
    <div class="page-header-box">
      <div>
        <h3 class="mb-0">Pengaturan Akun & Keamanan</h3>
        <p class="text-muted">Kelola informasi profil administrator dan perbarui kata sandi akun Anda.</p>
      </div>
      <div class="page-header-actions">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light border px-3">
          <i class="bi bi-arrow-left"></i>
          <span>Kembali ke Dashboard</span>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="app-content">
  <div class="container-fluid">

    @include('backend.dashboard.kosan.kamar.partials.alerts')

    <div class="row g-3">
      <!-- FORM 1: PROFIL ADMIN -->
      <div class="col-12 col-lg-6">
        <div class="card h-100">
          <div class="card-header d-flex align-items-center justify-content-between">
            <div>
              <h5 class="card-title mb-0">Informasi Profil</h5>
              <span class="fs-8 text-muted">Identitas dan alamat email login</span>
            </div>
            <span class="badge {{ $user->isSuperAdmin() ? 'badge-subtle-primary' : 'badge-subtle-secondary' }}">
              {{ $user->isSuperAdmin() ? 'Super Admin' : 'Admin Cabang' }}
            </span>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.profile.update') }}" method="POST">
              @csrf
              @method('PUT')

              <div class="mb-3">
                <label class="form-label">Nama Lengkap Admin <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required placeholder="Nama Administrator">
              </div>

              <div class="mb-4">
                <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required placeholder="admin@sinarcitralestari.com">
                <div class="form-text">Email ini digunakan sebagai kredensial masuk ke panel administrasi.</div>
              </div>

              <div class="d-flex justify-content-end border-top pt-3">
                <button type="submit" class="btn btn-primary px-3">
                  <i class="bi bi-check2"></i>
                  <span>Simpan Profil</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- FORM 2: GANTI PASSWORD -->
      <div class="col-12 col-lg-6">
        <div class="card h-100">
          <div class="card-header">
            <h5 class="card-title mb-0">Ubah Kata Sandi</h5>
            <span class="fs-8 text-muted">Gunakan kombinasi minimal 8 karakter untuk keamanan akun</span>
          </div>
          <div class="card-body">
            <form action="{{ route('admin.profile.password') }}" method="POST">
              @csrf
              @method('PUT')

              <div class="mb-3">
                <label class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                <input type="password" name="current_password" class="form-control" required placeholder="Masukkan password lama">
              </div>

              <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6">
                  <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                  <input type="password" name="password" class="form-control" required placeholder="Minimal 8 karakter">
                </div>
                <div class="col-12 col-sm-6">
                  <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                  <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password baru">
                </div>
              </div>

              <div class="d-flex justify-content-end border-top pt-3">
                <button type="submit" class="btn btn-primary px-3">
                  <i class="bi bi-key"></i>
                  <span>Perbarui Password</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
