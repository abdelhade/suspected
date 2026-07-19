<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * جدول الخيارات الديناميكية للقوائم المنسدلة
 * =============================================
 * group  = اسم المجموعة (مثل: weapon_type / danger_level / ...)
 * value  = قيمة الخيار
 * sort   = ترتيب العرض
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lookup_options', function (Blueprint $table) {
            $table->id();
            $table->string('group', 100)->comment('اسم المجموعة');
            $table->string('value', 255)->comment('قيمة الخيار');
            $table->unsignedSmallInteger('sort')->default(0)->comment('ترتيب العرض');
            $table->timestamps();

            $table->unique(['group', 'value']);
            $table->index('group');
        });

        // ---- القيم الافتراضية ----

        $now = now();
        $rows = [];

        // أنواع الأسلحة
        foreach (['آلي', 'طبنجة', 'خرطوش', 'بندقية صيد', 'أبيض', 'أخرى'] as $i => $v) {
            $rows[] = ['group' => 'weapon_type', 'value' => $v, 'sort' => $i, 'created_at' => $now, 'updated_at' => $now];
        }

        // تصنيفات السلاح
        foreach (['حرز قضية', 'سلاح مرخص', 'عهدة قسم', 'مضبوط بدون ترخيص', 'أخرى'] as $i => $v) {
            $rows[] = ['group' => 'weapon_classification', 'value' => $v, 'sort' => $i, 'created_at' => $now, 'updated_at' => $now];
        }

        // حالة السلاح
        foreach (['في المخزن', 'في المعمل الجنائي', 'محول للنيابة', 'مُسلَّم للحائز', 'مفقود', 'أخرى'] as $i => $v) {
            $rows[] = ['group' => 'weapon_status', 'value' => $v, 'sort' => $i, 'created_at' => $now, 'updated_at' => $now];
        }

        // فئة المسجّل
        foreach (['مسجل شقي خطر', 'معلومات', 'مطلوب', 'مشتبه به', 'زائر'] as $i => $v) {
            $rows[] = ['group' => 'registration_category', 'value' => $v, 'sort' => $i, 'created_at' => $now, 'updated_at' => $now];
        }

        // مستوى الخطورة
        foreach (['عالية جداً', 'عالية', 'متوسطة', 'منخفضة'] as $i => $v) {
            $rows[] = ['group' => 'danger_level', 'value' => $v, 'sort' => $i, 'created_at' => $now, 'updated_at' => $now];
        }

        // الحالة الجنائية
        foreach (['هارب', 'محبوس', 'مفرج عنه', 'تحت المراقبة', 'حر'] as $i => $v) {
            $rows[] = ['group' => 'suspect_status', 'value' => $v, 'sort' => $i, 'created_at' => $now, 'updated_at' => $now];
        }

        // بنية الجسم
        foreach (['نحيف', 'متوسط', 'رياضي', 'ممتلئ', 'بدين'] as $i => $v) {
            $rows[] = ['group' => 'body_build', 'value' => $v, 'sort' => $i, 'created_at' => $now, 'updated_at' => $now];
        }

        // لون البشرة
        foreach (['أبيض', 'قمحي', 'أسمر', 'أسود'] as $i => $v) {
            $rows[] = ['group' => 'skin_color', 'value' => $v, 'sort' => $i, 'created_at' => $now, 'updated_at' => $now];
        }

        DB::table('lookup_options')->insert($rows);
    }

    public function down(): void
    {
        Schema::dropIfExists('lookup_options');
    }
};
