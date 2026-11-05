<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductKosan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('kosans');

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"]);
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        $allKosans = ProductKosan::select('id', 'title', 'wilayah')->get();

        return view('backend.dashboard.users.index', compact('users', 'allKosans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email',
            'password'    => ['required', Password::min(8)],
            'role'        => 'required|in:super_admin,admin',
            'kosan_ids'   => 'nullable|array',
            'kosan_ids.*' => 'exists:product_kosans,id',
        ]);

        $user = User::create([
            'name'     => strip_tags($request->name),
            'email'    => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        if ($request->role === 'admin' && $request->has('kosan_ids')) {
            $user->kosans()->sync($request->kosan_ids);
        }

        return back()->with('success', 'Admin baru berhasil ditambahkan beserta penugasan kos.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'    => ['nullable', Password::min(8)],
            'role'        => 'required|in:super_admin,admin',
            'kosan_ids'   => 'nullable|array',
            'kosan_ids.*' => 'exists:product_kosans,id',
        ]);

        $data = [
            'name'  => strip_tags($request->name),
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'role'  => $request->role,
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->role === 'admin') {
            $user->kosans()->sync($request->kosan_ids ?? []);
        } else {
            $user->kosans()->detach();
        }

        return back()->with('success', 'Data admin ' . $user->name . ' dan penugasan cabang berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('failed', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $userName = $user->name;
        $user->kosans()->detach();
        $user->delete();

        return back()->with('success', 'Akun admin ' . $userName . ' berhasil dihapus.');
    }
}
