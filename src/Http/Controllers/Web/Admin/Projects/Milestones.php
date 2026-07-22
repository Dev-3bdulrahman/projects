<?php

namespace Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Dev3bdulrahman\Projects\Models\Project;
use Dev3bdulrahman\Projects\Models\Milestone;
use Dev3bdulrahman\Projects\Models\Task;

class Milestones extends Component
{
    public $projectId;
    public $project;

    public $milestoneId = null;
    public $name = '';
    public $description = '';
    public $due_date = '';
    public $status = 'pending';

    public $showModal = false;
    public $search = '';

    // Task assignment fields
    public $selectedMilestoneIdForTasks = null;
    public $showTasksModal = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|string|in:pending,completed',
        ];
    }

    #[Layout('layouts.admin')]
    public function mount($project)
    {
        $this->projectId = $project;
        $this->project = Project::findOrFail($this->projectId);
        $this->due_date = now()->format('Y-m-d');
    }

    public function openModal(?int $id = null): void
    {
        $this->resetFields();
        if ($id) {
            $m = Milestone::where('project_id', $this->projectId)->findOrFail($id);
            $this->milestoneId = $m->id;
            $this->name = $m->name;
            $this->description = $m->description ?? '';
            $this->due_date = $m->due_date ? $m->due_date->format('Y-m-d') : '';
            $this->status = $m->status;
        }
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetFields();
    }

    public function resetFields(): void
    {
        $this->reset(['milestoneId', 'name', 'description', 'status']);
        $this->due_date = now()->format('Y-m-d');
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate();
        $companyId = session('active_company_id', 1);

        $data = [
            'company_id' => $companyId,
            'project_id' => $this->projectId,
            'name' => $this->name,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'status' => $this->status,
        ];

        if ($this->milestoneId) {
            Milestone::where('project_id', $this->projectId)->findOrFail($this->milestoneId)->update($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('projects::projects.milestone_updated')]);
        } else {
            Milestone::create($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => __('projects::projects.milestone_created')]);
        }

        $this->closeModal();
    }

    #[On('delete')]
    public function delete($id): void
    {
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        if ($targetId) {
            Milestone::where('project_id', $this->projectId)->findOrFail($targetId)->delete();
            $this->dispatch('notify', ['type' => 'success', 'message' => __('projects::projects.milestone_deleted')]);
        }
    }

    public function toggleStatus(int $id): void
    {
        $m = Milestone::where('project_id', $this->projectId)->findOrFail($id);
        $m->status = $m->status === 'completed' ? 'pending' : 'completed';
        $m->save();

        $this->dispatch('notify', ['type' => 'success', 'message' => __('projects::projects.milestone_updated')]);
    }

    public function openTasksModal(int $milestoneId): void
    {
        $this->selectedMilestoneIdForTasks = $milestoneId;
        $this->showTasksModal = true;
    }

    public function closeTasksModal(): void
    {
        $this->showTasksModal = false;
        $this->selectedMilestoneIdForTasks = null;
    }

    public function assignTaskToMilestone($taskId): void
    {
        if ($this->selectedMilestoneIdForTasks) {
            $task = Task::where('project_id', $this->projectId)->findOrFail($taskId);
            $task->milestone_id = $this->selectedMilestoneIdForTasks;
            $task->save();
            $this->dispatch('notify', ['type' => 'success', 'message' => __('projects::projects.task_assigned')]);
        }
    }

    public function removeTaskFromMilestone($taskId): void
    {
        $task = Task::where('project_id', $this->projectId)->findOrFail($taskId);
        $task->milestone_id = null;
        $task->save();
        $this->dispatch('notify', ['type' => 'success', 'message' => __('projects::projects.task_removed')]);
    }

    public function render()
    {
        $companyId = session('active_company_id', 1);

        $milestones = Milestone::where('company_id', $companyId)
            ->where('project_id', $this->projectId)
            ->where('name', 'like', '%' . $this->search . '%')
            ->with(['tasks'])
            ->orderBy('due_date')
            ->get();

        // Calculate progress for each milestone
        foreach ($milestones as $milestone) {
            $totalTasks = $milestone->tasks->count();
            $completedTasks = $milestone->tasks->where('status', 'done')->count();
            $milestone->progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        }

        // Unassigned tasks for the project to allow assignment
        $unassignedTasks = Task::where('project_id', $this->projectId)
            ->whereNull('milestone_id')
            ->get();

        return view('projects::livewire.admin.projects.milestones', [
            'milestones' => $milestones,
            'unassignedTasks' => $unassignedTasks,
            'selectedMilestone' => $this->selectedMilestoneIdForTasks ? Milestone::find($this->selectedMilestoneIdForTasks) : null,
        ])->title($this->project->name . ' - ' . __('projects::projects.milestones'));
    }
}
