<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Login Pengelola - Sinar Citra Lestari</title>
    <link rel="icon" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #0b1120;
            background-image: 
                radial-gradient(at 50% 0%, rgba(15, 118, 110, 0.18) 0px, transparent 60%),
                radial-gradient(at 100% 100%, rgba(30, 41, 59, 0.4) 0px, transparent 50%);
            color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
        }

        .login-card {
            width: 100%;
            max-width: 410px;
            background: #131d31;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.6);
            padding: 2.25rem 2rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .login-logo {
            width: 56px;
            height: 56px;
            background: #ffffff;
            border-radius: 10px;
            padding: 7px;
            margin: 0 auto 0.85rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .login-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .login-title {
            font-family: 'Lora', Georgia, serif;
            font-size: 1.45rem;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: -0.01em;
            margin-bottom: 0.25rem;
        }

        .login-subtitle {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
            font-size: 0.8rem;
            border-radius: 8px;
            padding: 0.65rem 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.15rem;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 0.4rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 0.85rem;
            color: #64748b;
            font-size: 0.9rem;
            pointer-events: none;
            transition: color 0.15s ease;
        }

        .form-input {
            width: 100%;
            background: #090e1a;
            border: 1px solid #243048;
            border-radius: 8px;
            padding: 0.65rem 2.5rem 0.65rem 2.4rem;
            font-size: 0.875rem;
            color: #f8fafc;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-input::placeholder {
            color: #475569;
        }

        .form-input:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.2);
        }

        .input-wrapper:focus-within .input-icon {
            color: #14b8a6;
        }

        .btn-toggle-eye {
            position: absolute;
            right: 0.75rem;
            background: transparent;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 0.25rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s ease;
        }

        .btn-toggle-eye:hover {
            color: #cbd5e1;
        }

        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            margin-bottom: 1.4rem;
            color: #94a3b8;
        }

        .remember-checkbox {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            cursor: pointer;
            user-select: none;
        }

        .remember-checkbox input[type="checkbox"] {
            accent-color: #0f766e;
            width: 15px;
            height: 15px;
            cursor: pointer;
        }

        .forgot-link {
            color: #94a3b8;
            text-decoration: none;
            cursor: pointer;
            transition: color 0.15s ease;
        }

        .forgot-link:hover {
            color: #14b8a6;
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            background: #0f766e;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 0.72rem 1rem;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: background 0.15s ease, transform 0.1s ease;
        }

        .btn-submit:hover {
            background: #115e59;
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .spinner {
            display: inline-block;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .footer-back {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }

        .footer-link {
            font-size: 0.8rem;
            color: #64748b;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .footer-link:hover {
            color: #cbd5e1;
        }

        /* MODAL BANTUAN LUPA SANDI */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            z-index: 9999;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            background: #131d31;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            max-width: 380px;
            width: 100%;
            padding: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-box-title {
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .modal-box-text {
            font-size: 0.82rem;
            color: #94a3b8;
            line-height: 1.55;
            margin-bottom: 1.25rem;
        }

        .modal-box-btn {
            width: 100%;
            background: #1e293b;
            color: #f1f5f9;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 0.55rem;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .modal-box-btn:hover {
            background: #334155;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- HEADER -->
        <div class="login-header">
            <div class="login-logo">
                <img src="{{ asset('logo.png') }}" alt="Sinar Citra Lestari Logo">
            </div>
            <h1 class="login-title">Sinar Citra Lestari</h1>
            <p class="login-subtitle">Portal Masuk Pengelola Kos</p>
        </div>

        <!-- ERROR ALERT -->
        @if ($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-circle-fill" style="margin-top: 1px;"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- FORM -->
        <form action="{{ route('login.post') }}" method="POST" id="formLogin" onsubmit="handleLoginSubmit(event)">
            @csrf

            <!-- EMAIL -->
            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <div class="input-wrapper">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="email"
                           autocapitalize="none"
                           spellcheck="false"
                           placeholder="admin@sinarcitralestari.com"
                           class="form-input">
                </div>
            </div>

            <!-- PASSWORD WITH EYE TOGGLE -->
            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password"
                           name="password"
                           id="password"
                           required
                           autocomplete="current-password"
                           placeholder="••••••••"
                           class="form-input">
                    <button type="button"
                            class="btn-toggle-eye"
                            id="togglePassword"
                            onclick="togglePasswordVisibility()"
                            aria-label="Tampilkan atau sembunyikan kata sandi"
                            title="Tampilkan / Sembunyikan sandi">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- REMEMBER ME & FORGOT PASSWORD -->
            <div class="options-row">
                <label class="remember-checkbox">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Ingat saya</span>
                </label>
                <a href="javascript:void(0)" class="forgot-link" onclick="openForgotModal()">Lupa kata sandi?</a>
            </div>

            <!-- SUBMIT BUTTON WITH SPINNER STATE -->
            <button type="submit" class="btn-submit" id="btnSubmit">
                <span id="btnText">Masuk ke Dashboard</span>
                <i class="bi bi-arrow-right" id="btnArrow"></i>
            </button>
        </form>

        <!-- BACK TO HOME -->
        <div class="footer-back">
            <a href="{{ route('home') }}" class="footer-link">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda Publik
            </a>
        </div>
    </div>

    <!-- MODAL BANTUAN LUPA KATA SANDI -->
    <div class="modal-overlay" id="forgotModal" onclick="closeForgotModalOnBackdrop(event)">
        <div class="modal-box">
            <div class="modal-box-title">
                <i class="bi bi-shield-lock text-warning"></i> Bantuan Kata Sandi
            </div>
            <p class="modal-box-text">
                Untuk menjaga keamanan operasional kos, pengaturan ulang kata sandi dikelola langsung oleh <strong>Super Admin</strong>.
                <br><br>
                Silakan hubungi administrator pusat untuk mendapatkan kata sandi baru atau bantuan pemulihan akses akun Anda.
            </p>
            <button type="button" class="modal-box-btn" onclick="closeForgotModal()">Mengerti</button>
        </div>
    </div>

    <script>
        // Toggle Show/Hide Password
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput && eyeIcon) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.classList.remove('bi-eye');
                    eyeIcon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.classList.remove('bi-eye-slash');
                    eyeIcon.classList.add('bi-eye');
                }
            }
        }

        // Handle Submit Loading State
        function handleLoginSubmit(e) {
            const btn = document.getElementById('btnSubmit');
            const btnText = document.getElementById('btnText');
            const btnArrow = document.getElementById('btnArrow');

            if (btn && btnText) {
                btn.disabled = true;
                btnText.textContent = 'Memverifikasi...';
                if (btnArrow) {
                    btnArrow.className = 'bi bi-arrow-repeat spinner';
                }
            }
            // Form continues submit
        }

        // Modal Forgot Password
        function openForgotModal() {
            const modal = document.getElementById('forgotModal');
            if (modal) modal.classList.add('active');
        }

        function closeForgotModal() {
            const modal = document.getElementById('forgotModal');
            if (modal) modal.classList.remove('active');
        }

        function closeForgotModalOnBackdrop(e) {
            if (e.target.id === 'forgotModal') {
                closeForgotModal();
            }
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeForgotModal();
            }
        });
    </script>
</body>
</html>
