<div class="space-y-6 animate-[fade-in_0.4s_ease-out]">

    {{-- ============================================ --}}
    {{-- HEADER                                       --}}
    {{-- ============================================ --}}
    <div class="border-b border-slate-200 dark:border-slate-800 pb-6">

        {{-- Title Row --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                    {{ __('Raw Materials') }}
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    {{ __('Manage lab consumable inventory, restock, and monitor usage logs.') }}
                </p>
            </div>
            <button wire:click="create"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                       bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-400 text-white shadow-sm transition-colors w-full sm:w-auto cursor-pointer shrink-0">
                <x-icon name="o-plus" class="w-4 h-4" />
                {{ __('Add Material') }}
            </button>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- FILTERS BAR                                  --}}
    {{-- ============================================ --}}
    <div class="rounded-2xl p-6 shadow-lg border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026]">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="sm:col-span-3">
                <label class="block text-xs font-bold text-base-content/50 dark:text-[#94A3B8] uppercase tracking-wider mb-1.5">{{ __('Search') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-icon name="o-magnifying-glass" class="w-4 h-4 text-base-content/40 dark:text-[#94A3B8]" />
                    </div>
                    <input type="text" wire:model.live.debounce.500ms="search" placeholder="{{ __('Brand, category, or color...') }}"
                        class="w-full pl-9 pr-3 py-2 rounded-lg bg-base-200/50 dark:bg-[#062E5C]/60 border border-base-300 dark:border-[#0A3D7A]/50
                               text-base-content dark:text-[#F8FAFC] placeholder-base-content/40 dark:placeholder-[#94A3B8]/60 text-sm
                               focus:ring-1 focus:ring-primary dark:focus:ring-[#22D3EE] focus:border-primary dark:focus:border-[#22D3EE] transition-shadow" />
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-base-content/50 dark:text-[#94A3B8] uppercase tracking-wider mb-1.5">{{ __('Lab Filter') }}</label>
                <select wire:model.live="filterLabId"
                    class="w-full px-3 py-2 rounded-lg bg-base-200/50 dark:bg-[#062E5C]/60 border border-base-300 dark:border-[#0A3D7A]/50
                           text-base-content dark:text-[#F8FAFC] text-sm
                           focus:ring-1 focus:ring-primary dark:focus:ring-[#22D3EE] focus:border-primary dark:focus:border-[#22D3EE]">
                    <option value="">{{ __('All Labs') }}</option>
                    @foreach($labTypes as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- MAIN DATA TABLE                              --}}
    {{-- ============================================ --}}
    {{-- Golden Standard: table container --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                {{-- Golden Standard: thead row --}}
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <th class="py-3 px-6 text-center w-16">#</th>
                        <th class="py-3 px-6">{{ __('Lab') }}</th>
                        <th class="py-3 px-6">{{ __('Category') }}</th>
                        <th class="py-3 px-6">{{ __('Material') }}</th>
                        <th class="py-3 px-6">{{ __('Stock Status') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                {{-- Golden Standard: tbody --}}
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($materials as $material)
                        {{-- Golden Standard: row hover --}}
                        <tr wire:key="mat-{{ $material->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-6 text-center font-mono text-xs text-slate-400 dark:text-slate-500">{{ $loop->iteration + ($materials->firstItem() - 1) }}</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border
                                             bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-indigo-200 dark:border-indigo-500/30">
                                    {{ $material->lab->name }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $material->materialCategory->name }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $material->brand->name }}</span>
                                    <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400">{{ $material->color->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <div class="relative flex h-2.5 w-2.5">
                                        @if($material->current_stock <= 100)
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-500 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                                        @else
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="font-mono font-bold {{ $material->current_stock <= 100 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-800 dark:text-slate-200' }}">{{ $material->current_stock }}</span>
                                        <span class="text-xs text-slate-400 dark:text-slate-500 ml-1">{{ $material->unit }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                {{-- Golden Standard: action button container --}}
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="viewHistory({{ $material->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                               bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400
                                               hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200
                                               dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400 dark:hover:border-indigo-500/30
                                               border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer" title="{{ __('History') }}">
                                        <x-icon name="o-chart-bar" class="w-3.5 h-3.5" /> {{ __('History') }}
                                    </button>
                                    {{-- Golden Standard: standard icon button --}}
                                    <button wire:click="edit({{ $material->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Edit') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    {{-- Golden Standard: danger button --}}
                                    <button wire:click="confirmDelete({{ $material->id }})"
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
                            <td colspan="6" class="text-center py-16">
                                <x-icon name="o-cube-transparent" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                                <p class="text-slate-500 dark:text-slate-400">{{ __('No raw materials found in inventory.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Golden Standard: pagination footer --}}
        @if($materials->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">
                {{ $materials->links() }}
            </div>
        @endif
    </div>

    {{-- ============================================ --}}
    {{-- DRAWER: CRUD MASTER DATA (Custom Datatlists) --}}
    {{-- ============================================ --}}
    <x-drawer wire:model="drawerOpen" class="w-11/12 md:w-1/2 lg:w-1/3 bg-base-100 dark:bg-[#031026] border-l border-base-200 dark:border-[#0A3D7A]/50 shadow-2xl" right>
        <div class="p-6 h-full flex flex-col">
            <div class="mb-7">
                <h2 class="text-xl font-bold text-base-content dark:text-[#F8FAFC]">{{ $editingId ? __('Edit Material Details') : __('Add New Material') }}</h2>
                <p class="text-sm text-base-content/60 dark:text-[#94A3B8] mt-1.5 leading-relaxed">{{ __('You can type new names in the inputs below, and they will be created automatically in Master Data.') }}</p>
            </div>

            <form wire:submit="save" class="flex-1 flex flex-col overflow-y-auto pr-1">
                <div class="space-y-6 flex-1 pb-6">

                    {{-- Lab --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Lab') }} <span class="text-error dark:text-[#EF4444]">*</span></label>
                        <select wire:model="lab" required
                            class="w-full px-3 py-2.5 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                   text-base-content dark:text-[#F8FAFC] shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 transition-shadow">
                            <option value="">{{ __('Select Lab...') }}</option>
                            @foreach($labTypes as $labOption)
                                <option value="{{ $labOption->name }}">{{ $labOption->name }}</option>
                            @endforeach
                        </select>
                        @error('lab') <span class="block text-xs text-error dark:text-[#EF4444] mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    {{-- CREATABLE: Category --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Category') }} <span class="text-error dark:text-[#EF4444]">*</span></label>
                        <input type="text" wire:model="category" list="category-suggestions" required placeholder="{{ __('Type to search or create a new Category...') }}"
                            class="w-full px-3 py-2.5 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                   text-base-content dark:text-[#F8FAFC] placeholder-slate-400 dark:placeholder-slate-500 shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 transition-shadow" />
                        <datalist id="category-suggestions">
                            @foreach($categoryOptions as $opt) <option value="{{ $opt }}"> @endforeach
                        </datalist>
                        @error('category') <span class="block text-xs text-error dark:text-[#EF4444] mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- CREATABLE: Brand --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Brand') }} <span class="text-error dark:text-[#EF4444]">*</span></label>
                            <input type="text" wire:model="brand" list="brand-suggestions" required placeholder="{{ __('Type to search or create...') }}"
                                class="w-full px-3 py-2.5 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                       text-base-content dark:text-[#F8FAFC] placeholder-slate-400 dark:placeholder-slate-500 shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 transition-shadow" />
                            <datalist id="brand-suggestions">
                                @foreach($brandOptions as $opt) <option value="{{ $opt }}"> @endforeach
                            </datalist>
                            @error('brand') <span class="block text-xs text-error dark:text-[#EF4444] mt-1.5">{{ $message }}</span> @enderror
                        </div>

                        {{-- CREATABLE: Color --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Color') }} <span class="text-error dark:text-[#EF4444]">*</span></label>
                            <input type="text" wire:model="color" list="color-suggestions" required placeholder="{{ __('Type to search or create...') }}"
                                class="w-full px-3 py-2.5 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                       text-base-content dark:text-[#F8FAFC] placeholder-slate-400 dark:placeholder-slate-500 shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 transition-shadow" />
                            <datalist id="color-suggestions">
                                @foreach($colorOptions as $opt) <option value="{{ $opt }}"> @endforeach
                            </datalist>
                            @error('color') <span class="block text-xs text-error dark:text-[#EF4444] mt-1.5">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- CREATABLE: Unit --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Unit') }} <span class="text-error dark:text-[#EF4444]">*</span></label>
                        <input type="text" wire:model="unit" list="unit-suggestions" required placeholder="{{ __('e.g., gram, ml, pcs') }}"
                            class="w-full px-3 py-2.5 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                   text-base-content dark:text-[#F8FAFC] placeholder-slate-400 dark:placeholder-slate-500 shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 transition-shadow" />
                        <datalist id="unit-suggestions">
                            @foreach($unitOptions as $opt) <option value="{{ $opt }}"> @endforeach
                        </datalist>
                        @error('unit') <span class="block text-xs text-error dark:text-[#EF4444] mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    @if(!$editingId)
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Initial Stock') }} <span class="text-error dark:text-[#EF4444]">*</span></label>
                            <input type="number" wire:model="current_stock" required min="0"
                                class="w-full px-3 py-2.5 rounded-lg text-sm font-mono bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                       text-base-content dark:text-[#F8FAFC] shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 transition-shadow" />
                            <p class="text-[11px] text-slate-500 dark:text-slate-500 mt-2 leading-relaxed">{{ __('Set the starting stock balance. Future updates must use the restock flow.') }}</p>
                            @error('current_stock') <span class="block text-xs text-error dark:text-[#EF4444] mt-1.5">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <div class="bg-primary/10 dark:bg-[#22D3EE]/10 p-4 rounded-xl border border-primary/20 dark:border-[#22D3EE]/20 flex items-start gap-3">
                            <x-icon name="o-shield-check" class="w-5 h-5 text-primary dark:text-[#22D3EE] shrink-0 mt-0.5" />
                            <p class="text-xs text-primary dark:text-[#22D3EE] leading-relaxed">
                                {{ __('Stock editing is locked for auditing purposes. Please use the "Manage History" interface to record inbound restocks or usages securely.') }}
                            </p>
                        </div>
                    @endif

                </div>

                <div class="mt-auto pt-5 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" @click="$wire.drawerOpen = false"
                        class="px-5 py-2.5 rounded-lg text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-sm font-semibold
                               bg-primary dark:bg-[#00426D] hover:bg-primary/80 dark:hover:bg-[#0A3D7A] text-primary-content dark:text-[#F8FAFC] shadow-sm transition-colors cursor-pointer">
                        <span wire:loading.remove wire:target="save">{{ __('Save Material') }}</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <x-icon name="o-arrow-path" class="w-4 h-4 animate-spin" /> {{ __('Saving...') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </x-drawer>

    {{-- ============================================ --}}
    {{-- MODAL: CONFIRM DELETE                        --}}
    {{-- ============================================ --}}
    <x-modal wire:model="deleteModalOpen" class="backdrop-blur-sm">
        <div class="bg-base-100 dark:bg-[#031026] border border-base-200 dark:border-[#0A3D7A]/50 p-6 rounded-2xl shadow-xl max-w-md mx-auto">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-error/10 dark:bg-[#EF4444]/10 flex items-center justify-center shrink-0 border border-error/30 dark:border-[#EF4444]/30">
                    <x-icon name="o-exclamation-triangle" class="w-6 h-6 text-error dark:text-[#EF4444]" />
                </div>
                <h3 class="text-xl font-bold text-base-content dark:text-[#F8FAFC]">{{ __('Confirm Deletion') }}</h3>
            </div>
            <p class="text-sm text-base-content/60 dark:text-[#94A3B8] leading-relaxed mb-6">
                {{ __('Are you sure you want to permanently delete this material? This action cannot be undone and will fail if the material has movement history.') }}
            </p>
            <div class="flex justify-end gap-3">
                <button @click="$wire.deleteModalOpen = false"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-base-content/60 dark:text-[#94A3B8] hover:bg-base-200/50 dark:hover:bg-[#0A3D7A]/30 transition-colors cursor-pointer">
                    {{ __('Cancel') }}
                </button>
                <button wire:click="deleteRecord"
                    class="px-4 py-2 rounded-lg text-sm font-medium bg-error dark:bg-[#EF4444] hover:bg-error/90 dark:hover:bg-[#DC2626] text-white shadow-sm flex items-center gap-2 cursor-pointer">
                    <span wire:loading.remove wire:target="deleteRecord">{{ __('Yes, Delete') }}</span>
                    <span wire:loading wire:target="deleteRecord">
                        <x-icon name="o-arrow-path" class="w-4 h-4 animate-spin" />
                    </span>
                </button>
            </div>
        </div>
    </x-modal>

    {{-- ============================================ --}}
    {{-- DRAWER: HISTORY & RESTOCK (Combined)         --}}
    {{-- ============================================ --}}
    <x-drawer wire:model="historyDrawerOpen" right class="w-full md:w-11/12 lg:w-3/4 bg-base-100 dark:bg-[#031026] border-l border-base-200 dark:border-[#0A3D7A]/50 shadow-2xl" without-close-button>
        @if($activeMaterial)
            <div class="h-full flex flex-col">
                {{-- HEADER --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-base-200/40 dark:bg-[#062E5C]/40 backdrop-blur-xl p-6 border-b border-base-200 dark:border-[#0A3D7A]/50 sticky top-0 z-40 gap-4 shadow-sm">
                    <div>
                        <h2 class="text-2xl font-black text-base-content dark:text-[#F8FAFC] tracking-tight flex items-center gap-2">
                            {{ $activeMaterial->brand->name }} <span class="text-primary dark:text-[#22D3EE]">{{ $activeMaterial->color->name }}</span>
                        </h2>
                        <div class="flex items-center gap-3 mt-1.5">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold tracking-wider uppercase border
                                         bg-primary/10 dark:bg-[#00426D] text-primary dark:text-[#22D3EE] border-primary/20 dark:border-[#22D3EE]/30">
                                {{ $activeMaterial->lab->name }}
                            </span>
                            <span class="text-xs font-semibold text-base-content/50 dark:text-[#94A3B8] uppercase tracking-widest">{{ $activeMaterial->materialCategory->name }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-6 self-end sm:self-auto">
                        <div class="text-right hidden sm:block">
                            <div class="text-[10px] uppercase font-bold text-base-content/50 dark:text-[#94A3B8] tracking-widest mb-0.5">{{ __('Current Balance') }}</div>
                            <div class="font-mono text-2xl font-black {{ $activeMaterial->current_stock <= 100 ? 'text-error dark:text-[#EF4444]' : 'text-base-content dark:text-[#F8FAFC]' }}">
                                {{ $activeMaterial->current_stock }} <span class="text-sm font-semibold text-base-content/50 dark:text-[#94A3B8]">{{ $activeMaterial->unit }}</span>
                            </div>
                        </div>
                        <button @click="$wire.historyDrawerOpen = false"
                            class="p-2 rounded-full bg-base-200/50 dark:bg-[#062E5C]/60 hover:bg-base-200 dark:hover:bg-[#0A3D7A]/60
                                   text-base-content/50 dark:text-[#94A3B8] hover:text-base-content dark:hover:text-[#F8FAFC] transition-colors cursor-pointer">
                            <x-icon name="o-x-mark" class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                {{-- CONTENT BODY --}}
                <div class="flex-1 overflow-y-auto p-6">

                    {{-- STATS GRID --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                        <div class="bg-base-200/30 dark:bg-[#062E5C]/30 rounded-2xl p-5 border border-base-200 dark:border-[#0A3D7A]/40 shadow-sm relative overflow-hidden">
                            <div class="text-[11px] font-bold text-base-content/50 dark:text-[#94A3B8] uppercase tracking-widest mb-1">{{ __('Total Restock (IN)') }}</div>
                            <div class="text-2xl font-mono font-black text-success dark:text-[#22C55E]">+{{ $totalIn }} <span class="text-sm font-medium">{{ $activeMaterial->unit }}</span></div>
                        </div>

                        <div class="bg-base-200/30 dark:bg-[#062E5C]/30 rounded-2xl p-5 border border-base-200 dark:border-[#0A3D7A]/40 shadow-sm relative overflow-hidden">
                            <div class="text-[11px] font-bold text-base-content/50 dark:text-[#94A3B8] uppercase tracking-widest mb-1">{{ __('Total Usage (OUT)') }}</div>
                            <div class="text-2xl font-mono font-black text-error dark:text-[#EF4444]">-{{ $totalOut }} <span class="text-sm font-medium">{{ $activeMaterial->unit }}</span></div>
                        </div>

                        @php
                            $totalActivity = $totalIn + $totalOut;
                            $usageRatio = $totalActivity > 0 ? round(($totalOut / $totalActivity) * 100) : 0;
                        @endphp
                        <div class="bg-base-200/30 dark:bg-[#062E5C]/30 rounded-2xl p-5 border border-base-200 dark:border-[#0A3D7A]/40 shadow-sm flex flex-col justify-center">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-[11px] font-bold text-base-content/50 dark:text-[#94A3B8] uppercase tracking-widest">{{ __('Usage Ratio') }}</span>
                                <span class="text-lg font-black text-primary dark:text-[#22D3EE]">{{ $usageRatio }}%</span>
                            </div>
                            <div class="w-full bg-base-300 dark:bg-[#031026] rounded-full h-2.5 overflow-hidden">
                                <div class="h-full rounded-full bg-primary dark:bg-[#22D3EE] transition-all duration-1000 ease-out" style="width: {{ $usageRatio }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- INLINE RESTOCK FORM --}}
                    @if($showRestockForm)
                        <div class="mb-8 bg-success/10 dark:bg-[#22C55E]/10 rounded-2xl border border-success/20 dark:border-[#22C55E]/20 p-6 shadow-sm relative overflow-hidden animate-[fade-in_0.2s_ease-out]">
                            <div class="absolute top-0 left-0 w-1 h-full bg-success dark:bg-[#22C55E]"></div>

                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-lg font-bold text-base-content dark:text-[#F8FAFC] flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-success/20 dark:bg-[#22C55E]/20 flex items-center justify-center border border-success/30 dark:border-[#22C55E]/30">
                                            <x-icon name="o-arrow-down-tray" class="w-4 h-4 text-success dark:text-[#22C55E]" />
                                        </div>
                                        {{ __('Record Restock & Purchase') }}
                                    </h3>
                                    <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/60 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-700 rounded-md text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                                        <x-icon name="o-shield-check" class="w-3.5 h-3.5 text-success dark:text-[#22C55E]" />
                                        {{ __('Author:') }} <span class="font-bold text-base-content dark:text-[#F8FAFC]">{{ Auth::user()->name }}</span>
                                    </div>
                                </div>
                                <button wire:click="$set('showRestockForm', false)" class="text-base-content/50 dark:text-[#94A3B8] hover:text-error dark:hover:text-[#EF4444] transition-colors p-1 cursor-pointer">
                                    <x-icon name="o-x-mark" class="w-5 h-5" />
                                </button>
                            </div>

                            <form wire:submit="processRestock" class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Quantity Added') }} <span class="text-error dark:text-[#EF4444]">*</span></label>
                                        <div class="relative">
                                            <input type="number" wire:model="restockQty" required min="1"
                                                class="w-full text-sm font-mono bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg pl-3 pr-14 py-2.5
                                                       text-base-content dark:text-[#F8FAFC] shadow-sm
                                                       focus:outline-none focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 transition-shadow" placeholder="0" />
                                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-500 dark:text-slate-400 text-xs font-semibold border-l border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-r-lg">
                                                {{ $activeMaterial->unit }}
                                            </div>
                                        </div>
                                        @error('restockQty') <span class="block text-xs text-error dark:text-[#EF4444] mt-1.5">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Total Amount') }} <span class="text-error dark:text-[#EF4444]">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 flex items-center px-3 pointer-events-none text-slate-500 dark:text-slate-400 text-xs font-semibold border-r border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 rounded-l-lg">
                                                Rp
                                            </div>
                                            <input type="number" wire:model="restockAmount" required min="1"
                                                class="w-full text-sm font-mono bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg pl-12 pr-3 py-2.5
                                                       text-base-content dark:text-[#F8FAFC] shadow-sm
                                                       focus:outline-none focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 transition-shadow" placeholder="0" />
                                        </div>
                                        @error('restockAmount') <span class="block text-xs text-error dark:text-[#EF4444] mt-1.5">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Reimbursement Title') }} <span class="text-error dark:text-[#EF4444]">*</span></label>
                                    <input type="text" wire:model="restockTitle" required placeholder="{{ __('e.g., Restock Resins - Tokopedia') }}"
                                        class="w-full text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3 py-2.5
                                               text-base-content dark:text-[#F8FAFC] placeholder-slate-400 dark:placeholder-slate-500 shadow-sm
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 transition-shadow" />
                                    @error('restockTitle') <span class="block text-xs text-error dark:text-[#EF4444] mt-1.5">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Notes / Supplier') }} <span class="text-error dark:text-[#EF4444]">*</span></label>
                                    <input type="text" wire:model="restockNotes" required placeholder="{{ __('e.g., Supplier Anycubic Official Store') }}"
                                        class="w-full text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg px-3 py-2.5
                                               text-base-content dark:text-[#F8FAFC] placeholder-slate-400 dark:placeholder-slate-500 shadow-sm
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-indigo-500/50 focus:border-indigo-400 dark:focus:border-indigo-500 transition-shadow" />
                                    @error('restockNotes') <span class="block text-xs text-error dark:text-[#EF4444] mt-1.5">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Payment Proof (Receipt/Invoice)') }} <span class="text-error dark:text-[#EF4444]">*</span></label>
                                    <input type="file" wire:model="paymentProof" accept="image/jpeg,image/png,application/pdf" required
                                        class="block w-full text-sm text-slate-600 dark:text-slate-400
                                               file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                                               file:text-sm file:font-semibold file:bg-success/20 dark:file:bg-[#22C55E]/20 file:text-success dark:file:text-[#22C55E]
                                               hover:file:bg-success/30 dark:hover:file:bg-[#22C55E]/30 transition-colors
                                               bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 shadow-sm rounded-lg cursor-pointer
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-indigo-500/50" />
                                    <p class="text-[11px] text-slate-500 dark:text-slate-500 mt-2">{{ __('Accepted formats: JPG, PNG, PDF. Max size: 5MB.') }}</p>
                                    @error('paymentProof') <span class="block text-xs text-error dark:text-[#EF4444] mt-1.5">{{ $message }}</span> @enderror
                                </div>

                                <div class="pt-5 border-t border-slate-200 dark:border-slate-800 flex justify-end">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-success dark:bg-[#22C55E] hover:bg-success/80 dark:hover:bg-[#16A34A] text-white shadow-sm transition-colors cursor-pointer">
                                        <span wire:loading.remove wire:target="processRestock">{{ __('Confirm Restock') }}</span>
                                        <span wire:loading wire:target="processRestock" class="flex items-center gap-2">
                                            <x-icon name="o-arrow-path" class="w-4 h-4 animate-spin" /> {{ __('Processing...') }}
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    {{-- MOVEMENT LOG SECTION --}}
                    <div class="flex justify-between items-end mb-5">
                        <h3 class="text-lg font-bold text-base-content dark:text-[#F8FAFC]">{{ __('Movement Feed') }}</h3>
                        @if(!$showRestockForm)
                            <button wire:click="$toggle('showRestockForm')"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold
                                       bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] hover:bg-primary/20 dark:hover:bg-[#22D3EE]/20
                                       border border-primary/30 dark:border-[#22D3EE]/30 transition-colors cursor-pointer">
                                <x-icon name="o-plus" class="w-4 h-4" />
                                {{ __('Add Stock') }}
                            </button>
                        @endif
                    </div>

                    {{-- TIMELINE/CARD FEED --}}
                    <div class="space-y-4">
                        @forelse($activeMaterial->movements as $log)
                            <div class="bg-base-200/30 dark:bg-[#062E5C]/30 rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 p-5 flex flex-col md:flex-row md:items-center gap-5 hover:border-base-300 dark:hover:border-[#0A3D7A]/80 transition-colors shadow-sm">

                                {{-- Activity Type Badge --}}
                                <div class="shrink-0 flex justify-center">
                                    @if($log->type === 'in')
                                        <div class="w-12 h-12 rounded-full bg-success/20 dark:bg-[#22C55E]/20 border border-success/30 dark:border-[#22C55E]/30 flex items-center justify-center">
                                            <x-icon name="o-arrow-down-left" class="w-6 h-6 text-success dark:text-[#22C55E]" />
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-error/20 dark:bg-[#EF4444]/20 border border-error/30 dark:border-[#EF4444]/30 flex items-center justify-center">
                                            <x-icon name="o-arrow-up-right" class="w-6 h-6 text-error dark:text-[#EF4444]" />
                                        </div>
                                    @endif
                                </div>

                                {{-- Main Details --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="font-mono text-xl font-black {{ $log->type === 'in' ? 'text-success dark:text-[#22C55E]' : 'text-error dark:text-[#EF4444]' }}">
                                            {{ $log->type === 'in' ? '+' : '-' }}{{ $log->quantity }} <span class="text-sm font-medium opacity-70">{{ $activeMaterial->unit }}</span>
                                        </span>
                                        <span class="text-xs text-base-content/50 dark:text-[#94A3B8] font-mono flex items-center gap-1.5">
                                            <x-icon name="o-clock" class="w-3.5 h-3.5" />
                                            {{ $log->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-base-content dark:text-[#F8FAFC] leading-relaxed">
                                        {{ $log->notes ?: __('No notes provided.') }}
                                    </p>

                                    {{-- Contextual References --}}
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @if($log->reimbursement)
                                            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20 text-[11px]">
                                                <x-icon name="o-banknotes" class="w-3 h-3" />
                                                <span class="font-semibold">{{ $log->reimbursement->title }}</span>
                                                <span class="opacity-50 mx-0.5">•</span>
                                                <span class="font-mono font-bold">Rp {{ number_format($log->reimbursement->total_amount, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        @if($log->service_booking_id)
                                            <a href="{{ route('admin.order-center') }}"
                                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-warning/10 dark:bg-[#F59E0B]/10 text-warning dark:text-[#F59E0B] border border-warning/20 dark:border-[#F59E0B]/20 text-[11px] hover:bg-warning/20 dark:hover:bg-[#F59E0B]/20 transition-colors cursor-pointer">
                                                <x-icon name="o-shopping-bag" class="w-3 h-3" />
                                                <span class="font-semibold">{{ __('Order') }} #{{ str_pad($log->service_booking_id, 4, '0', STR_PAD_LEFT) }}</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                {{-- Accountability / Logged By --}}
                                <div class="shrink-0 flex items-center gap-3 bg-base-200/50 dark:bg-[#031026] rounded-xl px-4 py-3 border border-base-300 dark:border-[#0A3D7A]/40 w-full md:w-auto mt-2 md:mt-0">
                                    <div class="w-8 h-8 rounded-full bg-base-300 dark:bg-[#062E5C]/60 flex items-center justify-center shrink-0">
                                        <span class="text-xs font-black text-base-content dark:text-[#F8FAFC]">{{ strtoupper(substr($log->creator->name ?? 'S', 0, 1)) }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-base-content/50 dark:text-[#94A3B8] font-bold uppercase tracking-wider mb-0.5">{{ __('Logged By') }}</span>
                                        <span class="text-xs font-semibold text-base-content dark:text-[#F8FAFC] truncate max-w-[120px]">{{ $log->creator->name ?? __('System') }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 flex flex-col items-center justify-center border-2 border-dashed border-base-200 dark:border-[#0A3D7A]/40 rounded-2xl bg-base-200/20 dark:bg-[#062E5C]/20">
                                <x-icon name="o-document-text" class="w-12 h-12 text-base-content/30 dark:text-[#94A3B8] mb-3" />
                                <p class="text-base-content/60 dark:text-[#94A3B8] font-medium">{{ __('No movement history recorded yet.') }}</p>
                                <p class="text-xs text-base-content/40 dark:text-[#94A3B8]/60 mt-1">{{ __('Records will appear here when stock is added or used.') }}</p>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        @endif
    </x-drawer>
</div>
