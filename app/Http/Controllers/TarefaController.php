<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tarefas = Tarefa::paginate();
        return response()->json($tarefas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $tarefa = Tarefa::create($validatedData);

        return response()->json(['message' => 'Sucessful created task', $tarefa], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarefa $tarefa)
    {
        return response()->json($tarefa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarefa $tarefa)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:1000',
            'due_date' => 'sometimes|date|after_or_equal:today',
        ]);

        $tarefa->update(array_filter($validatedData));

        return response()->json(['message' => 'Successful updated task', $tarefa],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarefa $tarefa)
    {
        Tarefa::destroy($tarefa);
        return response()->json(['message' => 'Successful deleted task'], 204);
    }
}
