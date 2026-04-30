<div>
    <x-header title="{{ __('Lab Structure') }}" subtitle="{{ __('Manage team hierarchy and display order') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add Member') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    <div class="mb-6 max-w-sm">
        <x-input placeholder="{{ __('Search name or position...') }}" wire:model.live.debounce="search" icon="o-magnifying-glass" />
    </div>

    <x-card class="p-0 overflow-hidden border border-base-200 shadow-sm">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-100">
                    <th class="w-20 text-center">{{ __('Order') }}</th>
                    <th>{{ __('Member Name') }}</th>
                    <th>{{ __('Position') }}</th>
                    <th>{{ __('Account Link') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr wire:key="mem-{{ $member->id }}" class="hover:bg-base-200/50 transition-colors">
                        <td class="text-center">
                            <span class="badge badge-neutral font-mono font-bold">#{{ $member->display_order }}</span>
                        </td>
                        <td>
                            <div class="font-bold text-primary">{{ $member->name }}</div>
                        </td>
                        <td>
                            <div class="text-sm font-medium">{{ $member->position }}</div>
                        </td>
                        <td>
                            @if($member->user)
                                <div class="flex items-center gap-2">
                                    <x-avatar :image="$member->user->profile_photo ? asset('storage/'.$member->user->profile_photo) : null" class="!w-6 !h-6" />
                                    <span class="text-xs text-gray-500">{{ $member->user->email }}</span>
                                </div>
                            @else
                                <span class="text-xs italic text-gray-400">{{ __('No account linked') }}</span>
                            @endif
                        </td>
                        <td>
                            <x-button
                                wire:click="toggleStatus({{ $member->id }})"
                                class="btn-xs {{ $member->is_active ? 'btn-success' : 'btn-ghost border-base-300' }} text-white"
                                label="{{ $member->is_active ? __('Visible') : __('Hidden') }}"
                            />
                        </td>
                        <td class="text-right">
                            <x-button icon="o-pencil-square" wire:click="edit({{ $member->id }})" class="btn-sm btn-circle btn-ghost text-blue-500" />
                            <x-button icon="o-trash" wire:click="confirmDelete({{ $member->id }})" class="btn-sm btn-circle btn-ghost text-red-500" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-gray-400">{{ __('No members found.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $members->links() }}</div>
    </x-card>

    {{-- DRAWER FORM --}}
    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit Member') : __('Add New Member') }}" right separator with-close-button class="w-11/12 lg:w-1/3">
        <x-form wire:submit="save">
            <x-choices
                label="{{ __('Link to System Account (Optional)') }}"
                wire:model.live="user_id"
                :options="$availableUsers"
                option-label="name"
                option-value="id"
                single searchable
                placeholder="{{ __('Search user...') }}"
                hint="{{ __('Linking will auto-suggest the name.') }}"
            />

            <x-input label="{{ __('Full Name / Display Name') }}" wire:model="name" required />
            <x-input label="{{ __('Position / Title') }}" wire:model="position" placeholder="e.g. Kepala Laboratorium" required />
            <x-input label="{{ __('Display Order') }}" wire:model="display_order" type="number" hint="{{ __('Lower numbers appear first (e.g., 1 is top priority)') }}" required />

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.drawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save Member') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- MODAL DELETE --}}
    <x-modal wire:model="deleteModalOpen" title="{{ __('Confirm Deletion') }}">
        <div class="py-4">{{ __('Are you sure you want to remove this person from the lab structure?') }}</div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModalOpen = false" />
            <x-button label="{{ __('Delete') }}" class="btn-error" wire:click="deleteRecord" />
        </x-slot:actions>
    </x-modal>
</div>
