<?php

use Illuminate\Support\Facades\Route;
use Dev3bdulrahman\Projects\Http\Controllers\Api\ProjectsApiController;

Route::prefix('api/v1/projects')->middleware(['auth:sanctum', 'throttle:60,1', 'api.tenant'])->group(function () {
    // Projects
    Route::get('/projects', [ProjectsApiController::class, 'index'])->name('api.v1.projects.projects.index');
    Route::post('/projects', [ProjectsApiController::class, 'store'])->name('api.v1.projects.projects.store');
    Route::get('/projects/{project}', [ProjectsApiController::class, 'show'])->name('api.v1.projects.projects.show');
    Route::put('/projects/{project}', [ProjectsApiController::class, 'update'])->name('api.v1.projects.projects.update');
    Route::delete('/projects/{project}', [ProjectsApiController::class, 'destroy'])->name('api.v1.projects.projects.destroy');
    Route::get('/projects/{project}/tasks', [ProjectsApiController::class, 'tasks'])->name('api.v1.projects.projects.tasks');
    Route::post('/projects/{project}/tasks', [ProjectsApiController::class, 'storeTask'])->name('api.v1.projects.projects.tasks.store');

    // Tasks
    Route::get('/tasks/{task}', [ProjectsApiController::class, 'showTask'])->name('api.v1.projects.tasks.show');
    Route::put('/tasks/{task}', [ProjectsApiController::class, 'updateTask'])->name('api.v1.projects.tasks.update');
    Route::delete('/tasks/{task}', [ProjectsApiController::class, 'destroyTask'])->name('api.v1.projects.tasks.destroy');
});
