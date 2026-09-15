<!doctype html>
<html lang="id">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sinar Citra Lestari | Admin Dashboard</title>

    <!--begin::Theme Init (prevents flash of incorrect theme on load)-->
    <script>
      (() => {
        'use strict';
        const STORAGE_KEY = 'lte-theme';
        let stored = null;
        try {
          stored = localStorage.getItem(STORAGE_KEY);
        } catch {}
        const prefersDark = globalThis.matchMedia('(prefers-color-scheme: dark)').matches;
        let resolved = 'light';
        if (stored === 'dark') {
          resolved = 'dark';
        } else if (stored === 'light') {
          resolved = 'light';
        } else {
          // 'auto' or not set: follows OS preferences
          resolved = prefersDark ? 'dark' : 'light';
        }
        document.documentElement.setAttribute('data-bs-theme', resolved);
        document.documentElement.style.colorScheme = resolved;
      })();
    </script>
    <!--end::Theme Init-->

<<<<<<< HEAD
    <link rel="icon" href="{{ asset('scl.png') }}">
=======
    <link rel="icon" href="{{ asset('logo.png') }}?v={{ @filemtime(public_path('logo.png')) ?: '4' }}">
>>>>>>> 7e0eeb6fa02a38cfbb9854e8a343e3b3311b9978
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#4f46e5" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)" />

    <!--begin::Google Fonts (Plus Jakarta Sans)-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!--end::Google Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('assets-dashboard-admin/css/adminlte.css') }}" />
    <!--end::Required Plugin(AdminLTE)-->

    <!--begin::Custom Modern Admin Design System-->
    <link rel="stylesheet" href="{{ asset('assets-dashboard-admin/css/custom-admin.css') }}" />
    <!--end::Custom Modern Admin Design System-->

    <!-- jQuery (Required early for views and Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
  </head>
  <!--end::Head-->

  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav align-items-center gap-2">
            <li class="nav-item">
              <a
                class="nav-link"
                data-lte-toggle="sidebar"
                href="#"
                role="button"
                aria-label="Toggle sidebar"
              >
                <i class="bi bi-list fs-5"></i>
              </a>
            </li>

            <!-- Breadcrumb Navigation -->
            <li class="nav-item d-none d-md-flex align-items-center">
              <ol class="header-breadcrumb">
                <li>
                  <a href="{{ route('admin.dashboard') }}">Workspace</a>
                  <i class="bi bi-chevron-right text-muted" style="font-size: 0.65rem;"></i>
                </li>
                <li class="active">
                  @if(request()->routeIs('admin.dashboard'))
                    Dashboard
                  @elseif(request()->routeIs('admin.users.*'))
                    Kelola Admin & Cabang
                  @elseif(request()->routeIs('admin.product.kosan.*'))
                    Kelola Kos-kosan
                  @elseif(request()->routeIs('admin..booking.*'))
                    Permintaan Booking
                  @elseif(request()->routeIs('admin.artikel.*'))
                    Artikel Blog
                  @elseif(request()->routeIs('admin.testimoni.*'))
                    Testimoni & Review
                  @elseif(request()->routeIs('admin.sosial.media.*'))
                    Sosial Media
                  @elseif(request()->routeIs('admin.profile.*'))
                    Pengaturan Akun
                  @else
                    Admin Panel
                  @endif
                </li>
              </ol>
            </li>
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto align-items-center gap-1.5">

            <!-- Quick Search Palette Trigger -->
            <li class="nav-item d-none d-sm-inline-block me-1">
              <a href="#" class="nav-search-trigger" data-bs-toggle="modal" data-bs-target="#globalSearchModal" title="Pencarian Cepat (Ctrl+K)">
                <i class="bi bi-search text-muted"></i>
                <span class="d-none d-md-inline">Cari modul...</span>
                <span class="nav-search-kbd">Ctrl K</span>
              </a>
            </li>

            <!-- Public Portal Link -->
            <li class="nav-item d-none d-lg-inline-block">
              <a href="{{ route('home') }}" target="_blank" class="nav-link text-muted" title="Buka Website Publik">
                <i class="bi bi-globe2"></i>
                <span class="fs-8">Web Publik</span>
                <i class="bi bi-box-arrow-up-right ms-0.5" style="font-size: 0.65rem;"></i>
              </a>
            </li>

            <!-- Notification Center Popover Dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown" aria-expanded="false" title="Pusat Notifikasi">
                <i class="bi bi-bell-fill fs-5"></i>
                @if(isset($pendingBookingCount) && $pendingBookingCount > 0)
                  <span class="position-absolute top-1 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                    {{ $pendingBookingCount > 99 ? '99+' : $pendingBookingCount }}
                    <span class="visually-hidden">booking pending</span>
                  </span>
                @endif
              </a>
              <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu shadow-lg border-0 mt-2">
                <div class="notification-dropdown-header">
                  <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-7 text-body-emphasis">Notifikasi Reservasi</span>
                    @if(isset($pendingBookingCount) && $pendingBookingCount > 0)
                      <span class="badge bg-danger rounded-pill px-2 py-0.5" style="font-size: 0.68rem;">{{ $pendingBookingCount }} Baru</span>
                    @endif
                  </div>
                  <a href="{{ route('admin..booking.index') }}" class="fs-8 text-decoration-none text-primary fw-semibold">Buka Semua</a>
                </div>
                <div class="notification-dropdown-body">
                  @if(isset($navbarPendingBookings) && $navbarPendingBookings->count() > 0)
                    @foreach($navbarPendingBookings as $nb)
                      <a href="{{ route('admin..booking.index') }}" class="notification-item">
                        <div class="notification-item-icon">
                          <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <div class="overflow-hidden flex-grow-1">
                          <div class="d-flex justify-content-between align-items-center mb-0.5">
                            <span class="fw-bold fs-8 text-body-emphasis text-truncate" style="max-width: 170px;">{{ $nb->name }}</span>
                            <small class="text-muted fs-9">{{ \Carbon\Carbon::parse($nb->created_at)->diffForHumans() }}</small>
                          </div>
                          <div class="fs-8 text-muted text-truncate" style="max-width: 230px;">
                            {{ $nb->productKamarKosan->productKosan->title ?? 'Kosan' }} &bull; Kamar {{ $nb->productKamarKosan->room ?? '-' }}
                          </div>
                          <div class="fw-semibold text-primary fs-9 mt-0.5">
                            Rp {{ number_format((float)$nb->total_price, 0, ',', '.') }}
                          </div>
                        </div>
                      </a>
                    @endforeach
                  @else
                    <div class="text-center py-4 px-3 text-muted">
                      <i class="bi bi-check-circle display-6 d-block mb-2 text-success opacity-75"></i>
                      <div class="fw-bold fs-8 text-body-emphasis">Semua Reservasi Bersih</div>
                      <small class="text-muted fs-9">Tidak ada permintaan booking yang menunggu konfirmasi saat ini.</small>
                    </div>
                  @endif
                </div>
                <div class="notification-dropdown-footer">
                  <a href="{{ route('admin..booking.index') }}" class="btn btn-xs btn-outline-primary w-100 rounded-pill py-1.5 fw-semibold">
                    <span>Lihat Seluruh Transaksi Booking</span>
                    <i class="bi bi-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </li>
            <!--end::Pending Bookings Notification-->

            <!--begin::Fullscreen Toggle-->
            <li class="nav-item d-none d-md-inline-block">
              <a
                class="nav-link"
                href="#"
                data-lte-toggle="fullscreen"
                aria-label="Toggle fullscreen"
                title="Fullscreen Mode"
              >
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit d-none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->

            <!--begin::Color Mode Toggle-->
            <li class="nav-item dropdown">
              <a
                class="nav-link"
                href="#"
                id="bd-theme"
                aria-label="Toggle color scheme"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
                <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
                <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
              </a>
              <ul
                class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                aria-labelledby="bd-theme"
                style="--bs-dropdown-min-width: 8.5rem; border-radius: 12px;"
              >
                <li>
                  <button
                    type="button"
                    class="dropdown-item d-flex align-items-center gap-2 py-2"
                    data-bs-theme-value="light"
                    aria-pressed="false"
                  >
                    <i class="bi bi-sun-fill text-warning"></i>
                    Light
                    <i class="bi bi-check-lg ms-auto d-none text-primary"></i>
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    class="dropdown-item d-flex align-items-center gap-2 py-2"
                    data-bs-theme-value="dark"
                    aria-pressed="false"
                  >
                    <i class="bi bi-moon-fill text-info"></i>
                    Dark
                    <i class="bi bi-check-lg ms-auto d-none text-primary"></i>
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    class="dropdown-item d-flex align-items-center gap-2 py-2 active"
                    data-bs-theme-value="auto"
                    aria-pressed="true"
                  >
                    <i class="bi bi-circle-half text-secondary"></i>
                    Auto
                    <i class="bi bi-check-lg ms-auto d-none text-primary"></i>
                  </button>
                </li>
              </ul>
            </li>
            <!--end::Color Mode Toggle-->

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu ms-1">
              <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2 p-1" data-bs-toggle="dropdown">
                <div class="user-avatar-badge">
                  {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="d-none d-md-block text-start" style="line-height: 1.2;">
                  <span class="d-block fw-bold text-body-emphasis fs-7">{{ Auth::user()->name ?? 'Admin' }}</span>
                  <span class="badge {{ Auth::user()->isSuperAdmin() ? 'badge-subtle-danger' : 'badge-subtle-primary' }}" style="font-size: 0.65rem; padding: 0.15rem 0.4rem !important;">
                    {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Admin Cabang' }}
                  </span>
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow-lg border-0" style="border-radius: 14px; min-width: 240px;">
                <li class="p-3 border-bottom text-center bg-body-tertiary">
                  <div class="user-avatar-badge mx-auto mb-2" style="width: 48px; height: 48px; font-size: 1.2rem;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                  </div>
                  <h6 class="mb-0 fw-bold text-body-emphasis">{{ Auth::user()->name ?? 'Administrator' }}</h6>
                  <span class="badge {{ Auth::user()->isSuperAdmin() ? 'bg-danger-subtle text-danger' : 'bg-primary-subtle text-primary' }} mb-1" style="font-size: 0.7rem;">
                    {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Admin Cabang' }}
                  </span>
                  <small class="text-muted d-block">{{ Auth::user()->email ?? 'admin@sinarcitralestari.com' }}</small>
                </li>
                <li class="p-2 border-bottom">
                  <a href="{{ route('admin.profile.edit') }}" class="btn btn-light w-100 d-flex align-items-center justify-content-center gap-2 py-2 text-secondary fw-semibold">
                    <i class="bi bi-person-gear text-primary"></i> Pengaturan Akun
                  </a>
                </li>
                <li class="p-2">
                  <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                      <i class="bi bi-box-arrow-right"></i> Logout dari Sistem
                    </button>
                  </form>
                </li>
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->

      <!--begin::Sidebar-->
      <aside class="app-sidebar shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <a href="{{ route('admin.dashboard') }}" class="brand-link">
<<<<<<< HEAD
            <img
              src="{{ asset('scl.png') }}"
              alt="NemuKos"
              class="brand-image"
            />
=======
            <div class="brand-logo-box">
              <img
                src="{{ asset('logo.png') }}?v={{ @filemtime(public_path('logo.png')) ?: '4' }}"
                alt="Sinar Citra Lestari"
                class="brand-logo-img"
              />
            </div>
>>>>>>> 7e0eeb6fa02a38cfbb9854e8a343e3b3311b9978
            <div class="brand-text-wrapper">
              <span class="brand-title">Sinar Citra</span>
              <span class="brand-subtitle">Lestari</span>
            </div>
          </a>
        </div>
        <!--end::Sidebar Brand-->

        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-3" aria-label="Main navigation">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              data-accordion="false"
              id="navigation">

              <!-- OVERVIEW -->
              <li class="nav-header">OVERVIEW</li>

              <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-grid-1x2-fill"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <!-- PROPERTI & WILAYAH -->
              <li class="nav-header">PROPERTI & WILAYAH</li>

              <li class="nav-item">
                <a href="{{ route('admin.product.kosan.index') }}" class="nav-link {{ request()->routeIs('admin.product.kosan.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-house-door-fill"></i>
                  <p>Kelola Kos-kosan</p>
                </a>
              </li>

              <!-- TRANSAKSI & SEWA -->
              <li class="nav-header">TRANSAKSI & SEWA</li>

              <li class="nav-item">
                <a href="{{ route('admin..booking.index') }}" class="nav-link {{ request()->routeIs('admin..booking.*') ? 'active' : '' }} d-flex align-items-center justify-content-between">
                  <span class="d-flex align-items-center">
                    <i class="nav-icon bi bi-calendar-check-fill"></i>
                    <p class="mb-0">Permintaan Booking</p>
                  </span>
                  @if(isset($pendingBookingCount) && $pendingBookingCount > 0)
                    <span class="badge bg-danger rounded-pill px-2 py-0.5 fs-8 fw-bold ms-2">{{ $pendingBookingCount }}</span>
                  @endif
                </a>
              </li>

              <!-- KONTEN & INFORMASI -->
              <li class="nav-header">KONTEN & INFORMASI</li>

              <li class="nav-item">
                <a href="{{ route('admin.artikel.index') }}" class="nav-link {{ request()->routeIs('admin.artikel.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-file-earmark-text-fill"></i>
                  <p>Artikel Blog</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.testimoni.index') }}" class="nav-link {{ request()->routeIs('admin.testimoni.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-chat-left-quote-fill"></i>
                  <p>Testimoni & Review</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.sosial.media.index') }}" class="nav-link {{ request()->routeIs('admin.sosial.media.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-share-fill"></i>
                  <p>Sosial Media</p>
                </a>
              </li>

              @if(Auth::user()->isSuperAdmin())
              <!-- SUPER ADMIN SECTION -->
              <li class="nav-header">SUPER ADMIN</li>

              <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-person-gear"></i>
                  <p>Kelola Admin & Cabang</p>
                </a>
              </li>
              @endif

              <!-- KEAMANAN & AKUN -->
              <li class="nav-header">KEAMANAN & AKUN</li>

              <li class="nav-item">
                <a href="{{ route('admin.profile.edit') }}" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-shield-lock-fill"></i>
                  <p>Pengaturan Akun</p>
                </a>
              </li>

              <!-- TAUTAN WEBSITE -->
              <li class="nav-header">PORTAL PUBLIK</li>

              <li class="nav-item">
                <a href="{{ route('home') }}" target="_blank" class="nav-link">
                  <i class="nav-icon bi bi-box-arrow-up-right"></i>
                  <p>Lihat Halaman User</p>
                </a>
              </li>

            </ul>
            <!--end::Sidebar Menu-->

          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->

      <!--begin::App Main Content-->
      <main class="app-main">
        @yield('content')
      </main>
      <!--end::App Main Content-->

      <!--begin::Footer-->
      <footer class="app-footer text-muted fs-7 py-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">
          <div>
            <strong>Copyright &copy; 2026 <a href="{{ route('home') }}" class="text-decoration-none fw-bold" style="color: var(--nk-primary, #0d9488);">Sinar Citra Lestari</a>.</strong> All rights reserved.
          </div>
          <div class="d-none d-sm-inline text-secondary">
            Sinar Citra Lestari &bull; Residence & Living
          </div>
        </div>
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>

    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>

    <!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>

    <!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('assets-dashboard-admin/js/adminlte.js') }}"></script>

    <!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        const isMobile = window.innerWidth <= 992;

        if (
          sidebarWrapper &&
          OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
      $(document).ready(function() {
        // Inisialisasi Summernote ketika modal Tambah dibuka
        $('#modalTambah, #modalTambahArtikel').on('shown.bs.modal', function () {
          $(this).find('.summernote-init, #summernote_description').summernote({
            placeholder: 'Tuliskan deskripsi lengkap & informasi detail...',
            tabsize: 2,
            height: 180,
            toolbar: [
              ['style', ['style']],
              ['font', ['bold', 'underline', 'clear']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['table', ['table']],
              ['insert', ['link']],
              ['view', ['fullscreen', 'codeview']]
            ]
          });
        });

        // Destroy Summernote ketika modal Tambah ditutup
        $('#modalTambah, #modalTambahArtikel').on('hidden.bs.modal', function () {
          $(this).find('.summernote-init, #summernote_description').summernote('destroy');
        });

        // Inisialisasi Summernote ketika Modal Edit dibuka
        $('.modal').on('shown.bs.modal', function () {
          $(this).find('.summernote-edit').summernote({
            placeholder: 'Tuliskan deskripsi lengkap...',
            tabsize: 2,
            height: 180,
            toolbar: [
              ['style', ['style']],
              ['font', ['bold', 'underline', 'clear']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['table', ['table']],
              ['insert', ['link']],
              ['view', ['fullscreen', 'codeview']]
            ]
          });
        });

        // Destroy Summernote ketika Modal Edit ditutup
        $('.modal').on('hidden.bs.modal', function () {
          $(this).find('.summernote-edit').summernote('destroy');
        });
      });
    </script>

    <!-- Theme Switcher Synchronizer (Light, Dark, Auto) -->
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const STORAGE_KEY = 'lte-theme';
        const themeToggles = document.querySelectorAll('[data-bs-theme-value]');
        const themeIcons = document.querySelectorAll('[data-lte-theme-icon]');

        const getStoredTheme = () => {
          try {
            return localStorage.getItem(STORAGE_KEY) || 'auto';
          } catch {
            return 'auto';
          }
        };

        const prefersDark = () => globalThis.matchMedia('(prefers-color-scheme: dark)').matches;

        const updateThemeUI = (theme) => {
          const resolved = (theme === 'auto') ? (prefersDark() ? 'dark' : 'light') : theme;
          
          // Apply attribute & style
          document.documentElement.setAttribute('data-bs-theme', resolved);
          document.documentElement.style.colorScheme = resolved;

          // Update checkmarks in dropdown
          themeToggles.forEach(btn => {
            const val = btn.getAttribute('data-bs-theme-value');
            const isActive = (val === theme);
            btn.classList.toggle('active', isActive);
            btn.setAttribute('aria-pressed', String(isActive));
            const check = btn.querySelector('.bi-check-lg');
            if (check) {
              check.classList.toggle('d-none', !isActive);
            }
          });

          // Update active icon on main trigger button
          themeIcons.forEach(icon => {
            const iconTheme = icon.dataset.lteThemeIcon;
            icon.classList.toggle('d-none', iconTheme !== theme);
          });
        };

        // Initialize state on page load
        const initialTheme = getStoredTheme();
        updateThemeUI(initialTheme);

        // Click listeners for dropdown items
        themeToggles.forEach(btn => {
          btn.addEventListener('click', (e) => {
            e.preventDefault();
            const selected = btn.getAttribute('data-bs-theme-value');
            if (selected) {
              try {
                localStorage.setItem(STORAGE_KEY, selected);
              } catch {}
              updateThemeUI(selected);
            }
          });
        });

        // Dynamic listener for OS preference changes when 'auto' is active
        globalThis.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
          if (getStoredTheme() === 'auto') {
            updateThemeUI('auto');
          }
        });
      });

      // Quick Search Palette Modal (Ctrl+K or Cmd+K)
      document.addEventListener('DOMContentLoaded', () => {
        const searchModalEl = document.getElementById('globalSearchModal');
        const searchInput = document.getElementById('globalQuickSearchInput');
        const resultItems = document.querySelectorAll('#quickSearchResults .search-palette-item');

        document.addEventListener('keydown', (e) => {
          if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            if (searchModalEl) {
              const modal = bootstrap.Modal.getOrCreateInstance(searchModalEl);
              modal.show();
              setTimeout(() => {
                if (searchInput) searchInput.focus();
              }, 250);
            }
          }
        });

        if (searchInput) {
          searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            resultItems.forEach(item => {
              const text = item.textContent.toLowerCase();
              item.style.display = text.includes(query) ? 'flex' : 'none';
            });
          });
        }
      });
    </script>

    <!-- Global Quick Search Palette Modal -->
    <div class="modal fade search-palette-modal" id="globalSearchModal" tabindex="-1" aria-labelledby="globalSearchModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header border-bottom p-2 px-3">
            <div class="d-flex align-items-center gap-2 w-100">
              <i class="bi bi-search text-primary fs-5"></i>
              <input type="text" id="globalQuickSearchInput" class="form-control search-palette-input" placeholder="Ketik nama modul, halaman, atau pintasan..." autocomplete="off">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
          </div>
          <div class="modal-body p-2" id="quickSearchResults">
            <small class="text-muted px-3 py-1 d-block text-uppercase fs-9 fw-bold">Navigasi Langsung</small>
            <a href="{{ route('admin.dashboard') }}" class="search-palette-item">
              <i class="bi bi-grid-1x2-fill"></i>
              <div>
                <div class="fw-bold fs-8">Dashboard Utama</div>
                <small class="text-muted fs-9">Statistik, KPI, dan permintaan booking terbaru</small>
              </div>
            </a>
            <a href="{{ route('admin.product.kosan.index') }}" class="search-palette-item">
              <i class="bi bi-house-door-fill"></i>
              <div>
                <div class="fw-bold fs-8">Kelola Kos-kosan</div>
                <small class="text-muted fs-9">Daftar properti cabang dan unit kamar</small>
              </div>
            </a>
            <a href="{{ route('admin..booking.index') }}" class="search-palette-item">
              <i class="bi bi-calendar-check-fill"></i>
              <div>
                <div class="fw-bold fs-8">Permintaan Booking</div>
                <small class="text-muted fs-9">Konfirmasi dan verifikasi pembayaran tamu sewa</small>
              </div>
            </a>
            @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('admin.users.index') }}" class="search-palette-item">
              <i class="bi bi-person-gear"></i>
              <div>
                <div class="fw-bold fs-8">Kelola Admin & Cabang</div>
                <small class="text-muted fs-9">Atur penugasan dan akun admin kos</small>
              </div>
            </a>
            @endif
            <a href="{{ route('admin.artikel.index') }}" class="search-palette-item">
              <i class="bi bi-file-earmark-text-fill"></i>
              <div>
                <div class="fw-bold fs-8">Artikel Blog & Promosi</div>
                <small class="text-muted fs-9">Kelola berita, informasi, dan penawaran</small>
              </div>
            </a>
            <a href="{{ route('admin.testimoni.index') }}" class="search-palette-item">
              <i class="bi bi-chat-left-quote-fill"></i>
              <div>
                <div class="fw-bold fs-8">Testimoni & Review</div>
                <small class="text-muted fs-9">Moderasi ulasan dan rating pengunjung</small>
              </div>
            </a>
            <a href="{{ route('admin.sosial.media.index') }}" class="search-palette-item">
              <i class="bi bi-share-fill"></i>
              <div>
                <div class="fw-bold fs-8">Sosial Media</div>
                <small class="text-muted fs-9">Kelola tautan kanal komunikasi resmi</small>
              </div>
            </a>
            <a href="{{ route('admin.profile.edit') }}" class="search-palette-item">
              <i class="bi bi-shield-lock-fill"></i>
              <div>
                <div class="fw-bold fs-8">Pengaturan Akun</div>
                <small class="text-muted fs-9">Ubah profil dan kata sandi admin</small>
              </div>
            </a>
          </div>
          <div class="modal-footer p-2 px-3 border-top bg-body-tertiary d-flex justify-content-between align-items-center">
            <small class="text-muted fs-9">Tekan <kbd class="px-1.5 py-0.5 bg-body border rounded">ESC</kbd> untuk menutup</small>
            <small class="text-muted fs-9">Sinar Citra Lestari System</small>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
