<?php

namespace Dev3bdulrahman\Projects\Services;

use Dev3bdulrahman\Projects\Models\Project;
use Dev3bdulrahman\Projects\Models\ProjectMember;
use Dev3bdulrahman\Projects\Models\Task;
use Dev3bdulrahman\Projects\Models\TaskTimeLog;
use Dev3bdulrahman\Projects\Models\TaskAttachment;
use Illuminate\Support\Facades\Auth;

class ProjectsService
{
    // --- Project CRUD ---

    public function createProject(array $data)
    {
        if (!isset($data['created_by'])) {
            $data['created_by'] = Auth::id();
        }

        foreach (['start_date', 'end_date'] as $field) {
            if (isset($data[$field]) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        return Project::create($data);
    }

    public function updateProject($id, array $data)
    {
        $project = Project::findOrFail($id);

        foreach (['start_date', 'end_date'] as $field) {
            if (array_key_exists($field, $data) && $data[$field] === '') {
                $data[$field] = null;
            }
        }

        $project->update($data);
        return $project;
    }

    public function deleteProject($id)
    {
        $project = Project::findOrFail($id);
        return $project->delete();
    }

    public function getProject($id)
    {
        return Project::findOrFail($id);
    }

    public function getCompanyProjects()
    {
        return Project::all();
    }

    // --- Member Management ---

    public function addMember($projectId, $userId, $role = 'member')
    {
        return ProjectMember::create([
            'project_id' => $projectId,
            'user_id' => $userId,
            'role' => $role,
        ]);
    }

    public function removeMember($projectId, $userId)
    {
        return ProjectMember::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->delete();
    }

    public function getMembers($projectId)
    {
        return ProjectMember::where('project_id', $projectId)->with('user')->get();
    }

    // --- Task Assignment ---

    public function createTask(array $data)
    {
        if (!isset($data['created_by'])) {
            $data['created_by'] = Auth::id();
        }

        if (isset($data['due_date']) && $data['due_date'] === '') {
            $data['due_date'] = null;
        }

        return Task::create($data);
    }

    public function updateTask($id, array $data)
    {
        $task = Task::findOrFail($id);

        if (array_key_exists('due_date', $data) && $data['due_date'] === '') {
            $data['due_date'] = null;
        }

        $task->update($data);
        return $task;
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        return $task->delete();
    }

    public function assignTask($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);
        $task->update(['assigned_to' => $userId]);
        return $task;
    }

    public function updateTaskStatus($taskId, $status)
    {
        $task = Task::findOrFail($taskId);
        $task->update(['status' => $status]);
        return $task;
    }

    // --- Time Logging ---

    public function logTime($taskId, $userId, $hours, $date, $description = null)
    {
        return TaskTimeLog::create([
            'task_id' => $taskId,
            'user_id' => $userId,
            'hours' => $hours,
            'date' => $date,
            'description' => $description,
        ]);
    }

    public function getTaskTimeLogs($taskId)
    {
        return TaskTimeLog::where('task_id', $taskId)->with('user')->get();
    }

    public function getTotalHoursForProject($projectId)
    {
        return TaskTimeLog::whereHas('task', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->sum('hours');
    }

    // --- Attachments ---

    public function addAttachment($taskId, $name, $filePath, $uploadedBy = null)
    {
        if (!$uploadedBy) {
            $uploadedBy = Auth::id();
        }

        return TaskAttachment::create([
            'task_id' => $taskId,
            'name' => $name,
            'file_path' => $filePath,
            'uploaded_by' => $uploadedBy,
        ]);
    }

    public function getTaskAttachments($taskId)
    {
        return TaskAttachment::where('task_id', $taskId)->get();
    }
}
