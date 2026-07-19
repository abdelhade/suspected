<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonConviction extends Model
{
    use HasFactory;

    protected $table = 'person_convictions';

    protected $fillable = [
        'person_id',
        'charge',
        'verdict_date',
        'sentence',
        'court',
        'notes',
    ];

    protected $casts = [
        'verdict_date' => 'date',
    ];

    public function person()
    {
        return $this->belongsTo(Suspect::class, 'person_id');
    }
}
