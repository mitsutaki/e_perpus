<?php

namespace App\Http\Controllers;

use App\Models\Shelf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ActivityLog;

class ShelfController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $shelves = Shelf::when($search, function($query) use ($search) {
                return $query->where('nama_rak', 'like', "%{$search}%")
                            ->orWhere('kode_rak', 'like', "%{$search}%")
                            ->orWhere('lokasi', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.shelves.index', compact('shelves', 'search'));
    }

    public function create()
    {
        return view('admin.shelves.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rak' => 'required|string|max:255',
            'tipe' => 'required|in:rak,etalase',
            'kapasitas' => 'required|integer|min:0',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Shelf::create([
            'kode_rak' => 'RAK-' . Str::upper(Str::random(5)),
            'nama_rak' => $request->nama_rak,
            'tipe' => $request->tipe,
            'kapasitas' => $request->kapasitas,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
        ]);

        ActivityLog::record('create', "Menambahkan rak: {$request->nama_rak}");

        return redirect()->route('shelves.index')->with('success', 'Rak/Etalase berhasil ditambahkan.');
    }

    public function edit(Shelf $shelf)
    {
        return view('admin.shelves.edit', compact('shelf'));
    }

    public function update(Request $request, Shelf $shelf)
    {
        $request->validate([
            'nama_rak' => 'required|string|max:255',
            'tipe' => 'required|in:rak,etalase',
            'kapasitas' => 'required|integer|min:0',
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $shelf->update($request->all());
        ActivityLog::record('update', "Memperbarui rak: {$shelf->nama_rak}");
        return redirect()->route('shelves.index')->with('success', 'Data rak diperbarui.');
    }

    public function destroy(Shelf $shelf)
    {
        $shelf->delete();
        ActivityLog::record('delete', "Menghapus rak: {$shelf->nama_rak}");
        return redirect()->route('shelves.index')->with('success', 'Rak berhasil dihapus.');
    }
}