<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ActivityLog;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $publishers = Publisher::when($search, function($query) use ($search) {
                return $query->where('nama_penerbit', 'like', "%{$search}%")
                            ->orWhere('kode_penerbit', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.publishers.index', compact('publishers', 'search'));
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penerbit' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:15',
            'email' => 'nullable|email',
        ]);

        Publisher::create([
            'kode_penerbit' => 'PBT-' . Str::upper(Str::random(5)),
            'nama_penerbit' => $request->nama_penerbit,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
        ]);

        ActivityLog::record('create', "Menambahkan penerbit: {$request->nama_penerbit}");

        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil ditambahkan.');
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $request->validate([
            'nama_penerbit' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:15',
            'email' => 'nullable|email',
        ]);

        $publisher->update($request->all());
        ActivityLog::record('update', "Memperbarui penerbit: {$publisher->nama_penerbit}");
        return redirect()->route('publishers.index')->with('success', 'Data penerbit diperbarui.');
    }

    public function destroy(Publisher $publisher)
    {
        $publisher->delete();
        ActivityLog::record('delete', "Menghapus penerbit: {$publisher->nama_penerbit}");
        return redirect()->route('publishers.index')->with('success', 'Penerbit berhasil dihapus.');
    }
}