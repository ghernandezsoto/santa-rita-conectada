<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use Illuminate\Http\Request;

class ComunicadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comunicados = Comunicado::with('user')->orderBy('created_at', 'desc')->get();

        return view('comunicados.index', compact('comunicados'));
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
    public function show(Comunicado $comunicado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comunicado $comunicado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comunicado $comunicado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comunicado $comunicado)
    {
        //
    }
}
