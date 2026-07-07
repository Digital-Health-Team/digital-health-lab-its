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
                        @include('livewire.admin.material.partials.field-toolbar', [
                            'entity' => 'category',
                            'label' => __('Category'),
                            'hint' => __('(optional — filters brand list)'),
                            'placeholder' => __('New category name...'),
                        ])
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
                    <div>
                        @include('livewire.admin.material.partials.field-toolbar', [
                            'entity' => 'brand',
                            'label' => __('Brand'),
                            'required' => true,
                            'placeholder' => __('New brand name...'),
                        ])
                        <select wire:model.live="brandId" required
                            class="w-full px-3 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                   text-slate-700 dark:text-slate-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow">
                            <option value="0">{{ __('Select Brand...') }}</option>
                            @foreach($brandOptions as $opt)
                                <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                            @endforeach
                        </select>
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
                            {{ __('Pick a lab, select colors, then fill in each color\'s details. Leave empty to add stock later via the detail panel.') }}
                        </p>
                    </div>

                    {{-- Lab (applies to the whole batch) --}}
                    <div class="max-w-xs">
                        @include('livewire.admin.material.partials.field-toolbar', [
                            'entity' => 'lab',
                            'label' => __('Lab'),
                            'placeholder' => __('New lab name...'),
                        ])
                        <select wire:model="labId"
                            class="w-full px-3 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                   text-slate-700 dark:text-slate-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow">
                            <option value="">{{ __('Select lab...') }}</option>
                            @foreach($labOptions as $opt)
                                <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                            @endforeach
                        </select>
                        @error('labId') <span class="block text-xs text-rose-500 mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    {{-- Color palette --}}
                    <div x-data="{ q: '' }">
                        @include('livewire.admin.material.partials.field-toolbar', [
                            'entity' => 'color',
                            'label' => __('Colors'),
                            'placeholder' => __('New color name...'),
                            'withHex' => true,
                        ])
                        @if($colorOptions->isNotEmpty())
                            <div class="relative mb-2">
                                <x-icon name="o-magnifying-glass" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
                                <input type="text" x-model="q" placeholder="{{ __('Filter colors...') }}"
                                    class="w-full pl-9 pr-3 py-2 rounded-xl text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                           text-slate-700 dark:text-slate-200 placeholder-slate-400 shadow-sm
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow" />
                            </div>
                            <div class="flex flex-wrap gap-1.5 max-h-56 overflow-y-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-950/40 p-2.5">
                                @foreach($colorOptions as $opt)
                                    @php $checked = in_array((string) $opt->id, array_map('strval', $colorIds), true); @endphp
                                    <label wire:key="color-chip-{{ $opt->id }}"
                                        x-show="q === '' || {{ \Illuminate\Support\Js::from(mb_strtolower($opt->name)) }}.includes(q.toLowerCase())"
                                        class="inline-flex items-center gap-1.5 pl-2 pr-2.5 py-1.5 rounded-full border text-xs font-medium cursor-pointer select-none transition-colors
                                               {{ $checked
                                                    ? 'border-indigo-400 dark:border-indigo-500 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 ring-1 ring-indigo-400/40'
                                                    : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:border-indigo-300 dark:hover:border-indigo-600' }}">
                                        <input type="checkbox" wire:model.live="colorIds" value="{{ $opt->id }}" class="sr-only" />
                                        @if($opt->hex)
                                            <span class="shrink-0 w-3.5 h-3.5 rounded-full border border-slate-300/60 dark:border-slate-600/60"
                                                style="background-color: {{ $opt->hex }}"></span>
                                        @else
                                            <span class="shrink-0 w-3.5 h-3.5 rounded-full border border-dashed border-slate-400 dark:border-slate-500"></span>
                                        @endif
                                        {{ $opt->name }}
                                        @if($checked)
                                            <x-icon name="o-check" class="w-3 h-3" />
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">
                                {{ __('Selected colors are linked to the brand automatically.') }}
                            </p>
                        @else
                            <div class="w-full px-3 py-2.5 rounded-xl text-sm border border-dashed border-slate-300 dark:border-slate-700 text-slate-400 dark:text-slate-500">
                                {{ __('No colors yet — create one with + New.') }}
                            </div>
                        @endif
                        @error('colorIds') <span class="block text-xs text-rose-500 mt-1.5">{{ $message }}</span> @enderror
                        @error('colorIds.*') <span class="block text-xs text-rose-500 mt-1.5">{{ $message }}</span> @enderror
                    </div>

                    {{-- Stock details per selected color --}}
                    @php $selectedColors = $colorOptions->filter(fn ($c) => in_array((string) $c->id, array_map('strval', $colorIds), true)); @endphp
                    @if($selectedColors->isNotEmpty())
                        <div class="rounded-xl border border-slate-200 dark:border-slate-800 divide-y divide-slate-100 dark:divide-slate-800 overflow-hidden">
                            <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-950/40 flex flex-wrap items-center justify-between gap-x-4 gap-y-1">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    {{ __('Stock Details') }} · {{ $selectedColors->count() }}
                                </p>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                    {{ __('Amount, notes & payment proof are required for colors with a quantity.') }}
                                </p>
                            </div>
                            @foreach($selectedColors as $opt)
                                <div wire:key="color-entry-{{ $opt->id }}" class="px-4 py-4 space-y-3">
                                    <div class="flex items-center gap-2.5">
                                        @if($opt->hex)
                                            <span class="shrink-0 w-4 h-4 rounded-full border border-slate-300/60 dark:border-slate-600/60"
                                                style="background-color: {{ $opt->hex }}"></span>
                                        @else
                                            <span class="shrink-0 w-4 h-4 rounded-full border border-dashed border-slate-400 dark:border-slate-500"></span>
                                        @endif
                                        <span class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $opt->name }}</span>
                                        <button type="button" wire:click="deselectColor({{ $opt->id }})"
                                            class="ml-auto p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 cursor-pointer transition-colors"
                                            title="{{ __('Remove color') }}">
                                            <x-icon name="o-x-mark" class="w-4 h-4" />
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('Qty') }}</label>
                                            <div class="relative">
                                                <input type="number" wire:model="colorQuantities.{{ $opt->id }}" min="0" placeholder="0"
                                                    class="w-full px-3 py-2 rounded-lg text-sm font-mono bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                                           text-slate-700 dark:text-slate-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow {{ $unit ? 'pr-12' : '' }}" />
                                                @if($unit)
                                                    <div class="absolute inset-y-0 right-0 flex items-center px-2.5 pointer-events-none text-slate-400 text-[10px] font-semibold">
                                                        {{ $unit }}
                                                    </div>
                                                @endif
                                            </div>
                                            @error('colorQuantities.'.$opt->id) <span class="block text-[11px] text-rose-500 mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('Amount') }}</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 flex items-center px-2.5 pointer-events-none text-slate-400 text-[10px] font-semibold">Rp</div>
                                                <input type="number" wire:model="colorAmounts.{{ $opt->id }}" min="1" placeholder="0"
                                                    class="w-full pl-8 pr-3 py-2 rounded-lg text-sm font-mono bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                                           text-slate-700 dark:text-slate-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow" />
                                            </div>
                                            @error('colorAmounts.'.$opt->id) <span class="block text-[11px] text-rose-500 mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('Notes / Supplier') }}</label>
                                            <input type="text" wire:model="colorNotes.{{ $opt->id }}" placeholder="{{ __('Supplier name or notes') }}"
                                                class="w-full px-3 py-2 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                                       text-slate-700 dark:text-slate-200 placeholder-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 transition-shadow" />
                                            @error('colorNotes.'.$opt->id) <span class="block text-[11px] text-rose-500 mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">{{ __('Payment Proof') }}</label>
                                        <input type="file" wire:model="colorProofs.{{ $opt->id }}" accept="image/jpeg,image/png,application/pdf"
                                            class="block w-full text-xs text-slate-600 dark:text-slate-400
                                                   file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                                   file:text-xs file:font-semibold file:bg-emerald-50 dark:file:bg-emerald-500/10 file:text-emerald-700 dark:file:text-emerald-400
                                                   hover:file:bg-emerald-100 transition-colors
                                                   bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-lg cursor-pointer
                                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/30" />
                                        <div class="flex items-center gap-2 mt-1">
                                            <p class="text-[11px] text-slate-400">{{ __('JPG, PNG, PDF · max 5 MB') }}</p>
                                            <span wire:loading wire:target="colorProofs.{{ $opt->id }}" class="text-[11px] text-indigo-500 font-semibold flex items-center gap-1">
                                                <x-icon name="o-arrow-path" class="w-3 h-3 animate-spin" /> {{ __('Uploading...') }}
                                            </span>
                                        </div>
                                        @error('colorProofs.'.$opt->id) <span class="block text-[11px] text-rose-500 mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
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
                            <span>{{ __('Any color can be selected — chosen colors are automatically linked to the brand, so the Brands tab stays in sync.') }}</span>
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

    @include('livewire.admin.material.partials.manage-modal')

</div>
