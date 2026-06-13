<?php

namespace App\Services;

class DashboardService
{
    /**
     * @return array<string, mixed>
     */
    public function getStats(): array
    {
        // TODO: استبدال ببيانات حقيقية من Person / Report models
        return [
            'total_persons' => 1247,
            'registered_a' => 312,
            'registered_b' => 489,
            'visitors' => 446,
            'wanted' => 87,
            'detained' => 34,
            'pending_reports' => 23,
            'pending_approvals' => 15,
            'reports_this_month' => 156,
            'reports_today' => 8,
        ];
    }

    /**
     * @return list<array{label: string, value: int, color: string}>
     */
    public function getPersonTypeBreakdown(): array
    {
        return [
            ['label' => 'مسجّل A', 'value' => 312, 'color' => 'red'],
            ['label' => 'مسجّل B', 'value' => 489, 'color' => 'orange'],
            ['label' => 'زائر', 'value' => 446, 'color' => 'yellow'],
        ];
    }

    /**
     * @return list<array{label: string, value: int, color: string}>
     */
    public function getRiskLevelBreakdown(): array
    {
        return [
            ['label' => 'حرج', 'value' => 45, 'color' => 'red'],
            ['label' => 'عالي', 'value' => 198, 'color' => 'orange'],
            ['label' => 'متوسط', 'value' => 412, 'color' => 'yellow'],
            ['label' => 'منخفض', 'value' => 592, 'color' => 'green'],
        ];
    }

    /**
     * @return list<array{month: string, count: int}>
     */
    public function getMonthlyReports(): array
    {
        return [
            ['month' => 'يناير', 'count' => 98],
            ['month' => 'فبراير', 'count' => 112],
            ['month' => 'مارس', 'count' => 134],
            ['month' => 'أبريل', 'count' => 121],
            ['month' => 'مايو', 'count' => 145],
            ['month' => 'يونيو', 'count' => 156],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getRecentReports(): array
    {
        return [
            [
                'number' => 'RPT-2026-00156',
                'crime_type' => 'سرقة',
                'method' => 'مسلح',
                'location' => 'المعادي، القاهرة',
                'occurred_at' => '2026-06-11 23:15',
                'status' => 'pending_review',
                'persons_count' => 2,
            ],
            [
                'number' => 'RPT-2026-00155',
                'crime_type' => 'بلطجة',
                'method' => 'جماعي',
                'location' => '6 أكتوبر، الجيزة',
                'occurred_at' => '2026-06-11 19:40',
                'status' => 'approved',
                'persons_count' => 4,
            ],
            [
                'number' => 'RPT-2026-00154',
                'crime_type' => 'احتيال',
                'method' => 'إلكتروني',
                'location' => 'مدينة نصر، القاهرة',
                'occurred_at' => '2026-06-10 14:20',
                'status' => 'draft',
                'persons_count' => 1,
            ],
            [
                'number' => 'RPT-2026-00153',
                'crime_type' => 'مخدرات',
                'method' => 'فردي',
                'location' => 'الإسكندرية',
                'occurred_at' => '2026-06-10 02:30',
                'status' => 'approved',
                'persons_count' => 3,
            ],
            [
                'number' => 'RPT-2026-00152',
                'crime_type' => 'سرقة',
                'method' => 'كسر باب',
                'location' => 'المقطم، القاهرة',
                'occurred_at' => '2026-06-09 04:00',
                'status' => 'pending_review',
                'persons_count' => 1,
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getPendingApprovals(): array
    {
        return [
            [
                'file_number' => 'REG-2026-00441',
                'full_name' => 'محمد عبد الرحمن',
                'person_type' => 'visitor',
                'target_type' => 'registered_b',
                'submitted_at' => '2026-06-11 10:30',
                'submitted_by' => 'ض. أحمد حسن',
            ],
            [
                'file_number' => 'REG-2026-00438',
                'full_name' => 'خالد إبراهيم',
                'person_type' => 'visitor',
                'target_type' => 'registered_a',
                'submitted_at' => '2026-06-10 16:45',
                'submitted_by' => 'ض. سامي محمود',
            ],
            [
                'file_number' => 'REG-2026-00435',
                'full_name' => 'عمر يوسف',
                'person_type' => 'registered_b',
                'target_type' => 'registered_a',
                'submitted_at' => '2026-06-10 09:15',
                'submitted_by' => 'م. كريم فتحي',
            ],
        ];
    }

    /**
     * @return list<array{governorate: string, count: int}>
     */
    public function getTopGovernorates(): array
    {
        return [
            ['governorate' => 'القاهرة', 'count' => 423],
            ['governorate' => 'الجيزة', 'count' => 287],
            ['governorate' => 'الإسكندرية', 'count' => 156],
            ['governorate' => 'القليوبية', 'count' => 98],
            ['governorate' => 'الشرقية', 'count' => 76],
        ];
    }
}
