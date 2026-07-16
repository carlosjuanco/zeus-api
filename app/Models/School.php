<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'key',
        'type_of_school',
        'community_id',
        'secondary_number',
        'human_id',
    ];

    protected $appends = ['name_community']; // Para que siempre esté disponible

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function getNameCommunityAttribute()
    {
        return $this->community ? $this->community->name : null;
    }
}