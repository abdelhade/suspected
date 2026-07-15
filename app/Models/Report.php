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

        // بيانات الأطراف (legacy JSON)
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

    protected $casts = [
        'open_date_time'     => 'datetime',
        'incident_date_time' => 'datetime',
        'parties_details'    => 'array',
        'statements_details' => 'array',
        'seizures_details'   => 'array',
        'attachments_paths'  => 'array',
    ];

    public function setPartiesDetailsAttribute($value): void
    {
        $this->attributes['parties_details'] = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
    }

    public function setStatementsDetailsAttribute($value): void
    {
        $this->attributes['statements_details'] = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
    }

    public function setSeizuresDetailsAttribute($value): void
    {
        $this->attributes['seizures_details'] = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
    }

    public function setAttachmentsPathsAttribute($value): void
    {
        $this->attributes['attachments_paths'] = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
    }

    public function persons()
    {
        return $this->hasMany(ReportPerson::class, 'report_id');
    }

    public function weapons()
    {
        return $this->hasMany(ReportWeapon::class, 'report_id');
    }

    public function suspects()
    {
        return $this->belongsToMany(Suspect::class, 'report_persons', 'report_id', 'person_id')
            ->withPivot(['role', 'national_id', 'nationality', 'address', 'phone'])
            ->withTimestamps();
    }

    /**
     * تحويل الأنواع تلقائياً عند الجلب أو الحفظ
     *
     * @return array<string, string>
     */
    // -----------------------------------------------------------------------
    // ملاحظة: تم إضافة علاقات إلى Report لتخفيف التخزين النصي القديم.
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
