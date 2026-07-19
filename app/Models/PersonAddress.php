<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonAddress extends Model
{
    use HasFactory;

    protected $table = 'person_addresses';

    protected $fillable = [
        'person_id',
        'address_type',
        'governorate',
        'district',
        'street',
        'building',
        'latitude',
        'longitude',
        'valid_from',
        'valid_until',
        'notes',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function person()
    {
        return $this->belongsTo(Suspect::class, 'person_id');
    }
}
