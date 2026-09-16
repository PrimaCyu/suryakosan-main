@extends('backend.dashboard.main')

@section('content')
  <!-- Page Header -->
  <div class="page-header-box mb-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
      <div>
        <h2 class="page-title mb-1">Pengaturan Akun & Keamanan</h2>
        <p class="page-subtitle mb-0">Kelola informasi profil admin dan perbarui kata sandi akun Anda secara aman.</p>
      </div>
      <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="app-content">
    <div class="container-fluid">

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
          <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>{{ session('success') }}</div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
          <div class="d-flex align-items-center mb-1">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Terjadi kesalahan input:</strong>
          </div>
          <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="row g-4">
        <!-- FORM 1: PROFIL ADMIN -->
        <div class="col-lg-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
              <h3 class="section-title mb-0">Informasi Profil</h3>
            </div>
            <div class="card-body p-4">
              <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                  <label class="form-label fw-semibold">Nama Lengkap Admin <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required placeholder="Nama Administrator">
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required placeholder="admin@sinarcitralestari.com">
                  <div class="form-text text-muted">Email ini digunakan untuk masuk (login) ke dashboard admin.</div>
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-primary fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan Profil
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- FORM 2: GANTI PASSWORD -->
        <div class="col-lg-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
              <h3 class="section-title mb-0">Ganti Password</h3>
            </div>
            <div class="card-body p-4">
              <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                  <label class="form-label fw-semibold">Password Saat Ini <span class="text-danger">*</span></label>
                  <input type="password" name="current_password" class="form-control" required placeholder="Masukkan password lama">
                </div>

                <div class="mb-3">
                  <label class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                  <input type="password" name="password" class="form-control" required placeholder="Minimal 8 karakter">
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                  <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password baru">
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-primary fw-semibold">
                    <i class="bi bi-key me-1"></i> Perbarui Password
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <!--end::App Content-->
@endsection
