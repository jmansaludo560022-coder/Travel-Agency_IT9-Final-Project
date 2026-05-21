<?php

use App\Services\OverdueScheduleService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run daily to mark overdue payment schedules
Schedule::call(function () {
    app(OverdueScheduleService::class)->run();
})->daily()->name('mark-overdue-schedules');
