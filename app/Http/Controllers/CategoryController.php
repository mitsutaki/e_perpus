<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ActivityLog;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categories = Category::when($search, function($query) use ($search) {
                return $query->where('nama_kategori', 'like', "%{$search}%")
                            ->orWhere('kode_kategori', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        Category::create([
            'kode_kategori' => 'KAT-' . Str::upper(Str::random(5)),
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi
        ]);

        ActivityLog::record('create', "Menambahkan kategori baru: {$request->nama_kategori}");
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        $category->update($request->all());
        ActivityLog::record('update', "Memperbarui kategori: {$category->nama_kategori}");
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        ActivityLog::record('delete', "Menghapus kategori: {$category->nama_kategori}");
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}