<div>
    <x-header title="{{ __('Lab Services') }}" subtitle="{{ __('Manage 3D printing and design services') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add Service') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    <div class="mb-6 max-w-md">
        <x-input placeholder="{{ __('Search service name...') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" />
    </div>

    <x-card class="p-0 overflow-hidden shadow-sm border border-base-200">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-100">
                        <th class="w-12 text-center text-xs uppercase text-gray-500">#</th>
                        <th class="text-xs uppercase text-gray-500">{{ __('Service Name') }}</th>
                        <th class="text-xs uppercase text-gray-500">{{ __('Description') }}</th>
                        <th class="text-xs uppercase text-gray-500">{{ __('Base Price') }}</th>
                        <th class="text-right text-xs uppercase text-gray-500">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr wire:key="svc-{{ $service->id }}" class="hover:bg-base-200/50">
                            <td class="text-center text-gray-400 text-sm">{{ $loop->iteration + ($services->firstItem() - 1) }}</td>
                            <td class="font-bold text-base-content">{{ $service->name }}</td>
                            <td class="text-sm text-gray-500 max-w-xs truncate" title="{{ $service->description }}">
                                {{ $service->description ?? '-' }}
                            </td>
                            <td>
                                <span class="badge badge-success badge-sm text-white font-mono">
                                    Rp {{ number_format($service->base_price, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-right flex justify-end gap-1">
                                <x-button icon="o-pencil-square" wire:click="edit({{ $service->id }})" class="btn-sm btn-circle btn-ghost text-blue-500" />
                                <x-button icon="o-trash" wire:click="confirmDelete({{ $service->id }})" class="btn-sm btn-circle btn-ghost text-red-500" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-400">
                                <x-icon name="o-briefcase" class="w-12 h-12 mb-3 opacity-30 mx-auto" />
                                {{ __('No services found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($services->hasPages())
            <div class="p-4 border-t border-base-200 bg-base-50">{{ $services->links() }}</div>
        @endif
    </x-card>

    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit Service') : __('Add Service') }}" right separator>
        <x-form wire:submit="save">
            <x-input label="{{ __('Service Name') }}" wire:model="name" required />
            <x-input label="{{ __('Base Price (Rp)') }}" wire:model="base_price" type="number" prefix="Rp" required />
            <x-textarea label="{{ __('Description') }}" wire:model="description" rows="4" hint="{{ __('Explain the service details') }}" />

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.drawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    <x-modal wire:model="deleteModalOpen" title="{{ __('Confirm Deletion') }}" separator>
        <div class="py-4 text-base-content/80">
            {{ __('Are you sure you want to permanently delete this service? This action cannot be undone and will fail if the service is currently used in bookings.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModalOpen = false" class="btn-ghost" />
            <x-button label="{{ __('Yes, Delete') }}" class="btn-error" wire:click="deleteRecord" spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>
</div>
