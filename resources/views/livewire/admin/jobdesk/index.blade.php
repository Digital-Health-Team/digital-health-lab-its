<div>
    {{-- HEADER --}}
    <x-header title="Master Jobdesk" subtitle="Super Admin Control Panel" separator progress-indicator>
        <x-slot:middle class="!justify-end gap-2">
            {{-- Global Search --}}
            <x-input icon="o-magnifying-glass" placeholder="Search..." wire:model.live.debounce.250ms="globalSearch" />

            {{-- Tombol Filter dengan Badge Indikator --}}
            <x-button label="Filters" icon="o-funnel" @click="$wire.filterModal = true" responsive
                badge="{{ collect([$filterProject, $filterAssignee, $filterStatus, $filterDateStart, $filterDateEnd])->filter()->count() ?:null }}"
                class="{{ collect([$filterProject, $filterAssignee, $filterStatus, $filterDateStart, $filterDateEnd])->filter()->count() > 0? 'btn-outline border-primary text-primary': 'btn-ghost' }}" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Create Task" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- MODAL ADVANCED FILTER --}}
    <x-modal wire:model="filterModal" title="Advanced Filters" separator>
        <div class="grid grid-cols-1 gap-5">

            {{-- Filter Project (Searchable) --}}
            <x-choices label="Filter by Project" wire:model.live="filterProject" :options="$projectsList" option-label="name"
                option-value="id" icon="o-briefcase" single searchable search-function="searchProject"
                placeholder="Search & select project..." />

            {{-- Filter Staff (Searchable) --}}
            <x-choices label="Filter by Staff" wire:model.live="filterAssignee" :options="$staffsList" option-label="name"
                option-value="id" icon="o-user" single searchable search-function="searchStaff"
                placeholder="Search & select staff..." />

            {{-- Filter Status --}}
            <x-select label="Filter by Status" wire:model.live="filterStatus" :options="[
                ['id' => 'pending', 'name' => 'Pending'],
                ['id' => 'on_progress', 'name' => 'On Progress'],
                ['id' => 'review', 'name' => 'Review'],
                ['id' => 'approved', 'name' => 'Approved'],
                ['id' => 'revision', 'name' => 'Revision'],
            ]" icon="o-tag"
                placeholder="All Status" />

            {{-- Filter Date Range --}}
            <div class="grid grid-cols-2 gap-3">
                <x-datetime label="From Deadline" wire:model.live="filterDateStart" type="date" icon="o-calendar" />
                <x-datetime label="To Deadline" wire:model.live="filterDateEnd" type="date" icon="o-calendar" />
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Clear Filters" icon="o-trash" wire:click="clearFilters" class="btn-outline" />
            <x-button label="Apply Filters" icon="o-check" @click="$wire.filterModal = false" class="btn-primary" />
        </x-slot:actions>
    </x-modal>

    {{-- TABLE CONTENT --}}
    <x-card class="bg-base-100 shadow-sm mt-4">
        @if ($jobdesks->count() == 0)
            <div class="flex flex-col items-center justify-center py-12 text-gray-500 dark:text-gray-400">
                <x-icon name="o-clipboard-document-list" class="w-16 h-16 mb-4 opacity-50" />
                <p>No tasks found.</p>
                <x-button label="Clear Filters" wire:click="clearFilters" class="btn-sm btn-ghost mt-2" />
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Task Info</th>
                            <th>Project</th>
                            <th>Assignee</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobdesks as $task)
                            <tr wire:key="row-{{ $task->id }}">
                                <th>{{ $loop->iteration + ($jobdesks->firstItem() - 1) }}</th>
                                <td>
                                    <div class="font-bold">{{ $task->title }}</div>
                                    <div class="text-xs opacity-60 w-64 truncate">
                                        {{ Str::limit($task->description, 50) }}</div>
                                    <div class="mt-1 flex items-center gap-1">
                                        <x-icon name="o-calendar" class="w-3 h-3 text-gray-400" />
                                        <span
                                            class="text-xs {{ $task->deadline_task && \Carbon\Carbon::parse($task->deadline_task)->isPast() && $task->status !== 'approved' ? 'text-error font-bold' : 'text-gray-500' }}">
                                            {{ $task->deadline_task ? \Carbon\Carbon::parse($task->deadline_task)->format('d M Y, H:i') : '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="badge badge-ghost text-xs">{{ $task->project->name ?? 'Deleted' }}</div>
                                </td>
                                <td>
                                    @if ($task->assignee)
                                        <div class="flex items-center gap-2">
                                            <x-avatar :image="$task->assignee->avatar" class="!w-7 !h-7" />
                                            <span class="text-xs">{{ Str::limit($task->assignee->name, 12) }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = match ($task->status) {
                                            'pending' => 'badge-ghost',
                                            'on_progress' => 'badge-info',
                                            'review' => 'badge-warning',
                                            'approved' => 'badge-success',
                                            'revision' => 'badge-error',
                                            default => 'badge-ghost',
                                        };
                                    @endphp
                                    <span
                                        class="badge {{ $statusClass }} text-white text-[10px] font-bold uppercase">{{ str_replace('_', ' ', $task->status) }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-1">
                                        {{-- Link ke Halaman Revisi --}}
                                        <x-button icon="o-chat-bubble-left-right"
                                            link="{{ route('admin.jobdesks.revision', $task->id) }}"
                                            class="btn-xs btn-square btn-ghost text-warning tooltip"
                                            data-tip="Open Revision Thread" />

                                        <x-button icon="o-pencil" wire:click="edit({{ $task->id }})"
                                            class="btn-xs btn-square btn-ghost text-blue-600" />
                                        <x-button icon="o-trash" wire:click="confirmDelete({{ $task->id }})"
                                            class="btn-xs btn-square btn-ghost text-red-500" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-base-200">{{ $jobdesks->links() }}</div>
        @endif
    </x-card>

    {{-- MODAL CREATE / EDIT (FORM) --}}
    <x-modal wire:model="modalOpen" :title="$editingJobdeskId ? 'Edit Task' : 'Create New Task'" separator>
        <x-form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-choices label="Project" wire:model="project_id" :options="$projectsList" option-label="name"
                    option-value="id" single searchable search-function="searchProject" />
                <x-choices label="Assign To" wire:model="assigned_to" :options="$staffsList" option-label="name"
                    option-value="id" single searchable search-function="searchStaff" />
            </div>
            <x-translatable-input label="Task Title" model="title" />
            <x-translatable-input label="Description" model="description" type="textarea" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-datetime label="Deadline" wire:model="deadline_task" type="datetime-local" icon="o-calendar" />
                <x-select label="Status" wire:model="status" :options="[
                    ['id' => 'pending', 'name' => 'Pending'],
                    ['id' => 'on_progress', 'name' => 'On Progress'],
                    ['id' => 'review', 'name' => 'Review'],
                    ['id' => 'approved', 'name' => 'Approved'],
                    ['id' => 'revision', 'name' => 'Revision'],
                ]" />
            </div>
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.modalOpen = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- MODAL CONFIRM DELETE --}}
    <x-modal-confirm wire:model="deleteModalOpen" method="delete" title="Delete Task?" text="Are you sure?"
        confirm-text="Delete" />
</div>
