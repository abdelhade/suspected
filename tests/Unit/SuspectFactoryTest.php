<?php

namespace Tests\Unit;

use App\Models\Suspect;
use Tests\TestCase;

class SuspectFactoryTest extends TestCase
{
    public function test_factory_creates_a_valid_suspect_record(): void
    {
        $suspect = Suspect::factory()->make();

        $this->assertNotEmpty($suspect->full_name);
        $this->assertNotEmpty($suspect->national_id);
        $this->assertNotNull($suspect->birth_date);
        $this->assertNotEmpty($suspect->current_address);
    }
}
