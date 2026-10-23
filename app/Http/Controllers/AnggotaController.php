<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Booking;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AnggotaController extends Controller
{
    // Halaman Katalog Buku
    public function katalog(Request $request)
    {
        $search = $request->input('search');
        
        $books = Book::with(['category', 'publisher'])
            ->when($search, function($query) use ($search) {
                return $query->where('judul', 'like', "%{$search}%")
                              ->orWhere('isbn', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12);

        // Cek booking aktif anggota ini
        $user = Auth::user();
        $activeBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->pluck('book_id')->toArray();

        return view('anggota.katalog', compact('books', 'search', 'activeBookings'));
    }

    // Halaman Bookinganku
    public function myBookings()
        {
            $bookings = Booking::where('user_id', Auth::id())
                ->where('status', '!=', 'selesai')  // FILTER: hide completed bookings
                ->with(['book', 'confirmedBy'])
                ->latest()
                ->get();

            return view('anggota.bookings', compact('bookings'));
        }

    // Proses Booking Buku
    public function storeBooking(Request $request, Book $book)
    {
        $user = Auth::user();

        // Validasi 1: Pastikan anggota punya data member
        $member = Member::where('user_id', $user->id)->first();
        if (!$member) {
            return back()->withErrors(['error' => 'Data anggota tidak ditemukan. Hubungi admin.']);
        }

        // Validasi 2: Status anggota aktif
        if ($member->status !== 'aktif') {
            return back()->withErrors(['error' => 'Keanggotaan Anda nonaktif. Hubungi admin.']);
        }

        // Validasi 3: Cek denda belum lunas
        $unpaidFines = \App\Models\Fine::where('member_id', $member->id)
            ->where('status', 'belum_bayar')
            ->count();
        if ($unpaidFines > 0) {
            return back()->withErrors(['error' => 'Anda punya ' . $unpaidFines . ' denda belum lunas. Lunasi dulu.']);
        }

        // Validasi 4: Cek apakah buku sudah dibooking anggota ini (belum diproses)
        $existingBooking = Booking::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->exists();
        if ($existingBooking) {
            return back()->withErrors(['error' => 'Anda sudah membooking buku ini. Lihat di Bookinganku.']);
        }

        // Validasi 5: Cek stok tersedia
        if ($book->tersedia <= 0) {
            return back()->withErrors(['error' => 'Stok buku habis.']);
        }

        // Validasi 6: Batas maksimal booking aktif (misal: 3 buku)
        $activeBookingsCount = Booking::where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'dikonfirmasi'])
            ->count();
        if ($activeBookingsCount >= 3) {
            return back()->withErrors(['error' => 'Anda sudah membooking 3 buku. Selesaikan dulu.']);
        }

        // ✅ Buat Booking
        Booking::create([
            'kode_booking' => 'BKG-' . Str::upper(Str::random(8)),
            'user_id' => $user->id,
            'book_id' => $book->id,
            'tanggal_booking' => now(),
            'batas_ambil' => now()->addHours(24), // 24 jam
            'status' => 'menunggu',
        ]);

        // Kurangi stok tersedia, tambah counter dibooking
        $book->decrement('tersedia', 1);
        $book->increment('dibooking', 1);

        \App\Models\ActivityLog::record('booking', "Membooking buku: {$book->judul}");

        return redirect()->route('anggota.bookings')->with('success', 'Booking berhasil! Ambil buku dalam 24 jam di loket perpus.');
    }

    // Batalkan Booking (oleh anggota sendiri)
    public function cancelBooking(Booking $booking)
    {
        // Pastikan booking milik anggota ini
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya bisa batal kalau status menunggu
        if ($booking->status !== 'menunggu') {
            return back()->withErrors(['error' => 'Booking sudah dikonfirmasi, tidak bisa dibatalkan.']);
        }

        // Kembalikan stok
        $book = $booking->book;
        $book->increment('tersedia', 1);
        $book->decrement('dibooking', 1);

        $booking->update(['status' => 'dibatalkan']);

        return redirect()->route('anggota.bookings')->with('success', 'Booking dibatalkan.');
    }
}