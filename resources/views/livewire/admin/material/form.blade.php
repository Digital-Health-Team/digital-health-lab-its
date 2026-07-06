<div class="min-h-screen bg-slate-50 dark:bg-slate-950 animate-[fade-in_0.3s_ease-out]">

    {{-- PAGE HEADER --}}
    <div class="border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 sticky top-0 z-10">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center gap-4">
            <a wire:navigate href="{{ route('admin.inventory') }}"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <x-icon name="o-arrow-left" class="w-5 h-5" />
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">
                    {{ $material ? __('Edit Material') : __('Tambah Material Baru') }}
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    {{ __('Define the item (brand + name + unit). Stock quantities are recorded per color and lab via the detail panel.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="max-w-5xl mx-auto px-6 py-8">
        <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ── LEFT COLUMN: form fields ─────────────────────── --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- SECTION 1: Item Definition --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 space-y-5">
                    <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h2 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-2">
                            <x-icon name="o-cube-transparent" class="w-4 h-4 text-indigo-500" />
                            {{ __('Item Definition') }}
                        </h2>
                    </div>

                    {{-- Category (optional, filters brands) --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ __('Category') }}
                            <span class="text-xs font-normal text-slate-400 ml-1">{{ __('(optional — filters brand list)') }}</span>
                        </label>
                        <select wire:model.live="categoryId"
                            class="w-full px-3 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                   text-slate-700 dark:text-slate-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow">
                            <option value="0">{{ __('All categories...') }}</option>
                            @foreach($categoryOptions as $opt)
                                <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Brand --}}
                    <div x-data="{ adding: false, newBrand: '' }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                {{ __('Brand') }} <span class="text-rose-500">*</span>
                            </label>
                            <button type="button" @click="adding = !adding"
                                class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline cursor-pointer">
                                <span x-show="!adding">+ {{ __('New') }}</span>
                                <span x-show="adding" x-cloak class="text-slate-400">{{ __('Cancel') }}</span>
                            </button>
                        </div>
                        <select wire:model.live="brandId" required
                            class="w-full px-3 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                   text-slate-700 dark:text-slate-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow">
                            <option value="0">{{ __('Select Brand...') }}</option>
                            @foreach($brandOptions as $opt)
                                <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                            @endforeach
                        </select>
                        <div x-show="adding" x-cloak x-transition class="mt-2 flex gap-2 items-center">
                            <input type="text" x-model="newBrand"
                                placeholder="{{ __('New brand name...') }}"
                                @keydown.enter.prevent="$wire.quickCreateBrand(newBrand).then(() => { adding = false; newBrand = ''; })"
                                class="flex-1 px-2.5 py-1.5 text-sm rounded-lg border border-indigo-300 dark:border-indigo-600
                                       bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500/30" />
                            <button type="button"
                                @click="$wire.quickCreateBrand(newBrand).then(() => { adding = false; newBrand = ''; })"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white cursor-pointer transition-colors">
                                {{ __('Add') }}
                            </button>
                            <button type="button" @click="adding = false; newBrand = ''"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                                <x-icon name="o-x-mark" class="w-4 h-4" />
                            </button>
                        </div>
                        @error('brandId') <span class="block text-xs text-rose-500 mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    {{-- Item Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ __('Item Name') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" wire:model="name" required
                            placeholder="{{ __('e.g. PLA+ 1.75mm 1kg') }}"
                            class="w-full px-3 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                   text-slate-700 dark:text-slate-200 placeholder-slate-400 shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow" />
                        @error('name') <span class="block text-xs text-rose-500 mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    {{-- Unit --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            {{ __('Unit of Measure') }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" wire:model="unit" list="unit-datalist" required
                            placeholder="{{ __('e.g. gram, ml, pcs') }}"
                            class="w-full px-3 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                   text-slate-700 dark:text-slate-200 placeholder-slate-400 shadow-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow" />
                        <datalist id="unit-datalist">
                            @foreach($unitOptions as $u) <option value="{{ $u }}"> @endforeach
                        </datalist>
                        @error('unit') <span class="block text-xs text-rose-500 mt-1.5">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- SECTION 2: Initial Stock (create only) --}}
                @if(!$material)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 space-y-5">
                    <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h2 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-2">
                            <x-icon name="o-archive-box" class="w-4 h-4 text-emerald-500" />
                            {{ __('Initial Stock') }}
                            <span class="text-xs font-normal text-slate-400 normal-case tracking-normal ml-1">{{ __('(optional)') }}</span>
                        </h2>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                            {{ __('Set a starting quantity for one location. Leave blank to add stock later via the detail panel.') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Color --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Color') }}</label>
                            @if($brandId && $colorOptions->isNotEmpty())
                                <select wire:model="colorId"
                                    class="w-full px-3 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                           text-slate-700 dark:text-slate-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow">
                                    <option value="0">{{ __('Select color...') }}</option>
                                    @foreach($colorOptions as $opt)
                                        <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            @elseif($brandId && $colorOptions->isEmpty())
                                <div class="w-full px-3 py-2.5 rounded-xl text-sm border border-dashed border-slate-300 dark:border-slate-700 text-slate-400 dark:text-slate-500">
                                    {{ __('No colors assigned to this brand yet.') }}
                                </div>
                            @else
                                <div class="w-full px-3 py-2.5 rounded-xl text-sm border border-dashed border-slate-300 dark:border-slate-700 text-slate-400 dark:text-slate-500">
                                    {{ __('Select a brand first.') }}
                                </div>
                            @endif
                            @error('colorId') <span class="block text-xs text-rose-500 mt-1.5">{{ $message }}</span> @enderror
                        </div>

                        {{-- Lab --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Lab') }}</label>
                            <select wire:model="labId"
                                class="w-full px-3 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                       text-slate-700 dark:text-slate-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow">
                                <option value="0">{{ __('Select lab...') }}</option>
                                @foreach($labOptions as $opt)
                                    <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                                @endforeach
                            </select>
                            @error('labId') <span class="block text-xs text-rose-500 mt-1.5">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Initial Quantity') }}</label>
                        <div class="relative max-w-xs">
                            <input type="number" wire:model="initialQty" min="1" placeholder="0"
                                class="w-full px-3 py-2.5 rounded-xl text-sm font-mono bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                       text-slate-700 dark:text-slate-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow pr-16" />
                            @if($unit)
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400 text-xs font-semibold">
                                    {{ $unit }}
                                </div>
                            @endif
                        </div>
                        @error('initialQty') <span class="block text-xs text-rose-500 mt-1.5">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif

                {{-- FORM ACTIONS --}}
                <div class="flex items-center justify-between pt-2">
                    <a wire:navigate href="{{ route('admin.inventory') }}"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400
                               hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold
                               bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400 text-white shadow-sm transition-colors cursor-pointer">
                        <span wire:loading.remove wire:target="save">
                            {{ $material ? __('Simpan Perubahan') : __('Simpan Material') }}
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <x-icon name="o-arrow-path" class="w-4 h-4 animate-spin" />
                            {{ __('Menyimpan...') }}
                        </span>
                    </button>
                </div>
            </div>

            {{-- ── RIGHT COLUMN: info sidebar ───────────────────── --}}
            <div class="space-y-4">

                {{-- Schema info card --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">{{ __('How it works') }}</h3>
                    <ul class="space-y-3">
                        <li class="flex gap-2.5 text-xs text-slate-500 dark:text-slate-400">
                            <div class="shrink-0 w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center mt-0.5">
                                <span class="text-[9px] font-black text-indigo-600 dark:text-indigo-400">1</span>
                            </div>
                            <span>{{ __('Category → Brand → Item. A brand belongs to a category; an item belongs to a brand.') }}</span>
                        </li>
                        <li class="flex gap-2.5 text-xs text-slate-500 dark:text-slate-400">
                            <div class="shrink-0 w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center mt-0.5">
                                <span class="text-[9px] font-black text-emerald-600 dark:text-emerald-400">2</span>
                            </div>
                            <span>{{ __('Stock is tracked per Color × Lab combination. The same item can exist in multiple labs and multiple colors.') }}</span>
                        </li>
                        <li class="flex gap-2.5 text-xs text-slate-500 dark:text-slate-400">
                            <div class="shrink-0 w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center mt-0.5">
                                <span class="text-[9px] font-black text-amber-600 dark:text-amber-400">3</span>
                            </div>
                            <span>{{ __('Colors available for the initial stock depend on which colors are assigned to the brand in the Brands tab.') }}</span>
                        </li>
                    </ul>
                </div>

                {{-- Current state preview (edit mode) --}}
                @if($material)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">{{ __('Current State') }}</h3>
                    <p class="text-xs font-semibold text-indigo-500 dark:text-indigo-400 truncate">{{ $material->brand->name }}</p>
                    <p class="text-sm font-black text-slate-800 dark:text-slate-100 mt-0.5">{{ $material->name }}</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $material->unit }}</p>
                    <div class="mt-3 border-t border-slate-100 dark:border-slate-800 pt-3">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">{{ __('Stock') }}</p>
                        @forelse($material->stocks()->with(['color','lab'])->get() as $stock)
                            <div class="flex items-center justify-between text-xs py-0.5">
                                <span class="text-slate-600 dark:text-slate-300">{{ $stock->color->name }} @ {{ $stock->lab->name }}</span>
                                <span class="font-mono font-bold text-slate-700 dark:text-slate-200">{{ number_format($stock->quantity) }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 dark:text-slate-500 italic">{{ __('No stock recorded yet.') }}</p>
                        @endforelse
                    </div>
                </div>
                @endif

            </div>

        </form>
    </div>

</div>
