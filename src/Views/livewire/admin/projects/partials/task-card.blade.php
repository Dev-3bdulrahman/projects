<div class="bg-white dark:bg-gray-900 p-4 rounded-lg border border-gray-150 dark:border-gray-800 shadow-sm space-y-3">
    <div class="flex items-start justify-between gap-2">
        <h4 class="font-semibold text-gray-900 dark:text-white text-sm line-clamp-2">{{ $task->name }}</h4>
        @php
            $priorityClasses = [
                'low' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400',
                'medium' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400',
                'high' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950/30 dark:text-yellow-400',
                'urgent' => 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400',
            ];
        @endphp
        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $priorityClasses[$task->priority] ?? $priorityClasses['medium'] }}">
            {{ $task->priority }}
        </span>
    </div>

    @if($task->description)
        <p class="text-xs text-gray-500 line-clamp-2">{{ $task->description }}</p>
    @endif

    <div class="flex items-center justify-between text-xs pt-2 border-t border-gray-100 dark:border-gray-800">
        <div class="flex items-center gap-1.5 text-gray-500">
            @if($task->due_date)
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ $task->due_date->format('M d') }}</span>
            @endif
        </div>
        
        <div class="flex items-center gap-1">
            @if($task->assignee)
                <span class="w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-[9px]" title="Assigned to {{ $task->assignee->name }}">
                    {{ strtoupper(substr($task->assignee->name, 0, 2)) }}
                </span>
            @else
                <span class="w-5 h-5 rounded-full border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-gray-400 text-[10px]" title="Unassigned">
                    ?
                </span>
            @endif
        </div>
    </div>

    <div class="flex items-center justify-between pt-2 border-t border-gray-50 dark:border-gray-800/50">
        <!-- Move Status Buttons -->
        <div class="flex items-center gap-1">
            @if($task->status === 'in_progress' || $task->status === 'review' || $task->status === 'done')
                <button wire:click="moveTask({{ $task->id }}, '{{ $task->status === 'in_progress' ? 'todo' : ($task->status === 'review' ? 'in_progress' : 'review') }}')" 
                    class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded text-gray-500 transition-colors" title="Move Back">
                    &larr;
                </button>
            @endif
            
            @if($task->status === 'todo' || $task->status === 'in_progress' || $task->status === 'review')
                <button wire:click="moveTask({{ $task->id }}, '{{ $task->status === 'todo' ? 'in_progress' : ($task->status === 'in_progress' ? 'review' : 'done') }}')" 
                    class="p-1 hover:bg-gray-100 dark:hover:bg-gray-800 rounded text-gray-500 transition-colors" title="Move Forward">
                    &rarr;
                </button>
            @endif
        </div>

        <!-- Edit/Delete Buttons -->
        <div class="flex items-center gap-1">
            <button wire:click="openEditModal({{ $task->id }})" 
                class="p-1 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors" title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </button>
            <button 
                wire:click="$dispatch('swal:confirm', { 
                    title: 'Delete Task',
                    text: 'Are you sure you want to delete this task?',
                    onConfirm: 'deleteTask',
                    params: { id: {{ $task->id }} }
                })"
                class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors" title="Delete">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>
</div>
