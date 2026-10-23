<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'kode_booking', 'user_id', 'book_id', 'tanggal_booking', 'batas_ambil', 
        'status', 'confirmed_by', 'confirmed_at', 'loan_id', 'catatan'
    ];

    protected $casts = [
        'tanggal_booking' => 'datetime',
        'batas_ambil' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}