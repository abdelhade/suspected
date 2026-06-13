<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * اسم الجدول في قاعدة البيانات
     */
    protected $table = 'reports';

    /**
     * الحقول القابلة للتعبئة الجماعية (Mass Assignment)
     *
     * @var array<string>
     */
    protected $fillable = [
        // البيانات العامة للمحضر
        'report_number',
        'report_type',
        'open_date_time',
        'incident_date_time',
        'location_governorate',
        'location_details',
        'officer_name',

        // بيانات الأطراف (JSON)
        'parties_details',

        // تفاصيل البلاغ
        'report_subject',
        'statements_details',
        'seizures_details',

        // الإجراءات والقرارات
        'current_status',
        'prosecution_decision',
        'attachments_paths',
    ];

    /**
     * تحويل الأنواع تلقائياً عند الجلب أو الحفظ
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // تواريخ
            'open_date_time'     => 'datetime',
            'incident_date_time' => 'datetime',

            // حقول JSON — يقوم Laravel بتحويلها تلقائياً إلى array عند القراءة
            // وإلى JSON string عند الكتابة
            'parties_details'    => 'array',
            'statements_details' => 'array',
            'seizures_details'   => 'array',
            'attachments_paths'  => 'array',
        ];
    }

    // -----------------------------------------------------------------------
    // ملاحظة: لا توجد أي علاقات (relations) في هذا الموديل بحسب المواصفات.
    // جميع البيانات المركبة (الأطراف، الأقوال، الأحراز، المرفقات)
    // تُخزَّن كـ JSON داخل حقول نصية في نفس الجدول.
    // -----------------------------------------------------------------------

    /**
     * الحقول التي لا تظهر في الـ JSON output (اختياري)
     *
     * @var array<string>
     */
    protected $hidden = [];

    // ==========================================================================
    // Scopes مساعدة للاستعلام
    // ==========================================================================

    /**
     * فلترة المحاضر حسب نوعها
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('report_type', $type);
    }

    /**
     * فلترة المحاضر حسب حالتها الحالية
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('current_status', $status);
    }

    /**
     * فلترة المحاضر حسب المحافظة
     */
    public function scopeInGovernorate($query, string $governorate)
    {
        return $query->where('location_governorate', $governorate);
    }

    /**
     * فلترة المحاضر المفتوحة خلال نطاق زمني
     */
    public function scopeOpenedBetween($query, string $from, string $to)
    {
        return $query->whereBetween('open_date_time', [$from, $to]);
    }
}
