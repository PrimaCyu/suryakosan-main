<?php

namespace App\Http\Controllers;

use App\Models\SosialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SosialMediaController extends Controller
{
    public function index(Request $request)
    {
        $query = SosialMedia::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
        }

        $sosialMedias = $query->orderByDesc('created_at')->paginate(10, ['*'], 'sosial_media_page');

        return view('backend.dashboard.sosial-media.index', compact('sosialMedias'));
    }

    public function searchAjax(Request $request)
    {
        $search = strtolower($request->get('search'));
        $sosialMedias = SosialMedia::when($search, function ($query, $search) {
                return $query->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $sosialMedias
        ]);
    }

    public function insert(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url'   => 'required|url',
        ]);

        SosialMedia::create([
            'title' => $request->title,
            'url'   => $request->url,
        ]);

        return redirect()->back()->with('success', 'Sosial Media berhasil ditambahkan!');
    }

    public function update(Request $request, SosialMedia $sosial_media)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url'   => 'required|url',
        ]);

        $sosial_media->update([
            'title' => $request->title,
            'url'   => $request->url,
        ]);

        return redirect()->back()->with('success', 'Sosial Media berhasil diperbarui!');
    }

    public function delete(SosialMedia $sosial_media)
    {
        $sosial_media->delete();

        return redirect()->back()->with('success', 'Sosial Media berhasil dihapus!');
    }
}
