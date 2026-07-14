<?php

namespace Tests\Feature;

use App\Models\Suspect;
use App\Models\Report;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuspectReportLinkTest extends TestCase
{
    use RefreshDatabase;

    /**
     * اختبار ربط المحاضر بالمسجل خطر والبحث عنه
     */
    public function test_suspect_report_linking_and_search(): void
    {
        // 1. إنشاء مسجل خطر
        $suspect = Suspect::create([
            'full_name' => 'أحمد محمد علي',
            'national_id' => '12345678901234',
            'registration_category' => 'مسجل شقي خطر',
            'danger_level' => 'عالية',
            'current_status' => 'هارب',
        ]);

        // 2. إنشاء محضر يحتوي على بيانات المسجل في الأطراف (عن طريق الرقم القومي)
        $report = Report::create([
            'report_number' => 'RPT-2026-999',
            'report_subject' => 'سرقة متجر',
            'location_governorate' => 'القاهرة',
            'location_details' => 'وسط البلد، المعادي',
            'current_status' => 'new',
            'parties_details' => [
                [
                    'role' => 'متهم',
                    'full_name' => 'أحمد محمد علي',
                    'national_id' => '12345678901234',
                ]
            ]
        ]);

        // 3. التحقق من ربط المحضر بالمسجل خطر بنجاح عبر الـ Accessor
        $this->assertCount(1, $suspect->linked_reports);
        $this->assertEquals('RPT-2026-999', $suspect->linked_reports->first()->report_number);

        // 4. اختبار البحث عن المسجل خطر برقم المحضر
        $response = $this->get('/suspects?search=RPT-2026-999');
        $response->assertStatus(200);
        $response->assertSee('أحمد محمد علي');
        $response->assertSee('RPT-2026-999');
        $response->assertSee('new');
        $response->assertSee('القاهرة');
        $response->assertSee('وسط البلد، المعادي', false);

        // 5. اختبار البحث بموضوع المحضر
        $responseSubject = $this->get('/suspects?search=سرقة');
        $responseSubject->assertStatus(200);
        $responseSubject->assertSee('أحمد محمد علي');

        // 6. اختبار البحث بمكان المحضر
        $responseLocation = $this->get('/suspects?search=المعادي');
        $responseLocation->assertStatus(200);
        $responseLocation->assertSee('أحمد محمد علي');
    }

    /**
     * اختبار تسجيل أحداث التدقيق عند تعديل المحضر وتحويله إلى pending
     */
    public function test_audit_log_on_report_update_and_pending_transition(): void
    {
        // إنشاء محضر عبر POST لتشغيل منطق المتحكم وتسجيل التدقيق
        $this->post('/reports', [
            'report_number' => 'RPT-2026-888',
            'report_subject' => 'حيازة سلاح بدون ترخيص',
            'current_status' => 'new',
        ]);

        $report = Report::where('report_number', 'RPT-2026-888')->first();

        // تسجيل حدث الإنشاء يجب أن يكون تم بالفعل
        $this->assertDatabaseHas('audit_logs', [
            'event' => 'create',
            'auditable_type' => 'Report',
            'auditable_id' => $report->id,
            'description' => 'إنشاء محضر: RPT-2026-888',
        ]);

        // تحديث المحضر دون تغيير الحالة إلى pending
        $this->put("/reports/{$report->id}", [
            'report_number' => 'RPT-2026-888-MOD',
            'report_subject' => 'حيازة سلاح وتجارة',
            'current_status' => 'new',
        ]);

        // التحقق من تسجيل حدث تعديل عام
        $this->assertDatabaseHas('audit_logs', [
            'event' => 'update',
            'auditable_type' => 'Report',
            'auditable_id' => $report->id,
            'description' => 'تعديل محضر: RPT-2026-888-MOD',
        ]);

        // تحديث المحضر وتحويل الحالة إلى pending
        $this->put("/reports/{$report->id}", [
            'report_number' => 'RPT-2026-888-MOD',
            'report_subject' => 'حيازة سلاح وتجارة',
            'current_status' => 'pending',
        ]);

        // التحقق من تسجيل حدث تحويل إلى pending
        $this->assertDatabaseHas('audit_logs', [
            'event' => 'update',
            'auditable_type' => 'Report',
            'auditable_id' => $report->id,
            'description' => 'تحويل محضر إلى pending: RPT-2026-888-MOD',
        ]);
    }
}
