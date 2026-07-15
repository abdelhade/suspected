<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonAlias extends Model
{
    use HasFactory;

    protected $table = 'person_aliases';

    protected $fillable = [
        'person_id',
        'alias',
        'alias_type',
        'source',
        'notes',
    ];

    public function person()
    {
        return $this->belongsTo(Suspect::class, 'person_id');
    }
}
