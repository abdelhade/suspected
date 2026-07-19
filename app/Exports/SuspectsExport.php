<?php

namespace App\Exports;

use App\Models\Suspect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SuspectsExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    private Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query->select([
            'id',
            'full_name',
            'alias_name',
            'registration_category',
            'criminal_activity',
            'danger_level',
            'current_status',
            'current_address',
            'birth_date',
        ]);
    }

    public function map($suspect): array
    {
        return [
            $suspect->id,
            $suspect->full_name,
            $suspect->alias_name,
            $suspect->registration_category,
            $suspect->criminal_activity,
            $suspect->danger_level,
            $suspect->current_status,
            $suspect->current_address,
            optional($suspect->birth_date)->format('Y-m-d'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'الاسم',
            'الاسم المستعار',
            'نوع المسجل',
            'نوع التهمة / أسلوب الجريمة',
            'درجة الخطورة',
            'الحالة',
            'العنوان',
            'تاريخ الميلاد',
        ];
    }

    public function title(): string
    {
        return 'نتائج البحث';
    }
}
