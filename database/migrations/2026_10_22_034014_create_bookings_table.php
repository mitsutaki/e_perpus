<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // anggota
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->dateTime('tanggal_booking');
            $table->dateTime('batas_ambil'); // +24 jam
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'dipinjam', 'dibatalkan'])->default('menunggu');
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('confirmed_at')->nullable();
            $table->foreignId('loan_id')->nullable()->constrained()->onDelete('set null');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};