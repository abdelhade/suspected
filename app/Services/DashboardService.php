<?php

namespace App\Services;

use App\Models\Suspect;
use App\Models\Report;
use Carbon\Carbon;

class DashboardService
{
    /**
     * @return array<string, mixed>
     */
    public function getStats(): array
    {
        $now = Carbon::now();
        $startOfMonth = $now->startOfMonth();
        $startOfDay = $now->startOfDay();

        return [
            'total_persons' => Suspect::count(),
            'registered_a' => Suspect::where('registration_category', 'مسجل A')->count(),
            'registered_b' => Suspect::where('registration_category', 'مسجل B')->count(),
            'visitors' => Suspect::where('registration_category', 'زائر')->count(),
            'wanted' => Suspect::where('current_status', 'مطلوب')->count(),
            'detained' => Suspect::where('current_status', 'محبوس')->count(),
            'pending_reports' => Report::where('current_status', 'pending')->count(),
            'pending_approvals' => Suspect::where('current_status', 'pending')->count(),
            'reports_this_month' => Report::where('open_date_time', '>=', $startOfMonth)->count(),
            'reports_today' => Report::where('open_date_time', '>=', $startOfDay)->count(),
        ];
    }

    /**
     * @return list<array{label: string, value: int, color: string}>
     */
    public function getPersonTypeBreakdown(): array
    {
        $types = Suspect::selectRaw('registration_category as label, COUNT(*) as value')
            ->groupBy('registration_category')
            ->get()
            ->map(function ($item) {
                $color = match($item->label) {
                    'مسجل A' => 'red',
                    'مسجل B' => 'orange',
                    'زائر' => 'yellow',
                    default => 'gray',
                };
                return [
                    'label' => $item->label ?? 'غير محدد',
                    'value' => $item->value,
                    'color' => $color,
                ];
            })->values()->toArray();

        return $types;
    }

    /**
     * @return list<array{label: string, value: int, color: string}>
     */
    public function getRiskLevelBreakdown(): array
    {
        $levels = Suspect::selectRaw('danger_level as label, COUNT(*) as value')
            ->whereNotNull('danger_level')
            ->groupBy('danger_level')
            ->get()
            ->map(function ($item) {
                $color = match($item->label) {
                    'حرج' => 'red',
                    'عالي' => 'orange',
                    'متوسط' => 'yellow',
                    'منخفض' => 'green',
                    default => 'gray',
                };
                return [
                    'label' => $item->label ?? 'غير محدد',
                    'value' => $item->value,
                    'color' => $color,
                ];
            })->values()->toArray();

        return $levels;
    }

    /**
     * @return list<array{month: string, count: int}>
     */
    public function getMonthlyReports(): array
    {
        $months = [
            'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
            'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
        ];

        $currentYear = Carbon::now()->year;

        $reports = Report::selectRaw('MONTH(open_date_time) as month_num, COUNT(*) as count')
            ->whereYear('open_date_time', $currentYear)
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_num');

        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[] = [
                'month' => $months[$i - 1],
                'count' => $reports->get($i)?->count ?? 0,
            ];
        }

        return $result;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getRecentReports(): array
    {
        return Report::latest('open_date_time')
            ->limit(5)
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'number' => $report->report_number ?? 'بدون رقم',
                    'crime_type' => $report->report_type ?? 'غير محدد',
                    'method' => 'غير محدد',
                    'location' => $report->location_governorate ?? 'غير محدد',
                    'occurred_at' => $report->incident_date_time?->format('Y-m-d H:i') ?? $report->open_date_time?->format('Y-m-d H:i'),
                    'status' => $report->current_status ?? 'draft',
                    'persons_count' => $report->persons()->count(),
                ];
            })->toArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getPendingApprovals(): array
    {
        return Suspect::where('current_status', 'pending')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($suspect) {
                return [
                    'id' => $suspect->id,
                    'file_number' => 'REG-' . $suspect->id,
                    'full_name' => $suspect->full_name ?? 'غير محدد',
                    'person_type' => $suspect->registration_category ?? 'غير محدد',
                    'target_type' => 'غير محدد',
                    'submitted_at' => $suspect->created_at?->format('Y-m-d H:i'),
                    'submitted_by' => 'غير محدد',
                ];
            })->toArray();
    }

    /**
     * @return list<array{governorate: string, count: int}>
     */
    public function getTopGovernorates(): array
    {
        return Report::selectRaw('location_governorate as governorate, COUNT(*) as count')
            ->whereNotNull('location_governorate')
            ->groupBy('location_governorate')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'governorate' => $item->governorate ?? 'غير محدد',
                    'count' => $item->count,
                ];
            })->toArray();
    }
}
