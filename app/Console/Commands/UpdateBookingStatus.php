<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;

class UpdateBookingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto update bookings from confirmed → in_use and in_use → completed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $confirmed = Booking::where('status', 'confirmed')
            ->whereDate('start_date', '<=', now())
            ->update(['status' => 'in_use']);

        // Step 2: Move in_use → completed
        $inUse = Booking::where('status', 'in_use')
            ->whereDate('end_date', '<=', now())
            ->update(['status' => 'completed', 'payment_status' => 'released']);

        $this->info("Updated: {$confirmed} bookings to in_use, {$inUse} bookings to completed.");
    }
}
