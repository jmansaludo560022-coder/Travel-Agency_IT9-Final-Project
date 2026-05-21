<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class OverdueScheduleService
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function run(): void
    {
        try {
            $count = $this->paymentService->markOverdueSchedules();
            Log::info("Overdue schedule check complete. Marked {$count} schedules as overdue.");
        } catch (\Throwable $e) {
            Log::error('OverdueScheduleService failed', ['error' => $e->getMessage()]);
        }
    }
}
