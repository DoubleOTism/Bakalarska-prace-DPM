<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Product;
use App\Models\AccessCode;
use App\Models\ShoppingSession;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Přenášení pending_discount do discount na začátku slevy
        $schedule->call(function () {
            Product::where('discount_from', '<=', now())
                ->where(function ($query) {
                    $query->whereNull('discount_to')
                        ->orWhere('discount_to', '>=', now());
                })
                ->whereNotNull('pending_discount')
                ->each(function ($product) {
                    $product->update([
                        'discount' => $product->pending_discount,
                        'pending_discount' => null,
                    ]);
                });
        })->everyMinute();

        // Resetování slev po skončení slevového období
        $schedule->call(function () {
            Product::where('discount_to', '<', now())
                ->update([
                    'discount' => null,
                    'discount_from' => null,
                    'discount_to' => null,
                ]);
        })->everyMinute();

        // Mazání přístupových kódů starších než 3 měsíce, pokud jsou označeny jako neaktivní, jak z db, tak z disku
        $schedule->call(function () {
            $threeMonthsAgo = now()->subMonths(3);

            AccessCode::where('active', false)
                ->where('created_at', '<', $threeMonthsAgo)
                ->each(function ($code) {
                    $path = str_replace('storage/', '', $code->qr_path);

                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }

                    $code->delete();
                });

        })->everyMinute()->name('delete-inactive-codes')->withoutOverlapping();


        // Mail o nizkych zasobach
        $frequency = Setting::get('low_stock_alert_frequency') ?? 1;
        $time = Setting::get('low_stock_alert_time') ?? '09:00';

        $schedule->command('send:low-stock-alert')
            ->dailyAt($time)
            ->when(fn() => (date('j') - 1) % $frequency == 0);




        // Ukončení všech aktivních sessions starších než 1 den 
        $schedule->call(function () {

            $inactiveSessions = ShoppingSession::where('active', true)
                ->where('started_at', '<', now()->subDay())
                ->get();

            foreach ($inactiveSessions as $session) {
                $session->update([
                    'status' => 'cancelled',
                    'ended_at' => now(),
                    'active' => false,
                ]);
            }
        })->daily()->name('cancel-old-sessions')->withoutOverlapping();
    }



    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
