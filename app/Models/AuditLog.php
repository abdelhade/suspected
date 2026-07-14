<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AuditLog extends Model
{
    // سجلات التدقيق لا تُعدَّل أبداً — نُعطّل updated_at
    const UPDATED_AT = null;

    protected $table = 'audit_logs';

    protected $fillable = [
        'event',
        'auditable_type',
        'auditable_id',
        'description',
        'old_values',
        'new_values',
        'user_id',
        'user_name',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    // =========================================================
    // تسميات الأحداث بالعربية
    // =========================================================

    public const EVENT_LABELS = [
        'view'    => ['label' => 'عرض',       'badge' => 'badge-light'],
        'create'  => ['label' => 'إنشاء',      'badge' => 'badge-neon'],
        'update'  => ['label' => 'تعديل',      'badge' => 'badge-warning'],
        'delete'  => ['label' => 'حذف',        'badge' => 'badge-danger'],
        'login'   => ['label' => 'دخول',       'badge' => 'badge-light'],
        'logout'  => ['label' => 'خروج',       'badge' => 'badge-light'],
        'search'  => ['label' => 'بحث',        'badge' => 'badge-light'],
        'export'  => ['label' => 'تصدير',      'badge' => 'badge-warning'],
        'approve' => ['label' => 'اعتماد',     'badge' => 'badge-neon'],
        'promote' => ['label' => 'ترقية',      'badge' => 'badge-neon'],
    ];

    public const AUDITABLE_LABELS = [
        'Suspect' => 'مسجّل',
        'Report'  => 'محضر',
        'Weapon'  => 'سلاح',
        'User'    => 'مستخدم',
    ];

    // =========================================================
    // Helper: تسجيل حدث جديد
    // =========================================================

    public static function record(
        string  $event,
        ?string $auditableType = null,
        ?int    $auditableId   = null,
        ?string $description   = null,
        array   $oldValues     = [],
        array   $newValues     = [],
    ): self {
        return self::create([
            'event'          => $event,
            'auditable_type' => $auditableType,
            'auditable_id'   => $auditableId,
            'description'    => $description,
            'old_values'     => empty($oldValues) ? null : $oldValues,
            'new_values'     => empty($newValues) ? null : $newValues,
            'user_id'        => auth()->id(),
            'user_name'      => auth()->user()?->name,
            'ip_address'     => request()->ip(),
            'user_agent'     => substr((string) request()->userAgent(), 0, 500),
        ]);
    }

    // =========================================================
    // Scopes
    // =========================================================

    public function scopeForEvent(Builder $query, string $event): Builder
    {
        return $query->where('event', $event);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForAuditable(Builder $query, string $type, int $id): Builder
    {
        return $query->where('auditable_type', $type)->where('auditable_id', $id);
    }

    // =========================================================
    // Accessors
    // =========================================================

    public function getEventLabelAttribute(): string
    {
        return self::EVENT_LABELS[$this->event]['label'] ?? $this->event;
    }

    public function getEventBadgeAttribute(): string
    {
        return self::EVENT_LABELS[$this->event]['badge'] ?? 'badge-light';
    }

    public function getAuditableLabelAttribute(): string
    {
        return self::AUDITABLE_LABELS[$this->auditable_type] ?? ($this->auditable_type ?? '—');
    }
}
