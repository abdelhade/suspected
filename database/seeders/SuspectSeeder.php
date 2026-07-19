<?php

namespace Database\Seeders;

use App\Models\Suspect;
use Illuminate\Database\Seeder;

class SuspectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('جارٍ إنشاء بيانات Suspect...');

        $count = (int) env('SUSPECT_SEED_COUNT', 1000);

        if ($count < 1) {
            $this->command->warn('تم اختيار عدد غير صالح، سيتم إنشاء 1000 سجل بدلاً من ذلك.');
            $count = 1000;
        }

        $chunkSize = 100;
        $chunks = ceil($count / $chunkSize);

        for ($i = 0; $i < $chunks; $i++) {
            $remaining = $count - ($i * $chunkSize);
            $currentChunk = min($chunkSize, $remaining);

            Suspect::factory()->count($currentChunk)->create();

            $this->command->info("تم إنشاء " . (($i + 1) * $chunkSize) . " / {$count} سجل...");
        }

        $this->command->info("تم إنشاء {$count} سجل Suspect بنجاح.");
    }
}
