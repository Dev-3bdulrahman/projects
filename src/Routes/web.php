<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'role:super-admin|developer|admin|employee', 'license'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/projects', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Index::class)->name('admin.projects.index');
        Route::get('/projects/{project}/kanban', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Kanban::class)->name('admin.projects.kanban');
        Route::get('/projects/{project}/timesheet', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Timesheet::class)->name('admin.projects.timesheet');
        Route::get('/projects/{project}/expenses', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Expenses::class)->name('admin.projects.expenses');
        Route::get('/projects/{project}/milestones', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Milestones::class)->name('admin.projects.milestones');
    });
