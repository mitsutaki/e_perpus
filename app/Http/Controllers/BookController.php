<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Shelf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ActivityLog;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $books = Book::with(['category', 'publisher'])
            ->when($search, function($query) use ($search) {
                return $query->where('judul', 'like', "%{$search}%")
                            ->orWhere('kode_buku', 'like', "%{$search}%")
                            ->orWhere('isbn', 'like', "%{$search}%");
            })
            ->latest()
            ->get();  // ← GANTI dari paginate(10) ke get() biar semua muncul

        return view('admin.books.index', compact('books', 'search'));
    }

    public function create()
    {
        $categories = Category::all();
        $publishers = Publisher::all();
        $shelves = Shelf::all();
        return view('admin.books.create', compact('categories', 'publishers', 'shelves'));
    }

    public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'publisher_id' => 'required|exists:publishers,id',
        'tahun_terbit' => 'required|digits:4',
        'jumlah' => 'required|integer|min:1',
        'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // max 2MB
    ]);

    $coverPath = null;
    if ($request->hasFile('cover')) {
        $coverPath = $request->file('cover')->store('books', 'public');
    }

    Book::create([
        'kode_buku' => 'BK-' . Str::upper(Str::random(6)),
        'judul' => $request->judul,
        'isbn' => $request->isbn,
        'category_id' => $request->category_id,
        'publisher_id' => $request->publisher_id,
        'shelf_id' => $request->shelf_id,
        'tahun_terbit' => $request->tahun_terbit,
        'jumlah' => $request->jumlah,
        'tersedia' => $request->jumlah,
        'jumlah_rusak' => $request->jumlah_rusak ?? 0,
        'cover' => $coverPath,
        'deskripsi' => $request->deskripsi,
    ]);

    return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
}


    public function edit(Book $book)
    {
        $categories = Category::all();
        $publishers = Publisher::all();
        $shelves = Shelf::all();
        return view('admin.books.edit', compact('book', 'categories', 'publishers', 'shelves'));
    }

    public function update(Request $request, Book $book)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'publisher_id' => 'required|exists:publishers,id',
        'tahun_terbit' => 'required|digits:4',
        'jumlah' => 'required|integer|min:1',
        'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $data = $request->except('cover');
    
    if ($request->hasFile('cover')) {
        // Hapus cover lama kalau ada
        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }
        $data['cover'] = $request->file('cover')->store('books', 'public');
    }

    $book->update($data);
    return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui.');
}

    public function destroy(Book $book)
    {
        $book->delete();
        ActivityLog::record('delete', "Menghapus buku: {$book->judul}");
        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}