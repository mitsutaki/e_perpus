<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\Member;
use App\Models\Book;
use App\Models\Fine;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\ActivityLog;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['member', 'user'])->latest()->get();
        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $members = Member::where('status', 'aktif')->get();
        $books = Book::where('tersedia', '>', 0)->get();
        $maxBooks = (int) (Setting::where('key', 'maksimal_jumlah_buku')->value('value') ?? 3);
        $maxDays = (int) (Setting::where('key', 'maksimal_hari_pinjam')->value('value') ?? 7);
        $dueDate = Carbon::today()->addDays($maxDays)->format('d M Y');

        // Siapkan data buku untuk JavaScript (format array sederhana)
        $booksJson = $books->map(function($b) {
            return [
                'id' => $b->id,
                'judul' => $b->judul,
                'kode_buku' => $b->kode_buku,
                'tersedia' => $b->tersedia,
            ];
        })->toJson();

        return view('loans.create', compact('members', 'books', 'maxBooks', 'dueDate', 'booksJson'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'books' => 'required|array|min:1',
            'books.*' => 'exists:books,id'
        ]);

        $member = Member::find($request->member_id);

        // 1. Validasi: Cek apakah anggota aktif
        if ($member->status !== 'aktif') {
            return back()->withErrors(['member_id' => 'Anggota ini tidak aktif.'])->withInput();
        }

        // 2. Validasi: Cek apakah anggota memiliki denda belum lunas
        $unpaidFines = Fine::where('member_id', $member->id)->where('status', 'belum_bayar')->count();
        if ($unpaidFines > 0) {
            return back()->withErrors(['member_id' => 'Anggota ini memiliki ' . $unpaidFines . ' denda belum lunas. Harap lunasi denda terlebih dahulu.'])->withInput();
        }

        // 3. Validasi: Cek batas maksimal peminjaman buku
        $maksimalBuku = Setting::where('key', 'maksimal_jumlah_buku')->value('value') ?? 3;
        
        $sedangDipinjam = LoanDetail::whereHas('loan', function($query) use ($member) {
            $query->where('member_id', $member->id)->where('status', 'dipinjam');
        })->sum('jumlah');
        
        $totalAkanPinjam = $sedangDipinjam + count($request->books);

        if ($totalAkanPinjam > $maksimalBuku) {
            return back()->withErrors([
                'books' => "Anggota ini sudah meminjam {$sedangDipinjam} buku. Maksimal peminjaman adalah {$maksimalBuku} buku. Anda tidak bisa meminjam " . count($request->books) . " buku sekaligus."
            ])->withInput();
        }

        // Ambil durasi pinjam dari settings (default 7 hari)
        $maksimalHari = Setting::where('key', 'maksimal_hari_pinjam')->value('value') ?? 7;
        
        $tanggalPinjam = Carbon::today();
        $tanggalKembali = Carbon::today()->addDays($maksimalHari);

        // Buat Header Pinjaman
        $loan = Loan::create([
            'kode_pinjam' => 'LPN-' . Str::upper(Str::random(8)),
            'member_id' => $request->member_id,
            'user_id' => Auth::id(),
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status' => 'dipinjam',
        ]);

        // Buat Detail Pinjaman dan Kurangi Stok
        foreach ($request->books as $bookId) {
            $book = Book::find($bookId);
            
            if ($book && $book->tersedia > 0) {
                LoanDetail::create([
                    'loan_id' => $loan->id,
                    'book_id' => $bookId,
                    'jumlah' => 1,
                    'kondisi' => 'baik'
                ]);

                $book->decrement('tersedia', 1);
            }
        }


         $bookTitles = Book::whereIn('id', $request->books)->pluck('judul')->implode(', ');
                ActivityLog::record('loan', "Meminjamkan buku ke {$member->nama}: {$bookTitles}", $loan);
        return redirect()->route('loans.index')->with('success', 'Transaksi peminjaman berhasil dibuat. Batas pengembalian: ' . $tanggalKembali->format('d M Y'));
    }

    public function show(Loan $loan)
    {
        $loan->load('details.book', 'member', 'user');
        return view('loans.show', compact('loan'));
    }

    public function destroy(Loan $loan)
    {
        // Jika dihapus, kembalikan stok buku
        foreach ($loan->details as $detail) {
            $detail->book->increment('tersedia', $detail->jumlah);
        }
        $loan->delete();
        ActivityLog::record('delete', "Menghapus transaksi pinjaman: {$loan->kode_pinjam}");
        return redirect()->route('loans.index')->with('success', 'Data pinjaman dihapus.');
    }
}