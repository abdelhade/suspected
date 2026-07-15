<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('person_aliases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('suspects')->cascadeOnDelete();
            $table->string('alias');
            $table->string('alias_type')->nullable();
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('person_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('suspects')->cascadeOnDelete();
            $table->string('address_type')->nullable();
            $table->string('governorate')->nullable();
            $table->string('district')->nullable();
            $table->text('street')->nullable();
            $table->string('building')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('person_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('suspects')->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_hash')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('person_convictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('suspects')->cascadeOnDelete();
            $table->string('charge')->nullable();
            $table->date('verdict_date')->nullable();
            $table->string('sentence')->nullable();
            $table->string('court')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('person_associates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('suspects')->cascadeOnDelete();
            $table->foreignId('associate_person_id')->nullable()->constrained('suspects')->nullOnDelete();
            $table->string('relationship_type')->nullable();
            $table->text('description')->nullable();
            $table->string('confidence')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
        });

        Schema::create('person_weapons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('suspects')->cascadeOnDelete();
            $table->foreignId('weapon_id')->constrained('weapons')->cascadeOnDelete();
            $table->string('relationship')->nullable();
            $table->date('linked_at')->nullable();
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('report_persons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('person_id')->nullable()->constrained('suspects')->nullOnDelete();
            $table->string('role')->nullable();
            $table->string('full_name')->nullable();
            $table->string('national_id')->nullable();
            $table->string('national_id_hash')->nullable()->index();
            $table->string('nationality')->nullable();
            $table->string('age')->nullable();
            $table->string('occupation')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_hash')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('report_weapons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->foreignId('weapon_id')->constrained('weapons')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('quantity')->nullable();
            $table->string('condition')->nullable();
            $table->text('description')->nullable();
            $table->string('link_source')->nullable();
            $table->unsignedTinyInteger('confidence_score')->nullable();
            $table->timestamps();
        });

        Schema::table('suspects', function (Blueprint $table) {
            if (!Schema::hasColumn('suspects', 'national_id_hash')) {
                $table->string('national_id_hash')->nullable()->index()->after('national_id');
            }
        });

        $this->migrateExistingSensitiveValues();
        $this->migrateLegacyReportParties();
        $this->migrateLegacyReportWeapons();
    }

    private function migrateExistingSensitiveValues(): void
    {
        $suspects = DB::table('suspects')->select('id', 'national_id')->get();

        foreach ($suspects as $suspect) {
            if (!$suspect->national_id) {
                continue;
            }

            $nationalId = (string) $suspect->national_id;
            $encrypted = Crypt::encryptString($nationalId);
            $hash = hash('sha256', $nationalId);

            DB::table('suspects')
                ->where('id', $suspect->id)
                ->update([
                    'national_id' => $encrypted,
                    'national_id_hash' => $hash,
                ]);
        }
    }

    private function migrateLegacyReportParties(): void
    {
        $reports = DB::table('reports')->select('id', 'parties_details')->get();
        $now = Carbon::now();

        foreach ($reports as $report) {
            $parties = json_decode($report->parties_details, true);

            if (!is_array($parties)) {
                continue;
            }

            foreach ($parties as $party) {
                if (!is_array($party)) {
                    continue;
                }

                $personId = null;
                $nationalId = isset($party['national_id']) ? trim((string) $party['national_id']) : null;
                $nationalIdHash = $nationalId ? hash('sha256', $nationalId) : null;

                if ($nationalIdHash) {
                    $matchingPerson = DB::table('suspects')->where('national_id_hash', $nationalIdHash)->first();
                    if ($matchingPerson) {
                        $personId = $matchingPerson->id;
                    }
                }

                if (!$personId && !empty($party['full_name'])) {
                    $matchingPerson = DB::table('suspects')->where('full_name', trim((string) $party['full_name']))->first();
                    if ($matchingPerson) {
                        $personId = $matchingPerson->id;
                    }
                }

                DB::table('report_persons')->insert([
                    'report_id' => $report->id,
                    'person_id' => $personId,
                    'role' => $party['role'] ?? null,
                    'full_name' => $party['full_name'] ?? null,
                    'national_id' => $nationalId,
                    'national_id_hash' => $nationalIdHash,
                    'nationality' => $party['nationality'] ?? null,
                    'age' => isset($party['age']) ? (string) $party['age'] : null,
                    'occupation' => $party['occupation'] ?? null,
                    'address' => $party['address'] ?? null,
                    'phone' => $party['phone'] ?? null,
                    'phone_hash' => !empty($party['phone']) ? hash('sha256', (string) $party['phone']) : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    private function migrateLegacyReportWeapons(): void
    {
        $reports = DB::table('reports')->select('id', 'report_number', 'seizures_details')->get();
        $now = Carbon::now();

        foreach ($reports as $report) {
            $seizures = json_decode($report->seizures_details, true);

            if (!is_array($seizures)) {
                continue;
            }

            foreach ($seizures as $seizure) {
                if (!is_array($seizure)) {
                    continue;
                }

                $weaponId = DB::table('weapons')->insertGetId([
                    'weapon_type' => $seizure['name'] ?? null,
                    'caliber' => null,
                    'brand_make' => null,
                    'serial_number' => null,
                    'classification' => 'حرز قضية',
                    'current_status' => 'مضبوط',
                    'related_report_number' => $report->report_number,
                    'holder_info' => null,
                    'capture_date_time' => $now,
                    'weapon_condition' => $seizure['condition'] ?? null,
                    'notes' => $seizure['description'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                DB::table('report_weapons')->insert([
                    'report_id' => $report->id,
                    'weapon_id' => $weaponId,
                    'name' => $seizure['name'] ?? null,
                    'quantity' => $seizure['quantity'] ?? null,
                    'condition' => $seizure['condition'] ?? null,
                    'description' => $seizure['description'] ?? null,
                    'link_source' => 'migrated',
                    'confidence_score' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('report_weapons');
        Schema::dropIfExists('report_persons');
        Schema::dropIfExists('person_weapons');
        Schema::dropIfExists('person_associates');
        Schema::dropIfExists('person_convictions');
        Schema::dropIfExists('person_phones');
        Schema::dropIfExists('person_addresses');
        Schema::dropIfExists('person_aliases');

        Schema::table('suspects', function (Blueprint $table) {
            if (Schema::hasColumn('suspects', 'national_id_hash')) {
                $table->dropIndex(['national_id_hash']);
                $table->dropColumn('national_id_hash');
            }
        });
    }
};
