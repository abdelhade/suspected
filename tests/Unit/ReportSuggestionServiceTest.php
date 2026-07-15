<?php

namespace Tests\Unit;

use App\Models\Report;
use App\Models\ReportPerson;
use App\Models\Suspect;
use App\Services\ReportSuggestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportSuggestionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_prioritizes_matching_suspects_for_same_crime_method_and_location(): void
    {
        $matchingSuspect = Suspect::create([
            'full_name' => 'محمد أحمد سالم',
            'registration_category' => 'Class A',
            'danger_level' => 'High',
            'criminal_activity' => 'سرقة بالإكراه',
            'current_address' => 'المنطقة أ',
            'current_status' => 'مستمر',
        ]);

        $lessRelevantSuspect = Suspect::create([
            'full_name' => 'سالم علي',
            'registration_category' => 'Class B',
            'danger_level' => 'Medium',
            'criminal_activity' => 'تزييف',
            'current_address' => 'المنطقة ب',
            'current_status' => 'مستمر',
        ]);

        $report = Report::create([
            'report_type' => 'سرقة',
            'report_subject' => 'سرقة بالإكراه',
            'location_governorate' => 'المنطقة أ',
        ]);

        ReportPerson::create([
            'report_id' => $report->id,
            'person_id' => $matchingSuspect->id,
            'role' => 'مشتبه به',
            'full_name' => $matchingSuspect->full_name,
        ]);

        $service = new ReportSuggestionService();

        $suggestions = $service->suggest([
            'crime_type' => 'سرقة',
            'crime_method' => 'سرقة بالإكراه',
            'location' => 'المنطقة أ',
        ]);

        $this->assertNotEmpty($suggestions['suspects']);
        $this->assertSame($matchingSuspect->id, $suggestions['suspects'][0]['id']);
        $this->assertSame('سرقة بالإكراه', $suggestions['suspects'][0]['reason']);
    }
}
