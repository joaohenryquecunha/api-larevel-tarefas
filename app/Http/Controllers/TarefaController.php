<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarefaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Tarefa::where('user_id', $user->id);

        $type = $request->input('type', 0);
    
        if ($type == 1) {
            $today = now()->toDateString();
            $query->whereDate('due_date', $today);
        } else if ($type == 2) {
            $today = now()->toDateString();
            $query->whereDate('due_date', '<', $today);
        }else if ($type == 3) {
            $query->where('status', 'completed');
        }
    
        $tarefas = $query->with('subtarefas')->orderBy('created_at', 'DESC')->get();
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

        $validatedData['user_id'] = Auth::id();

        $tarefa = Tarefa::create($validatedData);

        return response()->json([
            'message' => 'Successful created task',
            'status' => 'success',
            'tarefa' => $tarefa
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarefa $tarefa)
    {
        $this->authorizeUser($tarefa);
        return response()->json($tarefa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarefa $tarefa)
    {
        $this->authorizeUser($tarefa);

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:1000',
            'due_date' => 'sometimes|date|after_or_equal:today',
            'status' => 'sometimes|required|in:pending,completed',
        ]);

        $tarefa->update(array_filter($validatedData));

        return response()->json(['message' => 'Successful updated task', 'tarefa' => $tarefa], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarefa $tarefa)
    {
        $this->authorizeUser($tarefa);

        $tarefa->delete();
        return response()->json([
            'message' => 'Successful deleted task',
            'status' => 'success'
        ], 200);
    }

    /**
     * Check if the authenticated user is authorized to perform actions on the task.
     */
    private function authorizeUser(Tarefa $tarefa)
    {
        if ($tarefa->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
