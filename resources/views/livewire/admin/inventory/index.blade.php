<div class="space-y-5 animate-[fade-in_0.4s_ease-out]">

    {{-- ============================================ --}}
    {{-- PAGE HEADER                                   --}}
    {{-- ============================================ --}}
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ __('Inventory Management') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            {{ __('Manage raw materials, labs, categories, brands, and colors in one place.') }}
        </p>
    </div>

    {{-- ============================================ --}}
    {{-- SECTION TAB BAR                              --}}
    {{-- ============================================ --}}
    <div class="flex flex-wrap gap-0.5 border-b border-slate-200 dark:border-slate-800">

        <button wire:click="$set('section', 'materials')"
            class="{{ $section === 'materials'
                ? 'border-b-2 border-indigo-600 dark:border-indigo-400 text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10'
                : 'border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/40' }}
                   flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-t-lg transition-colors cursor-pointer -mb-px">
            <x-icon name="o-cube-transparent" class="w-4 h-4" />
            {{ __('Raw Materials') }}
        </button>

        @foreach($lookupSections as $key => $tab)
            <button wire:click="$set('section', '{{ $key }}')"
                class="{{ $section === $key
                    ? 'border-b-2 border-indigo-600 dark:border-indigo-400 text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10'
                    : 'border-b-2 border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/40' }}
                       flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-t-lg transition-colors cursor-pointer -mb-px">
                <x-icon name="{{ $tab['icon'] }}" class="w-4 h-4" />
                {{ __($tab['label']) }}
            </button>
        @endforeach

    </div>

    {{-- ============================================ --}}
    {{-- RAW MATERIALS — POS LAYOUT                   --}}
    {{-- ============================================ --}}
    @if($section === 'materials')

        <div class="flex gap-5 items-start">

            {{-- ─── LEFT: browse panel ──────────────────── --}}
            <div class="{{ $activeMaterial ? 'flex-1 min-w-0' : 'w-full' }} space-y-4">

                {{-- Sub-header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                            <x-icon name="o-cube-transparent" class="w-4 h-4 text-indigo-500 dark:text-indigo-400" />
                            {{ __('Raw Materials') }}
                            @if($materials->total() > 0)
                                <span class="text-xs font-medium text-slate-400 dark:text-slate-500">({{ $materials->total() }})</span>
                            @endif
                        </h2>
                    </div>
                    <a wire:navigate href="{{ route('admin.inventory.materials.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold
                               bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-400 text-white shadow-sm transition-colors cursor-pointer shrink-0">
                        <x-icon name="o-plus" class="w-4 h-4" />
                        {{ __('Add Material') }}
                    </a>
                </div>

                {{-- Search --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-icon name="o-magnifying-glass" class="w-4 h-4 text-slate-400 dark:text-slate-500" />
                    </div>
                    <input type="text" wire:model.live.debounce.500ms="search"
                        placeholder="{{ __('Search by name or brand...') }}"
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700
                               text-sm text-slate-700 dark:text-slate-300 placeholder-slate-400 dark:placeholder-slate-600
                               focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 dark:focus:border-indigo-500 transition-shadow shadow-sm" />
                </div>

                {{-- CARD GRID --}}
                <div class="grid grid-cols-2 xl:grid-cols-3 gap-3">
                    @forelse($materials as $material)
                        @php
                            $totalStock = $material->stocks->sum('quantity');
                            $isSelected = $activeMaterial?->id === $material->id;
                        @endphp
                        <div wire:click="viewHistory({{ $material->id }})"
                            wire:key="mat-card-{{ $material->id }}"
                            class="group bg-white dark:bg-slate-900 rounded-2xl border transition-all duration-150 cursor-pointer
                                   {{ $isSelected
                                        ? 'border-indigo-500 dark:border-indigo-400 ring-2 ring-indigo-500/20 dark:ring-indigo-400/20 shadow-md'
                                        : 'border-slate-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-600/50 hover:shadow-md' }}">

                            {{-- Card header: brand badge + stock indicator --}}
                            <div class="flex items-start justify-between px-4 pt-4 pb-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider
                                             bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/30 truncate max-w-[70%]">
                                    {{ $material->brand->name }}
                                </span>
                                <div class="relative flex h-2.5 w-2.5 mt-1 shrink-0">
                                    @if($totalStock <= 100)
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-500 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                                    @else
                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                    @endif
                                </div>
                            </div>

                            {{-- Card body: item name + unit --}}
                            <div class="px-4 pb-3">
                                <p class="font-black text-slate-800 dark:text-slate-100 text-base leading-tight truncate">
                                    {{ $material->name }}
                                </p>
                                <p class="text-[11px] font-medium text-slate-400 dark:text-slate-500 uppercase tracking-wider mt-0.5">
                                    {{ $material->unit }} &bull; {{ $material->stocks->count() }} {{ __('stock location(s)') }}
                                </p>
                                @if($material->creator)
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 flex items-center gap-1 truncate">
                                        <x-icon name="o-user" class="w-2.5 h-2.5 shrink-0" />
                                        {{ $material->creator->name }}
                                    </p>
                                @endif
                            </div>

                            {{-- Card footer: total stock + actions --}}
                            <div class="border-t border-slate-100 dark:border-slate-800 px-4 py-2.5 flex items-center justify-between">
                                <div class="flex items-baseline gap-1">
                                    <span class="font-mono font-black text-base {{ $totalStock <= 100 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-800 dark:text-slate-200' }}">
                                        {{ number_format($totalStock) }}
                                    </span>
                                    <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500">{{ $material->unit }}</span>
                                </div>
                                <div class="flex items-center gap-1" wire:click.stop>
                                    <a wire:navigate href="{{ route('admin.inventory.materials.edit', $material->id) }}"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:text-indigo-400 dark:hover:bg-indigo-500/10 transition-colors cursor-pointer"
                                        title="{{ __('Edit') }}">
                                        <x-icon name="o-pencil-square" class="w-3.5 h-3.5" />
                                    </a>
                                    <button wire:click="confirmDelete({{ $material->id }})"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:text-rose-400 dark:hover:bg-rose-500/10 transition-colors cursor-pointer"
                                        title="{{ __('Delete') }}">
                                        <x-icon name="o-trash" class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 xl:col-span-3 py-20 text-center">
                            <x-icon name="o-cube-transparent" class="w-14 h-14 mx-auto mb-3 text-slate-300 dark:text-slate-700" />
                            <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('No raw materials found.') }}</p>
                            <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">{{ __('Try adjusting your search or add a new material.') }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($materials->hasPages())
                    <div class="pt-2">
                        {{ $materials->links() }}
                    </div>
                @endif

            </div>

            {{-- ─── RIGHT: detail panel — only when a card is selected ── --}}
            @if($activeMaterial)
            <div class="w-80 xl:w-96 shrink-0 sticky top-20">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

                    @if($activeMaterial)

                        {{-- PANEL HEADER --}}
                        <div class="flex items-start justify-between bg-slate-50 dark:bg-slate-800/50 px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                            <div class="min-w-0 flex-1 pr-3">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 dark:text-indigo-400">{{ $activeMaterial->brand->name }}</p>
                                <h3 class="font-black text-slate-800 dark:text-slate-100 text-lg leading-tight truncate">
                                    {{ $activeMaterial->name }}
                                </h3>
                                <p class="text-xs font-medium text-slate-400 dark:text-slate-500 mt-0.5">{{ $activeMaterial->unit }}</p>
                                @if($activeMaterial->creator)
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 flex items-center gap-1">
                                        <x-icon name="o-user-circle" class="w-3 h-3 shrink-0" />
                                        {{ __('Added by') }}
                                        <span class="font-semibold text-slate-600 dark:text-slate-300 ml-1">{{ $activeMaterial->creator->name }}</span>
                                    </p>
                                @endif
                            </div>
                            <button wire:click="clearMaterial"
                                class="shrink-0 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200 dark:hover:text-slate-300 dark:hover:bg-slate-700 transition-colors cursor-pointer mt-0.5"
                                title="{{ __('Close') }}">
                                <x-icon name="o-x-mark" class="w-4 h-4" />
                            </button>
                        </div>

                        {{-- STOCK PER LOCATION TABLE --}}
                        @if($activeMaterial->stocks->isNotEmpty())
                            <div class="px-5 pt-3 pb-1 border-b border-slate-100 dark:border-slate-800">
                                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">{{ __('Stock by Location') }}</p>
                                <div class="space-y-1">
                                    @foreach($activeMaterial->stocks as $stock)
                                        <div class="flex items-center justify-between gap-2">
                                            <div class="min-w-0">
                                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $stock->color->name }}</span>
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500"> @ {{ $stock->lab->name }}</span>
                                            </div>
                                            <span class="font-mono text-xs font-bold {{ $stock->quantity <= 50 ? 'text-rose-500 dark:text-rose-400' : 'text-slate-700 dark:text-slate-200' }} shrink-0">
                                                {{ number_format($stock->quantity) }} {{ $activeMaterial->unit }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- STATS ROW --}}
                        <div class="grid grid-cols-3 divide-x divide-slate-100 dark:divide-slate-800 border-b border-slate-100 dark:border-slate-800">
                            <div class="px-4 py-3 text-center">
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-0.5">{{ __('IN') }}</p>
                                <p class="font-mono font-black text-emerald-600 dark:text-emerald-400 text-sm">+{{ number_format($totalIn) }}</p>
                            </div>
                            <div class="px-4 py-3 text-center">
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-0.5">{{ __('OUT') }}</p>
                                <p class="font-mono font-black text-rose-600 dark:text-rose-400 text-sm">-{{ number_format($totalOut) }}</p>
                            </div>
                            <div class="px-4 py-3 text-center">
                                @php
                                    $totalActivity = $totalIn + $totalOut;
                                    $usageRatio = $totalActivity > 0 ? round(($totalOut / $totalActivity) * 100) : 0;
                                @endphp
                                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-0.5">{{ __('USED') }}</p>
                                <p class="font-mono font-black text-indigo-600 dark:text-indigo-400 text-sm">{{ $usageRatio }}%</p>
                            </div>
                        </div>
                        <div class="px-5 py-2 border-b border-slate-100 dark:border-slate-800">
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                <div class="h-full rounded-full bg-indigo-500 dark:bg-indigo-400 transition-all duration-700" style="width: {{ $usageRatio }}%"></div>
                            </div>
                        </div>

                        {{-- SCROLLABLE CONTENT --}}
                        <div class="max-h-[calc(100vh-360px)] overflow-y-auto">

                            {{-- ADD STOCK toggle / form --}}
                            <div class="px-5 py-3 border-b border-slate-100 dark:border-slate-800">
                                @if(!$showRestockForm)
                                    <button wire:click="$toggle('showRestockForm')"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                                               bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400
                                               hover:bg-emerald-100 dark:hover:bg-emerald-500/20 border border-emerald-200 dark:border-emerald-500/30
                                               transition-colors cursor-pointer">
                                        <x-icon name="o-plus" class="w-4 h-4" />
                                        {{ __('Add Stock') }}
                                    </button>
                                @else
                                    {{-- INLINE RESTOCK FORM --}}
                                    <div class="animate-[fade-in_0.2s_ease-out]">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                                <x-icon name="o-arrow-down-tray" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                                                {{ __('Record Restock') }}
                                            </h4>
                                            <button wire:click="$set('showRestockForm', false)"
                                                class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors cursor-pointer">
                                                <x-icon name="o-x-mark" class="w-4 h-4" />
                                            </button>
                                        </div>
                                        <div class="text-[10px] font-medium text-slate-500 dark:text-slate-400 mb-3 flex items-center gap-1">
                                            <x-icon name="o-shield-check" class="w-3 h-3 text-emerald-500" />
                                            {{ __('Author:') }} <span class="font-bold text-slate-700 dark:text-slate-300">{{ Auth::user()->name }}</span>
                                        </div>
                                        <form wire:submit="processRestock" class="space-y-3">

                                            {{-- Color + Lab selectors --}}
                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('Color') }} <span class="text-rose-500">*</span></label>
                                                    <select wire:model="restockColorId" required
                                                        class="w-full text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-2
                                                               text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-shadow">
                                                        <option value="0">{{ __('Color...') }}</option>
                                                        @foreach($colorOptions as $opt)
                                                            <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('restockColorId') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('Lab') }} <span class="text-rose-500">*</span></label>
                                                    <select wire:model="restockLabId" required
                                                        class="w-full text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-2
                                                               text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-shadow">
                                                        <option value="0">{{ __('Lab...') }}</option>
                                                        @foreach($labOptions as $opt)
                                                            <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('restockLabId') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('Qty') }} <span class="text-rose-500">*</span></label>
                                                    <div class="relative">
                                                        <input type="number" wire:model="restockQty" min="1" required placeholder="0"
                                                            class="w-full text-sm font-mono bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg pl-2.5 pr-10 py-2
                                                                   text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-shadow" />
                                                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-slate-400 text-[10px] font-semibold">
                                                            {{ $activeMaterial->unit }}
                                                        </div>
                                                    </div>
                                                    @error('restockQty') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('Amount') }} <span class="text-rose-500">*</span></label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 left-0 flex items-center px-2 pointer-events-none text-slate-400 text-[10px] font-semibold">Rp</div>
                                                        <input type="number" wire:model="restockAmount" min="1" required placeholder="0"
                                                            class="w-full text-sm font-mono bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg pl-7 pr-2.5 py-2
                                                                   text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-shadow" />
                                                    </div>
                                                    @error('restockAmount') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('Title') }} <span class="text-rose-500">*</span></label>
                                                <input type="text" wire:model="restockTitle" required placeholder="{{ __('e.g. Restock - Tokopedia') }}"
                                                    class="w-full text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2.5 py-2
                                                           text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-shadow" />
                                                @error('restockTitle') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('Notes / Supplier') }} <span class="text-rose-500">*</span></label>
                                                <input type="text" wire:model="restockNotes" required placeholder="{{ __('Supplier name or notes') }}"
                                                    class="w-full text-sm bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2.5 py-2
                                                           text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-shadow" />
                                                @error('restockNotes') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('Payment Proof') }} <span class="text-rose-500">*</span></label>
                                                <input type="file" wire:model="paymentProof" accept="image/jpeg,image/png,application/pdf" required
                                                    class="block w-full text-xs text-slate-600 dark:text-slate-400
                                                           file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                                           file:text-xs file:font-semibold file:bg-emerald-50 dark:file:bg-emerald-500/10 file:text-emerald-700 dark:file:text-emerald-400
                                                           hover:file:bg-emerald-100 transition-colors
                                                           bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer
                                                           focus:outline-none focus:ring-2 focus:ring-indigo-500/30" />
                                                <p class="text-[10px] text-slate-400 mt-1">{{ __('JPG, PNG, PDF · max 5 MB') }}</p>
                                                @error('paymentProof') <span class="text-[10px] text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                                            </div>
                                            <button type="submit"
                                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                                                       bg-emerald-600 dark:bg-emerald-500 hover:bg-emerald-700 dark:hover:bg-emerald-400 text-white shadow-sm transition-colors cursor-pointer">
                                                <span wire:loading.remove wire:target="processRestock">{{ __('Confirm Restock') }}</span>
                                                <span wire:loading wire:target="processRestock" class="flex items-center gap-2">
                                                    <x-icon name="o-arrow-path" class="w-4 h-4 animate-spin" /> {{ __('Processing...') }}
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            {{-- MOVEMENT FEED --}}
                            <div class="px-5 py-4">
                                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">{{ __('Movement Feed') }}</p>
                                <div class="space-y-3">
                                    @forelse($activeMaterial->movements as $log)
                                        <div class="flex gap-3">
                                            {{-- Icon --}}
                                            <div class="shrink-0 mt-0.5">
                                                @if($log->type === 'in')
                                                    <div class="w-7 h-7 rounded-full bg-emerald-100 dark:bg-emerald-500/20 border border-emerald-200 dark:border-emerald-500/30 flex items-center justify-center">
                                                        <x-icon name="o-arrow-down-left" class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" />
                                                    </div>
                                                @else
                                                    <div class="w-7 h-7 rounded-full bg-rose-100 dark:bg-rose-500/20 border border-rose-200 dark:border-rose-500/30 flex items-center justify-center">
                                                        <x-icon name="o-arrow-up-right" class="w-3.5 h-3.5 text-rose-600 dark:text-rose-400" />
                                                    </div>
                                                @endif
                                            </div>
                                            {{-- Content --}}
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-mono font-black text-sm {{ $log->type === 'in' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                                        {{ $log->type === 'in' ? '+' : '-' }}{{ number_format($log->quantity) }}
                                                        <span class="text-[10px] font-medium opacity-70">{{ $activeMaterial->unit }}</span>
                                                    </span>
                                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">
                                                        {{ $log->created_at->format('d M y') }}
                                                    </span>
                                                </div>
                                                @if($log->notes)
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed truncate">{{ $log->notes }}</p>
                                                @endif
                                                {{-- References --}}
                                                <div class="flex flex-wrap gap-1 mt-1">
                                                    @if($log->reimbursement)
                                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-semibold
                                                                     bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20">
                                                            <x-icon name="o-banknotes" class="w-2.5 h-2.5" />
                                                            Rp {{ number_format($log->reimbursement->total_amount, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                    @if($log->service_booking_id)
                                                        <a href="{{ route('admin.order-center') }}"
                                                            class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-semibold
                                                                   bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20 hover:bg-amber-100 transition-colors">
                                                            <x-icon name="o-shopping-bag" class="w-2.5 h-2.5" />
                                                            #{{ str_pad($log->service_booking_id, 4, '0', STR_PAD_LEFT) }}
                                                        </a>
                                                    @endif
                                                    @if($log->creator)
                                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-medium
                                                                     bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                                            <x-icon name="o-user" class="w-2.5 h-2.5 shrink-0" />
                                                            {{ $log->type === 'in' ? __('Restocked by') : __('Used by') }}
                                                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $log->creator->name }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-8 text-center">
                                            <x-icon name="o-document-text" class="w-8 h-8 mx-auto mb-2 text-slate-300 dark:text-slate-700" />
                                            <p class="text-xs text-slate-400 dark:text-slate-500">{{ __('No movements yet.') }}</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>{{-- end scrollable --}}

                    @endif
                </div>
            </div>{{-- end right panel --}}
            @endif{{-- end activeMaterial guard --}}

        </div>{{-- end POS flex --}}

        {{-- DELETE MODAL --}}
        <x-modal wire:model="deleteModalOpen" class="backdrop-blur-sm">
            <div class="bg-base-100 dark:bg-[#031026] border border-base-200 dark:border-[#0A3D7A]/50 p-6 rounded-2xl shadow-xl max-w-md mx-auto">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-error/10 flex items-center justify-center shrink-0 border border-error/30">
                        <x-icon name="o-exclamation-triangle" class="w-6 h-6 text-error" />
                    </div>
                    <h3 class="text-xl font-bold text-base-content dark:text-[#F8FAFC]">{{ __('Confirm Deletion') }}</h3>
                </div>
                <p class="text-sm text-base-content/60 dark:text-[#94A3B8] leading-relaxed mb-6">
                    {{ __('Are you sure you want to permanently delete this material? This will also remove all linked stock entries.') }}
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="$wire.deleteModalOpen = false"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-base-content/60 hover:bg-base-200/50 transition-colors cursor-pointer">
                        {{ __('Cancel') }}
                    </button>
                    <button wire:click="deleteRecord"
                        class="px-4 py-2 rounded-lg text-sm font-medium bg-error hover:bg-error/90 text-white shadow-sm flex items-center gap-2 cursor-pointer">
                        <span wire:loading.remove wire:target="deleteRecord">{{ __('Yes, Delete') }}</span>
                        <span wire:loading wire:target="deleteRecord"><x-icon name="o-arrow-path" class="w-4 h-4 animate-spin" /></span>
                    </button>
                </div>
            </div>
        </x-modal>

    {{-- ============================================ --}}
    {{-- LOOKUP SECTIONS — CARD GRID                   --}}
    {{-- ============================================ --}}
    @else

        @php
            $relLabels = [
                'rawMaterials' => 'Materials',
                'inventories'  => 'Inventories',
                'itemStocks'   => 'Stock Entries',
                'brands'       => 'Brands',
            ];
        @endphp

        {{-- SUB-HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                    <x-icon name="{{ $lookupConfig['icon'] }}" class="w-4 h-4 text-indigo-500 dark:text-indigo-400" />
                    {{ __($lookupConfig['label']) }}
                    @if($records->total() > 0)
                        <span class="text-xs font-medium text-slate-400 dark:text-slate-500">({{ $records->total() }})</span>
                    @endif
                </h2>
            </div>
            <button wire:click="createLookup"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold
                       bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-400 text-white shadow-sm transition-colors cursor-pointer shrink-0">
                <x-icon name="o-plus" class="w-4 h-4" />
                {{ __('Add :label', ['label' => __($lookupConfig['label'])]) }}
            </button>
        </div>

        {{-- POS LAYOUT: left card grid + right detail panel --}}
        <div class="flex gap-5 items-start">

            {{-- ─── LEFT: card grid ─────────────────────── --}}
            <div class="{{ $activeLookup ? 'flex-1 min-w-0' : 'w-full' }} space-y-4">

                {{-- SEARCH --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-icon name="o-magnifying-glass" class="w-4 h-4 text-slate-400 dark:text-slate-500" />
                    </div>
                    <input type="text" wire:model.live.debounce.400ms="lookupSearch"
                        placeholder="{{ __('Search :label...', ['label' => strtolower(__($lookupConfig['label']))]) }}"
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700
                               text-sm text-slate-700 dark:text-slate-300 placeholder-slate-400
                               focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 transition-shadow shadow-sm" />
                </div>

                {{-- CARD GRID --}}
                <div class="{{ $activeLookup ? 'grid-cols-2 xl:grid-cols-3' : 'grid-cols-2 md:grid-cols-3 xl:grid-cols-4' }} grid gap-4">
                    @forelse($records as $record)
                        @php $isSelected = $activeLookupId === $record->id; @endphp
                        <div wire:click="viewLookup({{ $record->id }})"
                            wire:key="lookup-{{ $section }}-{{ $record->id }}"
                            class="group bg-white dark:bg-slate-900 rounded-2xl border transition-all duration-150 cursor-pointer p-5 shadow-sm
                                   {{ $isSelected
                                        ? 'border-indigo-500 dark:border-indigo-400 ring-2 ring-indigo-500/20 dark:ring-indigo-400/20 shadow-md'
                                        : 'border-slate-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-600/50 hover:shadow-md' }}">

                            {{-- Name --}}
                            <p class="font-bold text-slate-800 dark:text-slate-100 text-base mb-3 truncate" title="{{ $record->name }}">
                                {{ $record->name }}
                            </p>

                            {{-- Usage badges --}}
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                @foreach($lookupConfig['relations'] as $relation)
                                    @php
                                        $countKey = \Illuminate\Support\Str::snake($relation) . '_count';
                                        $count = $record->{$countKey} ?? 0;
                                        $label = $relLabels[$relation] ?? ucfirst($relation);
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold
                                                 {{ $count > 0
                                                    ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
                                                    : 'bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500' }}">
                                        {{ $count }} {{ __($label) }}
                                    </span>
                                @endforeach
                            </div>

                            {{-- Created + actions --}}
                            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800" wire:click.stop>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500">{{ $record->created_at->format('d M Y') }}</span>
                                <div class="flex items-center gap-1">
                                    <button wire:click="editLookup({{ $record->id }})"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:text-indigo-400 dark:hover:bg-indigo-500/10 transition-colors cursor-pointer"
                                        title="{{ __('Edit') }}">
                                        <x-icon name="o-pencil-square" class="w-3.5 h-3.5" />
                                    </button>
                                    <button wire:click="confirmDeleteLookup({{ $record->id }})"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:text-rose-400 dark:hover:bg-rose-500/10 transition-colors cursor-pointer"
                                        title="{{ __('Delete') }}">
                                        <x-icon name="o-trash" class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 md:col-span-3 xl:col-span-4 py-20 text-center">
                            <x-icon name="{{ $lookupConfig['icon'] }}" class="w-14 h-14 mx-auto mb-3 text-slate-300 dark:text-slate-700" />
                            <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('No :label found.', ['label' => strtolower(__($lookupConfig['label']))]) }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                @if($records->hasPages())
                    <div class="pt-2">
                        {{ $records->links() }}
                    </div>
                @endif

            </div>{{-- end left --}}

            {{-- ─── RIGHT: detail panel — only when a card is selected ─── --}}
            @if($activeLookup)
            <div class="w-80 xl:w-96 shrink-0 sticky top-20">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

                    {{-- PANEL HEADER --}}
                    <div class="flex items-start justify-between bg-slate-50 dark:bg-slate-800/50 px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                        <div class="min-w-0 flex-1 pr-3">
                            @if($section === 'brands' && $activeLookup->materialCategory)
                                <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-0.5">
                                    {{ $activeLookup->materialCategory->name }}
                                </p>
                            @elseif($section === 'labs')
                                <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-0.5">
                                    {{ __('Lab') }}
                                </p>
                            @elseif($section === 'categories')
                                <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-0.5">
                                    {{ __('Category') }}
                                </p>
                            @elseif($section === 'colors')
                                <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-0.5">
                                    {{ __('Color') }}
                                </p>
                            @endif
                            <h3 class="font-black text-slate-800 dark:text-slate-100 text-lg leading-tight truncate">
                                {{ $activeLookup->name }}
                            </h3>
                        </div>
                        <button wire:click="clearLookup"
                            class="shrink-0 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200 dark:hover:text-slate-300 dark:hover:bg-slate-700 transition-colors cursor-pointer mt-0.5"
                            title="{{ __('Close') }}">
                            <x-icon name="o-x-mark" class="w-4 h-4" />
                        </button>
                    </div>

                    {{-- SCROLLABLE CONTENT --}}
                    <div class="max-h-[calc(100vh-300px)] overflow-y-auto px-5 py-4 space-y-5">

                        @if($section === 'labs')

                            {{-- Stock Items --}}
                            @if($activeLookup->itemStocks->isNotEmpty())
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">{{ __('Stock Items') }}</p>
                                    <div class="space-y-1">
                                        @foreach($activeLookup->itemStocks as $stock)
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-xs text-slate-600 dark:text-slate-300 truncate">
                                                    <span class="font-semibold">{{ $stock->rawMaterial->brand->name }}</span>
                                                    — {{ $stock->rawMaterial->name }}
                                                    <span class="text-slate-400 dark:text-slate-500">({{ $stock->color->name }})</span>
                                                </span>
                                                <span class="font-mono text-xs font-bold text-slate-700 dark:text-slate-200 shrink-0">
                                                    {{ number_format($stock->quantity) }} {{ $stock->rawMaterial->unit }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <p class="text-xs text-slate-400 dark:text-slate-500 italic">{{ __('No raw material stock in this lab.') }}</p>
                            @endif

                            {{-- Inventories --}}
                            @if($activeLookup->inventories->isNotEmpty())
                                <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">{{ __('Inventories') }}</p>
                                    <div class="space-y-1">
                                        @foreach($activeLookup->inventories as $inv)
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-xs text-slate-600 dark:text-slate-300 truncate">
                                                    {{ $inv->name }}
                                                    @if($inv->brand)<span class="text-slate-400 dark:text-slate-500"> ({{ $inv->brand->name }})</span>@endif
                                                </span>
                                                <span class="font-mono text-xs font-bold text-slate-700 dark:text-slate-200 shrink-0">
                                                    {{ $inv->available_quantity }}/{{ $inv->total_quantity }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        @elseif($section === 'categories')

                            @if($activeLookup->brands->isNotEmpty())
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">{{ __('Brands') }}</p>
                                    <div class="space-y-1">
                                        @foreach($activeLookup->brands as $brand)
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $brand->name }}</span>
                                                <span class="font-mono text-xs text-slate-400 dark:text-slate-500 shrink-0">
                                                    {{ $brand->rawMaterials->count() }} {{ __('items') }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <p class="text-xs text-slate-400 dark:text-slate-500 italic">{{ __('No brands in this category.') }}</p>
                            @endif

                        @elseif($section === 'brands')

                            {{-- Colors --}}
                            @if($activeLookup->colors->isNotEmpty())
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">{{ __('Supported Colors') }}</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($activeLookup->colors as $color)
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold
                                                         bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400
                                                         border border-indigo-200 dark:border-indigo-500/30">
                                                {{ $color->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Items --}}
                            @if($activeLookup->rawMaterials->isNotEmpty())
                                <div class="{{ $activeLookup->colors->isNotEmpty() ? 'border-t border-slate-100 dark:border-slate-800 pt-4' : '' }}">
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">{{ __('Items') }}</p>
                                    <div class="space-y-1">
                                        @foreach($activeLookup->rawMaterials as $mat)
                                            @php $matTotal = $mat->stocks->sum('quantity'); @endphp
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-xs text-slate-600 dark:text-slate-300 truncate">{{ $mat->name }}</span>
                                                <span class="font-mono text-xs font-bold {{ $matTotal <= 100 ? 'text-rose-500 dark:text-rose-400' : 'text-slate-700 dark:text-slate-200' }} shrink-0">
                                                    {{ number_format($matTotal) }} {{ $mat->unit }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <p class="text-xs text-slate-400 dark:text-slate-500 italic">{{ __('No items for this brand.') }}</p>
                            @endif

                        @elseif($section === 'colors')

                            @if($activeLookup->itemStocks->isNotEmpty())
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">{{ __('Stock Entries') }}</p>
                                    <div class="space-y-1">
                                        @foreach($activeLookup->itemStocks as $stock)
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-xs text-slate-600 dark:text-slate-300 truncate">
                                                    <span class="font-semibold">{{ $stock->rawMaterial->brand->name }}</span>
                                                    — {{ $stock->rawMaterial->name }}
                                                    <span class="text-slate-400 dark:text-slate-500">@ {{ $stock->lab->name }}</span>
                                                </span>
                                                <span class="font-mono text-xs font-bold text-slate-700 dark:text-slate-200 shrink-0">
                                                    {{ number_format($stock->quantity) }} {{ $stock->rawMaterial->unit }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <p class="text-xs text-slate-400 dark:text-slate-500 italic">{{ __('No stock entries for this color.') }}</p>
                            @endif

                        @endif

                    </div>{{-- end scrollable --}}

                </div>
            </div>{{-- end right panel --}}
            @endif{{-- end activeLookup guard --}}

        </div>{{-- end POS flex --}}

        {{-- CREATE / EDIT MODAL --}}
        <x-modal wire:model="lookupFormModal" class="backdrop-blur-sm">
            <div class="bg-base-100 dark:bg-[#031026] border border-base-200 dark:border-[#0A3D7A]/50 p-6 rounded-2xl shadow-xl max-w-md mx-auto">
                <h3 class="text-xl font-bold text-base-content dark:text-[#F8FAFC] mb-5">
                    {{ $lookupEditingId ? __('Edit :label', ['label' => __($lookupConfig['label'])]) : __('Add :label', ['label' => __($lookupConfig['label'])]) }}
                </h3>
                <form wire:submit="saveLookup" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                            {{ __('Name') }} <span class="text-error dark:text-[#EF4444]">*</span>
                        </label>
                        <input type="text" wire:model="lookupName" required
                            placeholder="{{ __('Enter :label name...', ['label' => strtolower(__($lookupConfig['label']))]) }}"
                            class="w-full px-3 py-2.5 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                   text-base-content dark:text-[#F8FAFC] placeholder-slate-400 shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 transition-shadow" />
                        @error('lookupName') <span class="block text-xs text-error mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    @if($section === 'brands')
                        @isset($categoryOptions)
                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Category') }}</label>
                            <select wire:model="brandCategoryId"
                                class="w-full px-3 py-2.5 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                       text-base-content dark:text-[#F8FAFC] shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 transition-shadow">
                                <option value="0">{{ __('No category') }}</option>
                                @foreach($categoryOptions as $opt)
                                    <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Supported Colors --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Supported Colors') }}</label>
                            <div class="grid grid-cols-2 gap-1 max-h-44 overflow-y-auto rounded-lg border border-slate-200 dark:border-slate-700 p-3 bg-slate-50 dark:bg-slate-900">
                                @foreach($colorOptions as $opt)
                                    <label class="flex items-center gap-2 text-sm cursor-pointer py-0.5">
                                        <input type="checkbox" wire:model="brandColorIds" value="{{ $opt->id }}"
                                            class="rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0 bg-white dark:bg-slate-800" />
                                        <span class="text-slate-700 dark:text-slate-300">{{ $opt->name }}</span>
                                    </label>
                                @endforeach
                                @if($colorOptions->isEmpty())
                                    <p class="col-span-2 text-xs text-slate-400 dark:text-slate-500 text-center py-2">{{ __('No colors defined yet.') }}</p>
                                @endif
                            </div>
                        </div>
                        @endisset
                    @endif

                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-800">
                        <button type="button" @click="$wire.lookupFormModal = false"
                            class="px-5 py-2.5 rounded-lg text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-sm font-semibold
                                   bg-primary dark:bg-[#00426D] hover:bg-primary/80 dark:hover:bg-[#0A3D7A] text-primary-content dark:text-[#F8FAFC] shadow-sm transition-colors cursor-pointer">
                            <span wire:loading.remove wire:target="saveLookup">{{ $lookupEditingId ? __('Update') : __('Save') }}</span>
                            <span wire:loading wire:target="saveLookup" class="flex items-center gap-2">
                                <x-icon name="o-arrow-path" class="w-4 h-4 animate-spin" /> {{ __('Saving...') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>

        {{-- DELETE MODAL --}}
        <x-modal wire:model="lookupDeleteModal" class="backdrop-blur-sm">
            <div class="bg-base-100 dark:bg-[#031026] border border-base-200 dark:border-[#0A3D7A]/50 p-6 rounded-2xl shadow-xl max-w-md mx-auto">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-error/10 flex items-center justify-center shrink-0 border border-error/30">
                        <x-icon name="o-exclamation-triangle" class="w-6 h-6 text-error" />
                    </div>
                    <h3 class="text-xl font-bold text-base-content dark:text-[#F8FAFC]">{{ __('Confirm Deletion') }}</h3>
                </div>
                <p class="text-sm text-base-content/60 dark:text-[#94A3B8] leading-relaxed mb-6">
                    {{ __('This record will be deleted permanently. Deletion is blocked if it is still linked to any materials or inventories.') }}
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="$wire.lookupDeleteModal = false"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-base-content/60 hover:bg-base-200/50 transition-colors cursor-pointer">
                        {{ __('Cancel') }}
                    </button>
                    <button wire:click="deleteLookupRecord"
                        class="px-4 py-2 rounded-lg text-sm font-semibold bg-error hover:bg-error/80 text-white transition-colors cursor-pointer">
                        <span wire:loading.remove wire:target="deleteLookupRecord">{{ __('Delete') }}</span>
                        <span wire:loading wire:target="deleteLookupRecord">{{ __('Deleting...') }}</span>
                    </button>
                </div>
            </div>
        </x-modal>

    @endif

</div>
