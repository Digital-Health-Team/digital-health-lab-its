<div class="space-y-8 pb-20">

    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-black mb-2">Global Search</h1>
        <div class="flex items-center gap-2 text-gray-500">
            <x-icon name="o-magnifying-glass" class="w-5 h-5" />
            <span>Results for: <span class="font-bold text-base-content">"{{ $search }}"</span></span>
        </div>
    </div>

    {{-- Main Search Input (In-Page) --}}
    <div class="bg-base-100 p-4 rounded-xl border border-base-200 shadow-sm">
        <x-input icon="o-magnifying-glass" placeholder="Type to search users, projects, tasks, logs..."
            wire:model.live.debounce.300ms="search" class="w-full" autofocus />
    </div>

    @if ($search == '')
        <div class="text-center py-20 opacity-50">
            <x-icon name="o-magnifying-glass" class="w-16 h-16 mx-auto mb-4 opacity-20" />
            <p>Start typing to search across the system.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- ========================================================= --}}
            {{-- 1. USERS SECTION                                          --}}
            {{-- ========================================================= --}}
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="font-bold flex items-center gap-2">
                        <x-icon name="o-users" class="w-5 h-5 text-primary" /> Users ({{ $users->count() }})
                    </h2>
                    {{-- Filter Role --}}
                    <select wire:model.live="filterUserRole" class="select select-xs select-bordered">
                        <option value="">All Roles</option>
                        <option value="staff">Staff</option>
                        <option value="pm">PM</option>
                        <option value="super_admin">Admin</option>
                    </select>
                </div>

                @forelse($users as $user)
                    <div
                        class="flex items-center justify-between p-3 bg-base-100 rounded-lg border border-base-200 hover:border-primary transition group">
                        <div class="flex items-center gap-3">
                            <x-avatar :image="$user->profile_photo ? asset('storage/' . $user->profile_photo) : null" class="w-10 h-10" />
                            <div>
                                <div class="font-bold text-sm">{{ $user->name }}</div>
                                <div class="text-xs opacity-60 uppercase">{{ $user->role }}</div>
                            </div>
                        </div>
                        {{-- BUTTON DETAIL --}}
                        <x-button icon="o-eye" class="btn-xs btn-square btn-ghost text-primary"
                            link="{{ route('admin.staff.show', $user->id) }}" tooltip="View Profile" />
                    </div>
                @empty
                    <div class="text-xs opacity-50 italic py-2">No users found.</div>
                @endforelse
            </div>

            {{-- ========================================================= --}}
            {{-- 2. PROJECTS SECTION                                       --}}
            {{-- ========================================================= --}}
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="font-bold flex items-center gap-2">
                        <x-icon name="o-briefcase" class="w-5 h-5 text-secondary" /> Projects ({{ $projects->count() }})
                    </h2>
                    {{-- Filter Status --}}
                    <select wire:model.live="filterProjectStatus" class="select select-xs select-bordered">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="on_hold">On Hold</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                @forelse($projects as $proj)
                    @php $pName = is_array($proj->name) ? $proj->name['id'] ?? '-' : $proj->name; @endphp
                    <div
                        class="flex justify-between items-center p-3 bg-base-100 rounded-lg border border-base-200 hover:border-secondary transition group">
                        <div>
                            <div class="font-bold text-sm">{{ $pName }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <span
                                    class="badge badge-xs uppercase {{ match ($proj->status) {'active' => 'badge-info','completed' => 'badge-success',default => 'badge-ghost'} }}">
                                    {{ str_replace('_', ' ', $proj->status) }}
                                </span>
                                <span class="text-[10px] opacity-60">Due:
                                    {{ $proj->deadline_global ? $proj->deadline_global->format('d M Y') : '-' }}</span>
                            </div>
                        </div>
                        {{-- BUTTON DETAIL --}}
                        <x-button icon="o-eye" class="btn-xs btn-square btn-ghost text-secondary"
                            link="{{ route('admin.projects.show', $proj->id) }}" tooltip="View Project" />
                    </div>
                @empty
                    <div class="text-xs opacity-50 italic py-2">No projects found.</div>
                @endforelse
            </div>

            {{-- ========================================================= --}}
            {{-- 3. JOBDESKS SECTION                                       --}}
            {{-- ========================================================= --}}
            <div class="space-y-4 lg:col-span-2">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="font-bold flex items-center gap-2">
                        <x-icon name="o-clipboard-document-list" class="w-5 h-5 text-warning" /> Jobdesks
                        ({{ $tasks->count() }})
                    </h2>
                    {{-- Filter Status --}}
                    <select wire:model.live="filterTaskStatus" class="select select-xs select-bordered">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="review">Review</option>
                        <option value="revision">Revision</option>
                        <option value="approved">Approved</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @forelse($tasks as $task)
                        @php $tTitle = is_array($task->title) ? $task->title['id'] ?? '-' : $task->title; @endphp
                        <div
                            class="flex justify-between items-start p-3 bg-base-100 rounded-lg border border-base-200 hover:border-warning transition group">
                            <div class="flex-1 overflow-hidden pr-2">
                                <div class="font-bold text-sm truncate">{{ $tTitle }}</div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="badge badge-xs font-bold text-white {{ match ($task->status) {'approved' => 'badge-success','revision' => 'badge-error','review' => 'badge-warning',default => 'badge-ghost'} }}">
                                        {{ str_replace('_', ' ', $task->status) }}
                                    </span>
                                    <div class="flex items-center gap-1 text-[10px] opacity-60">
                                        <x-avatar :image="$task->assignee->profile_photo
                                            ? asset('storage/' . $task->assignee->profile_photo)
                                            : null" class="!w-4 !h-4" />
                                        {{ $task->assignee->name }}
                                    </div>
                                </div>
                            </div>
                            {{-- BUTTON DETAIL --}}
                            <x-button icon="o-eye" class="btn-xs btn-square btn-ghost text-warning"
                                link="{{ route('admin.jobdesks.revision', $task->id) }}" tooltip="View Revision" />
                        </div>
                    @empty
                        <div class="col-span-2 text-xs opacity-50 italic py-2">No tasks found.</div>
                    @endforelse
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- 4. ATTENDANCE SECTION                                     --}}
            {{-- ========================================================= --}}
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="font-bold flex items-center gap-2">
                        <x-icon name="o-finger-print" class="w-5 h-5 text-success" /> Attendance
                        ({{ $attendances->count() }})
                    </h2>
                    <input type="date" wire:model.live="filterAttDate" class="input input-xs input-bordered" />
                </div>

                @forelse($attendances as $att)
                    <div
                        class="flex justify-between items-center p-3 bg-base-100 rounded-lg border border-base-200 hover:border-success transition group">
                        <div class="flex items-center gap-3">
                            <x-avatar :image="$att->user->profile_photo ? asset('storage/' . $att->user->profile_photo) : null" class="!w-8 !h-8" />
                            <div>
                                <div class="font-bold text-xs">{{ $att->user->name }}</div>
                                <div class="text-[10px] opacity-60">{{ $att->check_in->format('d M Y') }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-mono">
                                <span class="text-success">{{ $att->check_in->format('H:i') }}</span> -
                                <span
                                    class="text-error">{{ $att->check_out ? $att->check_out->format('H:i') : '...' }}</span>
                            </div>
                            {{-- BUTTON DETAIL (Redirect ke Staff Detail) --}}
                            <x-button icon="o-eye" class="btn-xs btn-square btn-ghost text-success"
                                link="{{ route('admin.staff.show', $att->user_id) }}" tooltip="View History" />
                        </div>
                    </div>
                @empty
                    <div class="text-xs opacity-50 italic py-2">No attendance records found.</div>
                @endforelse
            </div>

            {{-- ========================================================= --}}
            {{-- 5. ANNOUNCEMENTS SECTION                                  --}}
            {{-- ========================================================= --}}
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="font-bold flex items-center gap-2">
                        <x-icon name="o-bell" class="w-5 h-5 text-error" /> Announcements
                        ({{ $announcements->count() }})
                    </h2>
                </div>
                @forelse($announcements as $ann)
                    <div
                        class="flex justify-between items-start p-3 bg-base-100 rounded-lg border border-base-200 hover:border-error transition group">
                        <div class="flex-1 pr-2">
                            <div class="font-bold text-sm">{{ $ann->title }}</div>
                            <div class="text-xs opacity-60 line-clamp-1">{{ Str::limit($ann->content, 50) }}</div>
                        </div>
                        {{-- BUTTON DETAIL (Redirect ke Index Announcement) --}}
                        <x-button icon="o-eye" class="btn-xs btn-square btn-ghost text-error"
                            link="{{ route('admin.announcements') }}" tooltip="Manage" />
                    </div>
                @empty
                    <div class="text-xs opacity-50 italic py-2">No announcements found.</div>
                @endforelse
            </div>

        </div>
    @endif
</div>
