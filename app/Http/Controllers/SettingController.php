<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;

use Illuminate\Support\Facades\Mail;

use App\Notifications\lowStockNotification;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{

    public function index()
    {
        return view('fullViews/adminApp/settings');
    }
    public function showLowStockAlertSettings()
    {
        return view('fullViews/adminApp/lowQuantity', [
            'low_stock_email' => Setting::get('low_stock_alert_email') ?? '',
            'low_stock_frequency' => Setting::get('low_stock_alert_frequency') ?? '',
            'low_stock_time' => Setting::get('low_stock_alert_time') ?? ''
        ]);
    }

    public function updateLowStockAlert(Request $request)
    {


        Setting::updateOrCreate(
            ['key' => 'low_stock_alert_email'],
            ['value' => $request['email']]
        );
        Setting::updateOrCreate(
            ['key' => 'low_stock_alert_frequency'],
            ['value' => $request['frequency']]
        );
        Setting::updateOrCreate(
            ['key' => 'low_stock_alert_time'],
            ['value' => $request['time']]
        );

        return redirect()->route('settings.low_stock_alert')->with('success', 'Nastavení bylo úspěšně aktualizováno.');

    }


    public function sendTestLowStockAlert()
    {
        Artisan::call('SendLowStockAlert');

        return redirect()->back()->with('success', 'Notifikace upozornění na nízké zásoby byla úspěšně odeslána.');
    }
}
