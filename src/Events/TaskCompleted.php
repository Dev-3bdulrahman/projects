<?php

namespace Dev3bdulrahman\Projects\Events;

use Dev3bdulrahman\Projects\Models\Task;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Task $task,
        public int $userId,
        public int $companyId,
    ) {}
}
