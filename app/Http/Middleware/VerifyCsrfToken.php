<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    // Tambah ini biar token CSRF awet 12 jam (default cuma 2 jam)
    protected $expires = 43200;
}