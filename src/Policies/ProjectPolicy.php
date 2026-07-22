<?php

namespace Dev3bdulrahman\Projects\Policies;

use App\Models\User;
use Dev3bdulrahman\Projects\Models\Project;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('projects.projects.view');
    }

    public function view(User $user, Project $project): bool
    {
        return $user->can('projects.projects.view') && $project->company_id === $user->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('projects.projects.create');
    }

    public function update(User $user, Project $project): bool
    {
        return $user->can('projects.projects.update') && $project->company_id === $user->company_id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->can('projects.projects.delete') && $project->company_id === $user->company_id;
    }
}
