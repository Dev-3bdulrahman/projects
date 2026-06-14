<?php

namespace Dev3bdulrahman\Projects\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ProjectsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Views', 'projects');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../Translations', 'projects');

        // Register Livewire Components
        Livewire::component('projects-index', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Index::class);
        Livewire::component('projects-kanban', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Kanban::class);
        Livewire::component('projects-timesheet', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Timesheet::class);
        Livewire::component('projects-expenses', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Expenses::class);
        Livewire::component('projects-milestones', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Milestones::class);
    }
}
