<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Acta;
use Illuminate\Http\Request;

class ActaController extends Controller
{

    public function index()
    {
        $actas = Acta::latest()->get();

        return response()->json($actas);
    }
}