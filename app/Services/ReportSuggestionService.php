<?php

namespace App\Services;

use App\Models\Report;
use App\Models\Suspect;
use App\Models\Weapon;
use Illuminate\Support\Collection;

class ReportSuggestionService
{
    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    public function suggest(array $input): array
    {
        $crimeType = trim((string) ($input['crime_type'] ?? ''));
        $crimeMethod = trim((string) ($input['crime_method'] ?? ''));
        $location = trim((string) ($input['location'] ?? ''));

        $suspects = $this->suggestSuspects($crimeMethod, $location, $crimeType);
        $weapons = $this->suggestWeapons($crimeType, $crimeMethod, $location);

        return [
            'suspects' => $suspects,
            'weapons' => $weapons,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function suggestSuspects(string $crimeMethod, string $location, string $crimeType): array
    {
        $query = Suspect::query()
            ->where(function ($q) use ($crimeMethod, $location, $crimeType) {
                if ($crimeMethod !== '') {
                    $q->orWhere('criminal_activity', 'like', '%' . $crimeMethod . '%');
                }

                if ($location !== '') {
                    $q->orWhere('current_address', 'like', '%' . $location . '%');
                }

                if ($crimeType !== '') {
                    $q->orWhere('criminal_activity', 'like', '%' . $crimeType . '%');
                }
            });

        $suspects = $query->get();

        $scored = $suspects->map(function (Suspect $suspect) use ($crimeMethod, $location) {
            $score = 0;
            $reason = 'اقتراح عام';

            if ($crimeMethod !== '' && $suspect->criminal_activity && str_contains($suspect->criminal_activity, $crimeMethod)) {
                $score += 50;
                $reason = $crimeMethod;
            }

            if ($location !== '' && $suspect->current_address && str_contains($suspect->current_address, $location)) {
                $score += 30;
                if ($reason === 'اقتراح عام') {
                    $reason = 'نفس المنطقة';
                }
            }

            if ($suspect->registration_category === 'Class A' || $suspect->registration_category === 'Class B') {
                $score += 20;
            }

            return [
                'id' => $suspect->id,
                'name' => $suspect->full_name,
                'registration_category' => $suspect->registration_category,
                'danger_level' => $suspect->danger_level,
                'criminal_activity' => $suspect->criminal_activity,
                'current_address' => $suspect->current_address,
                'score' => $score,
                'reason' => $reason,
            ];
        });

        return $scored
            ->sortByDesc('score')
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function suggestWeapons(string $crimeType, string $crimeMethod, string $location): array
    {
        $query = Weapon::query();

        if ($crimeType !== '') {
            $query->where(function ($q) use ($crimeType, $crimeMethod) {
                $q->where('notes', 'like', '%' . $crimeType . '%')
                  ->orWhere('notes', 'like', '%' . $crimeMethod . '%')
                  ->orWhere('weapon_type', 'like', '%' . $crimeType . '%');
            });
        }

        if ($location !== '') {
            $query->orWhere('notes', 'like', '%' . $location . '%');
        }

        $weapons = $query->get();

        return $weapons->map(function (Weapon $weapon) use ($crimeMethod, $location) {
            $score = 0;
            $reasons = [];

            if ($crimeMethod !== '' && $weapon->notes && str_contains($weapon->notes, $crimeMethod)) {
                $score += 40;
                $reasons[] = 'سلاح مرتبط بطرق مماثلة';
            }

            if ($location !== '' && $weapon->notes && str_contains($weapon->notes, $location)) {
                $score += 25;
                $reasons[] = 'نفس المنطقة';
            }

            return [
                'id' => $weapon->id,
                'name' => $weapon->weapon_type,
                'classification' => $weapon->classification,
                'current_status' => $weapon->current_status,
                'related_report_number' => $weapon->related_report_number,
                'score' => $score,
                'reason' => implode(' + ', $reasons) ?: 'اقتراح عام',
            ];
        })->sortByDesc('score')->values()->all();
    }
}
