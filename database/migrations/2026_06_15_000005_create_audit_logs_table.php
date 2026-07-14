<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: إنشاء جدول سجل التدقيق
 * ======================================
 * يُسجَّل فيه كل حدث مهم في النظام:
 *  - عرض ملف مسجّل / محضر
 *  - تعديل حقل (قبل / بعد)
 *  - إنشاء سجل جديد
 *  - حذف سجل
 *  - تسجيل دخول / خروج
 *  - بحث
 *  - تصدير
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // -------------------------------------------------------
            // 1. معلومات الحدث
            // -------------------------------------------------------

            /**
             * نوع الحدث
             * view / create / update / delete / login / logout / search / export / approve / promote
             */
            $table->string('event', 50)
                  ->comment('نوع الحدث: view / create / update / delete / login / logout / search / export / approve / promote');

            /**
             * الكيان المتأثر (اسم الموديل أو الجدول)
             * أمثلة: Suspect, Report, Weapon, User
             */
            $table->string('auditable_type', 100)->nullable()
                  ->comment('اسم الموديل أو الكيان المتأثر بالحدث');

            /**
             * معرّف السجل المتأثر
             */
            $table->unsignedBigInteger('auditable_id')->nullable()
                  ->comment('id السجل المتأثر بالحدث');

            /**
             * وصف موجز للحدث (نص قابل للقراءة)
             */
            $table->string('description', 500)->nullable()
                  ->comment('وصف موجز للحدث مثل: عرض ملف محمد أحمد');

            // -------------------------------------------------------
            // 2. التغييرات (للأحداث من نوع update)
            // -------------------------------------------------------

            /**
             * القيم القديمة قبل التعديل (JSON)
             */
            $table->json('old_values')->nullable()
                  ->comment('JSON: القيم القديمة قبل التعديل');

            /**
             * القيم الجديدة بعد التعديل (JSON)
             */
            $table->json('new_values')->nullable()
                  ->comment('JSON: القيم الجديدة بعد التعديل');

            // -------------------------------------------------------
            // 3. معلومات المستخدم
            // -------------------------------------------------------

            /**
             * معرّف المستخدم الذي نفّذ الحدث (nullable لتدعم الأحداث التلقائية)
             */
            $table->unsignedBigInteger('user_id')->nullable()
                  ->comment('id المستخدم الذي نفّذ الحدث');

            /**
             * اسم المستخدم (مخزَّن للاحتفاظ بالسجل حتى بعد حذف المستخدم)
             */
            $table->string('user_name', 255)->nullable()
                  ->comment('اسم المستخدم وقت الحدث');

            /**
             * عنوان IP للمستخدم
             */
            $table->string('ip_address', 45)->nullable()
                  ->comment('عنوان IP للمستخدم');

            /**
             * User Agent (المتصفح والنظام)
             */
            $table->string('user_agent', 500)->nullable()
                  ->comment('User Agent: المتصفح ونظام التشغيل');

            // -------------------------------------------------------
            // 4. Timestamps
            // -------------------------------------------------------

            /**
             * لا نستخدم updated_at لأن سجلات التدقيق لا تُعدَّل أبداً
             */
            $table->timestamp('created_at')->useCurrent()
                  ->comment('وقت وقوع الحدث');

            // -------------------------------------------------------
            // Indexes لتحسين أداء البحث
            // -------------------------------------------------------
            $table->index(['auditable_type', 'auditable_id'], 'idx_auditable');
            $table->index('user_id',    'idx_audit_user');
            $table->index('event',      'idx_audit_event');
            $table->index('created_at', 'idx_audit_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
