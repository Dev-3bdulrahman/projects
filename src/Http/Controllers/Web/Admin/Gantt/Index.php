<?php

namespace Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Gantt;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Dev3bdulrahman\Projects\Services\GanttService;
use Dev3bdulrahman\Projects\Models\Project;
use Dev3bdulrahman\Projects\Models\Task;

class Index extends Component
{
    public string $projectId = '';
    public ?array $ganttData = null;

    protected $listeners = ['updateTaskDate', 'updateTaskProgress', 'viewTask'];

    public function updatedProjectId(GanttService $service): void
    {
        if ($this->projectId) {
            $this->ganttData = $service->getProjectGanttData((int) $this->projectId);
        } else {
            $this->ganttData = null;
        }
    }

    public function updateTaskDate(string $taskId, string $start, string $end): void
    {
        $id = str_replace('task_', '', $taskId);
        $task = Task::find((int) $id);
        if ($task) {
            $task->update(['due_date' => $end]);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('projects::projects.task_updated')]);
        }
    }

    public function updateTaskProgress(string $taskId, int $progress): void
    {
        $id = str_replace('task_', '', $taskId);
        $task = Task::find((int) $id);
        if ($task) {
            $status = match (true) {
                $progress >= 100 => 'completed',
                $progress >= 75 => 'review',
                $progress >= 25 => 'in_progress',
                default => 'todo',
            };
            $task->update(['status' => $status]);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('projects::projects.task_updated')]);
        }
    }

    public function viewTask(string $taskId): void
    {
        $this->dispatch('openTaskDetail', taskId: $taskId);
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $projects = Project::where('company_id', session('active_company_id', 1))->latest()->get();

        return view('projects::livewire.admin.gantt.index', [
            'projects' => $projects,
        ])->title(__('projects::projects.gantt_chart'));
    }
}
