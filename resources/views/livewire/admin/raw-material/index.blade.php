<div>
    <x-header title="{{ __('Raw Materials') }}" subtitle="{{ __('Manage lab material inventory base') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add Material') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    <div class="mb-6 max-w-md">
        <x-input placeholder="{{ __('Search material name...') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" />
    </div>

    <x-card class="p-0 overflow-hidden shadow-sm border border-base-200">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-100">
                        <th class="w-12 text-center text-xs uppercase text-gray-500">#</th>
                        <th class="text-xs uppercase text-gray-500">{{ __('Material Name') }}</th>
                        <th class="text-xs uppercase text-gray-500">{{ __('Category') }}</th>
                        <th class="text-xs uppercase text-gray-500">{{ __('Current Stock') }}</th>
                        <th class="text-right text-xs uppercase text-gray-500">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                        <tr wire:key="mat-{{ $material->id }}" class="hover:bg-base-200/50">
                            <td class="text-center text-gray-400 text-sm">{{ $loop->iteration + ($materials->firstItem() - 1) }}</td>
                            <td class="font-bold text-base-content">{{ $material->name }}</td>
                            <td>
                                <span class="badge badge-outline badge-sm uppercase text-[10px]">{{ $material->category }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $material->current_stock > 100 ? 'bg-green-500' : 'bg-warning' }}"></div>
                                    <span class="font-mono font-semibold">{{ $material->current_stock }}</span>
                                    <span class="text-xs text-gray-500">{{ $material->unit }}</span>
                                </div>
                            </td>
                            <td class="text-right flex justify-end gap-1">
                                <x-button icon="o-pencil-square" wire:click="edit({{ $material->id }})" class="btn-sm btn-circle btn-ghost text-blue-500" />
                                <x-button icon="o-trash" wire:click="confirmDelete({{ $material->id }})" class="btn-sm btn-circle btn-ghost text-red-500" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-400">
                                <x-icon name="o-cube" class="w-12 h-12 mb-3 opacity-30 mx-auto" />
                                {{ __('No raw materials found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($materials->hasPages())
            <div class="p-4 border-t border-base-200 bg-base-50">{{ $materials->links() }}</div>
        @endif
    </x-card>

    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit Material') : __('Add Material') }}" right separator>
        <x-form wire:submit="save">
            <x-input label="{{ __('Material Name') }}" wire:model="name" placeholder="e.g., PLA+ White 1.75mm" required />

            <div class="grid grid-cols-2 gap-4">
                <x-select label="{{ __('Category') }}" wire:model="category" :options="$categories" option-label="name" option-value="id" placeholder="{{ __('Select...') }}" required />
                <x-select label="{{ __('Measurement Unit') }}" wire:model="unit" :options="$units" option-label="name" option-value="id" placeholder="{{ __('Select...') }}" required />
            </div>

            <x-input label="{{ __('Initial Stock') }}" wire:model="current_stock" type="number" hint="{{ __('Update manually only if needed. Use movement log for regular restocks.') }}" required />

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.drawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    <x-modal wire:model="deleteModalOpen" title="{{ __('Confirm Deletion') }}" separator>
        <div class="py-4 text-base-content/80">
            {{ __('Are you sure you want to permanently delete this material? This action cannot be undone and will fail if the material has movement history.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModalOpen = false" class="btn-ghost" />
            <x-button label="{{ __('Yes, Delete') }}" class="btn-error" wire:click="deleteRecord" spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>
</div>
