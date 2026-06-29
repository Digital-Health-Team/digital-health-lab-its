<div class="space-y-6 animate-[fade-in_0.4s_ease-out]">

    {{-- HEADER --}}
    <div class="border-b border-slate-200 dark:border-slate-800 pb-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ __('Labs') }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Manage laboratory locations used for raw materials and inventories.') }}</p>
            </div>
            <button wire:click="create"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                       bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-400 text-white shadow-sm transition-colors w-full sm:w-auto cursor-pointer shrink-0">
                <x-icon name="o-plus" class="w-4 h-4" />
                {{ __('Add Lab') }}
            </button>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="rounded-2xl p-5 shadow-sm border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026]">
        <div class="relative max-w-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-icon name="o-magnifying-glass" class="w-4 h-4 text-base-content/40 dark:text-[#94A3B8]" />
            </div>
            <input type="text" wire:model.live.debounce.400ms="search" placeholder="{{ __('Search labs...') }}"
                class="w-full pl-9 pr-3 py-2 rounded-lg bg-base-200/50 dark:bg-[#062E5C]/60 border border-base-300 dark:border-[#0A3D7A]/50
                       text-base-content dark:text-[#F8FAFC] placeholder-base-content/40 dark:placeholder-[#94A3B8]/60 text-sm
                       focus:ring-1 focus:ring-primary dark:focus:ring-[#22D3EE] focus:border-primary dark:focus:border-[#22D3EE] transition-shadow" />
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <th class="py-3 px-6 text-center w-16">#</th>
                        <th class="py-3 px-6">{{ __('Lab Name') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Materials') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Inventories') }}</th>
                        <th class="py-3 px-6">{{ __('Created') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($labs as $lab)
                        <tr wire:key="lab-{{ $lab->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-6 text-center font-mono text-xs text-slate-400">{{ $loop->iteration + ($labs->firstItem() - 1) }}</td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $lab->name }}</span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                             {{ $lab->raw_materials_count > 0 ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}">
                                    {{ $lab->raw_materials_count }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                             {{ $lab->inventories_count > 0 ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}">
                                    {{ $lab->inventories_count }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-400 dark:text-slate-500">{{ $lab->created_at->format('d M Y') }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $lab->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Edit') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    <button wire:click="confirmDelete({{ $lab->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-rose-100 hover:text-rose-600 hover:border-rose-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-rose-500/20 dark:hover:text-rose-400 dark:hover:border-rose-500/30 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Delete') }}">
                                        <x-icon name="o-trash" class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <x-icon name="o-building-office-2" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                                <p class="text-slate-500 dark:text-slate-400">{{ __('No labs found.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($labs->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">
                {{ $labs->links() }}
            </div>
        @endif
    </div>

    {{-- DRAWER: CREATE / EDIT --}}
    <x-drawer wire:model="drawerOpen" class="w-11/12 md:w-96 bg-base-100 dark:bg-[#031026] border-l border-base-200 dark:border-[#0A3D7A]/50 shadow-2xl" right>
        <div class="p-6 h-full flex flex-col">
            <div class="mb-7">
                <h2 class="text-xl font-bold text-base-content dark:text-[#F8FAFC]">{{ $editingId ? __('Edit Lab') : __('Add New Lab') }}</h2>
                <p class="text-sm text-base-content/60 dark:text-[#94A3B8] mt-1">{{ __('Labs group raw materials and inventories by physical location.') }}</p>
            </div>

            <form wire:submit="save" class="flex-1 flex flex-col">
                <div class="flex-1 pb-6">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        {{ __('Lab Name') }} <span class="text-error dark:text-[#EF4444]">*</span>
                    </label>
                    <input type="text" wire:model="name" required placeholder="{{ __('e.g., Lab Tekkes') }}"
                        class="w-full px-3 py-2.5 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                               text-base-content dark:text-[#F8FAFC] placeholder-slate-400 shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 transition-shadow" />
                    @error('name') <span class="block text-xs text-error mt-1.5">{{ $message }}</span> @enderror
                </div>

                <div class="mt-auto pt-5 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" @click="$wire.drawerOpen = false"
                        class="px-5 py-2.5 rounded-lg text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-sm font-semibold
                               bg-primary dark:bg-[#00426D] hover:bg-primary/80 dark:hover:bg-[#0A3D7A] text-primary-content dark:text-[#F8FAFC] shadow-sm transition-colors cursor-pointer">
                        <span wire:loading.remove wire:target="save">{{ $editingId ? __('Update Lab') : __('Save Lab') }}</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <x-icon name="o-arrow-path" class="w-4 h-4 animate-spin" /> {{ __('Saving...') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </x-drawer>

    {{-- MODAL: CONFIRM DELETE --}}
    <x-modal wire:model="deleteModalOpen" class="backdrop-blur-sm">
        <div class="bg-base-100 dark:bg-[#031026] border border-base-200 dark:border-[#0A3D7A]/50 p-6 rounded-2xl shadow-xl max-w-md mx-auto">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-error/10 dark:bg-[#EF4444]/10 flex items-center justify-center shrink-0 border border-error/30 dark:border-[#EF4444]/30">
                    <x-icon name="o-exclamation-triangle" class="w-6 h-6 text-error dark:text-[#EF4444]" />
                </div>
                <h3 class="text-xl font-bold text-base-content dark:text-[#F8FAFC]">{{ __('Confirm Deletion') }}</h3>
            </div>
            <p class="text-sm text-base-content/60 dark:text-[#94A3B8] leading-relaxed mb-6">
                {{ __('Are you sure you want to delete this lab? This will fail if any materials or inventories are still linked to it.') }}
            </p>
            <div class="flex justify-end gap-3">
                <button @click="$wire.deleteModalOpen = false"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-base-content/60 dark:text-[#94A3B8] hover:bg-base-200/50 dark:hover:bg-[#0A3D7A]/30 transition-colors cursor-pointer">
                    {{ __('Cancel') }}
                </button>
                <button wire:click="deleteRecord"
                    class="px-4 py-2 rounded-lg text-sm font-semibold bg-error dark:bg-[#EF4444] hover:bg-error/80 dark:hover:bg-[#DC2626] text-white transition-colors cursor-pointer">
                    <span wire:loading.remove wire:target="deleteRecord">{{ __('Delete') }}</span>
                    <span wire:loading wire:target="deleteRecord">{{ __('Deleting...') }}</span>
                </button>
            </div>
        </div>
    </x-modal>

</div>
