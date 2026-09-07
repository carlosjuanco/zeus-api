<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Casts\TelephoneCast;
use App\Casts\DateFormatCast;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'paternal_surname',
        'maternal_surname',
        'curp',
        'rfc',
        'gender',
        'budget_code',
        'funcion',
        'telephone',
        'reason',
        'date_of_entry_into_the_sep',
        'study_profile',
        'language',
        'language_variant',
        'school_id',
        'human_id',
    ];

    /**
     * Los casts de los atributos del modelo.
     *
     * @var array
     */
    protected $casts = [
        // Cast para fecha de ingreso (formato dd/mm/YYYY al obtener, y se guarda como YYYY-mm-dd)
        'date_of_entry_into_the_sep' => DateFormatCast::class . ':d/m/Y',
        // Cast para teléfono (formato xxx xxx xxxx al obtener, y al guardar como xxxxxxxxxx)
        'telephone' => TelephoneCast::class,
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}