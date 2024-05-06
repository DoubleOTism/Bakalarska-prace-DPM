<?php

// app/Console/Commands/SendLowStockAlert.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;
use App\Notifications\lowStockNotification;

class SendLowStockAlert extends Command
{
    // Správný název příkazu
    protected $signature = 'SendLowStockAlert';

    public function handle()
    {
        $email = Setting::get('low_stock_alert_email');
        if (!$email) {
            $this->error('Nebylo možné získat e-mail z nastavení.');
            return;
        }

        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            $this->error('Uživatel s tímto e-mailem nebyl nalezen.');
            return;
        }

        $products = \App\Models\Product::with('stores')
            ->whereHas('stores', function ($query) {
                $query->where('store_products.keep_track', 1)
                    ->whereColumn('store_products.quantity', '<=', 'store_products.minimum_quantity_alert');
            })->get();

        if ($products->isEmpty()) {
            $this->info('Žádné produkty s nízkými zásobami.');
            return;
        }

        $user->notify(new lowStockNotification($products));

        $this->info('Notifikace upozornění na nízké zásoby byla úspěšně odeslána.');
    }
}


