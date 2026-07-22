<?php

namespace Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Dev3bdulrahman\Projects\Models\Project;
use Dev3bdulrahman\Projects\Models\Task;
use Dev3bdulrahman\Projects\Models\TaskTimeLog;
use Dev3bdulrahman\Projects\Services\ProjectsService;
use App\Models\User;

class Timesheet extends Component
{
    public $projectId;
    public $project;

    // Log time fields
    public $task_id;
    public $user_id;
    public $hours;
    public $date;
    public $description = '';

    public $showLogModal = false;

    #[Layout('layouts.admin')]
    public function mount($project)
    {
        $this->projectId = $project;
        $this->project = Project::with(['tasks.timeLogs.user'])->findOrFail($this->projectId);
        $this->date = now()->format('Y-m-d');
    }

    public function openLogModal()
    {
        $this->resetForm();
        $this->showLogModal = true;
    }

    public function resetForm()
    {
        $this->task_id = null;
        $this->user_id = null;
        $this->hours = '';
        $this->date = now()->format('Y-m-d');
        $this->description = '';
    }

    public function save(ProjectsService $service)
    {
        $validated = $this->validate([
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
            'hours' => 'required|numeric|min:0.1|max:24',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $service->logTime(
            $validated['task_id'],
            $validated['user_id'],
            $validated['hours'],
            $validated['date'],
            $validated['description']
        );

        $this->showLogModal = false;
        $this->resetForm();
        $this->project = Project::with(['tasks.timeLogs.user'])->findOrFail($this->projectId);
    }

    public function render()
    {
        $users = User::all();
        $tasks = $this->project->tasks;

        $timeLogs = TaskTimeLog::whereHas('task', function ($query) {
            $query->where('project_id', $this->projectId);
        })->with(['task', 'user'])->latest()->get();

        $totalHours = $timeLogs->sum('hours');

        return view('projects::livewire.admin.projects.timesheet', [
            'users' => $users,
            'tasks' => $tasks,
            'timeLogs' => $timeLogs,
            'totalHours' => $totalHours,
        ])->title($this->project->name . ' - ' . __('projects::projects.timesheet'));
    }
}
