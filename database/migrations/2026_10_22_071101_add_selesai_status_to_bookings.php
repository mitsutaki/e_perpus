<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('menunggu', 'dikonfirmasi', 'dipinjam', 'dibatalkan', 'selesai') DEFAULT 'menunggu'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('menunggu', 'dikonfirmasi', 'dipinjam', 'dibatalkan') DEFAULT 'menunggu'");
    }
};