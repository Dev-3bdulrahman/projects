<?php

namespace Dev3bdulrahman\Projects\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Dev3bdulrahman\Projects\Events\TaskCreated;
use Dev3bdulrahman\Projects\Listeners\NotifyTaskAssignee;
use Dev3bdulrahman\Projects\Models\Project;
use Dev3bdulrahman\Projects\Models\Task;
use Dev3bdulrahman\Projects\Policies\ProjectPolicy;
use Dev3bdulrahman\Projects\Policies\TaskPolicy;

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

        // Register Policies
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);

        // Register Event Listeners
        Event::listen(TaskCreated::class, NotifyTaskAssignee::class);

        // Register Livewire Components
        if (class_exists(Livewire::class)) {
            Livewire::component('projects-index', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Index::class);
            Livewire::component('projects-kanban', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Kanban::class);
            Livewire::component('projects-timesheet', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Timesheet::class);
            Livewire::component('projects-expenses', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Expenses::class);
            Livewire::component('projects-milestones', \Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects\Milestones::class);
        }
    }
}
