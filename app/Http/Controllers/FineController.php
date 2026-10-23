<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class FineController extends Controller
{
    public function index()
    {
        $fines = Fine::with(['member', 'loan'])->latest()->get();
        return view('fines.index', compact('fines'));
    }

    public function update(Request $request, Fine $fine)
    {
        // Proses pelunasan denda
        $fine->update([
            'status' => 'lunas',
            'tanggal_bayar' => now(),
        ]);

        ActivityLog::record('payment', "Melunasi denda {$fine->member->nama} sebesar Rp " . number_format($fine->jumlah));

        return redirect()->route('fines.index')->with('success', 'Denda berhasil dilunasi.');
    }
}