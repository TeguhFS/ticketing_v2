<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\ExpireOrdersJob;
use App\Jobs\ExpireTicketsJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Melakukan pengecekan setiap 15 menit
Schedule::job(new ExpireOrdersJob)->everyFifteenMinutes()
    ->name('expire-orders')
    ->withoutOverlapping()
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('ExpireOrdersJob schedule failed!');
    });

// Melakukan pengecekan setiap 1 jam
Schedule::job(new ExpireTicketsJob)->hourly()
    ->name('expire-tickets')
    ->withoutOverlapping()
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('ExpireTicketsJob schedule failed!');
    });
