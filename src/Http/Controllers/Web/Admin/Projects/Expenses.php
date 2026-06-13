<?php

namespace Dev3bdulrahman\Projects\Http\Controllers\Web\Admin\Projects;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Dev3bdulrahman\Projects\Models\Project;

class Expenses extends Component
{
    public $projectId;
    public $project;

    // Expense fields
    public $category = '';
    public $amount = '';
    public $date = '';
    public $description = '';

    public $showExpenseModal = false;

    public $expensesList = [];

    #[Layout('layouts.admin')]
    public function mount($project)
    {
        $this->projectId = $project;
        $this->project = Project::findOrFail($this->projectId);
        $this->date = now()->format('Y-m-d');

        // Initial mock data
        $this->expensesList = [
            [
                'id' => 1,
                'category' => 'Software Licenses',
                'amount' => 150.00,
                'date' => now()->subDays(5)->format('Y-m-d'),
                'description' => 'Figma and Github Copilot licenses',
            ],
            [
                'id' => 2,
                'category' => 'Marketing',
                'amount' => 500.00,
                'date' => now()->subDays(2)->format('Y-m-d'),
                'description' => 'Google Ads campaign launch',
            ],
        ];
    }

    public function openExpenseModal()
    {
        $this->resetForm();
        $this->showExpenseModal = true;
    }

    public function resetForm()
    {
        $this->category = '';
        $this->amount = '';
        $this->date = now()->format('Y-m-d');
        $this->description = '';
    }

    public function save()
    {
        $this->validate([
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $this->expensesList[] = [
            'id' => count($this->expensesList) + 1,
            'category' => $this->category,
            'amount' => (float)$this->amount,
            'date' => $this->date,
            'description' => $this->description,
        ];

        $this->showExpenseModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $totalExpenses = collect($this->expensesList)->sum('amount');

        return view('projects::livewire.admin.projects.expenses', [
            'expenses' => $this->expensesList,
            'totalExpenses' => $totalExpenses,
        ])->title($this->project->name . ' - ' . __('projects::projects.expenses'));
    }
}
