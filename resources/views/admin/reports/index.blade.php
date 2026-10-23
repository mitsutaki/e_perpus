@extends('layouts.app')

@section('title', 'Laporan Perpustakaan')
@section('header_title', 'Dashboard Laporan')

@section('content')
<!-- ============ CSS KHUSUS PRINT (PDF) ============ -->
<style media="print">
    @page { size: A4; margin: 1.5cm; }
    body { background: #fff !important; font-family: 'Inter', sans-serif !important; color: #000 !important; font-size: 11pt; }
    aside, header { display: none !important; }
    main { margin: 0 !important; padding: 0 !important; }
    .no-print { display: none !important; }
    .glass, .glass-card { border: none !important; background: #fff !important; backdrop-filter: none !important; box-shadow: none !important; }
    .print-header { display: block !important; }
    .print-footer { display: block !important; }
    .report-section { page-break-inside: avoid; }
    .stat-card { border: 1px solid #ccc !important; border-radius: 8px !important; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000 !important; padding: 6px 10px !important; font-size: 10pt; }
    th { background: #f0f0f0 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .text-right-print { text-align: right; }
    .text-center-print { text-align: center; }
    canvas { max-width: 100%; height: auto; }
    .page-break { page-break-before: always; }
</style>

<style>
    /* Hidden saat layar, tampil saat print */
    .print-header, .print-footer { display: none; }
    
    @media screen {
        .report-section { padding: 0; }
    }
</style>

<div class="space-y-6">

    <!-- ============ HEADER PRINT (KOP SURAT) ============ -->
    <div class="print-header" style="display: none;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px double #000; padding-bottom: 12px; margin-bottom: 24px;">
            <div style="display: flex; gap: 16px; align-items: center;">
                <div style="width: 60px; height: 60px; background: #1e293b; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <span style="color: #fff; font-size: 24px; font-weight: bold;">P</span>
                </div>
                <div>
                    <h1 style="font-size: 20px; font-weight: bold; margin: 0; color: #000;">{{ $namaPerpus }}</h1>
                    <p style="font-size: 11px; margin: 4px 0 0 0; color: #555;">Sistem Informasi Manajemen Perpustakaan Digital</p>
                    <p style="font-size: 10px; margin: 2px 0 0 0; color: #777;">Dicetak pada: {{ now()->format('d M Y, H:i') }} WIB</p>
                </div>
            </div>
            <div style="text-align: right; font-size: 10px; color: #555;">
                <p style="margin: 0;"><strong>LAPORAN PERPUSTAKAAN</strong></p>
                <p style="margin: 2px 0 0 0;">Periode: {{ now()->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- ============ HEADER LAYAR (NO PRINT) ============ -->
    <div class="no-print flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-light tracking-tight">Laporan Komprehensif</h2>
            <p class="text-gray-500 text-sm mt-1">Rekapitulasi data perpustakaan secara real-time.</p>
        </div>
        <button onclick="window.print()" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm hover:bg-gray-800 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export PDF
        </button>
    </div>

    <!-- ============ SECTION 1: RINGKASAN STATISTIK ============ -->
    <div class="report-section">
        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3 no-print">📊 Ringkasan Statistik</h3>
        <h3 class="print-header" style="font-size: 13pt; font-weight: bold; margin: 16px 0 8px 0;">A. Ringkasan Statistik Perpustakaan</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="stat-card glass border border-white/60 rounded-2xl p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-gray-500 font-medium no-print">Total Buku</p>
                <p class="print-header" style="font-size: 9pt; color: #555; margin: 0 0 4px 0;">Total Buku</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-2 no-print">{{ $totalBooks }}</h3>
                <p class="print-header" style="font-size: 18pt; font-weight: bold; margin: 0;">{{ $totalBooks }}</p>
                <div class="mt-2 text-xs">
                    <p class="text-green-600">Baik: {{ $baikBooks }}</p>
                    <p class="text-red-600">Rusak: {{ $rusakBooks }}</p>
                </div>
            </div>
            <div class="stat-card glass border border-white/60 rounded-2xl p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-gray-500 font-medium no-print">Total Anggota</p>
                <p class="print-header" style="font-size: 9pt; color: #555; margin: 0 0 4px 0;">Total Anggota Aktif</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-2 no-print">{{ $totalMembers }}</h3>
                <p class="print-header" style="font-size: 18pt; font-weight: bold; margin: 0;">{{ $totalMembers }}</p>
                <p class="text-xs text-gray-400 mt-2">Anggota terdaftar</p>
            </div>
            <div class="stat-card glass border border-white/60 rounded-2xl p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-gray-500 font-medium no-print">Denda Belum Lunas</p>
                <p class="print-header" style="font-size: 9pt; color: #555; margin: 0 0 4px 0;">Denda Belum Lunas</p>
                <h3 class="text-2xl font-bold text-red-600 mt-2 no-print">Rp {{ number_format($unpaidFines, 0, ',', '.') }}</h3>
                <p class="print-header" style="font-size: 14pt; font-weight: bold; color: #dc2626; margin: 0;">Rp {{ number_format($unpaidFines, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-2">Tunggakan anggota</p>
            </div>
            <div class="stat-card glass border border-white/60 rounded-2xl p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-gray-500 font-medium no-print">Denda Lunas</p>
                <p class="print-header" style="font-size: 9pt; color: #555; margin: 0 0 4px 0;">Denda Lunas</p>
                <h3 class="text-2xl font-bold text-green-600 mt-2 no-print">Rp {{ number_format($paidFines, 0, ',', '.') }}</h3>
                <p class="print-header" style="font-size: 14pt; font-weight: bold; color: #16a34a; margin: 0;">Rp {{ number_format($paidFines, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-400 mt-2">Pemasukan denda</p>
            </div>
        </div>
    </div>

    <!-- ============ SECTION 2: GRAFIK TREN ============ -->
    <div class="report-section page-break">
        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3 no-print">📈 Tren Transaksi (4 Minggu Terakhir)</h3>
        <h3 class="print-header" style="font-size: 13pt; font-weight: bold; margin: 16px 0 8px 0;">B. Tren Transaksi 4 Minggu Terakhir</h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="glass border border-white/60 rounded-2xl p-5 shadow-sm">
                <h4 class="font-semibold text-gray-800 mb-3 no-print">📈 Peminjaman</h4>
                <h4 class="print-header" style="font-size: 11pt; font-weight: bold; margin: 0 0 8px 0;">Peminjaman per Minggu</h4>
                <canvas id="loansChart" height="150"></canvas>
                <!-- Image placeholder buat PDF -->
                <img id="loansChartImg" style="display:none; max-width: 100%;">
            </div>
            <div class="glass border border-white/60 rounded-2xl p-5 shadow-sm">
                <h4 class="font-semibold text-gray-800 mb-3 no-print">↩️ Pengembalian</h4>
                <h4 class="print-header" style="font-size: 11pt; font-weight: bold; margin: 0 0 8px 0;">Pengembalian per Minggu</h4>
                <canvas id="returnsChart" height="150"></canvas>
                <img id="returnsChartImg" style="display:none; max-width: 100%;">
            </div>
        </div>
    </div>

    <!-- ============ SECTION 3: BUKU POPULER ============ -->
    <div class="report-section">
        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3 no-print">🔥 Buku Paling Populer</h3>
        <h3 class="print-header" style="font-size: 13pt; font-weight: bold; margin: 16px 0 8px 0;">C. Top 5 Buku Paling Sering Dipinjam</h3>
        
        <div class="glass border border-white/60 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/40 no-print">
                        <th class="py-3 px-4 text-xs uppercase tracking-wider text-gray-500 font-medium">Peringkat</th>
                        <th class="py-3 px-4 text-xs uppercase tracking-wider text-gray-500 font-medium">Judul Buku</th>
                        <th class="py-3 px-4 text-xs uppercase tracking-wider text-gray-500 font-medium">Kategori</th>
                        <th class="py-3 px-4 text-xs uppercase tracking-wider text-gray-500 font-medium text-right">Total Dipinjam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/50">
                    @foreach($popularBooks as $index => $book)
                    <tr class="hover:bg-white/50 transition">
                        <td class="py-3 px-4 text-sm font-bold text-gray-700 text-center-print">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 text-sm font-medium text-gray-800">{{ $book->judul }}</td>
                        <td class="py-3 px-4 text-sm text-gray-500">{{ $book->category->nama_kategori ?? '-' }}</td>
                        <td class="py-3 px-4 text-sm text-gray-700 text-right-print">{{ $book->loan_details_count }}x</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============ FOOTER PRINT (Tanda Tangan) ============ -->
    <div class="print-footer" style="display: none; margin-top: 60px;">
        <div style="display: flex; justify-content: flex-end;">
            <div style="text-align: center; min-width: 250px;">
                <p style="margin: 0 0 4px 0;">Diterbitkan oleh,</p>
                <p style="margin: 0 0 60px 0;">{{ $namaPerpus }}</p>
                <p style="margin: 0; border-top: 1px solid #000; padding-top: 4px; font-weight: bold;">Admin Perpustakaan</p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 30px; font-size: 9pt; color: #777; border-top: 1px solid #ccc; padding-top: 8px;">
            <p>Halaman ini dicetak secara otomatis oleh Sistem {{ $namaPerpus }} · {{ now()->format('d M Y H:i') }}</p>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const weeks = @json($weeks);
    const loansData = @json($loansData);
    const returnsData = @json($returnsData);

    // Chart Peminjaman
    const ctxLoans = document.getElementById('loansChart').getContext('2d');
    const loansChart = new Chart(ctxLoans, {
        type: 'bar',
        data: {
            labels: weeks,
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: loansData,
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: { 
            responsive: true, 
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { legend: { display: false } }
        }
    });

    // Chart Pengembalian
    const ctxReturns = document.getElementById('returnsChart').getContext('2d');
    const returnsChart = new Chart(ctxReturns, {
        type: 'line',
        data: {
            labels: weeks,
            datasets: [{
                label: 'Jumlah Pengembalian',
                data: returnsData,
                fill: true,
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                borderColor: 'rgba(16, 185, 129, 1)',
                tension: 0.3,
                pointRadius: 5,
                pointBackgroundColor: 'rgba(16, 185, 129, 1)'
            }]
        },
        options: { 
            responsive: true, 
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { legend: { display: false } }
        }
    });

    // 🔥 Sebelum print: convert chart ke image biar tampil di PDF
    window.addEventListener('beforeprint', function() {
        document.getElementById('loansChartImg').src = loansChart.toBase64Image();
        document.getElementById('loansChartImg').style.display = 'block';
        document.getElementById('loansChart').style.display = 'none';

        document.getElementById('returnsChartImg').src = returnsChart.toBase64Image();
        document.getElementById('returnsChartImg').style.display = 'block';
        document.getElementById('returnsChart').style.display = 'none';
    });

    // 🔥 Setelah print: balikin ke canvas
    window.addEventListener('afterprint', function() {
        document.getElementById('loansChartImg').style.display = 'none';
        document.getElementById('loansChart').style.display = 'block';
        document.getElementById('returnsChartImg').style.display = 'none';
        document.getElementById('returnsChart').style.display = 'block';
    });
</script>
@endsection