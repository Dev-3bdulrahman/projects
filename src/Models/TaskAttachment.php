<?php

namespace Dev3bdulrahman\Projects\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class TaskAttachment extends Model
{
    protected $table = 'task_attachments';

    protected $fillable = [
        'task_id',
        'name',
        'file_path',
        'uploaded_by',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
