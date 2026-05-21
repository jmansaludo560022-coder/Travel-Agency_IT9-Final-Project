<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\CommissionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function index()
    {
        $employees = Employee::with('userAccount')
            ->orderBy('emp_ln')
            ->paginate(15);

        return view('admin.commissions.index', compact('employees'));
    }

    public function report(Request $request)
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
        ]);

        $from = Carbon::parse($request->input('from', now()->startOfMonth()));
        $to = Carbon::parse($request->input('to', now()->endOfMonth()));

        if ($request->filled('employee_id')) {
            $employee = Employee::findOrFail($request->employee_id);
            $report = [$this->commissionService->reportForEmployee($employee, $from, $to)];
        } else {
            $report = $this->commissionService->reportForAll($from, $to);
        }

        $employees = Employee::orderBy('emp_ln')->get();
        $totalCommission = collect($report)->sum('total_commission');
        $totalSales = collect($report)->sum('total_sales');

        return view('admin.commissions.report', compact('report', 'employees', 'from', 'to', 'totalCommission', 'totalSales'));
    }
}
