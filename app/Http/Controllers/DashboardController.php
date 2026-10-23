<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Fine;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
            public function index()
        {
            $user = Auth::user();

            // Anggota diarahkan ke katalog buku
            if ($user->role === 'anggota') {
                return redirect()->route('katalog.index');
            }

            // Super Admin diarahkan ke laporan
            if ($user->role === 'super_admin') {
                return redirect()->route('reports.index');
            }

            // ... kode admin/petugas tetap sama
            $totalBooks = Book::sum('jumlah');
            $totalMembers = Member::count();
            $activeLoans = Loan::where('status', 'dipinjam')->count();
            $unpaidFines = Fine::where('status', 'belum_bayar')->sum('jumlah');

            $recentActivities = ActivityLog::with('user')->latest()->take(10)->get();
            $showTour = !$user->has_seen_tour;

            if ($user->role === 'admin') {
                return view('admin.dashboard.index', compact(
                    'totalBooks', 'totalMembers', 'activeLoans', 'unpaidFines', 'recentActivities', 'showTour'
                ));
            }

            return view('petugas.dashboard.index', compact(
                'totalBooks', 'totalMembers', 'activeLoans', 'unpaidFines', 'recentActivities', 'showTour'
            ));
        }
}