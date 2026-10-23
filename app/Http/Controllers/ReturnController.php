<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\BookReturn;
use App\Models\Fine;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = BookReturn::with(['loan.member', 'user'])->latest()->get();
        return view('returns.index', compact('returns'));
    }

    public function create()
    {
        $loans = Loan::where('status', 'dipinjam')->with(['member', 'details.book'])->get();
        $dendaPerHari = (int) (Setting::where('key', 'denda_per_hari')->value('value') ?? 1000);

        $loansData = $loans->map(function ($loan) use ($dendaPerHari) {
            $batas = Carbon::parse($loan->tanggal_kembali)->startOfDay();
            $hariIni = now()->startOfDay();
            $terlambat = $hariIni->gt($batas) ? (int) $hariIni->diffInDays($batas) : 0;

            return [
                'id'             => $loan->id,
                'kode'           => $loan->kode_pinjam,
                'member'         => $loan->member->nama ?? '-',
                'batas'          => $batas->format('d M Y'),
                'terlambat'      => $terlambat,
                'estimasi_denda' => $terlambat * $dendaPerHari,
                'books'          => $loan->details->map(fn ($d) => [
                    'judul'  => $d->book->judul ?? '-',
                    'jumlah' => $d->jumlah,
                ])->all(),
            ];
        });

        return view('returns.create', compact('loansData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
        ]);

        $loan = Loan::with('details.book')->findOrFail($request->loan_id);

        $tanggalKembaliAktual = Carbon::today();
        $batasKembali = Carbon::parse($loan->tanggal_kembali);
        
        $terlambatHari = 0;
        if ($tanggalKembaliAktual > $batasKembali) {
            $terlambatHari = $tanggalKembaliAktual->diffInDays($batasKembali);
        }

        $dendaPerHari = (int) (Setting::where('key', 'denda_per_hari')->value('value') ?? 1000);
        $denda = $terlambatHari * $dendaPerHari;

        BookReturn::create([
            'kode_kembali' => 'KBL-' . Str::upper(Str::random(8)),
            'loan_id' => $loan->id,
            'user_id' => $request->user()?->getAuthIdentifier(),
            'tanggal_kembali' => $tanggalKembaliAktual,
            'terlambat_hari' => $terlambatHari,
            'denda' => $denda,
        ]);

        foreach ($loan->details as $detail) {
            $detail->book->increment('tersedia', $detail->jumlah);
        }

         $loan->update([
    'status' => 'dikembalikan',
    'tanggal_dikembalikan' => $tanggalKembaliAktual,
            ]);

            // TAMBAHKAN INI: Update booking jadi 'selesai' jika ada
            $booking = \App\Models\Booking::where('loan_id', $loan->id)->first();
            if ($booking) {
                $booking->update(['status' => 'selesai']);
            }

        if ($denda > 0) {
            Fine::create([
                'kode_denda' => 'DND-' . Str::upper(Str::random(8)),
                'member_id' => $loan->member_id,
                'loan_id' => $loan->id,
                'jumlah' => $denda,
                'keterangan' => "Terlambat {$terlambatHari} hari",
                'status' => 'belum_bayar',
            ]);
        }

        ActivityLog::record('return', "Menerima pengembalian dari {$loan->member->nama}" . ($denda > 0 ? " (Denda Rp " . number_format($denda) . ")" : ""));

        return redirect()->route('returns.index')->with('success', "Buku berhasil dikembalikan. Keterlambatan: {$terlambatHari} hari. Denda: Rp " . number_format($denda));
    }
}