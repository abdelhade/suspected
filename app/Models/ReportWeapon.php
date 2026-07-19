<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportWeapon extends Model
{
    use HasFactory;

    protected $table = 'report_weapons';

    protected $fillable = [
        'report_id',
        'weapon_id',
        'name',
        'quantity',
        'condition',
        'description',
        'link_source',
        'confidence_score',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function weapon()
    {
        return $this->belongsTo(Weapon::class, 'weapon_id');
    }
}
