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

        <x-button label="Edit Project" icon="o-pencil" class="btn-outline" wire:click="openEditProjectModal" />
    </div>

    {{-- OVERVIEW CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

        <div
            class="stat bg-base-100 dark:bg-gray-800 shadow-sm rounded-2xl border border-base-200 dark:border-gray-700">
            <div class="stat-figure text-warning bg-warning/10 p-2 rounded-full"><x-icon name="o-clock"
                    class="w-6 h-6" /></div>
            <div class="stat-title text-xs font-bold opacity-60">Pending / In Progress</div>
            <div class="stat-value text-2xl">{{ $stats['pending'] }}</div>
        </div>

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

        {{-- ================================================================= --}}
        {{-- TAB 1: JOBDESK MANAGEMENT (FILTERED)                              --}}
        {{-- ================================================================= --}}
        <x-tab name="tasks" label="Jobdesk & Tasks" icon="o-clipboard-document-list">
            <div class="mt-4 space-y-4">

                {{-- Toolbar Filter --}}
                <div
                    class="flex flex-col lg:flex-row gap-4 justify-between items-end lg:items-center bg-base-100 p-4 rounded-xl border border-base-200 dark:border-gray-700">
                    <div class="flex flex-wrap gap-2 w-full lg:w-auto">
                        <x-input icon="o-magnifying-glass" placeholder="Search tasks..."
                            wire:model.live.debounce="taskSearch" class="w-full lg:w-60 input-sm" />

                        <x-select wire:model.live="taskStatus" class="select-sm w-full lg:w-40" placeholder="All Status"
                            :options="[
                                ['id' => 'pending', 'name' => 'Pending'],
                                ['id' => 'on_progress', 'name' => 'In Progress'],
                                ['id' => 'review', 'name' => 'Review'],
                                ['id' => 'revision', 'name' => 'Revision'],
                                ['id' => 'approved', 'name' => 'Approved'],
                            ]" />

                        <x-datetime wire:model.live="taskDateStart" type="date" class="input-sm w-full lg:w-40"
                            placeholder="From Date" />
                    </div>

                    {{-- TOMBOL ADD TASK DAN BUILD ROADMAP --}}
                    <div class="flex gap-2 w-full lg:w-auto">
                        <x-button label="Add Task" icon="o-plus" class="btn-sm btn-outline flex-1 lg:flex-none"
                            wire:click="openCreateTaskModal" />
                        <x-button label="Build Roadmap" icon="o-queue-list"
                            class="btn-sm btn-primary flex-1 lg:flex-none"
                            link="{{ route('admin.projects.roadmap', $project->id) }}" />
                    </div>
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
                                        @if ($task->assignee)
                                            <div class="flex items-center gap-2">
                                                <x-avatar :image="$task->assignee->profile_photo
                                                    ? asset('storage/' . $task->assignee->profile_photo)
                                                    : null" class="!w-6 !h-6" />
                                                <span class="text-xs">{{ $task->assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs italic opacity-50">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div
                                            class="text-xs {{ $task->deadline_task && \Carbon\Carbon::parse($task->deadline_task)->isPast() && $task->status !== 'approved' ? 'text-error font-bold' : '' }}">
                                            {{ $task->deadline_task ? \Carbon\Carbon::parse($task->deadline_task)->format('d M') : '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ match ($task->status) {'approved' => 'badge-success','review' => 'badge-warning','revision' => 'badge-error',default => 'badge-ghost'} }} badge-xs text-[10px] uppercase font-bold text-white">{{ str_replace('_', ' ', $task->status) }}</span>
                                    </td>
                                    <td class="text-right">
                                        <x-button icon="o-eye" class="btn-xs btn-ghost"
                                            link="{{ route('admin.jobdesks.revision', $task->id) }}"
                                            tooltip="View Detail" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 opacity-50">No tasks found matching your
                                        filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $tasks->links() }}</div>
            </div>
        </x-tab>

        {{-- ================================================================= --}}
        {{-- TAB 2: STAFF PERFORMANCE (SEARCHABLE)                             --}}
        {{-- ================================================================= --}}
        <x-tab name="team" label="Staff Involved" icon="o-users">
            <div class="mt-4 space-y-4">

                {{-- Simple Search --}}
                <div class="flex justify-end">
                    <x-input icon="o-magnifying-glass" placeholder="Search staff..."
                        wire:model.live.debounce="staffSearch" class="input-sm w-full md:w-64" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($this->staffInvolved as $staff)
                        <div
                            class="bg-base-100 dark:bg-gray-800 p-4 rounded-xl border border-base-200 dark:border-gray-700 flex flex-col gap-3 relative group">
                            <div class="absolute top-2 right-2">
                                <x-button icon="o-information-circle" class="btn-xs btn-ghost"
                                    wire:click="openStaffDetail({{ $staff->id }})" tooltip="View Full Profile" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-avatar :image="$staff->profile_photo ? asset('storage/' . $staff->profile_photo) : null" class="w-14 h-14" />
                                <div class="flex-1">
                                    <div class="font-bold">{{ $staff->name }}</div>
                                    <div class="text-xs opacity-60 mb-2">{{ $staff->email }}</div>
                                    <span
                                        class="badge badge-neutral text-xs font-bold uppercase">{{ $staff->role }}</span>
                                    @if (!empty($staff->departments) && is_array($staff->departments))
                                        <div
                                            class="flex flex-wrap gap-1 mt-1 max-w-[200px] justify-center md:justify-start">
                                            @foreach ($staff->departments as $dept)
                                                <span
                                                    class="badge badge-ghost badge-xs text-[10px] font-semibold border-gray-300">
                                                    {{ $dept }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Stats --}}
                            <div class="flex justify-between text-[10px] mb-1">
                                <span>Project Performance</span>
                                <span
                                    class="{{ $staff->performance == 100 ? 'text-success font-bold' : '' }}">{{ $staff->performance }}%</span>
                            </div>
                            <progress class="progress progress-success w-full h-1.5"
                                value="{{ $staff->performance }}" max="100"></progress>

                            <div class="flex gap-2 mt-1">
                                <div class="badge badge-neutral badge-sm text-[10px]">{{ $staff->project_total }}
                                    Tasks</div>
                                <div class="badge badge-success badge-sm text-white text-[10px]">
                                    {{ $staff->project_done }} Done</div>
                                @if ($staff->project_late > 0)
                                    <div class="badge badge-error text-white badge-sm text-[10px]">
                                        {{ $staff->project_late }} Late</div>
                                @endif
                            </div>

                            <x-button label="View Details" class="btn-outline btn-sm w-full mt-2"
                                wire:click="openStaffDetail({{ $staff->id }})" />
                        </div>
                    @endforeach
                    @if ($this->staffInvolved->isEmpty())
                        <div class="col-span-3 text-center py-10 opacity-50">No staff found.</div>
                    @endif
                </div>
            </div>
        </x-tab>

        {{-- ================================================================= --}}
        {{-- TAB 3: WORK LOGS / ATTENDANCE REPORT (FILTERED)                   --}}
        {{-- ================================================================= --}}
        <x-tab name="logs" label="Work Logs" icon="o-document-text">
            <div class="mt-4 space-y-4">

                {{-- Toolbar Filter Logs --}}
                <div
                    class="flex flex-col lg:flex-row gap-4 items-end lg:items-center bg-base-100 p-4 rounded-xl border border-base-200 dark:border-gray-700">
                    <x-input icon="o-magnifying-glass" placeholder="Search logs (task/content)..."
                        wire:model.live.debounce="logSearch" class="w-full input-sm" />

                    <x-select wire:model.live="logStatus" class="select-sm w-full lg:w-40"
                        placeholder="Report Status" :options="[
                            ['id' => 'on_progress', 'name' => 'In Progress'],
                            ['id' => 'completed', 'name' => 'Completed'],
                        ]" />

                    <x-datetime wire:model.live="logDate" type="date" class="input-sm w-full lg:w-40"
                        placeholder="Date" />

                    <div class="ml-auto text-xs opacity-50">
                        Showing results for <b>{{ $pName }}</b>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($workLogs as $log)
                        @php
                            $logTitle = is_array($log->jobdesk->title)
                                ? $log->jobdesk->title['id'] ?? '-'
                                : $log->jobdesk->title;
                        @endphp

                        {{-- DIUBAH: Klik akan mengarah ke detail Attendance Report --}}
                        <a href="{{ route('admin.attendances.show', $log->attendance_id) }}"
                            class="flex gap-4 p-4 bg-base-100 dark:bg-gray-800 rounded-xl border border-base-200 dark:border-gray-700 hover:border-primary/50 transition cursor-pointer group block">

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
                                    "{{ Str::limit($log->details->first()->content ?? 'No details provided.', 100) }}"
                                </p>
                                <div
                                    class="text-[10px] text-gray-500 group-hover:text-primary mt-2 font-bold flex items-center gap-1 transition">
                                    Click to view attendance detail <x-icon name="o-arrow-right" class="w-3 h-3" />
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-12 opacity-50">No work logs match your criteria.</div>
                    @endforelse
                </div>
                <div class="mt-4">{{ $workLogs->links() }}</div>
            </div>
        </x-tab>
    </x-tabs>

    {{-- ================================================================= --}}
    {{-- MODAL 1: EDIT PROJECT                                             --}}
    {{-- ================================================================= --}}
    <x-modal wire:model="projectEditModalOpen" title="Edit Project" separator>
        <x-form wire:submit="updateProject">
            <x-translatable-input label="Project Name" model="pName" />
            <x-translatable-input label="Description" model="pDesc" type="textarea" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-datetime label="Deadline" wire:model="pDeadline" type="datetime-local" />
                <x-select label="Status" wire:model="pStatus" :options="[
                    ['id' => 'active', 'name' => 'Active'],
                    ['id' => 'on_hold', 'name' => 'On Hold'],
                    ['id' => 'completed', 'name' => 'Completed'],
                ]" />
            </div>

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.projectEditModalOpen = false" />
                <x-button label="Update" class="btn-primary" type="submit" spinner="updateProject" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- ================================================================= --}}
    {{-- MODAL 2: CREATE TASK                                              --}}
    {{-- ================================================================= --}}
    <x-modal wire:model="taskModalOpen" title="Add Task to Project" separator>
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

    {{-- ================================================================= --}}
    {{-- DRAWER 3: STAFF DETAIL (PERFORMANCE & HISTORY)                    --}}
    {{-- ================================================================= --}}
    <x-drawer wire:model="staffDetailModalOpen" title="Staff Performance" right separator with-close-button
        class="lg:w-1/2">
        @if ($selectedStaff && $selectedStaffData)
            <div class="space-y-6 pb-20">

                {{-- 1. PROFILE & STATS CARD --}}
                <div class="bg-base-200 dark:bg-gray-800 p-4 rounded-xl border border-base-300 dark:border-gray-700">
                    <div class="flex items-center gap-4 mb-4">
                        <x-avatar :image="$selectedStaff->profile_photo
                            ? asset('storage/' . $selectedStaff->profile_photo)
                            : null" class="w-16 h-16" />
                        <div>
                            <h3 class="font-bold text-lg">{{ $selectedStaff->name }}</h3>
                            <p class="text-sm opacity-60">{{ $selectedStaff->email }}</p>
                            <div class="badge badge-neutral mt-1">{{ $selectedStaff->role }}</div>
                        </div>
                    </div>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-base-100 p-3 rounded-lg text-center border border-base-200">
                            <div class="text-[10px] opacity-60 uppercase font-bold">Completion</div>
                            <div class="text-xl font-black text-primary">
                                {{ $selectedStaffData['stats']['progress'] }}%</div>
                        </div>
                        <div class="bg-base-100 p-3 rounded-lg text-center border border-base-200">
                            <div class="text-[10px] opacity-60 uppercase font-bold">Done / Total</div>
                            <div class="text-xl font-black">
                                {{ $selectedStaffData['stats']['done'] }} <span
                                    class="text-sm font-normal text-gray-400">/
                                    {{ $selectedStaffData['stats']['total'] }}</span>
                            </div>
                        </div>
                        <div class="bg-base-100 p-3 rounded-lg text-center border border-base-200">
                            <div class="text-[10px] opacity-60 uppercase font-bold">Late Submission</div>
                            <div class="text-xl font-black text-error">{{ $selectedStaffData['stats']['late'] }}</div>
                        </div>
                    </div>
                </div>

                {{-- 2. JOBDESK LIST --}}
                <div>
                    <div class="divider text-xs font-bold opacity-50">JOBDESK STATUS</div>

                    <div class="space-y-2">
                        @forelse($selectedStaffData['tasks'] as $t)
                            @php
                                $tName = is_array($t->title) ? $t->title['id'] ?? '-' : $t->title;
                            @endphp
                            <div
                                class="flex justify-between items-center text-sm p-3 bg-base-100 border border-base-200 rounded-lg hover:bg-base-200/50 transition">
                                <div class="flex flex-col gap-1 overflow-hidden pr-2">
                                    <span class="font-medium truncate">{{ $tName }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] opacity-60">
                                            Due:
                                            {{ $t->deadline_task ? \Carbon\Carbon::parse($t->deadline_task)->format('d M') : '-' }}
                                        </span>
                                        @if ($t->lateness_minutes > 0)
                                            <span class="text-[10px] text-error font-bold">• Late
                                                {{ $t->lateness_minutes }}m</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span
                                        class="badge badge-xs {{ match ($t->status) {'approved' => 'badge-success','revision' => 'badge-error','review' => 'badge-warning',default => 'badge-ghost'} }} uppercase font-bold text-white">
                                        {{ str_replace('_', ' ', $t->status) }}
                                    </span>

                                    <x-button icon="o-eye" class="btn-sm btn-square btn-ghost text-primary"
                                        link="{{ route('admin.jobdesks.revision', $t->id) }}"
                                        tooltip="View Discussion" />
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-xs opacity-50 py-4 border rounded-lg border-dashed">No tasks
                                assigned in this project.</div>
                        @endforelse
                    </div>
                </div>

                {{-- 3. ATTENDANCE HISTORY (PROJECT CONTEXT) --}}
                <div>
                    <div class="divider text-xs font-bold opacity-50">PROJECT WORK SESSIONS</div>
                    <div class="space-y-2">
                        @forelse($selectedStaffData['attendances'] as $att)
                            <div
                                class="flex justify-between items-center text-xs p-3 bg-base-100 border border-base-200 rounded-lg hover:bg-base-200/50 transition">
                                <div>
                                    <div class="font-bold flex items-center gap-2">
                                        {{ $att->created_at->format('d M Y') }}
                                        <span class="badge badge-ghost badge-xs">{{ $att->reports->count() }}
                                            Logs</span>
                                    </div>
                                    <div class="opacity-50 mt-0.5 font-mono">
                                        <span class="text-success">{{ $att->check_in->format('H:i') }}</span> -
                                        <span
                                            class="text-error">{{ $att->check_out ? $att->check_out->format('H:i') : 'Active' }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="flex gap-1">
                                        @if ($att->check_in_latitude)
                                            <x-icon name="o-map-pin" class="w-3 h-3 text-success"
                                                tooltip="Location Recorded" />
                                        @endif
                                        @if ($att->selfie_in)
                                            <x-icon name="o-camera" class="w-3 h-3 text-info"
                                                tooltip="Selfie Verified" />
                                        @endif
                                    </div>

                                    <x-button icon="o-eye" class="btn-sm btn-square btn-ghost text-primary"
                                        link="{{ route('admin.attendances.show', $att->id) }}"
                                        tooltip="View Maps & Photos" />
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-xs opacity-50 py-4 border rounded-lg border-dashed">
                                No attendance records linked to this project found.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </x-drawer>
</div>
