<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonAssociate extends Model
{
    use HasFactory;

    protected $table = 'person_associates';

    protected $fillable = [
        'person_id',
        'associate_person_id',
        'relationship_type',
        'description',
        'confidence',
        'source',
    ];

    public function person()
    {
        return $this->belongsTo(Suspect::class, 'person_id');
    }

    public function associate()
    {
        return $this->belongsTo(Suspect::class, 'associate_person_id');
    }
}
