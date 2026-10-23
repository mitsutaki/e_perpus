<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'petugas', 'anggota') NOT NULL DEFAULT 'petugas'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'petugas') NOT NULL DEFAULT 'petugas'");
    }
};