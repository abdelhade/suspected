<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonPhone extends Model
{
    use HasFactory;

    protected $table = 'person_phones';

    protected $fillable = [
        'person_id',
        'label',
        'phone',
        'phone_hash',
        'notes',
    ];

    protected $casts = [
        'phone' => 'encrypted',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $phone) {
            if ($phone->isDirty('phone') && $phone->phone !== null) {
                $phone->phone_hash = hash('sha256', $phone->phone);
            }
        });
    }

    public function person()
    {
        return $this->belongsTo(Suspect::class, 'person_id');
    }
}
