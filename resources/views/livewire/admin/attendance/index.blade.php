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
                        @php
                            $taskTitle = $rep->jobdesk->title ?? 'Deleted Task';
                            if (is_array($taskTitle)) {
                                $taskTitle = $taskTitle['id'] ?? ($taskTitle['en'] ?? '-');
                            }
                        @endphp
                        <span class="badge badge-ghost badge-sm text-[10px]">
                            {{ Str::limit($taskTitle, 15) }}
                        </span>
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
                    {{-- [UPDATE] Button ini sekarang nge-link ke route Show --}}
                    <x-button icon="o-eye" link="{{ route('admin.attendances.show', $att->id) }}" class="btn-xs btn-square btn-ghost" />

                    <x-button icon="o-pencil" wire:click="edit({{ $att->id }})"
                        class="btn-xs btn-square btn-ghost text-blue-600" />
                    <x-button icon="o-trash" wire:click="confirmDelete({{ $att->id }})"
                        class="btn-xs btn-square btn-ghost text-error" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- ==================================================== --}}
    {{-- FORM DRAWER (CREATE / EDIT)                          --}}
    {{-- ==================================================== --}}
    <x-drawer wire:model="drawerOpen" :title="$editingId ? 'Edit Attendance Record' : 'Manual Entry'" right separator with-close-button class="lg:w-1/2">
        <x-form wire:submit="save">
            <div class="space-y-6 overflow-y-auto pr-2 pb-20">

                {{-- Section 1: Staff & Time --}}
                <div class="card bg-base-200 dark:bg-gray-800 p-4 border border-base-300 dark:border-gray-700">
                    <h3 class="font-bold text-sm mb-3 text-gray-500 dark:text-gray-400 uppercase">1. Attendance Detail
                    </h3>
                    <div class="grid gap-4">
                        <x-choices label="Staff" wire:model.live="targetUserId" :options="$usersList"
                            option-label="name" option-value="id" single searchable search-function="searchUser"
                            icon="o-user" class="text-base-content dark:text-gray-200" />
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
                            <div class="card bg-base-100 dark:bg-gray-800 border border-base-300 dark:border-gray-700 p-4 relative shadow-sm"
                                wire:key="rep-{{ $index }}">
                                @if (count($reports) > 1)
                                    <button type="button" wire:click="removeReportItem({{ $index }})"
                                        class="absolute top-2 right-2 btn btn-circle btn-xs btn-ghost text-error">
                                        <x-icon name="o-trash" />
                                    </button>
                                @endif

                                <div class="grid gap-3">
                                    <x-select label="Task" wire:model.live="reports.{{ $index }}.jobdesk_id"
                                        :options="$staffJobdesks" placeholder="Select task..."
                                        class="dark:bg-gray-900 dark:border-gray-600" />

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

                                        <div>
                                            <x-file label="Add New Files"
                                                wire:model="reports.{{ $index }}.new_files" multiple
                                                accept="image/*,application/pdf" />
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
