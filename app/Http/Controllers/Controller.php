<?php

namespace App\Http\Controllers;

use App\Models\Receta; // Importa tu modelo Receta
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; // Para retornar respuestas JSON

class RecetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $recetas = Receta::all(); // Obtiene todas las recetas de la base de datos
        return response()->json($recetas); // Retorna las recetas como JSON
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Valida los datos de entrada
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tiempo_preparacion' => 'required|integer|min:1',
        ]);

        // Crea una nueva receta
        $receta = Receta::create($validatedData);

        // Retorna la receta creada con un código de estado 201 (Created)
        return response()->json($receta, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Receta $receta): JsonResponse
    {
        return response()->json($receta);
    }

}
