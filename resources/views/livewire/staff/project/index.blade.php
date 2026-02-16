<div>
    {{-- HANDLING JSON DATA SAFELY --}}
    @php
        $pName = is_array($project->title) ? $project->title['id'] ?? $project->title['en'] : $project->title;
        $pDesc = is_array($project->description)
            ? $project->description['id'] ?? $project->description['en']
            : $project->description;
    @endphp

    {{-- HEADER --}}
    <div class="mb-6">
        <a href="{{ route('user.dashboard') }}"
            class="btn btn-ghost btn-sm mb-2 gap-2 pl-0 hover:bg-transparent text-gray-500">
            <x-icon name="o-arrow-left" class="w-4 h-4" /> Back to Dashboard
        </a>

        <x-card class="bg-base-100 shadow-sm border border-base-200">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black">{{ $pName }}</h1>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <div class="badge badge-neutral uppercase text-xs font-bold">{{ $project->status }}</div>
                        <div class="badge badge-outline text-xs">
                            Deadline: {{ $project->deadline_global ? $project->deadline_global->format('d M Y') : '-' }}
                        </div>
                    </div>
                </div>

                {{-- Progress Bar Mini --}}
                <div class="w-full md:w-48 bg-base-200 rounded-lg p-3 text-center md:text-right">
                    <div class="text-xs opacity-60 mb-1">Your Tasks Completion</div>
                    @php
                        $totalMyTasks = \App\Models\Jobdesk::where('project_id', $project->id)
                            ->where('assigned_to', auth()->id())
                            ->count();
                        $doneMyTasks = \App\Models\Jobdesk::where('project_id', $project->id)
                            ->where('assigned_to', auth()->id())
                            ->where('status', 'approved')
                            ->count();
                        $percent = $totalMyTasks > 0 ? round(($doneMyTasks / $totalMyTasks) * 100) : 0;
                    @endphp
                    <div class="flex items-center gap-2">
                        <progress class="progress progress-primary w-full" value="{{ $percent }}"
                            max="100"></progress>
                        <span class="text-xs font-bold">{{ $percent }}%</span>
                    </div>
                    <div class="text-[10px] mt-1 opacity-50">{{ $doneMyTasks }} / {{ $totalMyTasks }} tasks done</div>
                </div>
            </div>

            <div
                class="mt-4 text-sm opacity-70 leading-relaxed bg-base-50 dark:bg-base-200/50 p-4 rounded-xl border border-base-200 dark:border-base-700">
                {{ $pDesc }}
            </div>
        </x-card>
    </div>

    {{-- LIST TUGAS --}}
    <x-card title="My Tasks in this Project" separator>
        <x-slot:menu>
            <div class="flex flex-col sm:flex-row gap-2">
                {{-- Search Input --}}
                <div class="relative">
                    <x-icon name="o-magnifying-glass" class="w-4 h-4 absolute top-3 left-3 opacity-50" />
                    <input type="text" wire:model.live.debounce.300ms="taskSearch" placeholder="Search tasks..."
                        class="input input-sm input-bordered pl-9 w-full sm:w-48" />
                </div>

                {{-- Status Filter --}}
                <select wire:model.live="taskStatus" class="select select-sm select-bordered w-full sm:w-auto">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="on_progress">On Progress</option>
                    <option value="revision">Revision</option>
                    <option value="approved">Completed</option>
                </select>
            </div>
        </x-slot:menu>

        <div class="space-y-2">
            @forelse($tasks as $task)
                @php
                    $tTitle = is_array($task->title) ? $task->title['id'] ?? $task->title['en'] : $task->title;
                @endphp

                {{-- Link ke halaman detail/revisi tugas --}}
                <a href="{{ route('user.jobdesks.revision', $task->id) }}"
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 hover:bg-base-200 rounded-lg border border-base-200 transition group gap-3">

                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        {{-- Status Dot --}}
                        <div
                            class="w-2 h-2 shrink-0 rounded-full
                            {{ $task->status == 'revision' ? 'bg-error' : ($task->status == 'approved' ? 'bg-success' : 'bg-gray-300') }}">
                        </div>

                        <div>
                            <div class="font-bold text-sm group-hover:text-primary transition">{{ $tTitle }}
                            </div>
                            <div class="text-xs opacity-50 flex items-center gap-1">
                                <x-icon name="o-calendar" class="w-3 h-3" />
                                Deadline: {{ $task->deadline_task ? $task->deadline_task->format('d M Y, H:i') : '-' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between w-full sm:w-auto gap-3 pl-5 sm:pl-0">
                        <span
                            class="badge badge-sm uppercase text-[10px] font-bold
                            {{ match ($task->status) {
                                'revision' => 'badge-error text-white',
                                'approved' => 'badge-success text-white',
                                'on_progress' => 'badge-info',
                                default => 'badge-ghost',
                            } }}">
                            {{ str_replace('_', ' ', $task->status) }}
                        </span>
                        <x-icon name="o-chevron-right" class="w-4 h-4 opacity-30 group-hover:opacity-100" />
                    </div>
                </a>
            @empty
                <div class="text-center py-10 opacity-50">
                    <x-icon name="o-clipboard-document-list" class="w-10 h-10 mx-auto mb-2 opacity-20" />
                    <p>No tasks found matching your criteria.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4 border-t border-base-200 pt-4">
            {{ $tasks->links() }}
        </div>
    </x-card>
</div>
