<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * موديل الأسلحة والمضبوطات النارية
 *
 * @property int         $id
 * @property string|null $weapon_type
 * @property string|null $caliber
 * @property string|null $brand_make
 * @property string|null $serial_number
 * @property string|null $classification
 * @property string|null $current_status
 * @property string|null $related_report_number
 * @property string|null $holder_info
 * @property \Carbon\Carbon|null $capture_date_time
 * @property string|null $weapon_condition
 * @property string|null $notes
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
class Weapon extends Model
{
    use SoftDeletes;

    protected $table = 'weapons';

    // =========================================================================
    // Mass Assignment
    // =========================================================================

    protected $fillable = [
        // البيانات الفنية
        'weapon_type',
        'caliber',
        'brand_make',
        'serial_number',

        // الموقف القانوني والربط النصي
        'classification',
        'current_status',
        'related_report_number',
        'holder_info',
        'capture_date_time',

        // تفاصيل إضافية
        'weapon_condition',
        'notes',
    ];

    // =========================================================================
    // Casts
    // =========================================================================

    protected $casts = [
        'capture_date_time' => 'datetime',
    ];

    // =========================================================================
    // القيم المقبولة (لوائح مرجعية — تُستخدم في الـ Validation)
    // يمكن تعديلها أو توسيعها بدون تغيير الـ Schema
    // =========================================================================

    /** أنواع الأسلحة المعتمدة */
    public const WEAPON_TYPES = [
        'آلي',
        'طبنجة',
        'خرطوش',
        'بندقية صيد',
        'أبيض',
        'أخرى',
    ];

    /** تصنيفات السلاح القانونية */
    public const CLASSIFICATIONS = [
        'حرز قضية',
        'سلاح مرخص',
        'عهدة قسم',
        'مضبوط بدون ترخيص',
        'أخرى',
    ];

    /** حالات السلاح الحالية */
    public const STATUSES = [
        'في المخزن',
        'في المعمل الجنائي',
        'محول للنيابة',
        'مُسلَّم للحائز',
        'مفقود',
        'أخرى',
    ];

    // =========================================================================
    // Query Scopes — للفلترة السريعة
    // =========================================================================

    /**
     * فلترة حسب نوع السلاح
     *
     * @example Weapon::ofType('طبنجة')->get()
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('weapon_type', $type);
    }

    /**
     * فلترة حسب التصنيف القانوني
     *
     * @example Weapon::classified('حرز قضية')->get()
     */
    public function scopeClassified(Builder $query, string $classification): Builder
    {
        return $query->where('classification', $classification);
    }

    /**
     * فلترة حسب الحالة الحالية
     *
     * @example Weapon::withStatus('في المخزن')->get()
     */
    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('current_status', $status);
    }

    /**
     * فلترة حسب رقم المحضر المرتبط (بحث جزئي)
     *
     * @example Weapon::relatedToReport('2026/100')->get()
     */
    public function scopeRelatedToReport(Builder $query, string $reportNumber): Builder
    {
        return $query->where('related_report_number', 'like', "%{$reportNumber}%");
    }

    /**
     * فلترة حسب الرقم التسلسلي (بحث جزئي)
     *
     * @example Weapon::bySerial('SN-001')->get()
     */
    public function scopeBySerial(Builder $query, string $serial): Builder
    {
        return $query->where('serial_number', 'like', "%{$serial}%");
    }

    /**
     * بحث نصي عام في أهم الحقول
     *
     * @example Weapon::search('جلوك')->get()
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term) {
            $q->where('serial_number', 'like', "%{$term}%")
              ->orWhere('brand_make', 'like', "%{$term}%")
              ->orWhere('weapon_type', 'like', "%{$term}%")
              ->orWhere('related_report_number', 'like', "%{$term}%")
              ->orWhere('holder_info', 'like', "%{$term}%")
              ->orWhere('notes', 'like', "%{$term}%");
        });
    }

    /**
     * ضبط السلاح خلال نطاق زمني
     *
     * @example Weapon::capturedBetween('2026-01-01', '2026-06-15')->get()
     */
    public function scopeCapturedBetween(Builder $query, string $from, string $to): Builder
    {
        return $query->whereBetween('capture_date_time', [$from, $to]);
    }
}
