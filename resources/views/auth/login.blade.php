<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengelola - Sinar Citra Lestari</title>
    <link rel="icon" href="{{ asset('logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#1A110B] text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-[#251912] border border-[#3D291E] rounded-3xl shadow-2xl p-8 space-y-6">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white p-2.5 mb-2 shadow-md border border-teal-500/20">
                <img src="{{ asset('logo.png') }}" alt="Sinar Citra Lestari Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Sinar Citra Lestari</h1>
            <p class="text-xs text-[#CBD5E1]">Portal Masuk Super Admin & Pengelola Cabang Kos</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-sm rounded-xl p-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-envelope text-sm"></i>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           placeholder="admin@example.com"
                            class="w-full pl-10 pr-4 py-3 bg-[#170E08] border border-[#3D291E] rounded-xl text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </div>
                    <input type="password" name="password" id="password" required
                           placeholder="••••••••"
                           class="w-full pl-10 pr-4 py-3 bg-[#170E08] border border-[#3D291E] rounded-xl text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center space-x-2 cursor-pointer text-slate-300">
                    <input type="checkbox" name="remember" class="rounded bg-[#170E08] border-[#3D291E] text-teal-600 focus:ring-teal-500">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-teal-600 hover:bg-teal-500 active:bg-teal-700 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-teal-900/30 flex items-center justify-center gap-2">
                <span>Masuk ke Dashboard</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

        <div class="text-center pt-2 border-t border-[#3D291E]">
            <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-teal-400 transition-colors">
                &larr; Kembali ke Beranda Public
            </a>
        </div>
    </div>
</body>
</html>
