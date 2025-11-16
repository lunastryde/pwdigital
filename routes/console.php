<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Carbon;
use App\Models\FormPersonal;
use App\Models\Notification;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule: Notify users when their ID is expiring within 7 days (excluding today)
Schedule::call(function () {
    $today = now()->startOfDay();
    $inSeven = now()->addDays(7)->endOfDay();

    $expiring = FormPersonal::query()
        ->whereNotNull('expiration_date')
        ->whereDate('expiration_date', '>', $today)
        ->whereDate('expiration_date', '<=', $inSeven)
        ->get();

    foreach ($expiring as $p) {
        $accountId = $p->account_id ?? null;
        if (!$accountId) continue;

        $existsToday = Notification::where('account_id', $accountId)
            ->where('type', 'id_expiring')
            ->where('reference_type', 'form_personal')
            ->where('reference_id', $p->applicant_id)
            ->whereDate('created_at', $today)
            ->exists();

        if ($existsToday) continue;

        $expDate = $p->expiration_date ? Carbon::parse($p->expiration_date) : null;
        Notification::create([
            'account_id'    => $accountId,
            'title'         => 'Your PWD ID will expire soon',
            'message'       => $expDate
                                ? 'Your PWD ID is expiring on ' . $expDate->format('M d, Y') . '. Please renew.'
                                : 'Your PWD ID is expiring soon. Please renew.',
            'type'          => 'id_expiring',
            'reference_id'  => $p->applicant_id,
            'reference_type'=> 'form_personal',
            'is_read'       => false,
            'expires_at'    => now()->addDays(7),
        ]);
    }
})->dailyAt('08:00');

// Schedule: Notify users when their ID expires today
Schedule::call(function () {
    $today = now()->toDateString();

    $expiredToday = FormPersonal::query()
        ->whereNotNull('expiration_date')
        ->whereDate('expiration_date', '=', $today)
        ->get();

    foreach ($expiredToday as $p) {
        $accountId = $p->account_id ?? null;
        if (!$accountId) continue;

        $existsToday = Notification::where('account_id', $accountId)
            ->where('type', 'id_expired')
            ->where('reference_type', 'form_personal')
            ->where('reference_id', $p->applicant_id)
            ->whereDate('created_at', $today)
            ->exists();

        if ($existsToday) continue;

        Notification::create([
            'account_id'    => $accountId,
            'title'         => 'Your PWD ID has expired',
            'message'       => 'Your PWD ID expired today. Please renew to continue receiving services.',
            'type'          => 'id_expired',
            'reference_id'  => $p->applicant_id,
            'reference_type'=> 'form_personal',
            'is_read'       => false,
            'expires_at'    => now()->addDays(7),
        ]);
    }
})->dailyAt('09:00');
