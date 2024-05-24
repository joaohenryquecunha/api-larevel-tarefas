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
    public function index()
    {
        $subtarefa = Subtarefa::orderBy('created_at', 'desc')->paginate();
        return response()->json($subtarefa);
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

        return response()->json([
            'status' => 'success',
            'message' => 'Subtarefa criada com sucesso!',
            $subtarefa], 
            201);
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
    public function update(Request $request, $tarefaId, $subtarefaId)
    {
        $tarefa = Tarefa::find($tarefaId);
    
        if (!$tarefa) {
            return response()->json(['message' => 'Task not found'], 404);
        }
    
        $subtarefa = $tarefa->subtarefas()->find($subtarefaId);
    
        if (!$subtarefa) {
            return response()->json(['message' => 'Subtask not found'], 404);
        }
    
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|required|in:pending,completed',
        ]);
    
        $subtarefa->update($request->all());
    
        return response()->json(['message' => 'Successfully updated subtask', 'subtarefa' => $subtarefa], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($tarefaId, $subtarefaId)
{
    // Use uma única consulta para buscar a subtarefa diretamente
    $subtarefa = Subtarefa::where('task_id', $tarefaId)->find($subtarefaId);

    if (!$subtarefa) {
        return response()->json(['message' => 'Task or subtask not found'], 404);
    }

    $subtarefa->delete();

    return response()->json([
        'message' => 'Successfully deleted subtask',
        'status' => 'success'
    ], 200);
}
}
