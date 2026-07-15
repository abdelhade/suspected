<?php

namespace App\Models;

use App\Models\PersonWeapon;
use App\Models\ReportPerson;
use App\Models\Weapon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Suspect extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'alias_name',
        'national_id',
        'birth_date',
        'current_address',
        'registration_category',
        'danger_level',
        'criminal_activity',
        'current_status',
        'distinguishing_marks',
        'height_cm',
        'body_build',
        'skin_color',
        'profile_image_path',
    ];

    protected $casts = [
        'national_id' => 'encrypted',
        'birth_date'  => 'date',
        'height_cm'   => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $suspect) {
            if ($suspect->isDirty('national_id') && $suspect->national_id !== null) {
                $suspect->national_id_hash = hash('sha256', $suspect->national_id);
            }
        });
    }

    public function getNationalIdAttribute($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return $this->fromEncryptedString($value);
        } catch (DecryptException) {
            return $value;
        }
    }

    public function aliases()
    {
        return $this->hasMany(PersonAlias::class, 'person_id');
    }

    public function addresses()
    {
        return $this->hasMany(PersonAddress::class, 'person_id');
    }

    public function phones()
    {
        return $this->hasMany(PersonPhone::class, 'person_id');
    }
    public function personWeapons()
    {
        return $this->hasMany(PersonWeapon::class, 'person_id');
    }

    public function weapons()
    {
        return $this->hasManyThrough(Weapon::class, PersonWeapon::class, 'person_id', 'id', 'id', 'weapon_id');
    }

    public function reportPersons()
    {
        return $this->hasMany(ReportPerson::class, 'person_id');
    }

    public function convictions()
    {
        return $this->hasMany(PersonConviction::class, 'person_id');
    }

    public function associates()
    {
        return $this->hasMany(PersonAssociate::class, 'person_id');
    }

    /** @var Collection<int, Report>|null */
    protected ?Collection $linkedReportsCache = null;

    /**
     * جلب المحاضر المرتبطة بالمسجل خطر ديناميكياً
     */
    public function getLinkedReportsAttribute(): Collection
    {
        if ($this->linkedReportsCache !== null) {
            return $this->linkedReportsCache;
        }

        return $this->linkedReportsCache = $this->queryLinkedReports();
    }

    /**
     * تحميل المحاضر المرتبطة لمجموعة مسجلين في استعلام واحد (تجنب N+1)
     */
    public static function attachLinkedReports(Collection $suspects): void
    {
        if ($suspects->isEmpty()) {
            return;
        }

        $linkable = $suspects->filter(fn (self $suspect) => $suspect->national_id || $suspect->full_name);

        if ($linkable->isEmpty()) {
            foreach ($suspects as $suspect) {
                $suspect->linkedReportsCache = collect();
            }

            return;
        }

        $reports = Report::query()->get();

        foreach ($suspects as $suspect) {
            $suspect->linkedReportsCache = $reports->filter(
                fn (Report $report) => $suspect->matchesReport($report)
            )->values();
        }
    }

    protected function queryLinkedReports(): Collection
    {
        if (!$this->national_id && !$this->full_name) {
            return collect();
        }

        return Report::query()->get()->filter(fn (Report $report) => $this->matchesReport($report))->values();
    }

    public function matchesReport(Report $report): bool
    {
        if ($this->national_id) {
            $hash = hash('sha256', $this->national_id);
            if ($report->persons()->where('national_id_hash', $hash)->exists()) {
                return true;
            }

            foreach ((array) ($report->parties_details ?? []) as $party) {
                if (!is_array($party)) {
                    continue;
                }

                $partyNationalId = trim((string) ($party['national_id'] ?? ''));
                if ($partyNationalId !== '' && $partyNationalId === $this->national_id) {
                    return true;
                }
            }
        }

        if ($this->full_name) {
            if ($report->persons()->where('full_name', 'like', '%' . $this->full_name . '%')->exists()) {
                return true;
            }

            foreach ((array) ($report->parties_details ?? []) as $party) {
                if (!is_array($party)) {
                    continue;
                }

                $partyName = trim((string) ($party['full_name'] ?? ''));
                if ($partyName !== '' && str_contains($partyName, $this->full_name)) {
                    return true;
                }
            }
        }

        return false;
    }
}
