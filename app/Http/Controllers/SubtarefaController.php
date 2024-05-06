<?php

namespace App\Http\Controllers;

use App\Models\Subtarefa;
use App\Models\Tarefa;
use Illuminate\Http\Request;

class SubtarefaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Tarefa $tarefa)
    {
        return response()->json($tarefa->subtarefas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Tarefa $tarefa)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,completed',
        ]);

        $subtarefa = $tarefa->subtarefas()->create($request->all());

        return response()->json($subtarefa, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subtarefa $subtarefa)
    {
        return response()->json($subtarefa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subtarefa $subtarefa)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,completed',
        ]);

        $subtarefa->update($request->all());

        return response()->json($subtarefa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subtarefa $subtarefa)
    {
        $subtarefa->delete();

        return response()->json(['message' => 'Sucessful deleted subtask'], 204);
    }
}
