<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
    ) {}

    public function index(): View
    {
        return view('dashboard.index', [
            'stats' => $this->dashboard->getStats(),
            'personTypes' => $this->dashboard->getPersonTypeBreakdown(),
            'riskLevels' => $this->dashboard->getRiskLevelBreakdown(),
            'monthlyReports' => $this->dashboard->getMonthlyReports(),
            'recentReports' => $this->dashboard->getRecentReports(),
            'pendingApprovals' => $this->dashboard->getPendingApprovals(),
            'topGovernorates' => $this->dashboard->getTopGovernorates(),
        ]);
    }
}
