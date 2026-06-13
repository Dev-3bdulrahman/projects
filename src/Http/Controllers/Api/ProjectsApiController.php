<?php

namespace Dev3bdulrahman\Projects\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dev3bdulrahman\Projects\Services\ProjectsService;

class ProjectsApiController extends Controller
{
    protected $projectsService;

    public function __construct(ProjectsService $projectsService)
    {
        $this->projectsService = $projectsService;
    }

    public function index()
    {
        $projects = $this->projectsService->getCompanyProjects();
        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $project = $this->projectsService->createProject($validated);
        return response()->json($project, 201);
    }

    public function show($id)
    {
        $project = $this->projectsService->getProject($id);
        return response()->json($project);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $project = $this->projectsService->updateProject($id, $validated);
        return response()->json($project);
    }

    public function destroy($id)
    {
        $this->projectsService->deleteProject($id);
        return response()->json(null, 204);
    }

    public function tasks($id)
    {
        $project = $this->projectsService->getProject($id);
        return response()->json($project->tasks);
    }

    public function storeTask(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $validated['project_id'] = $id;

        $task = $this->projectsService->createTask($validated);
        return response()->json($task, 201);
    }
}
