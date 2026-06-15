<?php

namespace Dev3bdulrahman\Projects\Listeners;

use Dev3bdulrahman\Projects\Events\TaskCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyTaskAssignee implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Handle the TaskCreated event.
     */
    public function handle(TaskCreated $event): void
    {
        try {
            $task = $event->task;

            if (!$task->assigned_to) {
                return;
            }

            $assignee = $task->assignee;

            if (!$assignee) {
                return;
            }

            $assignee->notify(
                new \Illuminate\Notifications\DatabaseNotification([
                    'type' => 'task_assigned',
                    'title' => __('projects::projects.task_assigned'),
                    'message' => __('projects::projects.task_assigned_message', [
                        'task' => $task->name,
                        'project' => $task->project->name ?? '',
                    ]),
                    'task_id' => $task->id,
                    'project_id' => $task->project_id,
                    'assigned_by' => $event->userId,
                ])
            );
        } catch (\Throwable $e) {
            Log::error('NotifyTaskAssignee: Failed to send notification.', [
                'error' => $e->getMessage(),
                'task_id' => $event->task->id ?? null,
                'user_id' => $event->userId ?? null,
            ]);
        }
    }
}
