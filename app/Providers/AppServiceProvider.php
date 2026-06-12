<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Event;
use App\Observers\OrderObserver;
use App\Observers\EventObserver;
use Illuminate\Support\ServiceProvider;
use App\View\Components\Mail\Layout as MailLayout;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Order::observe(OrderObserver::class);
        Event::observe(EventObserver::class);

        // Register mail layout component
        Blade::component('mail::layout', MailLayout::class);
    }
}
