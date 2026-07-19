<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonWeapon extends Model
{
    use HasFactory;

    protected $table = 'person_weapons';

    protected $fillable = [
        'person_id',
        'weapon_id',
        'relationship',
        'linked_at',
        'source',
        'notes',
    ];

    protected $casts = [
        'linked_at' => 'date',
    ];

    public function person()
    {
        return $this->belongsTo(Suspect::class, 'person_id');
    }

    public function weapon()
    {
        return $this->belongsTo(Weapon::class, 'weapon_id');
    }
}
