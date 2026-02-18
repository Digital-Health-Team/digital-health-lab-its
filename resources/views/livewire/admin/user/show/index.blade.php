<div class="space-y-8 pb-10">

    {{-- BREADCRUMBS --}}
    <div class="text-xs breadcrumbs text-gray-500">
        <ul>
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.users') }}">Staff List</a></li>
            <li class="font-bold">Detail</li>
        </ul>
    </div>

    {{-- 1. PROFILE HEADER --}}
    <div class="card bg-base-100 dark:bg-gray-800 shadow-sm border border-base-200 dark:border-gray-700">
        <div class="card-body p-6">
            <div class="flex flex-col md:flex-row gap-6 items-center md:items-start">
                <div class="avatar">
                    <div class="w-24 h-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                        <img
                            src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" />
                    </div>
                </div>
                <div class="flex-1 w-full text-center md:text-left">
                    <h1 class="text-3xl font-black">{{ $user->name }}</h1>
                    <div
                        class="flex flex-col md:flex-row items-center gap-2 md:gap-4 text-gray-500 text-sm mt-1 justify-center md:justify-start">
                        <span class="flex items-center gap-1"><x-icon name="o-envelope" class="w-4 h-4" />
                            {{ $user->email }}</span>
                        <span class="badge badge-neutral text-xs font-bold uppercase">{{ $user->role }}</span>
                        @if (!empty($user->departments) && is_array($user->departments))
                            <div class="flex flex-wrap gap-1 mt-1 max-w-[200px] justify-center md:justify-start">
                                @foreach ($user->departments as $dept)
                                    <span class="badge badge-ghost badge-xs text-[10px] font-semibold border-gray-300">
                                        {{ $dept }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <div class="bg-base-200/50 p-3 rounded-lg text-center border border-base-200">
                            <div class="text-xs opacity-60 uppercase font-bold">Total Tasks</div>
                            <div class="text-xl font-black">{{ $stats['total'] }}</div>
                        </div>
                        <div class="bg-base-200/50 p-3 rounded-lg text-center border border-base-200">
                            <div class="text-xs opacity-60 uppercase font-bold">Performance</div>
                            <div
                                class="text-xl font-black {{ $stats['performance'] >= 80 ? 'text-success' : 'text-warning' }}">
                                {{ $stats['performance'] }}%</div>
                        </div>
                        <div class="bg-base-200/50 p-3 rounded-lg text-center border border-base-200">
                            <div class="text-xs opacity-60 uppercase font-bold">Late Tasks</div>
                            <div class="text-xl font-black text-error">{{ $stats['late'] }}</div>
                        </div>
                        <div class="bg-base-200/50 p-3 rounded-lg text-center border border-base-200">
                            <div class="text-xs opacity-60 uppercase font-bold">Projects</div>
                            <div class="text-xl font-black">{{ $projectsList->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. TABS CONTENT --}}
    <x-tabs wire:model="selectedTab">

        {{-- TAB 1: PROJECTS --}}
        <x-tab name="projects" label="Projects" icon="o-briefcase">
            <div class="mt-4 overflow-x-auto bg-base-100 rounded-xl border border-base-200">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th class="w-10">#</th>
                            <th>Project Name</th>
                            <th>Status</th>
                            <th>Tasks Assigned</th>
                            <th>Deadline</th>
                            <th class="text-right">Performance</th> {{-- Tambahkan Header --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projectsList as $index => $proj)
                            <tr>
                                <th>{{ $index + 1 }}</th>
                                <td class="font-bold">
                                    <a href="{{ route('admin.projects.show', $proj->id) }}"
                                        class="hover:text-primary hover:underline">
                                        {{ $proj->display_name }}
                                    </a>
                                </td>
                                <td>
                                    <span
                                        class="badge badge-xs uppercase {{ match ($proj->status) {'active' => 'badge-info','completed' => 'badge-success',default => 'badge-ghost'} }}">
                                        {{ $proj->status }}
                                    </span>
                                </td>
                                <td>{{ $proj->staff_tasks_count }} Tasks</td>
                                <td class="text-xs">
                                    {{ $proj->deadline_global ? $proj->deadline_global->format('d M Y') : '-' }}</td>
                                <td class="text-right">
                                    {{-- TOMBOL BUKA DRAWER PERFORMA --}}
                                    <x-button label="View Report" icon="o-chart-bar"
                                        class="btn-xs btn-outline btn-primary"
                                        wire:click="openProjectPerformance({{ $proj->id }})" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 opacity-50">No projects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-tab>

        {{-- TAB 2: TASKS --}}
        <x-tab name="tasks" label="Jobdesk & Tasks" icon="o-clipboard-document-list">
            <div class="mt-4 space-y-4">
                <div
                    class="bg-base-100 p-4 rounded-xl border border-base-200 flex flex-col lg:flex-row gap-3 items-end lg:items-center">
                    <x-input icon="o-magnifying-glass" placeholder="Search task..."
                        wire:model.live.debounce="taskSearch" class="input-sm w-full lg:w-1/4" />
                    <select wire:model.live="taskProject" class="select select-sm select-bordered w-full lg:w-1/4">
                        <option value="">All Projects</option>
                        @foreach ($projectsList as $p)
                            <option value="{{ $p->id }}">{{ $p->display_name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="taskStatus" class="select select-sm select-bordered w-full lg:w-1/6">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="on_progress">On Progress</option>
                        <option value="review">Review</option>
                        <option value="revision">Revision</option>
                        <option value="approved">Approved</option>
                    </select>
                    <div class="flex gap-2 w-full lg:w-auto ml-auto">
                        <input type="date" wire:model.live="taskDateStart"
                            class="input input-sm input-bordered w-1/2 lg:w-auto" />
                        <input type="date" wire:model.live="taskDateEnd"
                            class="input input-sm input-bordered w-1/2 lg:w-auto" />
                    </div>
                </div>

                <div class="overflow-x-auto bg-base-100 rounded-xl border border-base-200">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th class="w-10">#</th>
                                <th>Task Info</th>
                                <th>Project</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                @php
                                    $tTitle = is_array($task->title) ? $task->title['id'] ?? '-' : $task->title;
                                    $pName = is_array($task->project->name)
                                        ? $task->project->name['id'] ?? '-'
                                        : $task->project->name;
                                @endphp
                                <tr>
                                    <th>{{ $loop->iteration + ($tasks->firstItem() - 1) }}</th>
                                    <td>
                                        <div class="font-bold">{{ $tTitle }}</div>
                                        <div class="text-xs text-gray-500 truncate w-64">
                                            {{ Str::limit(is_array($task->description) ? $task->description['id'] : $task->description, 50) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge badge-ghost badge-sm">{{ $pName }}</div>
                                    </td>
                                    <td>
                                        <div
                                            class="text-xs {{ $task->deadline_task && \Carbon\Carbon::parse($task->deadline_task)->isPast() && $task->status != 'approved' ? 'text-error font-bold' : '' }}">
                                            {{ $task->deadline_task ? \Carbon\Carbon::parse($task->deadline_task)->format('d M H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-xs uppercase font-bold text-white {{ match ($task->status) {'approved' => 'badge-success','revision' => 'badge-error','review' => 'badge-warning',default => 'badge-ghost'} }}">
                                            {{ str_replace('_', ' ', $task->status) }}
                                        </span>
                                        @if ($task->lateness_minutes > 0)
                                            <span class="badge badge-error badge-xs text-white ml-1">LATE</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <x-button icon="o-eye" class="btn-xs btn-square btn-ghost"
                                            link="{{ route('admin.jobdesks.revision', $task->id) }}"
                                            tooltip="Detail" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 opacity-50">No tasks found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>{{ $tasks->links() }}</div>
            </div>
        </x-tab>

        {{-- TAB 3: ATTENDANCE HISTORY --}}
        <x-tab name="attendance" label="Attendance History" icon="o-finger-print">
            <div class="mt-4 space-y-4">
                <div class="flex justify-between items-center bg-base-100 p-4 rounded-xl border border-base-200">
                    <h3 class="font-bold text-sm">Monthly Recap</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-xs opacity-60">Filter Month:</span>
                        <input type="month" wire:model.live="attMonth" class="input input-sm input-bordered" />
                    </div>
                </div>

                <div class="overflow-x-auto bg-base-100 rounded-xl border border-base-200">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th class="w-10">#</th>
                                <th>Date</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Duration</th>
                                <th>Work Reports</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $att)
                                <tr>
                                    <th>{{ $loop->iteration + ($attendances->firstItem() - 1) }}</th>
                                    <td class="font-bold">{{ $att->check_in->format('d M Y') }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-success font-mono font-bold">{{ $att->check_in->format('H:i') }}</span>
                                            @if ($att->check_in_latitude)
                                                <x-icon name="o-map-pin" class="w-3 h-3 text-gray-400"
                                                    tooltip="Loc Recorded" />
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if ($att->check_out)
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-error font-mono font-bold">{{ $att->check_out->format('H:i') }}</span>
                                                @if ($att->check_out_latitude)
                                                    <x-icon name="o-map-pin" class="w-3 h-3 text-gray-400"
                                                        tooltip="Loc Recorded" />
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge badge-warning badge-xs">Active</span>
                                        @endif
                                    </td>
                                    <td class="text-xs opacity-60">
                                        {{ $att->check_out ? $att->check_in->diff($att->check_out)->format('%Hh %Im') : 'Running...' }}
                                    </td>
                                    <td>
                                        <div class="badge badge-ghost badge-sm">{{ $att->reports_count }} Logs</div>
                                    </td>
                                    <td class="text-right">
                                        {{-- BUTTON OPEN DRAWER --}}
                                        <x-button icon="o-eye" class="btn-xs btn-square btn-ghost text-primary"
                                            wire:click="openAttendanceDetail({{ $att->id }})"
                                            tooltip="View Log & Map" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-8 opacity-50">No attendance records
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>{{ $attendances->links() }}</div>
            </div>
        </x-tab>
    </x-tabs>

    {{-- ================================================================= --}}
    {{-- DRAWER: ATTENDANCE DETAIL (MAPS + SELFIE + LOGS)                  --}}
    {{-- ================================================================= --}}
    <x-drawer wire:model="attendanceDrawerOpen" title="Attendance Detail" right separator with-close-button
        class="lg:w-1/2">
        @if ($selectedAttendance)
            <div class="space-y-6 pb-20">

                {{-- Header Summary --}}
                <div
                    class="bg-base-200 dark:bg-gray-800 p-4 rounded-xl border border-base-300 flex justify-between items-center">
                    <div>
                        <div class="text-xs opacity-60 uppercase mb-1">Date</div>
                        <div class="font-bold text-lg">{{ $selectedAttendance->check_in->format('l, d F Y') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs opacity-60 uppercase mb-1">Total Duration</div>
                        <div class="font-mono font-bold text-lg">
                            {{ $selectedAttendance->check_out ? $selectedAttendance->check_in->diff($selectedAttendance->check_out)->format('%Hh %Im') : 'Active' }}
                        </div>
                    </div>
                </div>

                {{-- VISUAL EVIDENCE GRID --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- 1. START SESSION --}}
                    <div class="card bg-base-100 border border-base-200 shadow-sm">
                        <div
                            class="p-3 bg-base-200/50 border-b border-base-200 font-bold text-xs flex justify-between items-center text-success">
                            <span>CHECK IN</span>
                            <span class="font-mono">{{ $selectedAttendance->check_in->format('H:i') }}</span>
                        </div>
                        <div class="p-3 space-y-2">
                            @if ($selectedAttendance->check_in_latitude)
                                <iframe width="100%" height="120" frameborder="0" style="border:0"
                                    class="rounded-lg border border-base-300"
                                    src="https://maps.google.com/maps?q={{ $selectedAttendance->check_in_latitude }},{{ $selectedAttendance->check_in_longitude }}&z=15&output=embed">
                                </iframe>
                            @else
                                <div
                                    class="h-[120px] bg-base-200 flex items-center justify-center text-xs opacity-50 rounded-lg">
                                    No Map Data</div>
                            @endif

                            @if ($selectedAttendance->selfie_in)
                                <div
                                    class="aspect-video bg-black rounded-lg overflow-hidden relative border border-base-300">
                                    <img src="{{ asset('storage/' . $selectedAttendance->selfie_in) }}"
                                        class="w-full h-full object-cover">
                                    <span
                                        class="absolute bottom-1 left-1 bg-black/50 text-white text-[9px] px-1 rounded">Selfie
                                        In</span>
                                </div>
                            @else
                                <div
                                    class="h-[100px] bg-base-200 flex items-center justify-center text-xs opacity-50 rounded-lg">
                                    No Selfie</div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. END SESSION --}}
                    <div class="card bg-base-100 border border-base-200 shadow-sm">
                        <div
                            class="p-3 bg-base-200/50 border-b border-base-200 font-bold text-xs flex justify-between items-center text-error">
                            <span>CHECK OUT</span>
                            <span
                                class="font-mono">{{ $selectedAttendance->check_out ? $selectedAttendance->check_out->format('H:i') : 'Active' }}</span>
                        </div>
                        <div class="p-3 space-y-2">
                            @if ($selectedAttendance->check_out_latitude)
                                <iframe width="100%" height="120" frameborder="0" style="border:0"
                                    class="rounded-lg border border-base-300"
                                    src="https://maps.google.com/maps?q={{ $selectedAttendance->check_out_latitude }},{{ $selectedAttendance->check_out_longitude }}&z=15&output=embed">
                                </iframe>
                            @else
                                <div
                                    class="h-[120px] bg-base-200 flex items-center justify-center text-xs opacity-50 rounded-lg">
                                    {{ $selectedAttendance->check_out ? 'No Map Data' : 'Not Checked Out' }}
                                </div>
                            @endif

                            @if ($selectedAttendance->selfie_out)
                                <div
                                    class="aspect-video bg-black rounded-lg overflow-hidden relative border border-base-300">
                                    <img src="{{ asset('storage/' . $selectedAttendance->selfie_out) }}"
                                        class="w-full h-full object-cover">
                                    <span
                                        class="absolute bottom-1 left-1 bg-black/50 text-white text-[9px] px-1 rounded">Selfie
                                        Out</span>
                                </div>
                            @else
                                <div
                                    class="h-[100px] bg-base-200 flex items-center justify-center text-xs opacity-50 rounded-lg">
                                    {{ $selectedAttendance->check_out ? 'No Selfie' : '...' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- WORK LOGS / REPORTS --}}
                <div>
                    <div class="divider text-xs font-bold opacity-50">WORK LOGS SUBMITTED</div>
                    <div class="space-y-4">
                        @forelse($selectedAttendance->reports as $report)
                            @php
                                $repTitle = is_array($report->jobdesk->title)
                                    ? $report->jobdesk->title['id'] ?? '-'
                                    : $report->jobdesk->title;
                            @endphp
                            <div
                                class="card bg-base-100 dark:bg-gray-900 border border-base-200 dark:border-gray-700 p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="font-bold text-sm">{{ $repTitle }}</div>
                                        <div class="text-[10px] opacity-60">Submitted at
                                            {{ $report->created_at->format('H:i') }}</div>
                                    </div>
                                    <span
                                        class="badge {{ $report->status_at_report == 'completed' ? 'badge-success' : 'badge-warning' }} badge-xs text-white">
                                        {{ $report->status_at_report }}
                                    </span>
                                </div>
                                <div
                                    class="bg-base-200/50 p-3 rounded-lg text-sm italic text-gray-600 dark:text-gray-300">
                                    "{{ $report->details->first()->content ?? '-' }}"
                                </div>

                                {{-- Report Attachments --}}
                                @if ($report->attachments->count() > 0)
                                    <div class="flex gap-2 mt-3 overflow-x-auto pb-1">
                                        @foreach ($report->attachments as $att)
                                            <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank"
                                                class="block w-16 h-16 flex-shrink-0 rounded-lg border overflow-hidden hover:ring-2 hover:ring-primary">
                                                <img src="{{ asset('storage/' . $att->file_path) }}"
                                                    class="w-full h-full object-cover">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4 opacity-50 text-sm italic">No work logs submitted in this
                                session.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        @endif
    </x-drawer>

    {{-- ================================================================= --}}
    {{-- DRAWER: PROJECT PERFORMANCE DETAIL                                --}}
    {{-- ================================================================= --}}
    <x-drawer wire:model="projectPerformanceDrawer" title="Performance Report" right separator with-close-button
        class="lg:w-1/2">
        @if ($selectedProjectPerformance && !empty($projectPerformanceData))
            @php
                $pName = is_array($selectedProjectPerformance->name)
                    ? $selectedProjectPerformance->name['id'] ?? '-'
                    : $selectedProjectPerformance->name;
                $stats = $projectPerformanceData['stats'];
            @endphp

            <div class="space-y-6 pb-20">

                {{-- 1. HEADER SUMMARY --}}
                <div
                    class="bg-base-200 dark:bg-gray-800 p-5 rounded-xl border border-base-300 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="text-xs opacity-60 uppercase mb-1">Project Context</div>
                        <h2 class="font-black text-xl mb-1">{{ $pName }}</h2>
                        <div
                            class="badge {{ $selectedProjectPerformance->status == 'active' ? 'badge-info' : 'badge-ghost' }} badge-sm uppercase">
                            {{ str_replace('_', ' ', $selectedProjectPerformance->status) }}
                        </div>
                    </div>
                    {{-- Decorative Icon --}}
                    <x-icon name="o-briefcase"
                        class="absolute -right-4 -bottom-4 w-32 h-32 text-base-300 opacity-20 rotate-12" />
                </div>

                {{-- 2. KPI CARDS --}}
                <div class="grid grid-cols-2 gap-3">
                    {{-- Progress --}}
                    <div
                        class="p-4 bg-base-100 border border-base-200 rounded-xl flex flex-col items-center justify-center text-center">
                        <div class="radial-progress text-primary text-xs font-bold"
                            style="--value:{{ $stats['progress'] }}; --size:3rem;">
                            {{ $stats['progress'] }}%
                        </div>
                        <div class="text-xs font-bold mt-2">Completion</div>
                    </div>

                    {{-- Tasks --}}
                    <div class="p-4 bg-base-100 border border-base-200 rounded-xl flex flex-col justify-center">
                        <div class="text-xs opacity-60">Tasks Done</div>
                        <div class="text-xl font-black">
                            {{ $stats['done'] }} <span class="text-sm font-normal text-gray-400">/
                                {{ $stats['total'] }}</span>
                        </div>
                    </div>

                    {{-- Late --}}
                    <div class="p-4 bg-base-100 border border-base-200 rounded-xl flex flex-col justify-center">
                        <div class="text-xs opacity-60">Late Submissions</div>
                        <div class="text-xl font-black {{ $stats['late'] > 0 ? 'text-error' : 'text-success' }}">
                            {{ $stats['late'] }}
                        </div>
                    </div>

                    {{-- Time Spent --}}
                    <div class="p-4 bg-base-100 border border-base-200 rounded-xl flex flex-col justify-center">
                        <div class="text-xs opacity-60">Est. Time Spent</div>
                        <div class="text-xl font-black text-info">{{ $stats['time_spent'] }}</div>
                    </div>
                </div>

                {{-- 3. TASK LIST --}}
                <div>
                    <div class="divider text-xs font-bold opacity-50">TASK BREAKDOWN</div>
                    <div class="space-y-2">
                        @forelse($projectPerformanceData['tasks'] as $task)
                            @php $tName = is_array($task->title) ? $task->title['id'] ?? '-' : $task->title; @endphp
                            <div
                                class="flex justify-between items-center text-sm p-3 bg-base-100 border border-base-200 rounded-lg hover:border-primary transition group">
                                <div class="flex-1 pr-2">
                                    <div class="font-bold line-clamp-1">{{ $tName }}</div>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if ($task->lateness_minutes > 0)
                                            <span class="badge badge-error badge-xs text-white">Late
                                                {{ $task->lateness_minutes }}m</span>
                                        @endif
                                        <span class="text-[10px] opacity-60">Due:
                                            {{ $task->deadline_task ? \Carbon\Carbon::parse($task->deadline_task)->format('d M') : '-' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="badge badge-xs {{ match ($task->status) {'approved' => 'badge-success','revision' => 'badge-error',default => 'badge-ghost'} }} uppercase font-bold text-white">
                                        {{ str_replace('_', ' ', $task->status) }}
                                    </span>
                                    {{-- Tombol Lihat Detail Revisi --}}
                                    <x-button icon="o-eye" class="btn-xs btn-square btn-ghost text-primary"
                                        link="{{ route('admin.jobdesks.revision', $task->id) }}"
                                        tooltip="View Detail" />
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-xs opacity-50 py-4 italic">No tasks assigned in this project.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- 4. ATTENDANCE & WORK LOGS (PROJECT CONTEXT) --}}
                <div>
                    <div class="divider text-xs font-bold opacity-50">WORK SESSIONS</div>
                    <div class="space-y-4">
                        @forelse($projectPerformanceData['attendances'] as $att)
                            <div class="bg-base-100 border border-base-200 rounded-xl p-3">
                                {{-- Attendance Header --}}
                                <div class="flex justify-between items-center mb-2 pb-2 border-b border-base-200">
                                    <div class="flex items-center gap-2">
                                        <x-icon name="o-calendar" class="w-4 h-4 text-gray-400" />
                                        <span class="font-bold text-xs">{{ $att->check_in->format('d M Y') }}</span>
                                    </div>
                                    <div class="text-[10px] font-mono">
                                        <span class="text-success">{{ $att->check_in->format('H:i') }}</span> -
                                        <span
                                            class="text-error">{{ $att->check_out ? $att->check_out->format('H:i') : 'Active' }}</span>
                                    </div>
                                </div>

                                {{-- Logs in this session for this project --}}
                                <div class="space-y-2">
                                    @foreach ($att->reports as $report)
                                        @php $rTitle = is_array($report->jobdesk->title) ? $report->jobdesk->title['id'] ?? '-' : $report->jobdesk->title; @endphp
                                        <div class="bg-base-200/50 p-2 rounded text-xs">
                                            <div class="flex justify-between mb-1">
                                                <span class="font-bold truncate w-3/4">{{ $rTitle }}</span>
                                                <span
                                                    class="text-[9px] opacity-60">{{ $report->created_at->format('H:i') }}</span>
                                            </div>
                                            <div class="italic opacity-70">
                                                "{{ Str::limit($report->details->first()->content ?? '-', 50) }}"</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-xs opacity-50 py-4 italic">No work sessions recorded for this
                                project.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        @endif
    </x-drawer>

</div>
