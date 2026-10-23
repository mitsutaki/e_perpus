<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Loan;
use App\Models\BookReturn;
use App\Models\Fine;
// Request not used in this controller
use Carbon\Carbon;

class ReportController extends Controller
{
        public function index()
    {
        $totalBooks = Book::sum('jumlah');
        $rusakBooks = Book::sum('jumlah_rusak');
        $baikBooks = $totalBooks - $rusakBooks;
        $totalMembers = Member::count();

        $weeks = [];
        $loansData = [];
        $returnsData = [];

        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();

            $weeks[] = "Minggu-" . $startOfWeek->weekOfYear;
            $loansData[] = Loan::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
            $returnsData[] = BookReturn::whereBetween('tanggal_kembali', [$startOfWeek, $endOfWeek])->count();
        }

        $unpaidFines = Fine::where('status', 'belum_bayar')->sum('jumlah');
        $paidFines = Fine::where('status', 'lunas')->sum('jumlah');

        // Ambil info perpus dari settings
        $namaPerpus = \App\Models\Setting::where('key', 'nama_perpustakaan')->value('value') ?? 'Perpus Digital';
        $dendaPerHari = \App\Models\Setting::where('key', 'denda_per_hari')->value('value') ?? 1000;

        // Ambil 5 buku paling populer (paling banyak dipinjam)
        $popularBooks = Book::withCount(['loanDetails' => function($q) {
                $q->whereHas('loan', function($q2) {
                    $q2->where('status', 'dipinjam')->orWhere('status', 'dikembalikan');
                });
            }])
            ->with('category')
            ->orderByDesc('loan_details_count')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact(
            'totalBooks', 'rusakBooks', 'baikBooks', 'totalMembers', 
            'weeks', 'loansData', 'returnsData', 'unpaidFines', 'paidFines',
            'namaPerpus', 'dendaPerHari', 'popularBooks'
        ));
    }
}