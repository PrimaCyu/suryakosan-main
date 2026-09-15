@extends('backend.dashboard.main')

@section('content')
  <!--begin::App Content Header-->
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-sm-6">
          <h3 class="mb-0 fw-bold text-dark">
            <i class="bi bi-person-gear text-primary me-2"></i>Pengaturan Akun & Keamanan
          </h3>
          <p class="text-muted fs-7 mb-0">Kelola informasi profil admin dan perbarui kata sandi akun Anda secara aman.</p>
        </div>
        <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
          <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>
  <!--end::App Content Header-->

  <!--begin::App Content-->
  <div class="app-content">
    <div class="container-fluid">

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
          <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
          <div class="d-flex align-items-center mb-1">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
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
          <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom py-3">
              <h5 class="card-title fw-bold mb-0 text-dark">
                <i class="bi bi-person-badge text-primary me-2"></i>Informasi Profil
              </h5>
            </div>
            <div class="card-body p-4">
              <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                  <label class="form-label fw-semibold text-secondary">Nama Lengkap Admin <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control py-2" value="{{ old('name', $user->name) }}" required placeholder="Nama Administrator">
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold text-secondary">Alamat Email <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control py-2" value="{{ old('email', $user->email) }}" required placeholder="admin@sinarcitralestari.com">
                  <div class="form-text text-muted">Email ini digunakan untuk masuk (login) ke dashboard admin.</div>
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan Profil
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- FORM 2: GANTI PASSWORD -->
        <div class="col-lg-6">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom py-3">
              <h5 class="card-title fw-bold mb-0 text-dark">
                <i class="bi bi-shield-lock text-danger me-2"></i>Ganti Password
              </h5>
            </div>
            <div class="card-body p-4">
              <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                  <label class="form-label fw-semibold text-secondary">Password Saat Ini <span class="text-danger">*</span></label>
                  <input type="password" name="current_password" class="form-control py-2" required placeholder="Masukkan password lama">
                </div>

                <div class="mb-3">
                  <label class="form-label fw-semibold text-secondary">Password Baru <span class="text-danger">*</span></label>
                  <input type="password" name="password" class="form-control py-2" required placeholder="Minimal 8 karakter">
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold text-secondary">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                  <input type="password" name="password_confirmation" class="form-control py-2" required placeholder="Ulangi password baru">
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-danger px-4 py-2 fw-semibold">
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
