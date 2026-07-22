<?php

use Illuminate\Support\Facades\Route;
use Dev3bdulrahman\Projects\Http\Controllers\Api\ProjectsApiController;

Route::prefix('api/v1/projects')->middleware(['auth:sanctum', 'throttle:60,1', 'api.tenant'])->group(function () {
    // Projects
    Route::get('/projects', [ProjectsApiController::class, 'index'])->middleware('can:projects.projects.view')->name('api.v1.projects.projects.index');
    Route::post('/projects', [ProjectsApiController::class, 'store'])->middleware('can:projects.projects.create')->name('api.v1.projects.projects.store');
    Route::get('/projects/{project}', [ProjectsApiController::class, 'show'])->middleware('can:projects.projects.view')->name('api.v1.projects.projects.show');
    Route::put('/projects/{project}', [ProjectsApiController::class, 'update'])->middleware('can:projects.projects.edit')->name('api.v1.projects.projects.update');
    Route::delete('/projects/{project}', [ProjectsApiController::class, 'destroy'])->middleware('can:projects.projects.delete')->name('api.v1.projects.projects.destroy');
    Route::get('/projects/{project}/tasks', [ProjectsApiController::class, 'tasks'])->middleware('can:projects.tasks.view')->name('api.v1.projects.projects.tasks');
    Route::post('/projects/{project}/tasks', [ProjectsApiController::class, 'storeTask'])->middleware('can:projects.tasks.create')->name('api.v1.projects.projects.tasks.store');

    // Tasks
    Route::get('/tasks/{task}', [ProjectsApiController::class, 'showTask'])->middleware('can:projects.tasks.view')->name('api.v1.projects.tasks.show');
    Route::put('/tasks/{task}', [ProjectsApiController::class, 'updateTask'])->middleware('can:projects.tasks.edit')->name('api.v1.projects.tasks.update');
    Route::delete('/tasks/{task}', [ProjectsApiController::class, 'destroyTask'])->middleware('can:projects.tasks.delete')->name('api.v1.projects.tasks.destroy');
});
