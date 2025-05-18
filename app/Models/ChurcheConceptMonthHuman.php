<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChurcheConceptMonthHuman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'churche_concept_month_human';

    protected $fillable = [
        'churche_id',
        'concept_id',
        'month_id',
        'human_id',
        'week',
        'value',
        'accumulated',
        'status'
    ];

    // Relaciones
    public function churche()
    {
        return $this->belongsTo(Churche::class);
    }

    public function concept()
    {
        return $this->belongsTo(Concept::class);
    }

    public function month()
    {
        return $this->belongsTo(Month::class);
    }

    public function human()
    {
        return $this->belongsTo(Human::class);
    }
}
