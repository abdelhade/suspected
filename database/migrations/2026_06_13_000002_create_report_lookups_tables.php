<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('report_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // إدخال القيم الافتراضية
        $types = ['بلاغ جنائي','حادث مروري','شكوى','بلاغ إداري','تحريات','أخرى'];
        foreach($types as $type) {
            DB::table('report_types')->insert(['name' => $type, 'created_at' => now(), 'updated_at' => now()]);
        }

        $statuses = ['مفتوح','محول للنيابة','محفوظ','مغلق'];
        foreach($statuses as $status) {
            DB::table('report_statuses')->insert(['name' => $status, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_statuses');
        Schema::dropIfExists('report_types');
    }
};
