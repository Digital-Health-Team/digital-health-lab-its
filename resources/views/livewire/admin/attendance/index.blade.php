<div>
    {{-- HEADER --}}
    <x-header title="Attendance Management" subtitle="Full Access Control" separator progress-indicator>
        <x-slot:middle class="!justify-end gap-2">
            <x-input icon="o-magnifying-glass" placeholder="Search staff..." wire:model.live.debounce.250ms="search" />
            <x-datetime wire:model.live="filterDate" type="date" icon="o-calendar" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Manual Entry" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- LIST TABLE --}}
    <x-card class="bg-base-100 shadow-sm">
        <x-table :headers="[
            ['key' => 'user.name', 'label' => 'Staff'],
            ['key' => 'check_in', 'label' => 'Time'],
            ['key' => 'reports', 'label' => 'Reports'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'actions', 'label' => '', 'sortable' => false],
        ]" :rows="$attendances" with-pagination>

            @scope('cell_user.name', $att)
                <div class="flex items-center gap-2">
                    <x-avatar :image="$att->user->profile_photo ? asset('storage/' . $att->user->profile_photo) : null" class="!w-8 !h-8" />
                    <span class="font-bold">{{ $att->user->name }}</span>
                </div>
            @endscope

            @scope('cell_check_in', $att)
                <div class="flex flex-col text-xs">
                    <span class="font-bold">{{ $att->check_in->format('d M Y') }}</span>
                    <span class="text-gray-500">{{ $att->check_in->format('H:i') }} -
                        {{ $att->check_out ? $att->check_out->format('H:i') : '...' }}</span>
                </div>
            @endscope

            @scope('cell_reports', $att)
                <div class="flex flex-wrap gap-1">
                    @forelse($att->reports->take(2) as $rep)
                        <span
                            class="badge badge-ghost badge-sm text-[10px]">{{ Str::limit($rep->jobdesk->title ?? 'Deleted Task', 15) }}</span>
                    @empty
                        <span class="text-xs text-error italic">No reports</span>
                    @endforelse
                    @if ($att->reports->count() > 2)
                        <span class="badge badge-ghost badge-sm text-[10px]">+{{ $att->reports->count() - 2 }}</span>
                    @endif
                </div>
            @endscope

            @scope('cell_status', $att)
                @if ($att->check_out)
                    <span class="badge badge-success badge-sm">Done</span>
                @else
                    <span class="badge badge-warning badge-sm">Active</span>
                @endif
            @endscope

            @scope('cell_actions', $att)
                <div class="flex justify-end gap-1">
                    <x-button icon="o-eye" wire:click="view({{ $att->id }})" class="btn-xs btn-square btn-ghost" />
                    <x-button icon="o-pencil" wire:click="edit({{ $att->id }})"
                        class="btn-xs btn-square btn-ghost text-blue-600" />
                    <x-button icon="o-trash" wire:click="confirmDelete({{ $att->id }})"
                        class="btn-xs btn-square btn-ghost text-error" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- ==================================================== --}}
    {{-- 1. DETAIL DRAWER (READ ONLY)                         --}}
    {{-- ==================================================== --}}
    <x-drawer wire:model="detailDrawerOpen" title="Attendance Detail" right separator with-close-button
        class="lg:w-1/2">
        @if ($selectedAttendance)
            <div class="space-y-6">
                {{-- User Info --}}
                <div
                    class="flex items-center gap-4 bg-base-200 dark:bg-base-800 p-4 rounded-xl border border-base-300 dark:border-base-700">
                    <x-avatar :image="$selectedAttendance->user->profile_photo ? asset('storage/' . $selectedAttendance->user->profile_photo) : null" class="w-14 h-14" />
                    <div>
                        <div class="font-bold text-lg text-base-content">{{ $selectedAttendance->user->name }}</div>
                        <div class="text-sm opacity-60">{{ $selectedAttendance->user->email }}</div>
                    </div>
                </div>

                {{-- Time Info --}}
                <div class="grid grid-cols-2 gap-4">
                    <div
                        class="stat bg-base-100 dark:bg-base-900 border border-base-200 dark:border-base-700 rounded-xl p-3">
                        <div class="stat-title text-xs">Date</div>
                        <div class="stat-value text-lg text-base-content">
                            {{ $selectedAttendance->check_in->format('d M Y') }}</div>
                    </div>
                    <div
                        class="stat bg-base-100 dark:bg-base-900 border border-base-200 dark:border-base-700 rounded-xl p-3">
                        <div class="stat-title text-xs">Duration</div>
                        <div class="stat-value text-lg text-base-content">
                            {{ $selectedAttendance->check_out ? $selectedAttendance->check_in->diff($selectedAttendance->check_out)->format('%Hh %Im') : 'Ongoing' }}
                        </div>
                    </div>
                    <div
                        class="stat bg-base-100 dark:bg-base-900 border border-base-200 dark:border-base-700 rounded-xl p-3">
                        <div class="stat-title text-xs">Check In</div>
                        <div class="stat-value text-lg text-success">{{ $selectedAttendance->check_in->format('H:i') }}
                        </div>
                    </div>
                    <div
                        class="stat bg-base-100 dark:bg-base-900 border border-base-200 dark:border-base-700 rounded-xl p-3">
                        <div class="stat-title text-xs">Check Out</div>
                        <div class="stat-value text-lg text-error">
                            {{ $selectedAttendance->check_out ? $selectedAttendance->check_out->format('H:i') : '-' }}
                        </div>
                    </div>
                </div>

                <div class="divider text-xs font-bold opacity-50">WORK REPORTS</div>

                {{-- Reports List --}}
                <div class="space-y-4">
                    @forelse($selectedAttendance->reports as $report)
                        <div
                            class="card bg-base-100 dark:bg-base-900 border border-base-200 dark:border-base-700 p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <div class="font-bold text-sm text-base-content">
                                    {{ $report->jobdesk->title ?? 'Deleted Task' }}</div>
                                <div
                                    class="badge {{ $report->status_at_report == 'completed' ? 'badge-success' : 'badge-warning' }} badge-xs">
                                    {{ $report->status_at_report }}
                                </div>
                            </div>

                            @if ($report->revision_thread_id)
                                <div class="badge badge-error badge-xs text-white mb-2">Fixed Revision</div>
                            @endif

                            <p
                                class="text-sm text-gray-600 dark:text-gray-300 mb-3 bg-base-200/50 dark:bg-base-800/50 p-2 rounded">
                                {{ $report->details->first()->content ?? '-' }}
                            </p>

                            @if ($report->attachments->count() > 0)
                                <div class="flex gap-2 flex-wrap">
                                    @foreach ($report->attachments as $att)
                                        <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank"
                                            class="group relative w-12 h-12 rounded-lg border border-base-300 dark:border-base-600 overflow-hidden hover:ring-2 hover:ring-primary">
                                            @if (Str::startsWith($att->file_type, 'image'))
                                                <img src="{{ asset('storage/' . $att->file_path) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div
                                                    class="flex items-center justify-center h-full bg-base-200 dark:bg-base-700 text-[10px] font-bold">
                                                    FILE</div>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center opacity-50 text-sm italic">No reports submitted.</div>
                    @endforelse
                </div>
            </div>
        @endif
    </x-drawer>

    {{-- ==================================================== --}}
    {{-- 2. FORM DRAWER (CREATE / EDIT)                       --}}
    {{-- ==================================================== --}}
    <x-drawer wire:model="drawerOpen" :title="$editingId ? 'Edit Attendance Record' : 'Manual Entry'" right separator with-close-button class="lg:w-1/2">
        <x-form wire:submit="save">
            <div class="space-y-6 overflow-y-auto pr-2 pb-20">

                {{-- Section 1: Staff & Time --}}
                <div class="card bg-base-200 dark:bg-gray-800 p-4 border border-base-300 dark:border-gray-700">
                    <h3 class="font-bold text-sm mb-3 text-gray-500 dark:text-gray-400 uppercase">1. Attendance Detail
                    </h3>
                    <div class="grid gap-4">
                        <x-choices label="Staff" wire:model.live="targetUserId" :options="$usersList" option-label="name"
                            option-value="id" single searchable search-function="searchUser" icon="o-user"
                            class="text-base-content dark:text-gray-200" />
                        <x-datetime label="Date" wire:model="checkInDate" type="date" icon="o-calendar" />
                        <div class="grid grid-cols-2 gap-4">
                            <x-datetime label="In" wire:model="checkInTime" type="time" icon="o-clock" />
                            <x-datetime label="Out" wire:model="checkOutTime" type="time" icon="o-clock" />
                        </div>
                    </div>
                </div>

                {{-- Section 2: Reports Repeater --}}
                @if ($targetUserId)
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-sm text-gray-500 dark:text-gray-400 uppercase">2. Tasks & Reports
                            </h3>
                            <x-button label="Add Item" icon="o-plus" size="sm" wire:click="addReportItem"
                                class="btn-ghost btn-xs" />
                        </div>

                        @foreach ($reports as $index => $report)
                            {{-- Card Repeater: Fix Dark Mode --}}
                            <div class="card bg-base-100 dark:bg-gray-800 border border-base-300 dark:border-gray-700 p-4 relative shadow-sm"
                                wire:key="rep-{{ $index }}">

                                @if (count($reports) > 1)
                                    <button type="button" wire:click="removeReportItem({{ $index }})"
                                        class="absolute top-2 right-2 btn btn-circle btn-xs btn-ghost text-error">
                                        <x-icon name="o-trash" />
                                    </button>
                                @endif

                                <div class="grid gap-3">
                                    {{-- Jobdesk Select --}}
                                    <x-select label="Task" wire:model.live="reports.{{ $index }}.jobdesk_id"
                                        :options="$staffJobdesks" placeholder="Select task..."
                                        class="dark:bg-gray-900 dark:border-gray-600" />

                                    {{-- Revision Select --}}
                                    @if (!empty($reports[$index]['available_revisions']))
                                        <div class="bg-warning/10 p-2 rounded border border-warning/20">
                                            <x-select label="Linked Revision"
                                                wire:model="reports.{{ $index }}.revision_thread_id"
                                                :options="$reports[$index]['available_revisions']" placeholder="None"
                                                class="select-sm dark:bg-gray-900" />
                                        </div>
                                    @endif

                                    <x-textarea label="Content" wire:model="reports.{{ $index }}.content"
                                        rows="2"
                                        class="dark:bg-gray-900 dark:text-gray-200 dark:border-gray-600" />

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <x-select label="Status"
                                            wire:model="reports.{{ $index }}.status_at_report"
                                            :options="[
                                                ['id' => 'on_progress', 'name' => 'In Progress'],
                                                ['id' => 'completed', 'name' => 'Completed'],
                                            ]" class="dark:bg-gray-900" />

                                        {{-- File Upload & Existing Preview --}}
                                        <div>
                                            <x-file label="Add New Files"
                                                wire:model="reports.{{ $index }}.new_files" multiple
                                                accept="image/*,application/pdf" />

                                            {{-- [FIX] Preview File Lama --}}
                                            @if (!empty($reports[$index]['existing_files']))
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach ($reports[$index]['existing_files'] as $file)
                                                        <a href="{{ asset('storage/' . $file['file_path']) }}"
                                                            target="_blank"
                                                            class="relative group w-10 h-10 rounded border border-base-300 dark:border-gray-600 overflow-hidden hover:ring-2 hover:ring-primary"
                                                            title="{{ $file['file_name'] }}">
                                                            @if (Str::startsWith($file['file_type'], 'image'))
                                                                <img src="{{ asset('storage/' . $file['file_path']) }}"
                                                                    class="object-cover w-full h-full">
                                                            @else
                                                                <div
                                                                    class="flex items-center justify-center h-full bg-base-200 text-[9px]">
                                                                    FILE</div>
                                                            @endif
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.drawerOpen = false" />
                <x-button label="Save Changes" class="btn-primary" type="submit" spinner="save" icon="o-check" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- MODAL DELETE --}}
    <x-modal-confirm wire:model="deleteModalOpen" method="delete" title="Delete Record?"
        text="Are you sure you want to delete this attendance?" confirm-text="Delete" />
</div>
