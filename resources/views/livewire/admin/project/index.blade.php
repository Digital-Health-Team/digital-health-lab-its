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

    {{-- CARD TABEL --}}
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
                        @php
                            // Handle Name
                            $pName = $project->name;
                            if (is_array($pName)) {
                                $pName = $pName[app()->getLocale()] ?? ($pName['en'] ?? ($pName['id'] ?? '-'));
                            }

                            // Handle Description
                            $pDesc = $project->description;
                            if (is_array($pDesc)) {
                                $pDesc = $pDesc[app()->getLocale()] ?? ($pDesc['en'] ?? ($pDesc['id'] ?? '-'));
                            }
                        @endphp

                        <tr wire:key="{{ $project->id }}">
                            <th>{{ $loop->iteration + ($projects->firstItem() - 1) }}</th>
                            <td>
                                <div class="font-bold">{{ $pName }}</div>
                                <div class="text-xs text-gray-400">Created by: {{ $project->creator->name ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <span class="text-gray-500 text-sm">{{ Str::limit($pDesc, 50) }}</span>
                            </td>
                            <td>
                                <div class="flex flex-col">
                                    <span
                                        class="font-bold text-xs">{{ $project->deadline_global ? $project->deadline_global->format('d M Y, H:i') : '-' }}</span>
                                    @if ($project->deadline_global)
                                        <span
                                            class="text-[10px] {{ $project->deadline_global->isPast() ? 'text-error' : 'text-success' }}">
                                            {{ $project->deadline_global->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div
                                    class="badge {{ match ($project->status) {'active' => 'badge-info','on_hold' => 'badge-warning','completed' => 'badge-success',default => 'badge-ghost'} }} text-white text-xs uppercase font-bold">
                                    {{ str_replace('_', ' ', ucfirst($project->status)) }}
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <x-button icon="o-eye" link="{{ route('admin.projects.show', $project->id) }}" spinner
                                        class="btn-sm btn-square btn-ghost text-blue-500" />
                                    <x-button icon="o-pencil-square" wire:click="edit({{ $project->id }})" spinner
                                        class="btn-sm btn-square btn-ghost text-blue-500" />
                                    <x-button icon="o-trash" wire:click="confirmDelete({{ $project->id }})"
                                        class="btn-sm btn-square btn-ghost text-red-500" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500">{{ __('No active projects.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $projects->links() }}</div>
    </x-card>

    {{-- MODAL CREATE / EDIT --}}
    <x-modal wire:model="modalOpen" :title="$editingProjectId ? __('Edit Project') : __('New Project')" separator>
        <x-form wire:submit="save">
            {{-- Input Multi Bahasa --}}
            {{-- Pastikan component x-translatable-input mendukung model array --}}
            <x-translatable-input label="{{ __('Project Name') }}" model="name" />
            <x-translatable-input label="{{ __('Description') }}" model="description" type="textarea" />

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

    {{-- MODAL DELETE --}}
    <x-modal-confirm wire:model="deleteModalOpen" method="delete" title="{{ __('Delete Project?') }}"
        text="{{ __('Are you sure? This action cannot be undone.') }}" confirm-text="{{ __('Yes, Delete') }}"
        cancel-text="{{ __('Cancel') }}" />
</div>
