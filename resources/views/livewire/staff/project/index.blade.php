<div class="space-y-8 pb-10">

    {{-- 1. HEADER & NAVIGATION --}}
    <div>
        <a href="javascript:history.back()"
            class="btn btn-ghost btn-sm mb-4 gap-2 pl-0 hover:bg-transparent text-gray-500">
            <x-icon name="o-arrow-left" class="w-4 h-4" /> Back
        </a>

        {{-- Project Context --}}
        <div
            class="bg-gradient-to-r from-gray-900 to-gray-800 text-white p-6 rounded-2xl shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <div class="text-xs opacity-60 uppercase mb-1 tracking-widest">Project Context</div>
                @php
                    $pName = is_array($project->name) ? $project->name['id'] ?? $project->name['en'] : $project->name;
                @endphp
                <h1 class="text-2xl md:text-3xl font-black mb-2">{{ $pName }}</h1>
                <div class="flex items-center gap-3 mt-3">
                    <div class="badge badge-info text-white font-bold uppercase border-none">
                        {{ str_replace('_', ' ', $project->status) }}</div>
                    <div class="text-xs opacity-70 flex items-center gap-1">
                        <x-icon name="o-user" class="w-3 h-3" /> PM: {{ $project->creator->name ?? 'Admin' }}
                    </div>
                    @if ($project->deadline_global)
                        <div class="text-xs opacity-70 flex items-center gap-1">
                            <x-icon name="o-calendar" class="w-3 h-3" /> Deadline:
                            {{ $project->deadline_global->format('d M Y') }}
                        </div>
                    @endif
                </div>
            </div>
            {{-- Decoration --}}
            <x-icon name="o-briefcase" class="absolute -right-6 -bottom-6 w-48 h-48 text-white opacity-5 rotate-12" />
        </div>
    </div>

    {{-- 2. PERFORMANCE CARDS (GRID) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Card 1: Completion (Circle) --}}
        <div
            class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col items-center justify-center text-center h-40">
            <div class="radial-progress text-primary font-black text-xl"
                style="--value:{{ $stats['progress'] }}; --size:4.5rem; --thickness: 6px;">
                {{ $stats['progress'] }}%
            </div>
            <div class="text-xs font-bold mt-2 opacity-60 uppercase tracking-wide">My Completion</div>
        </div>

        {{-- Card 2: Tasks Done --}}
        <div
            class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col justify-center h-40">
            <div class="text-xs opacity-60 uppercase font-bold tracking-wide mb-1">Tasks Done</div>
            <div class="text-4xl font-black text-gray-800 dark:text-gray-100">
                {{ $stats['done'] }} <span class="text-lg text-gray-400 font-normal">/ {{ $stats['total'] }}</span>
            </div>
            <div class="text-xs text-gray-400 mt-2">Personal Assignments</div>
        </div>

        {{-- Card 3: Late Submissions --}}
        <div
            class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col justify-center h-40">
            <div class="text-xs opacity-60 uppercase font-bold tracking-wide mb-1">Late Submissions</div>
            <div class="text-4xl font-black {{ $stats['late'] > 0 ? 'text-error' : 'text-success' }}">
                {{ $stats['late'] }}
            </div>
            <div class="text-xs text-gray-400 mt-2">KPI Indicator</div>
        </div>

        {{-- Card 4: Time Spent --}}
        <div
            class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col justify-center h-40">
            <div class="text-xs opacity-60 uppercase font-bold tracking-wide mb-1">Est. Time Spent</div>
            <div class="text-4xl font-black text-info">
                {{ $stats['time_spent'] }}
            </div>
            <div class="text-xs text-gray-400 mt-2">Based on logs</div>
        </div>
    </div>

    {{-- 3. TASK BREAKDOWN (LIST) --}}
    <div class="mt-4">
        <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-4 gap-4">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <x-icon name="o-clipboard-document-list" class="w-5 h-5 text-primary" /> Task Breakdown
            </h3>

            {{-- Filters --}}
            <div class="flex gap-2 w-full md:w-auto">
                <x-input icon="o-magnifying-glass" placeholder="Search task..."
                    wire:model.live.debounce.300ms="taskSearch" class="input-sm flex-1 md:w-48" />
                <select wire:model.live="taskStatus" class="select select-sm select-bordered w-32">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="on_progress">In Progress</option>
                    <option value="revision">Revision</option>
                    <option value="approved">Done</option>
                </select>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($tasks as $task)
                @php
                    $tTitle = is_array($task->title) ? $task->title['id'] ?? $task->title['en'] : $task->title;
                    $deadline = \Carbon\Carbon::parse($task->deadline_task);
                    $isOverdue = $deadline->isPast() && $task->status != 'approved';
                @endphp

                <a href="{{ route('user.jobdesks.revision', $task->id) }}"
                    class="group block bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary/50 hover:shadow-md transition relative overflow-hidden">

                    {{-- Status Indicator Bar --}}
                    <div
                        class="absolute left-0 top-0 bottom-0 w-1
                        {{ match ($task->status) {
                            'revision' => 'bg-error',
                            'on_progress' => 'bg-info',
                            'approved' => 'bg-success',
                            default => 'bg-gray-300',
                        } }}">
                    </div>

                    <div class="pl-3 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div class="flex-1">
                            <h4 class="font-bold text-base group-hover:text-primary transition">{{ $tTitle }}
                            </h4>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                <span class="flex items-center gap-1 {{ $isOverdue ? 'text-error font-bold' : '' }}">
                                    <x-icon name="o-calendar" class="w-3 h-3" />
                                    Due: {{ $deadline->format('d M H:i') }}
                                    @if ($isOverdue)
                                        (Overdue)
                                    @endif
                                </span>
                                @if ($task->lateness_minutes > 0)
                                    <span class="text-error font-bold">• Late {{ $task->lateness_minutes }}m</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                            <span
                                class="badge badge-sm uppercase font-bold border-none
                                {{ match ($task->status) {
                                    'approved' => 'badge-success text-white',
                                    'revision' => 'badge-error text-white animate-pulse',
                                    'on_progress' => 'badge-info text-white',
                                    'review' => 'badge-warning text-black',
                                    default => 'badge-ghost text-gray-500',
                                } }}">
                                {{ str_replace('_', ' ', $task->status) }}
                            </span>

                            <div class="btn btn-circle btn-xs btn-ghost group-hover:bg-primary/10">
                                <x-icon name="o-eye" class="w-4 h-4 text-primary" />
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div
                    class="text-center py-12 opacity-50 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-gray-300">
                    <x-icon name="o-clipboard" class="w-12 h-12 mx-auto mb-2 opacity-20" />
                    <p>No tasks found matching your criteria.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $tasks->links() }}
        </div>
    </div>

</div>
