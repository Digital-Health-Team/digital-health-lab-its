<div class="space-y-8">
    {{-- HEADER --}}
    <x-header title="Admin Dashboard" separator progress-indicator>
        <x-slot:middle class="!justify-start">
            <div class="text-gray-500">
                Hello, <span class="font-bold text-base-content">{{ auth()->user()->name }}</span>.
                Here's what's happening today.
            </div>
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Add Staff" icon="o-user-plus" link="#" class="btn-outline btn-sm" />
            <x-button label="New Project" icon="o-plus" link="#" class="btn-primary btn-sm" />
        </x-slot:actions>
    </x-header>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat title="Total Staff" value="{{ $stats['total_staff'] }}" icon="o-users"
            class="bg-base-100 shadow-sm border-l-4 border-primary" color="text-primary" />

        <x-stat title="Active Projects" value="{{ $stats['active_projects'] }}" icon="o-briefcase"
            class="bg-base-100 shadow-sm border-l-4 border-info" color="text-info" />

        <x-stat title="Attendance Today" value="{{ $stats['attendance_today'] }}" icon="o-finger-print"
            class="bg-base-100 shadow-sm border-l-4 border-success" color="text-success" />

        <x-stat title="Pending Revisions" value="{{ $stats['pending_revisions'] }}" icon="o-exclamation-triangle"
            class="bg-base-100 shadow-sm border-l-4 border-warning" color="text-warning" />
    </div>

    {{-- CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT COLUMN: URGENT DEADLINES & ANNOUNCEMENTS --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Urgent Tasks --}}
            <x-card title="Urgent Task Deadlines" subtitle="Tasks due within 7 days" separator shadow>
                <div class="space-y-4">
                    @forelse($urgentTasks as $task)
                        {{-- FIX: Handle Array Name --}}
                        @php
                            // Handle Task Name
                            $tName = $task->name;
                            if (is_array($tName)) {
                                $tName = $tName['id'] ?? ($tName['en'] ?? json_encode($tName));
                            }
                            // Handle Title (jika pakai kolom title)
                            if (!$tName && $task->title) {
                                $tName = is_array($task->title)
                                    ? $task->title['id'] ?? $task->title['en']
                                    : $task->title;
                            }

                            // Handle Project Name
                            $pName = $task->project->name;
                            if (is_array($pName)) {
                                $pName = $pName['id'] ?? ($pName['en'] ?? 'Unknown Project');
                            }
                        @endphp

                        <div
                            class="flex items-center justify-between p-3 rounded-lg bg-base-200/50 hover:bg-base-200 transition border border-base-300">
                            <div class="flex flex-col">
                                <span class="font-bold text-sm">{{ Str::limit($tName, 40) }}</span>
                                <span class="text-xs opacity-60">{{ $pName }}</span>
                            </div>
                            <div class="text-right">
                                <div
                                    class="text-xs font-mono {{ \Carbon\Carbon::parse($task->deadline_task)->isPast() ? 'text-error font-bold' : '' }}">
                                    {{ \Carbon\Carbon::parse($task->deadline_task)->format('d M, H:i') }}
                                </div>
                                <div class="badge badge-ghost badge-xs">{{ $task->assignee->name ?? 'Unassigned' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 opacity-50 italic">No urgent tasks found.</div>
                    @endforelse
                </div>
                <x-slot:actions>
                    <x-button label="View All Tasks" link="{{ route('admin.jobdesks') }}" class="btn-ghost btn-sm" />
                </x-slot:actions>
            </x-card>

            {{-- Recent Announcements --}}
            <x-card title="Latest Announcements" separator shadow>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($recentAnnouncements as $ann)
                        <div class="p-4 rounded-xl border border-base-200 dark:border-gray-700 bg-base-100">
                            <div class="flex justify-between items-start mb-2">
                                <span
                                    class="badge badge-outline badge-xs">{{ $ann->created_at->diffForHumans() }}</span>
                                @if ($ann->is_global)
                                    <x-icon name="o-globe-alt" class="w-3 h-3 opacity-50" />
                                @endif
                            </div>
                            <h4 class="font-bold text-sm mb-1">{{ $ann->title }}</h4>
                            <p class="text-xs opacity-70 line-clamp-2">{{ $ann->content }}</p>
                        </div>
                    @endforeach
                </div>
                <x-slot:actions>
                    <x-button label="Manage" link="{{ route('admin.announcements') }}" class="btn-ghost btn-sm" />
                </x-slot:actions>
            </x-card>
        </div>

        {{-- RIGHT COLUMN: RECENT USERS & QUICK MENU --}}
        <div class="space-y-8">

            {{-- Recent Users --}}
            <x-card title="New Registered Staff" separator shadow>
                <div class="space-y-4">
                    @foreach ($recentUsers as $user)
                        <div class="flex items-center gap-3">
                            <x-avatar :image="$user->profile_photo ? asset('storage/' . $user->profile_photo) : null" class="!w-9 !h-9" />
                            <div class="flex flex-col">
                                <span class="text-sm font-bold">{{ $user->name }}</span>
                                <span
                                    class="text-[10px] opacity-50 uppercase tracking-widest">{{ $user->role }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>

            {{-- Quick Menu --}}
            <x-card title="Quick Navigation" class="bg-primary/5 border border-primary/10">
                <div class="grid grid-cols-1 gap-2">
                    <x-button label="Check In/Out Management" icon="o-finger-print"
                        link="{{ route('admin.attendance') }}" class="btn-sm justify-start bg-base-100 shadow-sm" />

                    <x-button label="Project List" icon="o-briefcase" link="#"
                        class="btn-sm justify-start bg-base-100 shadow-sm" />

                    <x-button label="System Logs" icon="o-command-line"
                        class="btn-sm justify-start bg-base-100 shadow-sm" />
                </div>
            </x-card>

            {{-- Today's Status Summary --}}
            <div class="alert alert-info shadow-sm text-xs">
                <x-icon name="o-information-circle" class="w-4 h-4" />
                <span>System is running stable. <b>{{ $stats['attendance_today'] }}</b> staff members are currently on
                    duty.</span>
            </div>
        </div>
    </div>
</div>
