<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);


        // Al actualizar la contraseña, también se registra la fecha y hora actual
        // en 'password_changed_at'. Este es el paso clave que "libera" al usuario.
        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);


        return redirect()->route('dashboard')->with('status', 'Contraseña actualizada correctamente.');
    }
}