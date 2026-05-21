<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::policy(\App\Models\Booking::class,      \App\Policies\BookingPolicy::class);
        Gate::policy(\App\Models\TravelPackage::class, \App\Policies\PackagePolicy::class);
        Gate::policy(\App\Models\Payment::class,       \App\Policies\PaymentPolicy::class);
        Gate::policy(\App\Models\Review::class,        \App\Policies\ReviewPolicy::class);
        Gate::policy(\App\Models\Faq::class,           \App\Policies\FaqPolicy::class);
    }
}
