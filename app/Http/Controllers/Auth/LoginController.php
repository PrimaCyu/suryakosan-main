<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\TurnstileRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $rules = [
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ];

        if (config('services.turnstile.enabled', true) && !empty(config('services.turnstile.site_key'))) {
            $rules['cf-turnstile-response'] = ['required', new TurnstileRule];
        }

        $validated = $request->validate($rules, [
            'cf-turnstile-response.required' => 'Verifikasi keamanan bot wajib diselesaikan sebelum masuk.',
        ]);

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
