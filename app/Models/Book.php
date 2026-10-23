<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_buku', 'judul', 'isbn', 'category_id', 'publisher_id', 
        'shelf_id', 'tahun_terbit', 'jumlah', 'tersedia', 'dibooking', 'jumlah_rusak', 'cover', 'deskripsi'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }

        // Relasi ke LoanDetail (buku ini dipinjam di transaksi mana saja)
    public function loanDetails()
    {
        return $this->hasMany(LoanDetail::class);
    }
}