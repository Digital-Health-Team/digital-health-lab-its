<div>
    {{-- HEADER --}}
    <x-header title="{{ __('Project Management') }}" subtitle="{{ __('Active Projects') }}" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" placeholder="{{ __('Search') }}..." wire:model.live.debounce="search" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="{{ __('New Project') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- CARD TABEL MANUAL --}}
    <x-card class="bg-base-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Project Name') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Deadline') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr wire:key="{{ $project->id }}">
                            <th>{{ $loop->iteration + ($projects->firstItem() - 1) }}</th>

                            {{-- Nama Project (Otomatis Multi Bahasa) --}}
                            <td>
                                <div class="font-bold">{{ $project->name }}</div>
                                <div class="text-xs text-gray-400">Created by: {{ $project->creator->name ?? '-' }}
                                </div>
                            </td>

                            {{-- Deskripsi (Truncate) --}}
                            <td>
                                <span class="text-gray-500 text-sm">
                                    {{ Str::limit($project->description, 50) }}
                                </span>
                            </td>

                            {{-- Deadline & Sisa Waktu --}}
                            <td>
                                <div class="flex flex-col">
                                    <span class="font-bold text-xs">
                                        {{ $project->deadline_global->format('d M Y, H:i') }}
                                    </span>
                                    <span
                                        class="text-[10px] {{ $project->deadline_global->isPast() ? 'text-error' : 'text-success' }}">
                                        {{ $project->deadline_global->diffForHumans() }}
                                    </span>
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td>
                                @php
                                    $badge = match ($project->status) {
                                        'active' => 'badge-info',
                                        'on_hold' => 'badge-warning',
                                        'completed' => 'badge-success',
                                        default => 'badge-ghost',
                                    };
                                    // Mengubah format "on_hold" jadi "On Hold"
                                    $label = str_replace('_', ' ', ucfirst($project->status));
                                @endphp
                                <div class="badge {{ $badge }} text-white text-xs uppercase font-bold">
                                    {{ $label }}
                                </div>
                            </td>
                            {{-- ACTIONS COLUMN --}}
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    {{-- Tombol Edit --}}
                                    <x-button icon="o-pencil-square" wire:click="edit({{ $project->id }})" spinner
                                        class="btn-sm btn-square btn-ghost text-blue-500" />

                                    {{-- Tombol Delete (UPDATED) --}}
                                    {{-- Tidak pakai wire:confirm bawaan lagi --}}
                                    {{-- Panggil confirmDelete() dengan ID Project --}}
                                    <x-button icon="o-trash" wire:click="confirmDelete({{ $project->id }})"
                                        class="btn-sm btn-square btn-ghost text-red-500" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500">
                                {{ __('No active tasks.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </x-card>

    <x-modal wire:model="modalOpen" :title="$editingProjectId ? __('Edit Project') : __('New Project')" separator>
        <x-form wire:submit="save">

            {{-- INPUT MULTI BAHASA --}}
            {{-- Otomatis membuat tab ID dan EN --}}
            <x-translatable-input label="{{ __('Project Name') }}" model="name" />

            <x-translatable-input label="{{ __('Description') }}" model="description" type="textarea" />

            {{-- Input Biasa --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-datetime label="{{ __('Deadline') }}" wire:model="deadline_global" icon="o-calendar"
                    type="datetime-local" />

                <x-select label="{{ __('Status') }}" wire:model="status" :options="[
                    ['id' => 'active', 'name' => 'Active'],
                    ['id' => 'on_hold', 'name' => 'On Hold'],
                    ['id' => 'completed', 'name' => 'Completed'],
                ]" />
            </div>

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.modalOpen = false" />
                <x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- ================================================= --}}
    {{-- MODAL DELETE COMPONENT (Reusable) --}}
    {{-- ================================================= --}}
    <x-modal-confirm wire:model="deleteModalOpen" method="delete" title="{{ __('Delete Project?') }}"
        text="{{ __('Are you sure you want to delete this project? This action cannot be undone.') }}"
        confirm-text="{{ __('Yes, Delete') }}" cancel-text="{{ __('Cancel') }}" />
</div>
