<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Models\BookReturn;
use App\Models\Fine;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Memulai generate data dummy...');
        
        // Data master dummy (kalau belum ada)
        if (Book::count() < 10) {
            $this->seedMasterData();
        }
        
        // Generate 100 Anggota + Akun Login
        $this->seedMembers();
        
        // Generate 100 Buku Random
        $this->seedBooks();
        
        // Generate Transaksi Peminjaman (50 transaksi)
        $this->seedLoans();
        
        // Generate Pengembalian + Denda (20 transaksi)
        $this->seedReturns();
        
        // Generate Bookings (15 booking)
        $this->seedBookings();
        
        $this->command->info('✅ Generate data dummy selesai!');
    }
    
    private function seedMasterData()
    {
        $this->command->info('📦 Generate master data...');
        
        $categories = \App\Models\Category::all();
        $publishers = \App\Models\Publisher::all();
        $shelves = \App\Models\Shelf::all();
        
        if ($categories->isEmpty()) {
            for ($i = 1; $i <= 5; $i++) {
                \App\Models\Category::create([
                    'kode_kategori' => 'KAT-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'nama_kategori' => ['Fiksi', 'Non-Fiksi', 'Sains', 'Sejarah', 'Anak-Anak'][$i-1],
                ]);
            }
        }
        
        if ($publishers->isEmpty()) {
            for ($i = 1; $i <= 3; $i++) {
                \App\Models\Publisher::create([
                    'kode_penerbit' => 'PBT-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'nama_penerbit' => ['Gramedia', 'Mizan', 'Erlangga'][$i-1],
                ]);
            }
        }
        
        if ($shelves->isEmpty()) {
            for ($i = 1; $i <= 4; $i++) {
                \App\Models\Shelf::create([
                    'kode_rak' => 'RAK-' . chr(64+$i) . '01',
                    'nama_rak' => 'Rak ' . ['Ilmu Pengetahuan', 'Sastra', 'Sejarah', 'Anak-Anak'][$i-1],
                    'tipe' => $i % 2 == 0 ? 'etalase' : 'rak',
                    'kapasitas' => 50,
                    'lokasi' => 'Lantai ' . $i,
                ]);
            }
        }
    }
    
    private function seedMembers()
    {
        $this->command->info('👥 Generate 100 anggota...');
        
        $namaDepan = ['Andi', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gilang', 'Hana', 'Indra', 'Jihan', 'Krisna', 'Lina', 'Maman', 'Nisa', 'Oka', 'Putri', 'Rama', 'Sari', 'Tono', 'Umar', 'Vera', 'Wahyu', 'Yoga', 'Zahra'];
        $namaBelakang = ['Saputra', 'Wijaya', 'Pratama', 'Lestari', 'Hidayat', 'Maulana', 'Susanto', 'Rahmawati', 'Gunawan', 'Permata', 'Halim', 'Anggraini', 'Wibowo', 'Setiawan', 'Ningrum'];
        
        $jenisKelamin = ['L', 'P'];
        
        for ($i = 1; $i <= 100; $i++) {
            $nama = $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)];
            $email = 'anggota' . $i . '@test.com';
            
            // Cek apakah email sudah ada
            if (User::where('email', $email)->exists()) continue;
            
            $user = User::create([
                'name' => $nama,
                'username' => 'anggota' . $i,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'anggota',
                'has_seen_tour' => true, // skip tour biar gak ganggu testing
            ]);
            
            Member::create([
                'user_id' => $user->id,
                'kode_member' => 'MBR-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama' => $nama,
                'nis_nip' => '2024' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'jenis_kelamin' => $jenisKelamin[array_rand($jenisKelamin)],
                'alamat' => 'Jl. Test No. ' . $i . ', Jakarta',
                'telepon' => '08' . rand(1000000000, 9999999999),
                'email' => $email,
                'tanggal_daftar' => Carbon::now()->subDays(rand(1, 365)),
                'status' => rand(1, 10) > 1 ? 'aktif' : 'nonaktif', // 90% aktif, 10% nonaktif
            ]);
        }
        
        $this->command->info('✅ 100 anggota berhasil dibuat (password: password)');
    }
    
    private function seedBooks()
    {
        $this->command->info('📚 Generate 100 buku...');
        
        $judulKata1 = ['Bumi', 'Lautan', 'Mentari', 'Bintang', 'Rahasia', 'Petualangan', 'Kisah', 'Pencarian', 'Hutan', 'Langit', 'Cinta', 'Persahabatan', 'Mimpi', 'Waktu', 'Bayangan', 'Pilihan', 'Jalan', 'Cerah', 'Senja', 'Fajar'];
        $judulKata2 = ['di', 'dan', 'yang', 'untuk', 'menuju', 'tentang', 'ke', 'dari', 'akan', 'meraih'];
        $judulKata3 = ['Kita', 'Mereka', 'Esok', 'Surga', 'Cinta', 'Impian', 'Masa Depan', 'Hidup', 'Sejati', 'Selamanya'];
        
        $categories = \App\Models\Category::pluck('id');
        $publishers = \App\Models\Publisher::pluck('id');
        $shelves = \App\Models\Shelf::pluck('id');
        
        // Hitung offset buku yang sudah ada biar kode unik
        $existingCount = Book::count();
        
        for ($i = 1; $i <= 100; $i++) {
            $judul = $judulKata1[array_rand($judulKata1)] . ' ' . $judulKata2[array_rand($judulKata2)] . ' ' . $judulKata3[array_rand($judulKata3)];
            
            $jumlah = rand(1, 15);
            $dipinjam = rand(0, $jumlah - 1);
            $dibooking = rand(0, $jumlah - $dipinjam - 1);
            $tersedia = $jumlah - $dipinjam - $dibooking;
            
            Book::create([
                'kode_buku' => 'BK-' . str_pad($existingCount + $i, 4, '0', STR_PAD_LEFT),
                'judul' => $judul,
                'isbn' => '978-' . rand(1000000000, 9999999999),
                'category_id' => $categories->random(),
                'publisher_id' => $publishers->random(),
                'shelf_id' => $shelves->random(),
                'tahun_terbit' => rand(2010, 2024),
                'jumlah' => $jumlah,
                'tersedia' => $tersedia,
                'dibooking' => $dibooking,
                'jumlah_rusak' => rand(0, 2),
                'deskripsi' => 'Buku ini bercerita tentang ' . $judul . '. Sebuah karya yang menginspirasi pembacanya untuk merenungkan makna kehidupan.',
            ]);
        }
        
        $this->command->info('✅ 100 buku berhasil dibuat');
    }
    
    private function seedLoans()
    {
        $this->command->info('📅 Generate 50 transaksi peminjaman...');
        
        $members = Member::where('status', 'aktif')->pluck('id');
        $books = Book::where('tersedia', '>', 0)->pluck('id');
        $admin = User::where('role', 'admin')->first() ?? User::where('role', 'petugas')->first();
        
        for ($i = 0; $i < 50; $i++) {
            $memberId = $members->random();
            $bookId = $books->random();
            
            $tanggalPinjam = Carbon::now()->subDays(rand(1, 20));
            
            // 70% masih dipinjam, 30% sudah dikembalikan
            $isReturned = rand(1, 10) <= 3;
            
            $loan = Loan::create([
                'kode_pinjam' => 'LPN-' . Str::upper(Str::random(8)),
                'member_id' => $memberId,
                'user_id' => $admin->id,
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_kembali' => $tanggalPinjam->copy()->addDays(7),
                'tanggal_dikembalikan' => $isReturned ? $tanggalPinjam->copy()->addDays(rand(5, 12)) : null,
                'status' => $isReturned ? 'dikembalikan' : 'dipinjam',
            ]);
            
            LoanDetail::create([
                'loan_id' => $loan->id,
                'book_id' => $bookId,
                'jumlah' => 1,
                'kondisi' => 'baik',
            ]);
            
            // Kalau masih dipinjam, kurangi stok tersedia
            if (!$isReturned) {
                Book::where('id', $bookId)->decrement('tersedia', 1);
            }
        }
        
        $this->command->info('✅ 50 transaksi peminjaman dibuat');
    }
    
    private function seedReturns()
    {
        $this->command->info('↩️ Generate 20 pengembalian + denda...');
        
        // Ambil loan yang statusnya dikembalikan
        $loans = Loan::where('status', 'dikembalikan')->with('details.book')->take(20)->get();
        $admin = User::where('role', 'admin')->first() ?? User::where('role', 'petugas')->first();
        
        foreach ($loans as $loan) {
            $batas = Carbon::parse($loan->tanggal_kembali);
            $actual = Carbon::parse($loan->tanggal_dikembalikan);
            $terlambat = $actual->gt($batas) ? abs($actual->diffInDays($batas)) : 0;
            
            $denda = $terlambat * 1000;
            
            BookReturn::create([
                'kode_kembali' => 'KBL-' . Str::upper(Str::random(8)),
                'loan_id' => $loan->id,
                'user_id' => $admin->id,
                'tanggal_kembali' => $actual,
                'terlambat_hari' => $terlambat,
                'denda' => $denda,
            ]);
            
            if ($denda > 0) {
                Fine::create([
                    'kode_denda' => 'DND-' . Str::upper(Str::random(8)),
                    'member_id' => $loan->member_id,
                    'loan_id' => $loan->id,
                    'jumlah' => $denda,
                    'keterangan' => "Terlambat {$terlambat} hari",
                    'status' => rand(1, 10) > 3 ? 'belum_bayar' : 'lunas', // 70% belum bayar
                    'tanggal_bayar' => null,
                ]);
            }
        }
        
        $this->command->info('✅ 20 pengembalian + denda dibuat');
    }
    
    private function seedBookings()
    {
        $this->command->info('📥 Generate 15 booking...');
        
        $members = User::where('role', 'anggota')->pluck('id');
        $books = Book::where('tersedia', '>', 0)->pluck('id');
        
        for ($i = 0; $i < 15; $i++) {
            $userId = $members->random();
            $bookId = $books->random();
            
            // Cek duplikat
            $exists = Booking::where('user_id', $userId)
                ->where('book_id', $bookId)
                ->whereIn('status', ['menunggu', 'dikonfirmasi'])
                ->exists();
            if ($exists) continue;
            
            $tanggalBooking = Carbon::now()->subHours(rand(1, 48));
            $batasAmbil = $tanggalBooking->copy()->addHours(24);
            
            // 60% menunggu, 20% dikonfirmasi, 20% dibatalkan
            $rand = rand(1, 10);
            $status = $rand <= 6 ? 'menunggu' : ($rand <= 8 ? 'dikonfirmasi' : 'dibatalkan');
            
            Booking::create([
                'kode_booking' => 'BKG-' . Str::upper(Str::random(8)),
                'user_id' => $userId,
                'book_id' => $bookId,
                'tanggal_booking' => $tanggalBooking,
                'batas_ambil' => $batasAmbil,
                'status' => $status,
            ]);
        }
        
        $this->command->info('✅ 15 booking dibuat');
    }
}