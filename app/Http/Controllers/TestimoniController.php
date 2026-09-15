<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimoni::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
        }

        $testimonis = $query->orderByDesc('created_at')->paginate(10, ['*'], 'testimoni_page');

        return view('backend.dashboard.testimoni.index', compact('testimonis'));
    }

    public function searchAjax(Request $request)
    {
        $search = strtolower($request->get('search'));
        $testimonis = Testimoni::when($search, function ($query, $search) {
                return $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $testimonis
        ]);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'image_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'rating'        => 'required|integer|min:1|max:5',
            'review'        => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_profile')) {
            $imagePath = $request->file('image_profile')->store('testimonis', 'public');
        }

        Testimoni::create([
            'name'          => $request->name,
            'image_profile' => $imagePath,
            'rating'        => $request->rating,
            'review'        => $request->review,
        ]);

        Cache::forget('home_testimonis');

        return redirect()->back()->with('success', 'Testimoni berhasil ditambahkan!');
    }

    public function update(Request $request, Testimoni $testimoni)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'image_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'rating'        => 'required|integer|min:1|max:5',
            'review'        => 'nullable|string',
        ]);

        $imagePath = $testimoni->image_profile;
        if ($request->hasFile('image_profile')) {
            if ($testimoni->image_profile && Storage::disk('public')->exists($testimoni->image_profile)) {
                Storage::disk('public')->delete($testimoni->image_profile);
            }
            $imagePath = $request->file('image_profile')->store('testimonis', 'public');
        }

        $testimoni->update([
            'name'          => $request->name,
            'image_profile' => $imagePath,
            'rating'        => $request->rating,
            'review'        => $request->review,
        ]);

        Cache::forget('home_testimonis');

        return redirect()->back()->with('success', 'Testimoni berhasil diperbarui!');
    }

    public function delete(Testimoni $testimoni)
    {
        if ($testimoni->image_profile && Storage::disk('public')->exists($testimoni->image_profile)) {
            Storage::disk('public')->delete($testimoni->image_profile);
        }

        $testimoni->delete();
        Cache::forget('home_testimonis');

        return redirect()->back()->with('success', 'Testimoni berhasil dihapus!');
    }
}
