<div class="space-y-6 animate-[fade-in_0.4s_ease-out]">

    {{-- ============================================ --}}
    {{-- HEADER                                       --}}
    {{-- ============================================ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl p-6 shadow-lg border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026]">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-base-content dark:text-[#F8FAFC] tracking-tight">
                {{ __('Master Data') }}
            </h1>
            <p class="text-sm text-base-content/60 dark:text-[#94A3B8] mt-1">
                {{ __('Manage lookup tables for labs, categories, brands, and colors.') }}
            </p>
        </div>
        <button wire:click="create"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold
                   bg-primary dark:bg-[#00426D] hover:bg-primary/80 dark:hover:bg-[#0A3D7A] text-primary-content dark:text-[#F8FAFC] shadow-sm transition-colors w-full sm:w-auto cursor-pointer">
            <x-icon name="o-plus" class="w-4 h-4" />
            {{ __('Add :label', ['label' => __($tabConfig['label'])]) }}
        </button>
    </div>

    {{-- ============================================ --}}
    {{-- TAB BAR                                      --}}
    {{-- ============================================ --}}
    <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">

        {{-- Tab Navigation --}}
        <div class="flex border-b border-base-200 dark:border-[#0A3D7A]/40 bg-base-200/40 dark:bg-[#062E5C]/40 dark:backdrop-blur-xl overflow-x-auto">
            @foreach($tabs as $key => $tab)
                <button wire:click="$set('activeTab', '{{ $key }}')"
                    class="flex items-center gap-2 px-6 py-3.5 text-sm font-semibold transition-all whitespace-nowrap cursor-pointer
                           {{ $activeTab === $key
                                ? 'text-primary dark:text-[#22D3EE] border-b-2 border-primary dark:border-[#22D3EE] bg-primary/5 dark:bg-[#0A3D7A]/30'
                                : 'text-base-content/60 dark:text-[#94A3B8] hover:text-base-content dark:hover:text-[#F8FAFC] hover:bg-base-200/50 dark:hover:bg-[#0A3D7A]/20 border-b-2 border-transparent' }}">
                    <x-icon :name="$tab['icon']" class="w-4 h-4" />
                    {{ __($tab['label']) }}
                </button>
            @endforeach
        </div>

        {{-- Search Bar --}}
        <div class="p-4 border-b border-base-200 dark:border-[#0A3D7A]/30">
            <div class="relative max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-icon name="o-magnifying-glass" class="w-4 h-4 text-base-content/40 dark:text-[#94A3B8]" />
                </div>
                <input type="text" wire:model.live.debounce.400ms="search"
                    placeholder="{{ __('Search :label...', ['label' => strtolower(__($tabConfig['label']))]) }}"
                    class="w-full pl-9 pr-3 py-2 rounded-lg bg-base-200/50 dark:bg-[#062E5C]/60 border border-base-300 dark:border-[#0A3D7A]/50
                           text-base-content dark:text-[#F8FAFC] placeholder-base-content/40 dark:placeholder-[#94A3B8]/60 text-sm
                           focus:ring-1 focus:ring-primary dark:focus:ring-[#22D3EE] focus:border-primary dark:focus:border-[#22D3EE] transition-shadow" />
            </div>
        </div>

        {{-- Data Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-base-200/50 dark:bg-[#062E5C]/30 border-b border-base-200 dark:border-[#0A3D7A]/40 text-xs font-bold uppercase tracking-widest text-base-content/50 dark:text-[#94A3B8]">
                        <th class="py-3 px-6 text-center w-16">#</th>
                        <th class="py-3 px-6">{{ __('Name') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Used By') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30 text-base-content dark:text-[#F8FAFC]">
                    @forelse($records as $record)
                        @php
                            // Sum all relationship counts dynamically
                            $usageCount = 0;
                            foreach ($tabConfig['relations'] as $rel) {
                                $countKey = \Illuminate\Support\Str::snake($rel) . '_count';
                                $usageCount += $record->{$countKey} ?? 0;
                            }
                        @endphp
                        <tr wire:key="rec-{{ $activeTab }}-{{ $record->id }}" class="hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">
                            <td class="py-3.5 px-6 text-center font-mono text-xs text-base-content/40 dark:text-[#94A3B8]">
                                {{ $loop->iteration + ($records->firstItem() - 1) }}
                            </td>
                            <td class="py-3.5 px-6 font-semibold">{{ $record->name }}</td>
                            <td class="py-3.5 px-6 text-center">
                                @if($usageCount > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                        <x-icon name="o-link" class="w-3 h-3" />
                                        {{ $usageCount }} {{ $usageCount === 1 ? __('reference') : __('references') }}
                                    </span>
                                @else
                                    <span class="text-xs text-base-content/30 dark:text-[#94A3B8]/50 italic">{{ __('Unused') }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="edit({{ $record->id }})"
                                        class="p-1.5 rounded-lg bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] hover:text-primary dark:hover:text-[#22D3EE] hover:bg-primary/10 dark:hover:bg-[#0A3D7A]/40
                                               border border-base-300 dark:border-[#0A3D7A]/40 transition-colors cursor-pointer"
                                        title="{{ __('Edit') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    <button wire:click="confirmDelete({{ $record->id }})"
                                        class="p-1.5 rounded-lg bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] hover:text-error dark:hover:text-[#EF4444] hover:bg-error/10 dark:hover:bg-[#EF4444]/10
                                               border border-base-300 dark:border-[#0A3D7A]/40 hover:border-error/30 dark:hover:border-[#EF4444]/30 transition-colors cursor-pointer"
                                        title="{{ __('Delete') }}">
                                        <x-icon name="o-trash" class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-16">
                                <x-icon name="o-circle-stack" class="w-12 h-12 mx-auto mb-3 text-base-content/20 dark:text-[#0A3D7A]/60" />
                                <p class="text-base-content/50 dark:text-[#94A3B8]">{{ __('No :label found.', ['label' => strtolower(__($tabConfig['label']))]) }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
            <div class="p-4 border-t border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/20 dark:bg-[#062E5C]/20">
                {{ $records->links() }}
            </div>
        @endif
    </div>

    {{-- ============================================ --}}
    {{-- MODAL: CREATE / EDIT                         --}}
    {{-- ============================================ --}}
    <x-modal wire:model="formModal" class="backdrop-blur-sm">
        <div class="bg-base-100 dark:bg-[#031026] border border-base-200 dark:border-[#0A3D7A]/50 p-6 rounded-2xl shadow-xl max-w-md mx-auto">
            <h3 class="text-lg font-bold text-base-content dark:text-[#F8FAFC] mb-1">
                {{ $editingId ? __('Edit :label', ['label' => __($tabConfig['label'])]) : __('New :label', ['label' => __($tabConfig['label'])]) }}
            </h3>
            <p class="text-sm text-base-content/60 dark:text-[#94A3B8] mb-5">{{ __('Enter the name for this master record.') }}</p>

            <form wire:submit="save" class="space-y-5">
                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-base-content/70 dark:text-[#94A3B8]">{{ __('Name') }} <span class="text-error dark:text-[#EF4444]">*</span></label>
                    <input type="text" wire:model="name" required autofocus
                        placeholder="{{ __('e.g., Lab Tekkes, Filament, eSUN...') }}"
                        class="w-full text-sm bg-base-200/50 dark:bg-[#062E5C]/60 border border-base-300 dark:border-[#0A3D7A]/50 text-base-content dark:text-[#F8FAFC] placeholder-base-content/40 dark:placeholder-[#94A3B8]/50
                               rounded-lg px-3 py-2.5 focus:ring-1 focus:ring-primary dark:focus:ring-[#22D3EE] focus:border-primary dark:focus:border-[#22D3EE] transition-shadow" />
                    @error('name') <span class="text-xs text-error dark:text-[#EF4444]">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-base-200 dark:border-[#0A3D7A]/30">
                    <button type="button" @click="$wire.formModal = false"
                        class="px-4 py-2.5 rounded-lg text-sm font-semibold text-base-content/60 dark:text-[#94A3B8] hover:bg-base-200 dark:hover:bg-[#0A3D7A]/30 transition-colors cursor-pointer">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-sm font-semibold
                               bg-primary dark:bg-[#00426D] hover:bg-primary/80 dark:hover:bg-[#0A3D7A] text-primary-content dark:text-[#F8FAFC] shadow-sm transition-colors cursor-pointer">
                        <span wire:loading.remove wire:target="save">{{ __('Save') }}</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <x-icon name="o-arrow-path" class="w-4 h-4 animate-spin" /> {{ __('Saving...') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- ============================================ --}}
    {{-- MODAL: CONFIRM DELETE                        --}}
    {{-- ============================================ --}}
    <x-modal wire:model="deleteModal" class="backdrop-blur-sm">
        <div class="bg-base-100 dark:bg-[#031026] border border-base-200 dark:border-[#0A3D7A]/50 p-6 rounded-2xl shadow-xl max-w-md mx-auto">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-error/10 dark:bg-[#EF4444]/10 flex items-center justify-center shrink-0 border border-error/30 dark:border-[#EF4444]/30">
                    <x-icon name="o-exclamation-triangle" class="w-6 h-6 text-error dark:text-[#EF4444]" />
                </div>
                <h3 class="text-xl font-bold text-base-content dark:text-[#F8FAFC]">{{ __('Confirm Deletion') }}</h3>
            </div>
            <p class="text-sm text-base-content/60 dark:text-[#94A3B8] leading-relaxed mb-6">
                {{ __('This will permanently remove the record. If it is referenced by materials or inventories, deletion will be blocked.') }}
            </p>
            <div class="flex justify-end gap-3">
                <button @click="$wire.deleteModal = false"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-base-content/60 dark:text-[#94A3B8] hover:bg-base-200 dark:hover:bg-[#0A3D7A]/30 transition-colors cursor-pointer">
                    {{ __('Cancel') }}
                </button>
                <button wire:click="deleteRecord"
                    class="px-4 py-2 rounded-lg text-sm font-medium bg-error dark:bg-[#EF4444] hover:bg-error/80 dark:hover:bg-[#DC2626] text-white shadow-sm flex items-center gap-2 cursor-pointer">
                    <span wire:loading.remove wire:target="deleteRecord">{{ __('Yes, Delete') }}</span>
                    <span wire:loading wire:target="deleteRecord">
                        <x-icon name="o-arrow-path" class="w-4 h-4 animate-spin" />
                    </span>
                </button>
            </div>
        </div>
    </x-modal>

</div>
