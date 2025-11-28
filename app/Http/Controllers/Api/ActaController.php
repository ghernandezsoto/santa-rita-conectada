<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Acta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ActaController extends Controller
{
    public function index()
    {
        $actas = Acta::latest()->get();

        $actas->transform(function ($acta) {
            if ($acta->archivo_path) {
                $acta->archivo_path = URL::temporarySignedRoute(
                    'actas.publico',
                    now()->addMinutes(180),
                    ['acta' => $acta->id]
                );
            }
            return $acta;
        });

        return response()->json($actas);
    }
}