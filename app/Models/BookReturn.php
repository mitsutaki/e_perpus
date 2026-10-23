<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BookReturn extends Model
{
    // Karena tabel di migration bernama 'returns'
    protected $table = 'returns';

    protected $fillable = [
        'kode_kembali', 'loan_id', 'user_id', 'tanggal_kembali', 'terlambat_hari', 'denda', 'catatan'
    ];

    public function loan() { return $this->belongsTo(Loan::class); }
    public function user() { return $this->belongsTo(User::class); }
}