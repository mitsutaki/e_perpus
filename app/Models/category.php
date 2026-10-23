<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['kode_kategori', 'nama_kategori', 'deskripsi'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}