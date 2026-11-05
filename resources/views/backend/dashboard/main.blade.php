<!doctype html>
<html lang="id" data-bs-theme="light">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sinar Citra Lestari | Admin Dashboard</title>

    <script>
      (() => {
        'use strict';
        try {
          localStorage.setItem('lte-theme', 'light');
        } catch {}
        document.documentElement.setAttribute('data-bs-theme', 'light');
        document.documentElement.style.colorScheme = 'light';

        const observer = new MutationObserver(() => {
          if (document.documentElement.getAttribute('data-bs-theme') !== 'light') {
            document.documentElement.setAttribute('data-bs-theme', 'light');
            document.documentElement.style.colorScheme = 'light';
          }
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-bs-theme'] });
      })();
    </script>

    <link rel="icon" href="{{ asset('logo.png') }}?v={{ @filemtime(public_path('logo.png')) ?: '4' }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light" />
    <meta name="theme-color" content="#ffffff" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!--begin::Google Fonts (Plus Jakarta Sans)-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
    <link rel="stylesheet" href="{{ asset('assets-dashboard-admin/css/custom-admin.css') }}?v={{ @filemtime(public_path('assets-dashboard-admin/css/custom-admin.css')) ?: '4' }}" />
    <!--end::Custom Modern Admin Design System-->

    <!-- jQuery (Required early for views and Summernote) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
      @font-face {
        font-family: 'summernote';
        src: url('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/font/summernote.woff2') format('woff2'),
             url('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/font/summernote.woff') format('woff'),
             url('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/font/summernote.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
      }
      .note-editor [class*=" note-icon"]:before,
      .note-editor [class^="note-icon"]:before,
      .note-editor [class*=" note-icon"]::before,
      .note-editor [class^="note-icon"]::before,
      [class*=" note-icon"]:before,
      [class^="note-icon"]:before {
        font-family: 'summernote' !important;
        font-style: normal !important;
        font-weight: normal !important;
        display: inline-block !important;
      }
    </style>
  </head>
  <!--end::Head-->

  @php
    $currentPageLabel = match(true) {
      request()->routeIs('admin.dashboard') => 'Dashboard',
      request()->routeIs('admin.users.*') => 'Admin & Cabang',
      request()->routeIs('admin.product.kosan.kamar.*') => 'Kelola Kamar',
      request()->routeIs('admin.product.kosan.*') => 'Kelola Kos-kosan',
      request()->routeIs('admin.booking.*') => 'Permintaan Booking',
      request()->routeIs('admin.artikel.*') => 'Artikel Blog',
      request()->routeIs('admin.testimoni.*') => 'Testimoni & Review',
      request()->routeIs('admin.sosial.media.*') => 'Sosial Media',
      request()->routeIs('admin.payment-methods.*') => 'Metode Pembayaran',
      request()->routeIs('admin.profile.*') => 'Profil & Keamanan',
      default => 'Admin Panel',
    };
  @endphp

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
                class="nav-action-btn"
                data-lte-toggle="sidebar"
                href="#"
                role="button"
                aria-label="Toggle sidebar"
                title="Buka / Tutup Sidebar"
              >
                <i class="bi bi-list fs-5"></i>
              </a>
            </li>

            <!-- Mobile Page Title Indicator -->
            <li class="nav-item d-flex d-md-none align-items-center">
              <span class="fw-semibold text-dark fs-7 text-truncate" style="max-width: 145px;">
                {{ $currentPageLabel }}
              </span>
            </li>

            <!-- Desktop Breadcrumb Navigation -->
            <li class="nav-item d-none d-md-flex align-items-center">
              <nav class="header-breadcrumb-nav" aria-label="breadcrumb">
                <ol class="header-breadcrumb">
                  <li class="header-breadcrumb-item @if(request()->routeIs('admin.dashboard')) active @endif">
                    <a href="{{ route('admin.dashboard') }}" class="header-breadcrumb-link" title="Menuju Dashboard Utama">
                      <i class="bi bi-grid-1x2 text-muted fs-8"></i>
                      <span>Dashboard</span>
                    </a>
                  </li>
                  @if(request()->routeIs('admin.product.kosan.kamar.*'))
                  <li class="header-breadcrumb-sep">
                    <i class="bi bi-chevron-right"></i>
                  </li>
                  <li class="header-breadcrumb-item">
                    <a href="{{ route('admin.product.kosan.index') }}" class="header-breadcrumb-link">
                      <span>Kelola Kos-kosan</span>
                    </a>
                  </li>
                  @endif
                  @if(!request()->routeIs('admin.dashboard'))
                  <li class="header-breadcrumb-sep">
                    <i class="bi bi-chevron-right"></i>
                  </li>
                  <li class="header-breadcrumb-item active" aria-current="page">
                    <span>{{ $currentPageLabel }}</span>
                  </li>
                  @endif
                </ol>
              </nav>
            </li>
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto align-items-center gap-1.5 gap-sm-2">

            <!-- Quick Search Palette Trigger (Accessible on Desktop & Mobile) -->
            <li class="nav-item">
              <button type="button" class="nav-search-btn" data-bs-toggle="modal" data-bs-target="#globalSearchModal" title="Pencarian Cepat Modul (Ctrl+K)" aria-label="Pencarian Cepat Modul">
                <i class="bi bi-search nav-search-icon"></i>
                <span class="nav-search-placeholder">Cari modul...</span>
                <kbd class="nav-search-kbd">
                  <span>Ctrl</span>
                  <span>K</span>
                </kbd>
              </button>
            </li>

            <!-- Public Portal Link -->
            <li class="nav-item d-none d-lg-inline-block">
              <a href="{{ route('home') }}" target="_blank" class="nav-public-btn" title="Buka Website Publik Sinar Citra Lestari">
                <i class="bi bi-globe2 text-primary fs-8"></i>
                <span>Web Publik</span>
                <i class="bi bi-box-arrow-up-right text-muted" style="font-size: 0.65rem;"></i>
              </a>
            </li>

            <!-- Notification Center Popover Dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-action-btn position-relative" href="#" data-bs-toggle="dropdown" aria-expanded="false" title="Pusat Notifikasi" aria-label="Pusat Notifikasi">
                <i class="bi bi-bell fs-6"></i>
                @if(isset($pendingBookingCount) && $pendingBookingCount > 0)
                  <span class="nav-action-badge">
                    {{ $pendingBookingCount > 99 ? '99+' : $pendingBookingCount }}
                  </span>
                @endif
              </a>
              <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu mt-2">
                <div class="notification-dropdown-header">
                  <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-7 text-dark">Notifikasi Reservasi</span>
                    @if(isset($pendingBookingCount) && $pendingBookingCount > 0)
                      <span class="badge badge-subtle-danger rounded-pill px-2 py-0.5">{{ $pendingBookingCount }} Baru</span>
                    @endif
                  </div>
                  <a href="{{ route('admin.booking.index') }}" class="fs-8 text-decoration-none text-primary fw-semibold">Buka Semua</a>
                </div>
                <div class="notification-dropdown-body">
                  @if(isset($navbarPendingBookings) && $navbarPendingBookings->count() > 0)
                    @foreach($navbarPendingBookings as $nb)
                      <a href="{{ route('admin.booking.index') }}" class="notification-item">
                        <div class="notification-item-icon">
                          <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="overflow-hidden flex-grow-1">
                          <div class="d-flex justify-content-between align-items-center mb-0.5">
                            <span class="fw-semibold fs-8 text-dark text-truncate" style="max-width: 170px;">{{ $nb->name }}</span>
                            <small class="text-muted fs-9">{{ \Carbon\Carbon::parse($nb->created_at)->diffForHumans() }}</small>
                          </div>
                          @php
                            $nbRoom = $nb->productKamarKosan->room ?? '-';
                            $nbRoomLabel = str_starts_with(strtolower($nbRoom), 'kamar') ? $nbRoom : 'Kamar ' . $nbRoom;
                          @endphp
                          <div class="fs-8 text-muted text-truncate" style="max-width: 230px;">
                            {{ $nb->productKamarKosan->productKosan->title ?? 'Kosan' }} &bull; {{ $nbRoomLabel }}
                          </div>
                          <div class="fw-semibold text-primary fs-9 mt-0.5 tabular-nums">
                            Rp {{ number_format((float)$nb->total_price, 0, ',', '.') }}
                          </div>
                        </div>
                      </a>
                    @endforeach
                  @else
                    <div class="text-center py-4 px-3 text-muted">
                      <i class="bi bi-check2-circle fs-3 d-block mb-1.5 text-success"></i>
                      <div class="fw-semibold fs-8 text-dark">Tidak Ada Antrean Baru</div>
                      <small class="text-muted fs-9">Seluruh permintaan booking telah ditindaklanjuti.</small>
                    </div>
                  @endif
                </div>
                <div class="notification-dropdown-footer">
                  <a href="{{ route('admin.booking.index') }}" class="btn btn-xs btn-light border w-100 py-1.5 fw-semibold justify-content-center">
                    <span>Lihat Semua Permintaan Booking</span>
                    <i class="bi bi-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </li>
            <!--end::Pending Bookings Notification-->

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu ms-0.5">
              <a href="#" class="nav-user-chip" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Akun Admin">
                <div class="nav-user-avatar">
                  {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="nav-user-meta d-none d-md-flex flex-column text-start">
                  <span class="nav-user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                  <span class="nav-user-role {{ Auth::user()->isSuperAdmin() ? 'role-super-admin' : 'role-admin' }}">
                    {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Admin Cabang' }}
                  </span>
                </div>
                <i class="bi bi-chevron-down nav-user-chevron ms-0.5 d-none d-sm-inline"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end mt-2 p-0 overflow-hidden" style="min-width: 240px;">
                <li class="p-3 border-bottom bg-light">
                  <div class="d-flex align-items-center gap-2.5">
                    <div class="nav-user-avatar flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.95rem;">
                      {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                      <div class="fw-bold fs-8 text-dark text-truncate">{{ Auth::user()->name ?? 'Administrator' }}</div>
                      <div class="text-muted fs-9 text-truncate">{{ Auth::user()->email ?? 'admin@sinarcitralestari.com' }}</div>
                      <span class="badge {{ Auth::user()->isSuperAdmin() ? 'badge-subtle-primary' : 'badge-subtle-secondary' }} mt-1" style="font-size: 0.65rem;">
                        {{ Auth::user()->isSuperAdmin() ? 'Super Admin' : 'Admin Cabang' }}
                      </span>
                    </div>
                  </div>
                </li>
                <li class="p-1.5">
                  <a href="{{ route('admin.profile.edit') }}" class="dropdown-item d-flex align-items-center gap-2 py-2 px-2.5 rounded">
                    <i class="bi bi-shield-lock text-secondary"></i>
                    <span>Pengaturan Akun</span>
                  </a>
                  <a href="{{ route('home') }}" target="_blank" class="dropdown-item d-flex d-lg-none align-items-center gap-2 py-2 px-2.5 rounded">
                    <i class="bi bi-globe2 text-secondary"></i>
                    <span>Buka Website Publik</span>
                  </a>
                </li>
                <li class="p-1.5 border-top">
                  <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 px-2.5 rounded text-danger fw-semibold">
                      <i class="bi bi-box-arrow-right"></i>
                      <span>Keluar Sistem</span>
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
      <aside class="app-sidebar" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <div class="brand-logo-box">
              <img
                src="{{ asset('logo.png') }}?v={{ @filemtime(public_path('logo.png')) ?: '4' }}"
                alt="Sinar Citra Lestari"
                class="brand-logo-img"
              />
            </div>
            <div class="brand-text-wrapper">
              <div class="brand-title">
                <span class="brand-title-main">Sinar Citra</span>
                <span class="brand-title-accent">Lestari</span>
              </div>
              <div class="brand-subtitle">
                <span class="brand-status-dot"></span>
                <span>Management Panel</span>
              </div>
            </div>
          </a>
        </div>
        <!--end::Sidebar Brand-->

        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2 d-flex flex-column h-100" aria-label="Main navigation">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column flex-grow-1"
              data-lte-toggle="treeview"
              data-accordion="false"
              id="navigation">

              <!-- 1. Operasional -->
              <li class="nav-header">Operasional</li>

              <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-grid-1x2"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.product.kosan.index') }}" class="nav-link {{ request()->routeIs('admin.product.kosan.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-buildings"></i>
                  <p>Kelola Kos-kosan</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.booking.index') }}" class="nav-link {{ request()->routeIs('admin.booking.*') ? 'active' : '' }} d-flex align-items-center justify-content-between">
                  <span class="d-flex align-items-center">
                    <i class="nav-icon bi bi-calendar-check"></i>
                    <p class="mb-0">Permintaan Booking</p>
                  </span>
                  @if(isset($pendingBookingCount) && $pendingBookingCount > 0)
                    <span class="badge rounded-pill px-2 py-0.5 fs-9 fw-bold ms-2" style="background-color: #dc2626; color: #ffffff;">{{ $pendingBookingCount }}</span>
                  @endif
                </a>
              </li>

              <!-- 2. Konten Publik -->
              <li class="nav-header">Konten Publik</li>

              <li class="nav-item">
                <a href="{{ route('admin.artikel.index') }}" class="nav-link {{ request()->routeIs('admin.artikel.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-journal-text"></i>
                  <p>Artikel Blog</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.testimoni.index') }}" class="nav-link {{ request()->routeIs('admin.testimoni.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-chat-square-quote"></i>
                  <p>Testimoni & Review</p>
                </a>
              </li>

              <!-- 3. Pengaturan & Sistem -->
              <li class="nav-header">Pengaturan</li>

              @if(Auth::user()->isSuperAdmin())
              <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-people"></i>
                  <p>Kelola Admin & Cabang</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.payment-methods.index') }}" class="nav-link {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-credit-card-2-front"></i>
                  <p>Metode Pembayaran</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.sosial.media.index') }}" class="nav-link {{ request()->routeIs('admin.sosial.media.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-share"></i>
                  <p>Sosial Media</p>
                </a>
              </li>
              @endif

              <li class="nav-item">
                <a href="{{ route('admin.profile.edit') }}" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-shield-lock"></i>
                  <p>Profil Akun</p>
                </a>
              </li>

              <!-- Portal Publik -->
              <li class="nav-header">Pintasan</li>

              <li class="nav-item mb-3">
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
      <footer class="app-footer text-muted fs-8 py-2.5">
        <div class="container-fluid d-flex justify-content-between align-items-center">
          <div>
            <span>&copy; {{ date('Y') }}</span>
            <a href="{{ route('home') }}" target="_blank" class="text-decoration-none fw-semibold text-dark ms-1">Sinar Citra Lestari</a>
          </div>
          <div class="d-none d-sm-inline text-muted">
            Sistem Manajemen Operasional Kos
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

    <!--begin::SweetAlert2-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--end::SweetAlert2-->

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
        // Prevent Bootstrap 5 modal from stealing focus from Summernote dialogs (e.g. Link dialog)
        $(document).on('focusin', function(e) {
          if ($(e.target).closest('.note-modal, .note-editor').length) {
            e.stopImmediatePropagation();
          }
        });

        $('#modalTambah').on('shown.bs.modal', function () {
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

        $('#modalTambah').on('hidden.bs.modal', function () {
          $(this).find('.summernote-init, #summernote_description').summernote('destroy');
        });

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

        $('.modal').on('hidden.bs.modal', function () {
          $(this).find('.summernote-edit').summernote('destroy');
        });

        $(document).on('submit', 'form', function () {
          $(this).find('.summernote-init, .summernote-edit, .summernote-artikel, .summernote-edit-artikel, #summernote_description, textarea').each(function () {
            if (typeof $.fn.summernote !== 'undefined' && $(this).data('summernote')) {
              $(this).val($(this).summernote('code'));
            }
          });
        });
      });
    </script>

    <script>
      // Quick Search Palette Modal (Ctrl+K or Cmd+K)
      document.addEventListener('DOMContentLoaded', () => {
        const searchModalEl = document.getElementById('globalSearchModal');
        const searchInput = document.getElementById('globalQuickSearchInput');
        const resultItems = Array.from(document.querySelectorAll('#quickSearchResults .search-palette-item'));
        const emptyState = document.getElementById('quickSearchEmptyState');

        if (!searchModalEl || !searchInput) return;

        searchModalEl.addEventListener('shown.bs.modal', () => {
          searchInput.value = '';
          filterPalette('');
          searchInput.focus();
        });

        document.addEventListener('keydown', (e) => {
          if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            const modal = bootstrap.Modal.getOrCreateInstance(searchModalEl);
            modal.show();
          }
        });

        function filterPalette(query) {
          let visibleCount = 0;
          resultItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            const match = text.includes(query);
            item.style.display = match ? 'flex' : 'none';
            if (match) visibleCount++;
          });
          if (emptyState) {
            emptyState.classList.toggle('d-none', visibleCount > 0);
          }
        }

        searchInput.addEventListener('input', function () {
          filterPalette(this.value.toLowerCase().trim());
        });
      });
    </script>

    <!-- Global Quick Search Palette Modal -->
    <div class="modal fade search-palette-modal" id="globalSearchModal" tabindex="-1" aria-labelledby="globalSearchModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-header border-bottom py-2.5 px-3">
            <div class="d-flex align-items-center gap-2 w-100">
              <i class="bi bi-search text-muted fs-6"></i>
              <input type="text" id="globalQuickSearchInput" class="form-control search-palette-input" placeholder="Ketik nama modul atau halaman..." autocomplete="off">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
          </div>
          <div class="modal-body p-2" id="quickSearchResults">
            <small class="text-muted px-2.5 py-1.5 d-block text-uppercase fs-9 fw-bold">Navigasi Modul</small>
            <a href="{{ route('admin.dashboard') }}" class="search-palette-item">
              <i class="bi bi-grid-1x2"></i>
              <div>
                <div class="fw-semibold fs-8 text-dark">Dashboard Utama</div>
                <small class="text-muted fs-9">Ringkasan operasional, okupansi, dan pendapatan</small>
              </div>
            </a>
            <a href="{{ route('admin.product.kosan.index') }}" class="search-palette-item">
              <i class="bi bi-buildings"></i>
              <div>
                <div class="fw-semibold fs-8 text-dark">Kelola Kos-kosan & Kamar</div>
                <small class="text-muted fs-9">Daftar properti cabang dan unit kamar sewa</small>
              </div>
            </a>
            <a href="{{ route('admin.booking.index') }}" class="search-palette-item">
              <i class="bi bi-calendar-check"></i>
              <div>
                <div class="fw-semibold fs-8 text-dark">Permintaan Booking</div>
                <small class="text-muted fs-9">Verifikasi reservasi dan pembayaran penyewa</small>
              </div>
            </a>
            <a href="{{ route('admin.artikel.index') }}" class="search-palette-item">
              <i class="bi bi-journal-text"></i>
              <div>
                <div class="fw-semibold fs-8 text-dark">Artikel Blog & Promosi</div>
                <small class="text-muted fs-9">Publikasi informasi dan penawaran kos</small>
              </div>
            </a>
            <a href="{{ route('admin.testimoni.index') }}" class="search-palette-item">
              <i class="bi bi-chat-square-quote"></i>
              <div>
                <div class="fw-semibold fs-8 text-dark">Testimoni & Review</div>
                <small class="text-muted fs-9">Moderasi ulasan dan rating penghuni</small>
              </div>
            </a>
            @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('admin.users.index') }}" class="search-palette-item">
              <i class="bi bi-people"></i>
              <div>
                <div class="fw-semibold fs-8 text-dark">Kelola Admin & Cabang</div>
                <small class="text-muted fs-9">Atur akun pengelola dan penugasan cabang</small>
              </div>
            </a>
            <a href="{{ route('admin.payment-methods.index') }}" class="search-palette-item">
              <i class="bi bi-credit-card-2-front"></i>
              <div>
                <div class="fw-semibold fs-8 text-dark">Metode Pembayaran</div>
                <small class="text-muted fs-9">Atur rekening bank, e-wallet, dan QRIS resmi</small>
              </div>
            </a>
            <a href="{{ route('admin.sosial.media.index') }}" class="search-palette-item">
              <i class="bi bi-share"></i>
              <div>
                <div class="fw-semibold fs-8 text-dark">Sosial Media</div>
                <small class="text-muted fs-9">Kelola tautan WhatsApp, Instagram, dan TikTok</small>
              </div>
            </a>
            @endif
            <a href="{{ route('admin.profile.edit') }}" class="search-palette-item">
              <i class="bi bi-shield-lock"></i>
              <div>
                <div class="fw-semibold fs-8 text-dark">Pengaturan Akun</div>
                <small class="text-muted fs-9">Perbarui profil dan kata sandi</small>
              </div>
            </a>

            <div id="quickSearchEmptyState" class="text-center py-4 px-3 text-muted d-none">
              <i class="bi bi-search fs-4 d-block mb-1 opacity-50"></i>
              <div class="fs-8 fw-medium text-secondary">Modul tidak ditemukan</div>
              <small class="fs-9 text-muted">Coba kata kunci lain seperti "kamar", "booking", atau "pembayaran".</small>
            </div>
          </div>
          <div class="modal-footer py-2 px-3 border-top bg-light d-flex justify-content-between align-items-center">
            <small class="text-muted fs-9">Tekan <kbd class="px-1.5 py-0.5 bg-white border rounded">ESC</kbd> untuk menutup</small>
            <small class="text-muted fs-9">Sinar Citra Lestari</small>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
