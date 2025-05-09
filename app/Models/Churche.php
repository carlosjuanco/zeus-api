<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Churche extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'human_id',
    ];

    /**
     * Relación: una iglesia pertenece a un humano.
     */
    public function human()
    {
        return $this->belongsTo(Human::class);
    }
}
