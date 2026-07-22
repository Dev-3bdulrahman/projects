<?php

namespace Dev3bdulrahman\Projects\Policies;

use App\Models\User;
use Dev3bdulrahman\Projects\Models\Task;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('projects.tasks.view');
    }

    public function view(User $user, Task $task): bool
    {
        return $user->can('projects.tasks.view') && $task->project && $task->project->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('projects.tasks.create');
    }

    public function update(User $user, Task $task): bool
    {
        return $user->can('projects.tasks.update') && $task->project && $task->project->company_id === $user->company_id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->can('projects.tasks.delete') && $task->project && $task->project->company_id === $user->company_id;
    }
}
