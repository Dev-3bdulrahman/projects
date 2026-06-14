<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.projects.index') }}" class="hover:underline">Projects</a>
                <span>&rarr;</span>
                <a href="{{ route('admin.projects.kanban', $project->id) }}" class="hover:underline">{{ $project->name }}</a>
                <span>&rarr;</span>
                <span>Milestones</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('projects::projects.milestones') }}</h2>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.projects.kanban', $project->id) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <span>{{ __('projects::projects.kanban') }}</span>
            </a>
            <a href="{{ route('admin.projects.timesheet', $project->id) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <span>{{ __('projects::projects.timesheet') }}</span>
            </a>
            <a href="{{ route('admin.projects.expenses', $project->id) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <span>{{ __('projects::projects.expenses') }}</span>
            </a>
            <button wire:click="openModal()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>{{ __('projects::projects.add_milestone') }}</span>
            </button>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 p-4 mb-6 shadow-sm">
        <div class="relative flex items-center">
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="{{ __('projects::projects.milestones') }}..."
                class="w-full ps-10 pe-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-blue-500" />
            <div class="absolute start-3 top-1/2 -translate-y-1/2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Milestones Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        @forelse($milestones as $milestone)
            <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm transition-all hover:shadow-md flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $milestone->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ $milestone->description ?? '—' }}</p>
                        </div>
                        <button wire:click="toggleStatus({{ $milestone->id }})" class="px-2.5 py-1 text-xs font-bold rounded-full transition-colors {{ $milestone->status === 'completed' ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400' }}">
                            {{ __('projects::projects.status_' . $milestone->status) }}
                        </button>
                    </div>

                    <!-- Due date -->
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ __('projects::projects.due_date') }}: {{ $milestone->due_date ? $milestone->due_date->format('Y-m-d') : '—' }}</span>
                    </div>

                    <!-- Progress -->
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between items-center text-xs font-bold">
                            <span class="text-gray-400">{{ __('projects::projects.progress') }}</span>
                            <span class="text-blue-600 dark:text-blue-400">{{ $milestone->progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $milestone->progress }}%"></div>
                        </div>
                        <div class="flex justify-between items-center text-xs text-gray-400 mt-1">
                            <span>{{ $milestone->tasks->where('status', 'done')->count() }} / {{ $milestone->tasks->count() }} Tasks Done</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-50 dark:border-gray-800">
                    <button wire:click="openTasksModal({{ $milestone->id }})" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>{{ __('projects::projects.assign_tasks') }}</span>
                    </button>

                    <div class="flex items-center gap-1">
                        <!-- Edit -->
                        <button wire:click="openModal({{ $milestone->id }})" class="p-1.5 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <!-- Delete -->
                        <button onclick="confirmDeleteMilestone({{ $milestone->id }})" class="p-1.5 text-gray-500 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-2 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-16 text-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('projects::projects.no_milestones') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Create / Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-all">
            <div class="bg-white dark:bg-gray-900 rounded-2xl max-w-lg w-full border border-gray-100 dark:border-gray-800 shadow-2xl overflow-hidden animate__animated animate__fadeInUp animate__faster">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">
                        {{ $milestoneId ? __('projects::projects.edit_milestone') : __('projects::projects.add_milestone') }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('projects::projects.milestone_name') }} *</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('projects::projects.description') }}</label>
                        <textarea wire:model="description" rows="3" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"></textarea>
                        @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('projects::projects.due_date') }} *</label>
                        <input type="date" wire:model="due_date" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('due_date') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('projects::projects.status') }}</label>
                        <select wire:model="status" class="w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            <option value="pending">{{ __('projects::projects.status_pending') }}</option>
                            <option value="completed">{{ __('projects::projects.status_completed') }}</option>
                        </select>
                        @error('status') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-end gap-2">
                        <button type="button" wire:click="closeModal" class="px-5 py-2 text-sm font-bold bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl transition-all">
                            {{ __('projects::projects.cancel') }}
                        </button>
                        <button type="submit" class="px-5 py-2 text-sm font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                            {{ __('projects::projects.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Assign Tasks Modal -->
    @if($showTasksModal && $selectedMilestone)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-all">
            <div class="bg-white dark:bg-gray-900 rounded-2xl max-w-2xl w-full border border-gray-100 dark:border-gray-800 shadow-2xl overflow-hidden animate__animated animate__fadeInUp animate__faster">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white">
                            {{ __('projects::projects.assign_tasks') }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ __('projects::projects.assign_tasks_subtitle') }} ({{ $selectedMilestone->name }})
                        </p>
                    </div>
                    <button wire:click="closeTasksModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[400px] overflow-y-auto">
                    <!-- Assigned Tasks -->
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center justify-between">
                            <span>{{ __('projects::projects.assigned_tasks') }}</span>
                            <span class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-xs font-bold px-2 py-0.5 rounded-full">{{ $selectedMilestone->tasks->count() }}</span>
                        </h4>
                        <div class="space-y-2">
                            @forelse($selectedMilestone->tasks as $task)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 line-clamp-1">{{ $task->name }}</span>
                                    <button wire:click="removeTaskFromMilestone({{ $task->id }})" class="text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 dark:text-gray-500 py-4 text-center">No tasks assigned yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Unassigned Tasks -->
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center justify-between">
                            <span>{{ __('projects::projects.unassigned_tasks') }}</span>
                            <span class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-xs font-bold px-2 py-0.5 rounded-full">{{ $unassignedTasks->count() }}</span>
                        </h4>
                        <div class="space-y-2">
                            @forelse($unassignedTasks as $task)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 line-clamp-1">{{ $task->name }}</span>
                                    <button wire:click="assignTaskToMilestone({{ $task->id }})" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 dark:text-gray-500 py-4 text-center">No unassigned tasks remaining.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                    <button type="button" wire:click="closeTasksModal" class="px-5 py-2 text-sm font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                        {{ __('projects::projects.done') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function confirmDeleteMilestone(id) {
        window.dispatchEvent(new CustomEvent('swal:confirm', {
            detail: {
                title: '{{ __('projects::projects.delete_milestone_confirm') }}',
                text: '{{ __('projects::projects.cancel') }}',
                icon: 'warning',
                confirmButtonText: '{{ __('projects::projects.save') }}',
                cancelButtonText: '{{ __('projects::projects.cancel') }}',
                onConfirm: 'delete',
                params: [id]
            }
        }));
    }
</script>
