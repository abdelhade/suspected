<?php

namespace App\Models;

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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'height_cm'  => 'integer',
    ];

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

        $reports = Report::query()
            ->where(function ($query) use ($linkable) {
                foreach ($linkable as $suspect) {
                    $query->orWhere(function ($q) use ($suspect) {
                        if ($suspect->national_id) {
                            $q->where('parties_details', 'like', '%' . $suspect->national_id . '%');
                        }
                        if ($suspect->full_name) {
                            $q->orWhere('parties_details', 'like', '%' . $suspect->full_name . '%');
                        }
                    });
                }
            })
            ->get();

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

        return Report::where(function ($query) {
            if ($this->national_id) {
                $query->where('parties_details', 'like', '%' . $this->national_id . '%');
            }
            if ($this->full_name) {
                $query->orWhere('parties_details', 'like', '%' . $this->full_name . '%');
            }
        })->get();
    }

    public function matchesReport(Report $report): bool
    {
        $partiesJson = json_encode($report->parties_details, JSON_UNESCAPED_UNICODE) ?: '';

        if ($this->national_id && str_contains($partiesJson, $this->national_id)) {
            return true;
        }

        if ($this->full_name && str_contains($partiesJson, $this->full_name)) {
            return true;
        }

        return false;
    }
}
