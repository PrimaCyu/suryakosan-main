<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengelola - Sinar Citra Lestari</title>
    <link rel="icon" href="{{ asset('logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-title { font-family: 'Lora', Georgia, serif; }
    </style>
</head>
<body class="bg-[#0b0f19] text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-[#131b2e] border border-[#1e293b] rounded-lg shadow-xl p-8 space-y-6">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded bg-white p-2 mb-2 shadow-sm border border-slate-200">
                <img src="{{ asset('logo.png') }}" alt="Sinar Citra Lestari Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-2xl font-serif-title font-semibold text-white tracking-tight">Sinar Citra Lestari</h1>
            <p class="text-xs text-slate-400">Portal Pengelola Multi-Cabang Kos</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-950/40 border border-red-800 text-red-200 text-xs rounded p-3 space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation text-red-400"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-medium text-slate-300 mb-1.5">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-envelope text-xs"></i>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           placeholder="admin@sinarcitralestari.com"
                           class="w-full pl-9 pr-3.5 py-2.5 bg-[#0b0f19] border border-[#1e293b] rounded text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-slate-400 transition-colors">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-slate-300 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-xs"></i>
                    </div>
                    <input type="password" name="password" id="password" required
                           placeholder="••••••••"
                           class="w-full pl-9 pr-3.5 py-2.5 bg-[#0b0f19] border border-[#1e293b] rounded text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-slate-400 transition-colors">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center space-x-2 cursor-pointer text-slate-300">
                    <input type="checkbox" name="remember" class="rounded bg-[#0b0f19] border-[#1e293b] text-slate-700 focus:ring-0">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-700 active:bg-slate-900 text-white font-medium rounded text-sm transition-all border border-slate-700 flex items-center justify-center gap-2">
                <span>Masuk ke Dashboard</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

        <div class="text-center pt-2 border-t border-[#1e293b]">
            <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-slate-200 transition-colors">
                &larr; Kembali ke Beranda Publik
            </a>
        </div>
    </div>
</body>
</html>
