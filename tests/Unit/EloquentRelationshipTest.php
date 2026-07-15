<?php

namespace Tests\Unit;

use App\Models\Report;
use App\Models\ReportPerson;
use App\Models\ReportWeapon;
use App\Models\Suspect;
use App\Models\Weapon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_person_aliases_and_related_relations(): void
    {
        $suspect = Suspect::create([
            'full_name' => 'محمد علي',
            'national_id' => '12345678901234',
        ]);

        $alias = $suspect->aliases()->create([
            'alias' => 'العنكبوت',
            'alias_type' => 'nickname',
        ]);

        $this->assertTrue($suspect->aliases->contains($alias));
        $this->assertEquals('العنكبوت', $suspect->aliases->first()->alias);
    }

    public function test_report_weapons_and_persons_relations(): void
    {
        $suspect = Suspect::create([
            'full_name' => 'سعيد محمود',
            'national_id' => '98765432109876',
        ]);

        $report = Report::create([
            'report_number' => 'RPT-REL-1',
            'report_subject' => 'حيازة سلاح',
            'location_governorate' => 'الإسكندرية',
            'location_details' => 'سيدي جابر',
            'current_status' => 'new',
            'parties_details' => [],
            'statements_details' => [],
            'seizures_details' => [],
            'attachments_paths' => [],
        ]);

        $weapon = Weapon::create([
            'weapon_type' => 'طبنجة',
            'classification' => 'حرز قضية',
            'current_status' => 'مضبوط',
        ]);

        $reportPerson = ReportPerson::create([
            'report_id' => $report->id,
            'person_id' => $suspect->id,
            'role' => 'متهم',
            'full_name' => $suspect->full_name,
            'national_id' => '98765432109876',
        ]);

        $reportWeapon = ReportWeapon::create([
            'report_id' => $report->id,
            'weapon_id' => $weapon->id,
            'name' => 'طبنجة',
        ]);

        $this->assertTrue($report->persons->contains($reportPerson));
        $this->assertTrue($report->weapons->contains($reportWeapon));
        $this->assertTrue($report->suspects->contains($suspect));
        $this->assertEquals('متهم', $report->persons->first()->role);
        $this->assertEquals('طبنجة', $report->weapons->first()->name);
    }
}
