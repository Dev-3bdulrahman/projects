<?php

namespace Dev3bdulrahman\Projects\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\HasApiResponse;
use Dev3bdulrahman\Projects\Http\Requests\Api\StoreProjectApiRequest;
use Dev3bdulrahman\Projects\Http\Requests\Api\UpdateProjectApiRequest;
use Dev3bdulrahman\Projects\Http\Requests\Api\StoreTaskApiRequest;
use Dev3bdulrahman\Projects\Http\Requests\Api\UpdateTaskApiRequest;
use Dev3bdulrahman\Projects\Models\Project;
use Dev3bdulrahman\Projects\Models\Task;
use Dev3bdulrahman\Projects\Services\ProjectsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectsApiController extends Controller
{
    use HasApiResponse;

    protected ProjectsService $projectsService;

    public function __construct(ProjectsService $projectsService)
    {
        $this->projectsService = $projectsService;
    }

    /**
     * List all projects.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Project::class);

        $projects = $this->projectsService->getCompanyProjects();

        return $this->success($projects, __('projects::projects.projects_retrieved'));
    }

    /**
     * Store a new project.
     */
    public function store(StoreProjectApiRequest $request): JsonResponse
    {
        $this->authorize('create', Project::class);

        $project = $this->projectsService->createProject($request->validated());

        return $this->success($project, __('projects::projects.project_created'), 201);
    }

    /**
     * Show a single project.
     */
    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        return $this->success($project, __('projects::projects.project_retrieved'));
    }

    /**
     * Update an existing project.
     */
    public function update(UpdateProjectApiRequest $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $project = $this->projectsService->updateProject($project->id, $request->validated());

        return $this->success($project, __('projects::projects.project_updated'));
    }

    /**
     * Delete a project.
     */
    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $this->projectsService->deleteProject($project->id);

        return $this->success(null, __('projects::projects.project_deleted'));
    }

    /**
     * List tasks for a project.
     */
    public function tasks(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        return $this->success($project->tasks, __('projects::projects.tasks_retrieved'));
    }

    /**
     * Store a new task for a project.
     */
    public function storeTask(StoreTaskApiRequest $request, Project $project): JsonResponse
    {
        $this->authorize('create', Task::class);

        $validated = $request->validated();
        $validated['project_id'] = $project->id;

        $task = $this->projectsService->createTask($validated);

        return $this->success($task, __('projects::projects.task_created'), 201);
    }

    /**
     * Show a single task.
     */
    public function showTask(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        return $this->success($task, __('projects::projects.task_retrieved'));
    }

    /**
     * Update an existing task.
     */
    public function updateTask(UpdateTaskApiRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validated();
        $task->update($validated);

        return $this->success($task->fresh(), __('projects::projects.task_updated'));
    }

    /**
     * Delete a task.
     */
    public function destroyTask(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return $this->success(null, __('projects::projects.task_deleted'));
    }
}
