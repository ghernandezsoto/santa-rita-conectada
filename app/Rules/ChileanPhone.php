<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
// Importamos la librería de utilidades chilenas
use Freshwork\ChileanBundle\Chilean;

class ChileanPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Usamos la función de validación de teléfono que sí viene en el paquete.
        if (!Chilean::validatePhone($value)) {
            $fail('El formato del campo :attribute no es un teléfono chileno válido.');
        }
    }
}