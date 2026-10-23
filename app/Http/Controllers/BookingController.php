<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Loan;
use App\Models\LoanDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    // Daftar Booking Masuk (untuk Admin/Petugas)
    public function index()
    {
        $bookings = Booking::with(['user', 'book', 'confirmedBy'])
            ->where('status', 'menunggu')
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    // Halaman Konfirmasi Booking (form)
    public function show(Booking $booking)
    {
        $booking->load(['user', 'book']);
        return view('bookings.show', compact('booking'));
    }

    // Proses Konfirmasi Booking → Jadi Peminjaman Resmi
    public function confirm(Request $request, Booking $booking)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        if ($booking->status !== 'menunggu') {
            return back()->withErrors(['error' => 'Booking sudah diproses.']);
        }

        // 1. Buat Loan baru
        $loan = Loan::create([
            'kode_pinjam' => 'LPN-' . Str::upper(Str::random(8)),
            'member_id' => $request->member_id,
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'tanggal_pinjam' => Carbon::today(),
            'tanggal_kembali' => Carbon::today()->addDays(7),
            'status' => 'dipinjam',
        ]);

        // 2. Buat LoanDetail
        LoanDetail::create([
            'loan_id' => $loan->id,
            'book_id' => $booking->book_id,
            'jumlah' => 1,
            'kondisi' => 'baik',
        ]);

        // 3. Update Booking
        $booking->update([
            'status' => 'dipinjam',
            'confirmed_by' => \Illuminate\Support\Facades\Auth::id(),
            'confirmed_at' => now(),
            'loan_id' => $loan->id,
        ]);

        // 4. Kurangi counter dibooking (stok sudah dikurangi saat booking)
        $booking->book->decrement('dibooking', 1);

        \App\Models\ActivityLog::record('loan', "Mengkonfirmasi booking menjadi peminjaman: {$booking->book->judul}", $loan);

        return redirect()->route('bookings.index')->with('success', 'Booking dikonfirmasi menjadi peminjaman resmi.');
    }

    // Tolak/Batalkan Booking (oleh Admin/Petugas)
    public function reject(Booking $booking)
    {
        if ($booking->status !== 'menunggu') {
            return back()->withErrors(['error' => 'Booking sudah diproses.']);
        }

        // Kembalikan stok
        $booking->book->increment('tersedia', 1);
        $booking->book->decrement('dibooking', 1);

        $booking->update(['status' => 'dibatalkan']);

        return redirect()->route('bookings.index')->with('success', 'Booking ditolak/dibatalkan.');
    }
}