<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Human extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'paternal_surname',
        'paternal_surname',
    ];
}
