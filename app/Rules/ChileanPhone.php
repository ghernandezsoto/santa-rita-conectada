<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ChileanPhone implements ValidationRule
{
    /**
     * Valida que el valor sea un número de celular chileno válido.
     * No depende de ninguna librería externa.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Limpiamos el input, dejando solo los dígitos.
        $digits = preg_replace('/[^0-9]/', '', $value);

        // Un número válido puede tener 9 dígitos (9xxxxxxxx) o 11 (569xxxxxxxx).
        $isValid = false;
        if (strlen($digits) === 9 && substr($digits, 0, 1) === '9') {
            $isValid = true;
        } elseif (strlen($digits) === 11 && substr($digits, 0, 3) === '569') {
            $isValid = true;
        }

        if (!$isValid) {
            $fail('El :attribute debe ser un celular chileno válido (ej: 912345678 o +56912345678).');
        }
    }
}