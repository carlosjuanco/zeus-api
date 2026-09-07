<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class TelephoneCast implements CastsAttributes
{
    /**
     * Transformar el valor de la base de datos al formato xxx-xxx-xxxx.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function get($model, string $key, $value, array $attributes): string
    {
        // Asegurar que tenemos un string de solo números
        $numericValue = preg_replace('/[^0-9]/', '', $value);

        // Si tiene menos de 10 dígitos, devolver el valor original sin formato
        if (strlen($numericValue) !== 10) {
            return $value;
        }

        // Formatear a xxx xxx xxxx
        return substr($numericValue, 0, 3) . ' ' .
               substr($numericValue, 3, 3) . ' ' .
               substr($numericValue, 6, 4);
    }

    /**
     * Limpiar el valor a solo números antes de guardarlo en la BD.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return int|null
     */
    public function set($model, string $key, $value, array $attributes): ?int
    {
        // Eliminar todo lo que no sea número
        $numericValue = preg_replace('/[^0-9]/', '', $value);

        // Si el valor limpio es vacío, devolver null
        if (empty($numericValue)) {
            return null;
        }

        // Convertir a entero
        return (int) $numericValue;
    }
}