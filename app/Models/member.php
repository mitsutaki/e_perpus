<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'kode_member', 'nama', 'nis_nip', 'jenis_kelamin', 'alamat', 'telepon', 'email', 'tanggal_daftar', 'status'
    ];

    // Relasi ke User (akun login anggota)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }
}