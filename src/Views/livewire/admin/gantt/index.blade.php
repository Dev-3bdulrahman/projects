<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('projects::projects.gantt_chart') }}</h2>
        <select wire:model.live="projectId" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
            <option value="">{{ __('projects::projects.select_project') }}</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        @if($ganttData)
            <div id="gantt-container" wire:ignore>
                <svg id="gantt-chart"></svg>
            </div>

            @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.css">
            <script>
                document.addEventListener('livewire:init', function () {
                    initGantt();
                });

                function initGantt() {
                    const data = @json($ganttData['tasks'] ?? []);
                    if (data.length > 0) {
                        const tasks = data.map(t => ({
                            id: t.id,
                            name: t.name,
                            start: t.start,
                            end: t.end,
                            progress: t.progress || 0,
                            dependencies: t.dependencies || [],
                        }));

                        new Gantt("#gantt-chart", tasks, {
                            view_mode: 'Day',
                            date_format: 'YYYY-MM-DD',
                            bar_height: 30,
                            bar_corner_radius: 3,
                            arrow_curve: 5,
                            padding: 18,
                            on_date_change: (task, start, end) => {
                                @this.updateTaskDate(task.id, start, end);
                            },
                            on_progress_change: (task, progress) => {
                                @this.updateTaskProgress(task.id, progress);
                            },
                            on_click: (task) => {
                                @this.viewTask(task.id);
                            },
                        });
                    }
                }
            </script>
            @endpush
        @else
            <p class="text-gray-500 dark:text-gray-400 text-center py-12">{{ __('projects::projects.select_project_hint') }}</p>
        @endif
    </div>
</div>
