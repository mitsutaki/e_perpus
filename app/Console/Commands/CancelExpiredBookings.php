<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\ActivityLog;
use Illuminate\Console\Command;

class CancelExpiredBookings extends Command
{
    protected $signature = 'bookings:cancel-expired';
    protected $description = 'Batalkan otomatis booking yang lewat batas 24 jam';

    public function handle()
    {
        $expiredBookings = Booking::where('status', 'menunggu')
            ->where('batas_ambil', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredBookings as $booking) {
            // Kembalikan stok
            $booking->book->increment('tersedia', 1);
            $booking->book->decrement('dibooking', 1);
            $booking->update(['status' => 'dibatalkan']);
            $count++;
        }

        if ($count > 0) {
            ActivityLog::record('system', "Auto-cancel {$count} booking kedaluwarsa");
        }

        $this->info("Berhasil membatalkan {$count} booking kedaluwarsa.");
    }
}