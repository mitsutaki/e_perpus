<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{
    protected $fillable = ['loan_id', 'book_id', 'jumlah', 'kondisi'];

    public function book() { return $this->belongsTo(Book::class); }
    
    // TAMBAHKAN RELASI INI
    public function loan() { return $this->belongsTo(Loan::class); }
}