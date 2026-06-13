<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * إنشاء جدول المحاضر الأمنية
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {

            // -----------------------------------------------
            // المفتاح الأساسي (Primary Key) - إلزامي
            // -----------------------------------------------
            $table->id();

            // -----------------------------------------------
            // 1. البيانات العامة للمحضر
            // -----------------------------------------------

            // رقم المحضر
            $table->string('report_number')->nullable();

            // نوع المحضر (مثال: بلاغ جنائي، حادث مروري، شكوى، ...)
            $table->string('report_type')->nullable();

            // تاريخ ووقت فتح المحضر
            $table->dateTime('open_date_time')->nullable();

            // تاريخ ووقت الواقعة
            $table->dateTime('incident_date_time')->nullable();

            // المحافظة
            $table->string('location_governorate')->nullable();

            // مكان الواقعة بالتفصيل
            $table->text('location_details')->nullable();

            // اسم محرر المحضر (الضابط)
            $table->string('officer_name')->nullable();

            // -----------------------------------------------
            // 2. بيانات أطراف المحضر (JSON بدلاً من علاقات)
            // -----------------------------------------------
            // يحتوي على مصفوفة JSON لكل طرف بالبيانات التالية:
            // [{ "role": "الصفة", "full_name": "الاسم الكامل",
            //    "national_id": "الرقم القومي", "nationality": "الجنسية",
            //    "age": "السن", "occupation": "المهنة",
            //    "address": "محل الإقامة", "phone": "رقم الهاتف" }, ...]
            $table->longText('parties_details')->nullable()->comment('JSON: مصفوفة بيانات أطراف المحضر');

            // -----------------------------------------------
            // 3. تفاصيل ومضمون البلاغ
            // -----------------------------------------------

            // موضوع البلاغ الرئيسي
            $table->string('report_subject')->nullable();

            // نص أقوال الأطراف بالتفصيل (سؤال وجواب)
            // [{ "party": "اسم الطرف", "role": "المتهم/المجني عليه/الشاهد",
            //    "questions_and_answers": [{"q": "...", "a": "..."}, ...] }, ...]
            $table->longText('statements_details')->nullable()->comment('JSON: أقوال الأطراف سؤالاً وجواباً');

            // وصف الأحراز والمضبوطات وكميتها وحالتها
            // [{ "name": "اسم الحرز", "quantity": "الكمية", "condition": "الحالة", "description": "الوصف" }, ...]
            $table->text('seizures_details')->nullable()->comment('JSON: الأحراز والمضبوطات');

            // -----------------------------------------------
            // 4. الإجراءات والقرارات
            // -----------------------------------------------

            // حالة المحضر الحالية (مثال: مفتوح، محول للنيابة، محفوظ، مغلق، ...)
            $table->string('current_status')->nullable();

            // قرار النيابة
            $table->text('prosecution_decision')->nullable();

            // مسارات الملفات والمرفقات الرقمية
            // [{ "name": "اسم الملف", "path": "المسار", "type": "النوع", "uploaded_at": "..." }, ...]
            $table->text('attachments_paths')->nullable()->comment('JSON: مسارات ملفات المرفقات');

            // -----------------------------------------------
            // timestamps تلقائية
            // -----------------------------------------------
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * حذف جدول المحاضر
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
