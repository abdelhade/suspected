<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suspects', function (Blueprint $table) {
            $table->id();

            // البيانات الشخصية
            $table->string('full_name')->nullable();
            $table->string('alias_name')->nullable();
            $table->string('national_id')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('current_address')->nullable();

            // التصنيف الجنائي
            $table->string('registration_category')->nullable();
            $table->string('danger_level')->nullable();
            $table->string('criminal_activity')->nullable();
            $table->string('current_status')->nullable();

            // المواصفات الجسدية والمرفقات
            $table->text('distinguishing_marks')->nullable();
            $table->integer('height_cm')->nullable();
            $table->string('body_build')->nullable();
            $table->text('profile_image_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspects');
    }
};
