<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suspect extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'alias_name',
        'national_id',
        'birth_date',
        'current_address',
        'registration_category',
        'danger_level',
        'criminal_activity',
        'current_status',
        'distinguishing_marks',
        'height_cm',
        'body_build',
        'profile_image_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'height_cm'  => 'integer',
    ];
}
