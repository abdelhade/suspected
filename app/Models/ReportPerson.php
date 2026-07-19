<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportPerson extends Model
{
    use HasFactory;

    protected $table = 'report_persons';

    protected $fillable = [
        'report_id',
        'person_id',
        'role',
        'full_name',
        'national_id',
        'national_id_hash',
        'nationality',
        'age',
        'occupation',
        'address',
        'phone',
        'phone_hash',
    ];

    protected $casts = [
        'national_id' => 'encrypted',
        'phone'       => 'encrypted',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $person) {
            if ($person->isDirty('phone') && $person->phone !== null) {
                $person->phone_hash = hash('sha256', $person->phone);
            }
            if ($person->isDirty('national_id') && $person->national_id !== null) {
                $person->national_id_hash = hash('sha256', $person->national_id);
            }
        });
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function suspect()
    {
        return $this->belongsTo(Suspect::class, 'person_id');
    }
}
