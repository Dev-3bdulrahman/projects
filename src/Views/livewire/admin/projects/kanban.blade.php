<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.projects.index') }}" class="hover:underline">Projects</a>
                <span>&rarr;</span>
                <span>{{ $project->name }}</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('projects::projects.kanban') }}</h2>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.projects.timesheet', $project->id) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <span>{{ __('projects::projects.timesheet') }}</span>
            </a>
            <button wire:click="openCreateModal('todo')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Task</span>
            </button>
        </div>
    </div>

    <!-- Kanban Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Todo Column -->
        <div class="bg-gray-50 dark:bg-gray-950 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                    {{ __('projects::projects.todo') }}
                </h3>
                <span class="bg-gray-200 dark:bg-gray-800 text-xs font-semibold px-2 py-0.5 rounded-full text-gray-600 dark:text-gray-400">
                    {{ $todoTasks->count() }}
                </span>
            </div>
            <div class="space-y-3">
                @foreach($todoTasks as $task)
                    @include('projects::livewire.admin.projects.partials.task-card', ['task' => $task])
                @endforeach
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="bg-gray-50 dark:bg-gray-950 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                    {{ __('projects::projects.in_progress') }}
                </h3>
                <span class="bg-yellow-100 dark:bg-yellow-950 text-xs font-semibold px-2 py-0.5 rounded-full text-yellow-600 dark:text-yellow-400">
                    {{ $inProgressTasks->count() }}
                </span>
            </div>
            <div class="space-y-3">
                @foreach($inProgressTasks as $task)
                    @include('projects::livewire.admin.projects.partials.task-card', ['task' => $task])
                @endforeach
            </div>
        </div>

        <!-- Review Column -->
        <div class="bg-gray-50 dark:bg-gray-950 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                    {{ __('projects::projects.review') }}
                </h3>
                <span class="bg-purple-100 dark:bg-purple-950 text-xs font-semibold px-2 py-0.5 rounded-full text-purple-600 dark:text-purple-400">
                    {{ $reviewTasks->count() }}
                </span>
            </div>
            <div class="space-y-3">
                @foreach($reviewTasks as $task)
                    @include('projects::livewire.admin.projects.partials.task-card', ['task' => $task])
                @endforeach
            </div>
        </div>

        <!-- Done Column -->
        <div class="bg-gray-50 dark:bg-gray-950 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                    {{ __('projects::projects.done') }}
                </h3>
                <span class="bg-green-100 dark:bg-green-950 text-xs font-semibold px-2 py-0.5 rounded-full text-green-600 dark:text-green-400">
                    {{ $doneTasks->count() }}
                </span>
            </div>
            <div class="space-y-3">
                @foreach($doneTasks as $task)
                    @include('projects::livewire.admin.projects.partials.task-card', ['task' => $task])
                @endforeach
            </div>
        </div>
    </div>

    <!-- Task Form Modal -->
    <div x-data="{ open: @entangle('showTaskModal') }" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div @click="open = false" class="fixed inset-0 bg-gray-500/75 dark:bg-gray-950/75 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-middle bg-white dark:bg-gray-900 rounded-xl text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-800">
                <form wire:submit.prevent="save">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6 border-b border-gray-50 dark:border-gray-800 pb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                {{ $taskId ? 'Edit Task' : 'Add Task' }}
                            </h3>
                            <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Form Fields -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Task Name *</label>
                                <input type="text" wire:model="name" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                <textarea wire:model="description" rows="3" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white"></textarea>
                                @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status *</label>
                                    <select wire:model="status" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                                        <option value="todo">{{ __('projects::projects.todo') }}</option>
                                        <option value="in_progress">{{ __('projects::projects.in_progress') }}</option>
                                        <option value="review">{{ __('projects::projects.review') }}</option>
                                        <option value="done">{{ __('projects::projects.done') }}</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority *</label>
                                    <select wire:model="priority" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                    @error('priority') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Due Date</label>
                                    <input type="date" wire:model="due_date" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                                    @error('due_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assignee</label>
                                    <select wire:model="assigned_to" class="w-full py-2 px-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white">
                                        <option value="">Unassigned</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800 flex justify-end gap-2">
                        <button type="button" @click="open = false" class="px-4 py-2 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            {{ __('projects::projects.cancel') }}
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition-colors">
                            {{ __('projects::projects.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
