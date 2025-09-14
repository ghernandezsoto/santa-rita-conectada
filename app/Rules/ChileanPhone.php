<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
// --- CORRECCIÓN AQUÍ: Se añade el namespace correcto 'Validations' ---
use Freshwork\ChileanBundle\Validations\Chilean;

class ChileanPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Esta lógica ya era correcta y ahora encontrará la clase sin problemas.
        if (!Chilean::validatePhone($value)) {
            $fail('El formato del campo :attribute no es un teléfono chileno válido.');
        }
    }
}