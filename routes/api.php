<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\SubtarefaController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return 'o pai ta on                                  line';
});


#LOGIN
Route::post('/login', [LoginController::class, 'login'])->name('login');


#USER
Route::apiResource('users', UserController::class);



// Route::middleware(['auth:sanctum'])->group(function () {

    #TASK
    Route::apiResource('tarefas', TarefaController::class);
    
    
    #SUBTASK
    Route::apiResource('subtarefas', SubtarefaController::class);
    
    Route::group(['prefix' => 'tarefas/{tarefa}'], function () {
        Route::get('/subtarefas', [SubtarefaController::class, 'index']);
        Route::post('/subtarefas', [SubtarefaController::class, 'store']);
        Route::put('/subtarefas/{subtarefa}', [SubtarefaController::class, 'update']);
        Route::delete('/subtarefas/{subtarefa}', [SubtarefaController::class, 'delete']);
    });
// });


