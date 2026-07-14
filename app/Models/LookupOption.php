<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class LookupOption extends Model
{
    protected $table = 'lookup_options';

    protected $fillable = ['group', 'value', 'sort'];

    // =========================================================================
    // Helpers
    // =========================================================================

    /**
     * جلب قيم مجموعة معينة مرتّبة
     *
     * @return Collection<int, string>
     */
    public static function valuesFor(string $group): Collection
    {
        return self::where('group', $group)
            ->orderBy('sort')
            ->orderBy('value')
            ->pluck('value');
    }

    /**
     * إضافة قيمة جديدة لمجموعة (يُعيد الصف الموجود إن كان مكرراً)
     */
    public static function addValue(string $group, string $value): self
    {
        return self::firstOrCreate(
            ['group' => $group, 'value' => trim($value)],
            ['sort'  => self::where('group', $group)->max('sort') + 1],
        );
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    public function scopeForGroup(Builder $query, string $group): Builder
    {
        return $query->where('group', $group)->orderBy('sort')->orderBy('value');
    }
}
