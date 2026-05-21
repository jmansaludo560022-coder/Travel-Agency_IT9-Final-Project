<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Force HTTPS in production (Render deployment)
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // Policy registrations
        Gate::policy(\App\Models\Booking::class, \App\Policies\BookingPolicy::class);
        Gate::policy(\App\Models\TravelPackage::class, \App\Policies\PackagePolicy::class);
        Gate::policy(\App\Models\Payment::class, \App\Policies\PaymentPolicy::class);
        Gate::policy(\App\Models\Review::class, \App\Policies\ReviewPolicy::class);
        Gate::policy(\App\Models\Faq::class, \App\Policies\FaqPolicy::class);
    }
}