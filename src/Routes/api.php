<?php

use Illuminate\Support\Facades\Route;
use Dev3bdulrahman\Projects\Http\Controllers\Api\ProjectsApiController;

Route::prefix('api/v1/projects')->middleware(['api', 'auth'])->group(function () {
    Route::get('/', [ProjectsApiController::class, 'index']);
    Route::post('/', [ProjectsApiController::class, 'store']);
    Route::get('{id}', [ProjectsApiController::class, 'show']);
    Route::put('{id}', [ProjectsApiController::class, 'update']);
    Route::delete('{id}', [ProjectsApiController::class, 'destroy']);
    Route::get('{id}/tasks', [ProjectsApiController::class, 'tasks']);
    Route::post('{id}/tasks', [ProjectsApiController::class, 'storeTask']);
});
