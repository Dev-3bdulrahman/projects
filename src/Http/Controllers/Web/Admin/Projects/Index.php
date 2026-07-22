<?php

namespace Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Dev3bdulrahman\Projects\Services\ProjectsService;
use Dev3bdulrahman\Projects\Models\Project;
use App\Models\User;

class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $statusFilter = '';

    // Form fields
    public ?int $projectId = null;
    public string $name = '';
    public string $description = '';
    public string $status = 'planning';
    public string $start_date = '';
    public string $end_date = '';

    public bool $showFormModal = false;

    protected $listeners = ['delete' => 'deleteProject'];

    #[Layout('layouts.admin')]
    public function mount()
    {
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->addMonth()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $project = Project::findOrFail($id);
        $this->projectId = $project->id;
        $this->name = $project->name;
        $this->description = $project->description ?? '';
        $this->status = $project->status;
        $this->start_date = $project->start_date ? $project->start_date->format('Y-m-d') : '';
        $this->end_date = $project->end_date ? $project->end_date->format('Y-m-d') : '';
        $this->showFormModal = true;
    }

    public function resetForm()
    {
        $this->projectId = null;
        $this->name = '';
        $this->description = '';
        $this->status = 'planning';
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->addMonth()->format('Y-m-d');
    }

    public function save(ProjectsService $service)
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($this->projectId) {
            $service->updateProject($this->projectId, $validated);
        } else {
            $service->createProject($validated);
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function deleteProject(ProjectsService $service, $id)
    {
        $targetId = is_array($id) ? ($id['id'] ?? null) : $id;
        if ($targetId) {
            $service->deleteProject($targetId);
        }
    }

    public function render(ProjectsService $service)
    {
        $query = Project::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $projects = $query->latest()->paginate(10);

        return view('projects::livewire.admin.projects.index', [
            'projects' => $projects,
        ])->title(__('projects::projects.title'));
    }
}
