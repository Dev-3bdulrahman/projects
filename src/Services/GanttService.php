<?php

namespace Dev3bdulrahman\Projects\Services;

use Dev3bdulrahman\Projects\Models\Task;
use Dev3bdulrahman\Projects\Models\Milestone;
use Dev3bdulrahman\Projects\Models\Project;
use Carbon\Carbon;

class GanttService
{
    public function getProjectGanttData(int $projectId): array
    {
        $project = Project::with(['tasks', 'milestones'])->findOrFail($projectId);

        $tasks = $project->tasks->map(function ($task) use ($project) {
            return [
                'id' => "task_{$task->id}",
                'type' => 'task',
                'name' => $task->name,
                'start' => $project->start_date?->format('Y-m-d'),
                'end' => $task->due_date?->format('Y-m-d') ?? $project->end_date?->format('Y-m-d'),
                'progress' => match ($task->status) {
                    'done', 'completed' => 100,
                    'in_progress' => 50,
                    'review' => 75,
                    default => 0,
                },
                'dependencies' => [],
                'assignee' => $task->assignee?->name,
                'status' => $task->status,
                'priority' => $task->priority,
            ];
        })->toArray();

        $milestones = $project->milestones->map(function ($milestone) {
            return [
                'id' => "milestone_{$milestone->id}",
                'type' => 'milestone',
                'name' => $milestone->name,
                'start' => $milestone->due_date?->format('Y-m-d'),
                'end' => $milestone->due_date?->format('Y-m-d'),
                'progress' => 100,
                'dependencies' => [],
            ];
        })->toArray();

        return [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'start' => $project->start_date?->format('Y-m-d'),
                'end' => $project->end_date?->format('Y-m-d'),
            ],
            'tasks' => array_merge($tasks, $milestones),
        ];
    }
}
