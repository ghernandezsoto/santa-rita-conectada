<?php

namespace App\Http\Controllers;

use App\Models\Subsidio;
use Illuminate\Http\Request;

class SubsidioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Usamos with() para cargar las relaciones y evitar consultas N+1 (más eficiente).
        $subsidios = Subsidio::with('socio', 'user')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('subsidios.index', compact('subsidios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Subsidio $subsidio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subsidio $subsidio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subsidio $subsidio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subsidio $subsidio)
    {
        //
    }
}
