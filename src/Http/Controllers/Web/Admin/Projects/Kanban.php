<?php

namespace Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Dev3bdulrahman\Projects\Models\Project;
use Dev3bdulrahman\Projects\Models\Task;
use Dev3bdulrahman\Projects\Models\Milestone;
use Dev3bdulrahman\Projects\Services\ProjectsService;
use App\Models\User;

class Kanban extends Component
{
    public $projectId;
    public $project;

    // Task form fields
    public $taskId = null;
    public $milestone_id = null;
    public $name = '';
    public $description = '';
    public $status = 'todo';
    public $priority = 'medium';
    public $due_date = '';
    public $assigned_to = null;

    public $showTaskModal = false;

    protected $listeners = ['deleteTask' => 'deleteTask'];

    #[Layout('layouts.admin')]
    public function mount($project)
    {
        $this->projectId = $project;
        $this->project = Project::with('tasks.milestone')->findOrFail($this->projectId);
    }

    public function moveTask(ProjectsService $service, $taskId, $newStatus)
    {
        $service->updateTaskStatus($taskId, $newStatus);
        $this->project = Project::with('tasks.milestone')->findOrFail($this->projectId);
    }

    public function openCreateModal($status = 'todo')
    {
        $this->resetForm();
        $this->status = $status;
        $this->showTaskModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $task = Task::findOrFail($id);
        $this->taskId = $task->id;
        $this->milestone_id = $task->milestone_id;
        $this->name = $task->name;
        $this->description = $task->description ?? '';
        $this->status = $task->status;
        $this->priority = $task->priority;
        $this->due_date = $task->due_date ? $task->due_date->format('Y-m-d') : '';
        $this->assigned_to = $task->assigned_to;
        $this->showTaskModal = true;
    }

    public function resetForm()
    {
        $this->taskId = null;
        $this->milestone_id = null;
        $this->name = '';
        $this->description = '';
        $this->status = 'todo';
        $this->priority = 'medium';
        $this->due_date = '';
        $this->assigned_to = null;
    }

    public function save(ProjectsService $service)
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'milestone_id' => 'nullable|exists:project_milestones,id',
        ]);

        $validated['project_id'] = $this->projectId;

        if ($this->taskId) {
            $service->updateTask($this->taskId, $validated);
        } else {
            $service->createTask($validated);
        }

        $this->showTaskModal = false;
        $this->resetForm();
        $this->project = Project::with('tasks.milestone')->findOrFail($this->projectId);
    }

    public function deleteTask(ProjectsService $service, $id)
    {
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        if ($targetId) {
            $service->deleteTask($targetId);
            $this->project = Project::with('tasks.milestone')->findOrFail($this->projectId);
        }
    }

    public function render()
    {
        $users = User::all();

        $todoTasks = $this->project->tasks()->where('status', 'todo')->get();
        $inProgressTasks = $this->project->tasks()->where('status', 'in_progress')->get();
        $reviewTasks = $this->project->tasks()->where('status', 'review')->get();
        $doneTasks = $this->project->tasks()->where('status', 'done')->get();

        $milestones = Milestone::where('project_id', $this->projectId)->get();

        return view('projects::livewire.admin.projects.kanban', [
            'users' => $users,
            'todoTasks' => $todoTasks,
            'inProgressTasks' => $inProgressTasks,
            'reviewTasks' => $reviewTasks,
            'doneTasks' => $doneTasks,
            'milestones' => $milestones,
        ])->title($this->project->name . ' - ' . __('projects::projects.kanban'));
    }
}
