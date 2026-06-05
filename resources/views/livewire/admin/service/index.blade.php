<div>
    <x-header title="{{ __('Lab Services') }}" subtitle="{{ __('Manage 3D printing and design services') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add Service') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    <div class="mb-6 max-w-md">
        <x-input placeholder="{{ __('Search service name...') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" />
    </div>

    {{-- Golden Standard: table container --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                {{-- Golden Standard: thead row --}}
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <th class="py-3 px-6 text-center w-12">#</th>
                        <th class="py-3 px-6">{{ __('Service Name') }}</th>
                        <th class="py-3 px-6">{{ __('Description') }}</th>
                        <th class="py-3 px-6">{{ __('Base Price') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                {{-- Golden Standard: tbody --}}
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($services as $service)
                        {{-- Golden Standard: row hover --}}
                        <tr wire:key="svc-{{ $service->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-6 text-center text-slate-400 dark:text-slate-500 font-mono text-xs">{{ $loop->iteration + ($services->firstItem() - 1) }}</td>
                            <td class="py-4 px-6 font-bold text-slate-800 dark:text-slate-200">{{ $service->name }}</td>
                            <td class="py-4 px-6 text-sm text-slate-500 dark:text-slate-400 max-w-xs truncate" title="{{ $service->description }}">
                                {{ $service->description ?? '-' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono font-semibold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                    Rp {{ number_format($service->base_price, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                {{-- Golden Standard: action button container --}}
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Golden Standard: standard icon button --}}
                                    <button wire:click="edit({{ $service->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Edit') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    {{-- Golden Standard: danger button --}}
                                    <button wire:click="confirmDelete({{ $service->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-rose-100 hover:text-rose-600 hover:border-rose-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-rose-500/20 dark:hover:text-rose-400 dark:hover:border-rose-500/30 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Delete') }}">
                                        <x-icon name="o-trash" class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Golden Standard: empty state --}}
                        <tr>
                            <td colspan="5" class="text-center py-16">
                                <x-icon name="o-briefcase" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                                <p class="text-slate-500 dark:text-slate-400">{{ __('No services found.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Golden Standard: pagination footer --}}
        @if($services->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">{{ $services->links() }}</div>
        @endif
    </div>

    {{-- DRAWER DIPERLEBAR --}}
    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit Service') : __('Add Service') }}" class="w-11/12 md:w-1/2 lg:w-1/3" right separator>
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
            <x-button label="{{ __('Yes, Delete') }}" class="btn-error text-white" wire:click="deleteRecord" spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>
</div>
