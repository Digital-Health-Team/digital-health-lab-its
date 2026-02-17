<div class="space-y-8 pb-10">

    {{-- HEADER & BREADCRUMBS --}}
    <div class="flex flex-col md:flex-row justify-between items-start gap-4">
        <div>
            <div class="text-xs breadcrumbs text-gray-500 mb-1">
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.projects') }}">Projects</a></li>
                    <li class="font-bold">Detail</li>
                </ul>
            </div>

            {{-- Nama Project (Multi Bahasa Handler) --}}
            @php
                $pName = $project->name;
                if (is_array($pName)) {
                    $pName = $pName[app()->getLocale()] ?? ($pName['en'] ?? '-');
                }
            @endphp

            <h1 class="text-3xl font-black dark:text-white flex items-center gap-3">
                {{ $pName }}
                <div
                    class="badge {{ match ($project->status) {'active' => 'badge-info','completed' => 'badge-success',default => 'badge-warning'} }} text-white font-bold text-sm uppercase">
                    {{ str_replace('_', ' ', $project->status) }}
                </div>
            </h1>

            <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                <span class="flex items-center gap-1"><x-icon name="o-user" class="w-4 h-4" /> PM:
                    {{ $project->creator->name ?? 'Admin' }}</span>
                <span class="flex items-center gap-1"><x-icon name="o-calendar" class="w-4 h-4" /> Deadline:
                    {{ $project->deadline_global ? $project->deadline_global->format('d M Y') : 'No Deadline' }}</span>
                @if ($project->deadline_global)
                    <span class="{{ $project->deadline_global->isPast() ? 'text-error font-bold' : 'text-success' }}">
                        ({{ $project->deadline_global->diffForHumans() }})
                    </span>
                @endif
            </div>
        </div>

        <x-button label="Edit Project" icon="o-pencil" class="btn-outline" link="#" />
    </div>

    {{-- OVERVIEW CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Progress Bar Card --}}
        <div
            class="md:col-span-2 card bg-base-100 dark:bg-gray-800 shadow-sm border border-base-200 dark:border-gray-700 p-6">
            <div class="flex justify-between items-end mb-2">
                <span class="font-bold opacity-60 uppercase text-xs">Completion Progress</span>
                <span class="text-3xl font-black text-primary">{{ $stats['progress'] }}%</span>
            </div>
            <progress class="progress progress-primary w-full h-3" value="{{ $stats['progress'] }}"
                max="100"></progress>
            <div class="flex justify-between text-xs mt-2 opacity-60">
                <span>{{ $stats['completed'] }} Tasks Done</span>
                <span>{{ $stats['total'] }} Total Tasks</span>
            </div>
        </div>

        {{-- Stats Pending --}}
        <div
            class="stat bg-base-100 dark:bg-gray-800 shadow-sm rounded-2xl border border-base-200 dark:border-gray-700">
            <div class="stat-figure text-warning bg-warning/10 p-2 rounded-full"><x-icon name="o-clock"
                    class="w-6 h-6" /></div>
            <div class="stat-title text-xs font-bold opacity-60">Pending / In Progress</div>
            <div class="stat-value text-2xl">{{ $stats['pending'] }}</div>
        </div>

        {{-- Stats Review --}}
        <div
            class="stat bg-base-100 dark:bg-gray-800 shadow-sm rounded-2xl border border-base-200 dark:border-gray-700">
            <div class="stat-figure text-info bg-info/10 p-2 rounded-full"><x-icon name="o-inbox-arrow-down"
                    class="w-6 h-6" /></div>
            <div class="stat-title text-xs font-bold opacity-60">Needs Review</div>
            <div class="stat-value text-2xl text-info">{{ $stats['review'] }}</div>
        </div>
    </div>

    {{-- CONTENT TABS --}}
    <x-tabs wire:model="selectedTab">

        {{-- TAB 1: JOBDESK MANAGEMENT --}}
        <x-tab name="tasks" label="Jobdesk & Tasks" icon="o-clipboard-document-list">
            <div class="mt-4">
                <div class="flex justify-between items-center mb-4">
                    <x-input icon="o-magnifying-glass" placeholder="Search tasks..."
                        wire:model.live.debounce="taskSearch" class="w-full md:w-1/3 input-sm" />
                    <x-button label="Add Task" icon="o-plus" class="btn-sm btn-primary"
                        wire:click="openCreateTaskModal" />
                </div>

                <div class="overflow-x-auto bg-base-100 rounded-xl border border-base-200 dark:border-gray-700">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Task Name</th>
                                <th>Assignee</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                @php
                                    $tTitle = is_array($task->title)
                                        ? $task->title['id'] ?? $task->title['en']
                                        : $task->title;
                                    $tDesc = is_array($task->description)
                                        ? $task->description['id'] ?? $task->description['en']
                                        : $task->description;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="font-bold">{{ $tTitle }}</div>
                                        <div class="text-xs opacity-60 truncate w-64">{{ Str::limit($tDesc, 40) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <x-avatar :image="$task->assignee->profile_photo
                                                ? asset('storage/' . $task->assignee->profile_photo)
                                                : null" class="!w-6 !h-6" />
                                            <span class="text-xs">{{ $task->assignee->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div
                                            class="text-xs {{ $task->deadline_task && \Carbon\Carbon::parse($task->deadline_task)->isPast() && $task->status !== 'approved' ? 'text-error font-bold' : '' }}">
                                            {{ $task->deadline_task ? \Carbon\Carbon::parse($task->deadline_task)->format('d M') : '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $cls = match ($task->status) {
                                                'approved' => 'badge-success',
                                                'review' => 'badge-warning',
                                                'revision' => 'badge-error',
                                                default => 'badge-ghost',
                                            };
                                        @endphp
                                        <span
                                            class="badge {{ $cls }} badge-xs text-[10px] uppercase font-bold text-white">{{ str_replace('_', ' ', $task->status) }}</span>
                                    </td>
                                    <td class="text-right">
                                        <x-button icon="o-eye" class="btn-xs btn-ghost"
                                            link="{{ route('admin.jobdesks.revision', $task->id) }}" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 opacity-50">No tasks found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $tasks->links() }}</div>
            </div>
        </x-tab>

        {{-- TAB 2: STAFF PERFORMANCE --}}
        <x-tab name="team" label="Staff Involved" icon="o-users">
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($this->staffInvolved as $staff)
                    <div
                        class="bg-base-100 dark:bg-gray-800 p-4 rounded-xl border border-base-200 dark:border-gray-700 flex items-center gap-4">
                        <x-avatar :image="$staff->profile_photo ? asset('storage/' . $staff->profile_photo) : null" class="w-14 h-14" />
                        <div class="flex-1">
                            <div class="font-bold">{{ $staff->name }}</div>
                            <div class="text-xs opacity-60 mb-2">{{ $staff->email }}</div>

                            {{-- Mini Progress --}}
                            <div class="flex justify-between text-[10px] mb-1">
                                <span>Performance</span>
                                <span
                                    class="{{ $staff->performance == 100 ? 'text-success font-bold' : '' }}">{{ $staff->performance }}%</span>
                            </div>
                            <progress class="progress progress-success w-full h-1.5" value="{{ $staff->performance }}"
                                max="100"></progress>

                            <div class="flex gap-2 mt-3">
                                <div class="badge badge-neutral badge-sm text-[10px]">{{ $staff->project_total }} Tasks
                                </div>
                                @if ($staff->project_late > 0)
                                    <div class="badge badge-error text-white badge-sm text-[10px]">
                                        {{ $staff->project_late }} Late</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                @if ($this->staffInvolved->isEmpty())
                    <div class="col-span-3 text-center py-10 opacity-50">No staff assigned yet.</div>
                @endif
            </div>
        </x-tab>

        {{-- TAB 3: WORK LOGS / ATTENDANCE REPORT --}}
        <x-tab name="logs" label="Work Logs" icon="o-document-text">
            <div class="mt-4">
                <div class="alert alert-info shadow-sm text-xs mb-4">
                    <x-icon name="o-information-circle" />
                    <span>Showing daily reports submitted by staff for tasks related to
                        <b>{{ $pName }}</b>.</span>
                </div>

                <div class="space-y-4">
                    @forelse($workLogs as $log)
                        @php
                            $logTitle = is_array($log->jobdesk->title)
                                ? $log->jobdesk->title['id'] ?? '-'
                                : $log->jobdesk->title;
                        @endphp
                        <div
                            class="flex gap-4 p-4 bg-base-100 dark:bg-gray-800 rounded-xl border border-base-200 dark:border-gray-700">
                            {{-- Tanggal --}}
                            <div
                                class="flex flex-col items-center justify-center p-3 bg-base-200 dark:bg-gray-700 rounded-lg min-w-[70px]">
                                <span class="font-bold text-lg">{{ $log->created_at->format('d') }}</span>
                                <span class="text-xs uppercase opacity-60">{{ $log->created_at->format('M') }}</span>
                            </div>

                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <x-avatar :image="$log->attendance->user->profile_photo
                                                ? asset('storage/' . $log->attendance->user->profile_photo)
                                                : null" class="!w-5 !h-5" />
                                            <span class="text-xs font-bold">{{ $log->attendance->user->name }}</span>
                                            <span class="text-[10px] opacity-50">•
                                                {{ $log->created_at->format('H:i') }}</span>
                                        </div>
                                        <h4 class="font-bold text-sm">{{ $logTitle }}</h4>
                                    </div>
                                    <div
                                        class="badge {{ $log->status_at_report == 'completed' ? 'badge-success' : 'badge-info' }} badge-xs text-white">
                                        {{ $log->status_at_report }}
                                    </div>
                                </div>

                                <p class="text-xs mt-2 opacity-80 italic bg-base-200/50 p-2 rounded">
                                    "{{ $log->details->first()->content ?? 'No details provided.' }}"
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 opacity-50">No work logs found for this project yet.</div>
                    @endforelse
                </div>
                <div class="mt-4">{{ $workLogs->links() }}</div>
            </div>
        </x-tab>
    </x-tabs>

    {{-- MODAL CREATE TASK --}}
    <x-modal wire:model="taskModalOpen" title="Add Task to {{ $pName }}" separator>
        <x-form wire:submit="saveTask">
            <x-translatable-input label="Title" model="title" />
            <x-translatable-input label="Description" model="description" type="textarea" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-select label="Assign To" wire:model="assigned_to" :options="$staffList" option-label="name"
                    option-value="id" placeholder="Select Staff" />
                <x-datetime label="Deadline" wire:model="deadline_task" type="datetime-local" />
            </div>

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.taskModalOpen = false" />
                <x-button label="Create Task" class="btn-primary" type="submit" spinner="saveTask" />
            </x-slot:actions>
        </x-form>
    </x-modal>

</div>
