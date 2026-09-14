<!doctype html>
<html lang="id">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>NemuKos | Admin Dashboard</title>

    <!--begin::Theme Init (prevents flash of incorrect theme on load)-->
    <script>
      (() => {
        'use strict';
        const STORAGE_KEY = 'lte-theme';
        let stored = null;
        try {
          stored = localStorage.getItem(STORAGE_KEY);
        } catch {
          // localStorage may be unavailable
        }
        const prefersDark = globalThis.matchMedia('(prefers-color-scheme: dark)').matches;
        let resolved = 'light';
        if (stored === 'dark' || stored === 'light') {
          resolved = stored;
        } else if (prefersDark) {
          resolved = 'dark';
        }
        document.documentElement.setAttribute('data-bs-theme', resolved);
        document.documentElement.style.colorScheme = resolved;
      })();
    </script>
    <!--end::Theme Init-->

    <link rel="icon" href="{{ asset('scl.png') }}">
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
          <ul class="navbar-nav align-items-center">
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
            <li class="nav-item d-none d-sm-inline-block ms-2">
              <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1 rounded-pill px-3">
                <i class="bi bi-globe2"></i>
                <span>Lihat Website</span>
                <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.7rem;"></i>
              </a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto align-items-center gap-1">

            <!--begin::Pending Bookings Notification-->
            <li class="nav-item">
              <a class="nav-link position-relative" href="{{ route('admin..booking.index') }}" title="Permintaan Booking">
                <i class="bi bi-bell-fill fs-5"></i>
                @if(isset($pendingBookingCount) && $pendingBookingCount > 0)
                  <span class="position-absolute top-1 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                    {{ $pendingBookingCount }}
                    <span class="visually-hidden">booking pending</span>
                  </span>
                @endif
              </a>
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
                  <span class="d-block fw-bold text-dark fs-7">{{ Auth::user()->name ?? 'Admin' }}</span>
                  <span class="badge badge-subtle-primary" style="font-size: 0.65rem; padding: 0.15rem 0.4rem !important;">Administrator</span>
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow-lg border-0" style="border-radius: 14px; min-width: 240px;">
                <li class="p-3 border-bottom text-center bg-body-tertiary">
                  <div class="user-avatar-badge mx-auto mb-2" style="width: 48px; height: 48px; font-size: 1.2rem;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                  </div>
                  <h6 class="mb-0 fw-bold text-dark">{{ Auth::user()->name ?? 'Administrator' }}</h6>
                  <small class="text-muted">{{ Auth::user()->email ?? 'admin@nemukos.id' }}</small>
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
            <img
              src="{{ asset('scl.png') }}"
              alt="NemuKos"
              class="brand-image"
            />
            <div class="brand-text-wrapper">
              <span class="brand-title">NemuKos</span>
              <span class="brand-subtitle">Admin Workspace</span>
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

              <!-- MENU UTAMA -->
              <li class="nav-header">UTAMA</li>

              <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-grid-1x2-fill"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <!-- KELOLA KOSAN & WILAYAH -->
              <li class="nav-header">PROPERTI & WILAYAH</li>

              <li class="nav-item">
                <a href="{{ route('admin.product.kosan.index') }}" class="nav-link {{ request()->routeIs('admin.product.kosan.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-house-door-fill"></i>
                  <p>Kelola Kos-kosan</p>
                </a>
              </li>

              <!-- TRANSAKSI & BOOKING -->
              <li class="nav-header">TRANSAKSI & SEWA</li>

              <li class="nav-item">
                <a href="{{ route('admin..booking.index') }}" class="nav-link {{ request()->routeIs('admin..booking.*') ? 'active' : '' }} d-flex align-items-center justify-content-between">
                  <span class="d-flex align-items-center">
                    <i class="nav-icon bi bi-calendar-check-fill"></i>
                    <p class="mb-0">Permintaan Booking</p>
                  </span>
                  @if(isset($pendingBookingCount) && $pendingBookingCount > 0)
                    <span class="badge bg-danger rounded-pill px-2 py-1 fs-7 fw-bold shadow-sm ms-2">{{ $pendingBookingCount }}</span>
                  @endif
                </a>
              </li>

              <!-- PENGATURAN UMUM -->
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
            <strong>Copyright &copy; 2026 <a href="{{ route('home') }}" class="text-decoration-none fw-bold text-primary">NemuKos</a>.</strong> All rights reserved.
          </div>
          <div class="d-none d-sm-inline">
            Designed for Modern Property Management
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

  </body>
</html>
