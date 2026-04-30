<div>
    <x-header title="{{ __('Events & Exhibitions') }}" subtitle="{{ __('Manage innovation events') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Create Event') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-input placeholder="{{ __('Search event...') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" />
        <x-select wire:model.live="filterStatus" :options="[['id'=>'active','name'=>__('Active')], ['id'=>'inactive','name'=>__('Archived')]]" placeholder="{{ __('All Status') }}" />
    </div>

    <x-card class="p-0 overflow-hidden shadow-sm border border-base-200">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-100">
                    <th class="w-12 text-center">#</th>
                    <th>{{ __('Event Info') }}</th>
                    <th class="text-center">{{ __('Stats') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td class="text-center text-gray-400">{{ $loop->iteration }}</td>
                        <td>
                            <div class="font-bold text-base-content text-base">{{ $event->name }} ({{ $event->year }})</div>
                            <div class="text-xs text-gray-500 italic">"{{ $event->theme_title }}"</div>
                        </td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-3 text-xs font-semibold">
                                <span class="text-blue-500"><x-icon name="o-user-group" class="w-4 h-4 inline" /> {{ $event->teams_count }} {{ __('Teams') }}</span>
                                <span class="text-purple-500"><x-icon name="o-academic-cap" class="w-4 h-4 inline" /> {{ $event->projects_count }} {{ __('Projects') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="badge {{ $event->is_active ? 'badge-success' : 'badge-neutral' }} text-white text-xs">
                                {{ $event->is_active ? __('Active') : __('Archived') }}
                            </div>
                        </td>
                        <td class="text-right">
                            <x-button label="{{ __('Manage') }}" icon="o-arrow-right" link="{{ route('admin.events.show', $event->id) }}" class="btn-sm btn-primary btn-outline" />
                            <x-button icon="o-pencil-square" wire:click="edit({{ $event->id }})" class="btn-sm btn-circle btn-ghost text-blue-500" />
                            <x-button icon="o-trash" wire:click="confirmDelete({{ $event->id }})" class="btn-sm btn-circle btn-ghost text-red-500" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-10">{{ __('No events found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $events->links() }}</div>
    </x-card>

    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit Event') : __('Create Event') }}" right separator>
        <x-form wire:submit="save">
            <x-input label="{{ __('Event Name') }}" wire:model="name" required />
            <x-input label="{{ __('Year') }}" wire:model="year" type="number" required />
            <x-textarea label="{{ __('Theme') }}" wire:model="theme_title" required />
            <x-slot:actions>
                <x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- Modal Delete & Toggle omitted for brevity, standard MaryUI Modals --}}
    <x-modal wire:model="deleteModalOpen" title="{{ __('Delete Event') }}">
        {{ __('Cannot delete event with registered teams.') }}
        <x-slot:actions><x-button label="{{ __('Delete') }}" class="btn-error" wire:click="deleteRecord" /></x-slot:actions>
    </x-modal>
</div>
