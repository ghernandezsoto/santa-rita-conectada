<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Acta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActaController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Acta $acta): View
    {
        return view('portal.actas.show', compact('acta'));
    }
}