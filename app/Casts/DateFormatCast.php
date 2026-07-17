<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use InvalidArgumentException;

class DateFormatCast implements CastsAttributes
{
    /**
     * Formato de salida que deseas mostrar (ej. dd/mm/YYYY)
     */
    protected string $outputFormat;

    /**
     * Formato de entrada que esperas al asignar (ej. dd/mm/YYYY)
     */
    protected string $inputFormat;

    /**
     * Crear una nueva instancia del cast.
     *
     * @param  string  $outputFormat  Formato para mostrar (ej. 'dd/mm/YYYY')
     * @param  string  $inputFormat   Formato esperado al asignar (por defecto igual a $outputFormat)
     */
    public function __construct(string $outputFormat = 'd/m/Y', string $inputFormat = 'd/m/Y')
    {
        $this->outputFormat = $outputFormat;
        $this->inputFormat = $inputFormat;
    }

    /**
     * Transformar el valor de la base de datos al formato deseado (dd/mm/YYYY).
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function get($model, string $key, $value, array $attributes): ?string
    {
        // Si el valor es null, devolver null
        if (is_null($value)) {
            return null;
        }

        // Intentar parsear el valor como fecha
        try {
            $date = Carbon::parse($value);
            return $date->format($this->outputFormat);
        } catch (\Exception $e) {
            // Si no se puede parsear, devolver el valor original
            return $value;
        }
    }

    /**
     * Transformar el valor asignado (dd/mm/YYYY) al formato de base de datos (YYYY-mm-dd).
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string|null
     */
    public function set($model, string $key, $value, array $attributes): ?string
    {
        // Si el valor es null o vacío, devolver null
        if (is_null($value) || $value === '') {
            return null;
        }

        // Intentar crear una fecha desde el valor
        try {
            // Si es un string, intentar parsearlo con el formato de entrada
            if (is_string($value)) {
                $date = Carbon::createFromFormat($this->inputFormat, $value);
            } else {
                // Si es una instancia de Carbon o similar, usarla directamente
                $date = Carbon::parse($value);
            }
            
            // Devolver en formato estándar de base de datos (YYYY-mm-dd)
            return $date->format('Y-m-d');
        } catch (\Exception $e) {
            // Si no se puede parsear, lanzar una excepción o devolver null
            throw new InvalidArgumentException("El valor '{$value}' no es una fecha válida en el formato esperado.");
        }
    }
}