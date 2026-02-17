<div class="space-y-8 pb-10">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h1 class="text-3xl font-black dark:text-white">Project Manager Dashboard</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Overview & Approval Center | {{ \Carbon\Carbon::now()->format('d M Y') }}
            </p>
        </div>
        <x-button label="+ NEW TASK" icon="o-plus" class="btn-primary shadow-lg rounded-full"
            @click="$wire.createModal = true" />
    </div>

    {{-- STATS ROW --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="stat bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
            <div class="stat-figure text-primary bg-primary/10 p-3 rounded-full"><x-icon name="o-briefcase"
                    class="w-6 h-6" /></div>
            <div class="stat-title text-xs font-bold uppercase opacity-60">Active Projects</div>
            <div class="stat-value text-2xl font-black dark:text-gray-100">{{ $stats['active_projects'] }}</div>
        </div>
        <div class="stat bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
            <div class="stat-figure text-warning bg-warning/10 p-3 rounded-full"><x-icon name="o-inbox-arrow-down"
                    class="w-6 h-6" /></div>
            <div class="stat-title text-xs font-bold uppercase opacity-60">Pending Review</div>
            <div
                class="stat-value text-2xl font-black {{ $stats['pending_review'] > 0 ? 'text-warning' : 'dark:text-gray-100' }}">
                {{ $stats['pending_review'] }}</div>
        </div>
        <div class="stat bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
            <div class="stat-figure text-error bg-error/10 p-3 rounded-full"><x-icon name="o-clock" class="w-6 h-6" />
            </div>
            <div class="stat-title text-xs font-bold uppercase opacity-60 text-error">Late KPI (Month)</div>
            <div class="stat-value text-2xl font-black text-error">{{ $stats['team_lateness'] }}</div>
        </div>
    </div>

    {{-- TABS NAVIGATION --}}
    <x-tabs wire:model="selectedTab">

        {{-- TAB 1: OVERVIEW (PROJECTS & APPROVALS) --}}
        <x-tab name="overview" label="Overview" icon="o-home">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-4">

                {{-- LEFT: PROJECT MONITORING --}}
                <div class="lg:col-span-2 space-y-4">
                    <h2 class="font-bold flex items-center gap-2 dark:text-gray-100">
                        <x-icon name="o-chart-bar" class="w-5 h-5 text-primary" /> Project Progress
                    </h2>
                    <div class="space-y-4">
                        @forelse($projects as $proj)
                            <div
                                class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition group">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-bold text-lg dark:text-gray-100">{{ $proj->display_name }}
                                            </h3>
                                            @if ($proj->is_urgent)
                                                <span
                                                    class="badge badge-error text-white badge-xs font-bold animate-pulse">URGENT</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                            <x-icon name="o-calendar" class="w-3 h-3" /> Deadline:
                                            {{ $proj->deadline_global ? $proj->deadline_global->format('d M Y') : 'No Deadline' }}
                                        </div>
                                    </div>
                                    <div class="text-right"><span
                                            class="text-2xl font-black text-primary">{{ $proj->progress }}%</span></div>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                                    <div class="bg-primary h-2.5 rounded-full transition-all duration-1000"
                                        style="width: {{ $proj->progress }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs opacity-60">
                                    <span>{{ $proj->completed_tasks }} / {{ $proj->jobdesks_count }} Tasks Done</span>
                                    <span>{{ $proj->jobdesks_count - $proj->completed_tasks }} Remaining</span>
                                </div>
                            </div>
                        @empty
                            <div
                                class="text-center py-10 opacity-50 bg-gray-50 dark:bg-gray-800 rounded-xl border border-dashed border-gray-300">
                                No active projects found.</div>
                        @endforelse
                    </div>
                </div>

                {{-- RIGHT: APPROVAL QUEUE --}}
                <div class="space-y-4">
                    <h2 class="font-bold flex items-center gap-2 dark:text-gray-100">
                        <x-icon name="o-check-badge" class="w-5 h-5 text-warning" /> Needs Approval
                    </h2>
                    <div class="space-y-3">
                        @forelse($pendingReviews as $task)
                            @php
                                $tTitle = is_array($task->title)
                                    ? $task->title['id'] ?? $task->title['en']
                                    : $task->title;
                                $pName = is_array($task->project->name)
                                    ? $task->project->name['id'] ?? $task->project->name['en']
                                    : $task->project->name;
                            @endphp
                            <div
                                class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden">
                                @if ($task->lateness_minutes > 0)
                                    <div
                                        class="absolute top-0 right-0 bg-error text-white text-[10px] px-2 py-0.5 rounded-bl-lg font-bold">
                                        LATE</div>
                                @endif
                                <div class="flex items-center gap-3 mb-2">
                                    <x-avatar :image="$task->assignee->profile_photo
                                        ? asset('storage/' . $task->assignee->profile_photo)
                                        : null" class="!w-8 !h-8" />
                                    <div class="overflow-hidden">
                                        <div class="text-xs font-bold dark:text-gray-200">{{ $task->assignee->name }}
                                        </div>
                                        <div class="text-[10px] opacity-60 truncate">Submitted
                                            {{ $task->submitted_at ? $task->submitted_at->diffForHumans() : '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div
                                        class="text-[10px] uppercase opacity-50 tracking-wider bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded w-fit mb-1">
                                        {{ Str::limit($pName, 20) }}</div>
                                    <h4 class="font-bold text-sm dark:text-gray-100 leading-tight">{{ $tTitle }}
                                    </h4>
                                </div>
                                <x-button label="REVIEW NOW" class="btn-sm btn-outline w-full"
                                    @click="$wire.openReviewModal({{ $task->id }})" />
                            </div>
                        @empty
                            <div
                                class="text-center py-10 opacity-50 bg-gray-50 dark:bg-gray-800 rounded-xl border border-dashed border-gray-300">
                                All caught up!</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-tab>

        {{-- TAB 2: STAFF ATTENDANCE (BARU) --}}
        <x-tab name="attendance" label="Staff Attendance" icon="o-finger-print">
            <div
                class="mt-4 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th>Staff Name</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Duration</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffAttendance as $att)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <x-avatar :image="$att->user->profile_photo
                                                ? asset('storage/' . $att->user->profile_photo)
                                                : null" class="!w-8 !h-8" />
                                            <span class="font-bold">{{ $att->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="font-mono text-success">{{ $att->check_in->format('H:i') }}</td>
                                    <td class="font-mono {{ $att->check_out ? 'text-error' : 'text-gray-400' }}">
                                        {{ $att->check_out ? $att->check_out->format('H:i') : '--:--' }}
                                    </td>
                                    <td class="text-xs opacity-60">
                                        {{ $att->check_out ? $att->check_in->diff($att->check_out)->format('%Hh %Im') : 'Working...' }}
                                    </td>
                                    <td>
                                        <x-button icon="o-map-pin" class="btn-xs btn-ghost"
                                            tooltip="View Location & Photo"
                                            @click="$wire.openAttendanceDetail({{ $att->id }})" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 opacity-50">No attendance records for
                                        today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-tab>
    </x-tabs>

    {{-- MODAL REVIEW --}}
    <x-modal wire:model="reviewModal" title="Review Submission" separator size="4xl">
        @if ($selectedTask)
            @php
                $tTitle = is_array($selectedTask->title)
                    ? $selectedTask->title['id'] ?? $selectedTask->title['en']
                    : $selectedTask->title;
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="bg-base-200 dark:bg-gray-700 p-3 rounded-lg">
                        <div class="text-xs opacity-60 uppercase">Task</div>
                        <div class="font-bold dark:text-gray-100">{{ $tTitle }}</div>
                    </div>
                    @php $latestReport = $selectedTask->reports()->latest()->first(); @endphp
                    <div>
                        <div class="text-xs opacity-60 uppercase mb-1">Staff Note</div>
                        <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg text-sm border dark:border-gray-600">
                            @if ($latestReport && $latestReport->details->isNotEmpty())
                                "{{ $latestReport->details->first()->content }}"
                            @else
                                <em class="opacity-50">No note.</em>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    <div class="text-xs opacity-60 uppercase mb-2">Work Proofs</div>
                    @if ($latestReport && $latestReport->attachments->count() > 0)
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($latestReport->attachments as $att)
                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank"
                                    class="block aspect-video bg-black rounded overflow-hidden relative group">
                                    <img src="{{ asset('storage/' . $att->file_path) }}"
                                        class="w-full h-full object-cover">
                                    <div
                                        class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-white text-xs">
                                        View</div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div
                            class="h-32 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg text-xs opacity-50">
                            No files.</div>
                    @endif
                </div>
            </div>

            {{-- Tombol Chat Revisi --}}
            <div
                class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800 flex justify-between items-center">
                <div class="text-sm">
                    <span class="font-bold text-blue-600 dark:text-blue-400">Diskusi Lebih Lanjut?</span>
                    <p class="text-xs opacity-70">Masuk ke halaman detail revisi untuk chat dan history lengkap.</p>
                </div>
                <x-button label="Open Discussion / Revision Page" icon="o-chat-bubble-left-right"
                    link="{{ route('pm.jobdesks.revision', $selectedTask->id) }}" class="btn-sm btn-primary" />
            </div>

            <hr class="my-4 border-gray-200 dark:border-gray-700">
            <div class="flex gap-4 justify-end">
                <button wire:click="submitReview('approve')" class="btn btn-success text-white gap-2"><x-icon
                        name="o-check" /> Approve & Close</button>
            </div>
        @endif
        <x-slot:actions class="hidden"></x-slot:actions>
    </x-modal>

    {{-- MODAL CREATE TASK --}}
    <x-modal wire:model="createModal" title="Create New Jobdesk" separator>
        <div class="space-y-4">
            <x-select label="Project" wire:model="project_id" :options="$projects" option-value="id"
                option-label="display_name" placeholder="Select Project" />
            <x-select label="Assign To" wire:model="assigned_to" :options="$staffs" option-value="id"
                option-label="name" placeholder="Select Staff" />
            <x-input label="Task Title" wire:model="title" placeholder="e.g. Fix Login Bug" />
            <x-textarea label="Description" wire:model="description" placeholder="Task details..." />
            <x-datetime label="Deadline" wire:model="deadline_task" type="datetime-local" />
        </div>
        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.createModal = false" />
            <x-button label="Assign Task" class="btn-primary" wire:click="storeTask" spinner />
        </x-slot:actions>
    </x-modal>

    {{-- DRAWER ATTENDANCE DETAIL (MAPS & PHOTOS) --}}
    <x-drawer wire:model="attendanceDrawer" title="Attendance Detail" right separator with-close-button
        class="lg:w-1/2">
        @if ($selectedAttendance)
            <div class="space-y-6">
                {{-- Header --}}
                <div class="flex items-center gap-3 bg-base-200 dark:bg-base-800 p-4 rounded-xl">
                    <x-avatar :image="$selectedAttendance->user->profile_photo
                        ? asset('storage/' . $selectedAttendance->user->profile_photo)
                        : null" class="w-12 h-12" />
                    <div>
                        <div class="font-bold">{{ $selectedAttendance->user->name }}</div>
                        <div class="text-xs opacity-60">{{ $selectedAttendance->created_at->format('l, d F Y') }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Check In --}}
                    <div class="card bg-base-100 border shadow-sm">
                        <div class="card-body p-4">
                            <div class="flex justify-between mb-2">
                                <span class="badge badge-success text-white font-bold">IN</span>
                                <span
                                    class="font-mono font-bold">{{ $selectedAttendance->check_in->format('H:i') }}</span>
                            </div>
                            {{-- Map --}}
                            @if ($selectedAttendance->check_in_latitude)
                                <div class="rounded-lg overflow-hidden h-32 border mb-2 relative group">
                                    <iframe width="100%" height="100%" frameborder="0" style="border:0"
                                        src="https://maps.google.com/maps?q={{ $selectedAttendance->check_in_latitude }},{{ $selectedAttendance->check_in_longitude }}&z=15&output=embed"></iframe>
                                    <a href="https://maps.google.com/maps?q={{ $selectedAttendance->check_in_latitude }},{{ $selectedAttendance->check_in_longitude }}"
                                        target="_blank"
                                        class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold">Open
                                        Map</a>
                                </div>
                            @endif
                            {{-- Selfie --}}
                            <div class="aspect-video bg-black rounded-lg overflow-hidden">
                                @if ($selectedAttendance->selfie_in)
                                    <img src="{{ asset('storage/' . $selectedAttendance->selfie_in) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white/50 text-xs">
                                        No Selfie</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Check Out --}}
                    <div class="card bg-base-100 border shadow-sm">
                        <div class="card-body p-4">
                            <div class="flex justify-between mb-2">
                                <span class="badge badge-error text-white font-bold">OUT</span>
                                <span
                                    class="font-mono font-bold">{{ $selectedAttendance->check_out ? $selectedAttendance->check_out->format('H:i') : 'Active' }}</span>
                            </div>
                            @if ($selectedAttendance->check_out_latitude)
                                <div class="rounded-lg overflow-hidden h-32 border mb-2 relative group">
                                    <iframe width="100%" height="100%" frameborder="0" style="border:0"
                                        src="https://maps.google.com/maps?q={{ $selectedAttendance->check_out_latitude }},{{ $selectedAttendance->check_out_longitude }}&z=15&output=embed"></iframe>
                                    <a href="https://maps.google.com/maps?q={{ $selectedAttendance->check_out_latitude }},{{ $selectedAttendance->check_out_longitude }}"
                                        target="_blank"
                                        class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-bold">Open
                                        Map</a>
                                </div>
                            @endif
                            <div class="aspect-video bg-black rounded-lg overflow-hidden">
                                @if ($selectedAttendance->selfie_out)
                                    <img src="{{ asset('storage/' . $selectedAttendance->selfie_out) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-white/50 text-xs">
                                        {{ $selectedAttendance->check_out ? 'No Selfie' : '...' }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </x-drawer>
</div>
