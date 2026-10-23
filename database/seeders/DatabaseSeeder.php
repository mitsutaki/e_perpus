<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Shelf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin', 'username' => 'superadmin', 'email' => 'superadmin@test.com',
            'password' => Hash::make('password'), 'role' => 'super_admin',
        ]);
        User::create([
            'name' => 'Admin Perpus', 'username' => 'admin', 'email' => 'admin@test.com',
            'password' => Hash::make('password'), 'role' => 'admin',
        ]);
        User::create([
            'name' => 'Petugas Loket', 'username' => 'petugas', 'email' => 'petugas@test.com',
            'password' => Hash::make('password'), 'role' => 'petugas',
        ]);

        Category::create(['kode_kategori' => 'KAT-001', 'nama_kategori' => 'Fiksi']);
        Category::create(['kode_kategori' => 'KAT-002', 'nama_kategori' => 'Non-Fiksi']);

        Publisher::create(['kode_penerbit' => 'PBT-001', 'nama_penerbit' => 'Gramedia']);
        Publisher::create(['kode_penerbit' => 'PBT-002', 'nama_penerbit' => 'Mizan']);

        Shelf::create(['kode_rak' => 'RAK-A01', 'nama_rak' => 'Rak Depan', 'tipe' => 'rak', 'kapasitas' => 50]);
        Shelf::create(['kode_rak' => 'RAK-B01', 'nama_rak' => 'Etalase VIP', 'tipe' => 'etalase', 'kapasitas' => 20]);
        
        $this->call(DummyDataSeeder::class);
    }
}