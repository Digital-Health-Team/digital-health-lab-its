<div>
    {{-- HEADER --}}
    <x-header title="{{ __('Product Portfolio') }}" subtitle="{{ __('Manage made-by-order 3D catalog and references') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add Product') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- Golden Standard: table container (wraps filter + table) --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">

        {{-- FILTER BAR --}}
        <div class="p-4 bg-slate-50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <x-input placeholder="{{ __('Search product name...') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" />
                <x-select wire:model.live="filterStatus" :options="[['id'=>'active','name'=>__('Active')], ['id'=>'inactive','name'=>__('Inactive')]]" placeholder="{{ __('All Status') }}" icon="o-check-circle" />
                <x-select wire:model.live="sortBy" :options="[['id'=>'latest','name'=>__('Newest First')], ['id'=>'oldest','name'=>__('Oldest First')]]" icon="o-arrows-up-down" />
                <div>
                    <x-button label="{{ __('Clear Filters') }}" wire:click="clearFilters" icon="o-x-mark" class="btn-ghost w-full lg:w-auto text-gray-500" />
                </div>
            </div>
        </div>

        {{-- DATA TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                {{-- Golden Standard: thead row --}}
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <th class="py-3 px-6 text-center w-12">#</th>
                        <th class="py-3 px-6">{{ __('Product') }}</th>
                        <th class="py-3 px-6">{{ __('Estimated Price Range') }}</th>
                        <th class="py-3 px-6">{{ __('Status') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                {{-- Golden Standard: tbody --}}
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($products as $product)
                        @php
                            $primaryPhoto = $product->attachments->first();
                        @endphp
                        {{-- Golden Standard: row hover --}}
                        <tr wire:key="prod-{{ $product->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-6 text-center text-slate-400 dark:text-slate-500 font-mono text-xs">
                                {{ $loop->iteration + ($products->firstItem() - 1) }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 shadow-sm">
                                        @if($primaryPhoto)
                                            <img src="{{ asset('storage/' . $primaryPhoto->file_url) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                                        @else
                                            <x-icon name="o-cube" class="w-6 h-6 text-slate-400 dark:text-slate-500" />
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 dark:text-slate-200">{{ $product->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 max-w-xs">{{ $product->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-mono text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    Rp {{ number_format($product->price_min, 0, ',', '.') }} – Rp {{ number_format($product->price_max, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full {{ $product->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                                    <span class="text-xs {{ $product->is_active ? 'text-emerald-600 dark:text-emerald-400 font-medium' : 'text-rose-600 dark:text-rose-400 font-medium' }}">
                                        {{ $product->is_active ? __('Active') : __('Inactive') }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                {{-- Golden Standard: action button container --}}
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Golden Standard: standard icon button --}}
                                    <button wire:click="edit({{ $product->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Edit Product') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    <button wire:click="confirmToggle({{ $product->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer
                                               {{ $product->is_active ? 'hover:bg-rose-100 hover:text-rose-600 hover:border-rose-200 dark:hover:bg-rose-500/20 dark:hover:text-rose-400 dark:hover:border-rose-500/30' : 'hover:bg-emerald-100 hover:text-emerald-600 hover:border-emerald-200 dark:hover:bg-emerald-500/20 dark:hover:text-emerald-400 dark:hover:border-emerald-500/30' }}"
                                        title="{{ $product->is_active ? __('Deactivate') : __('Activate') }}">
                                        <x-icon name="{{ $product->is_active ? 'o-no-symbol' : 'o-check-circle' }}" class="w-4 h-4" />
                                    </button>
                                    {{-- Golden Standard: danger button --}}
                                    <button wire:click="confirmDelete({{ $product->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-rose-100 hover:text-rose-600 hover:border-rose-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-rose-500/20 dark:hover:text-rose-400 dark:hover:border-rose-500/30 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Delete Product') }}">
                                        <x-icon name="o-trash" class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Golden Standard: empty state --}}
                        <tr>
                            <td colspan="5" class="text-center py-16">
                                <x-icon name="o-photo" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                                <p class="text-slate-500 dark:text-slate-400">{{ __('No products found matching your filters.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Golden Standard: pagination footer --}}
        @if($products->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- DRAWER FORM --}}
    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit Product') : __('Add Product') }}" right separator with-close-button class="w-11/12 lg:w-2/5">

        <x-form wire:submit="save">
            <x-input label="{{ __('Product Name') }}" wire:model="name" required />

            <div class="grid grid-cols-2 gap-4">
                <x-input label="{{ __('Minimum Price') }}" wire:model="price_min" type="number" prefix="Rp" required />
                <x-input label="{{ __('Maximum Price') }}" wire:model="price_max" type="number" prefix="Rp" required />
            </div>

            <x-textarea label="{{ __('Description') }}" wire:model="description" rows="4" required />

            <div class="divider text-sm text-base-content/50 font-bold uppercase tracking-wider">{{ __('Product Gallery') }}</div>

            {{-- Existing Photos (Editing Mode) --}}
            @if($editingId && count($existing_photos) > 0)
                <div class="grid grid-cols-3 gap-3 mb-4">
                    @foreach($existing_photos as $photo)
                        <div class="relative group rounded-lg overflow-hidden border {{ $photo->is_primary ? 'border-primary ring-2 ring-primary/50' : 'border-base-300' }} aspect-square">
                            <img src="{{ asset('storage/' . $photo->file_url) }}" class="w-full h-full object-cover">

                            @if($photo->is_primary)
                                <div class="absolute top-2 left-2 bg-primary text-white text-[10px] px-2 py-1 rounded shadow-sm font-bold uppercase tracking-wider">
                                    {{ __('Primary') }}
                                </div>
                            @endif

                            {{-- Hover Overlay Actions --}}
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col items-center justify-center gap-2">
                                @if(!$photo->is_primary)
                                    <x-button icon="o-star" label="{{ __('Thumbnail') }}" wire:click.prevent="setPrimaryPhoto({{ $photo->id }})" class="btn-sm btn-outline text-white hover:bg-primary hover:border-primary border-white" />
                                @endif
                                <x-button icon="o-trash" label="{{ __('Delete') }}" wire:click.prevent="removeExistingPhoto({{ $photo->id }})" class="btn-sm btn-error text-white border-none" />
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Upload New Photos --}}
            <x-file wire:model="new_photos" label="{{ __('Upload New Photos') }}" multiple accept="image/*" hint="{{ __('Select multiple images. Max 3MB per file.') }}" />

            {{-- Preview New Uploads --}}
            @if($new_photos && count($new_photos) > 0)
                <div class="grid grid-cols-3 gap-3 mt-4">
                    @foreach($new_photos as $index => $photo)
                        <div class="relative rounded-lg overflow-hidden border border-base-300 aspect-square">
                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                            <div class="absolute top-1 right-1">
                                <x-button icon="o-x-mark" wire:click.prevent="removeNewPhoto({{ $index }})" class="btn-xs btn-circle btn-error text-white opacity-90 shadow-sm" />
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.drawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save Product') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- MODALS --}}
    <x-modal wire:model="toggleModalOpen" title="{{ __('Confirm Action') }}" separator>
        <div class="py-4 text-base-content/80">
            {{ __('Are you sure you want to change the status of this product? Inactive products will not be visible in the catalog.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.toggleModalOpen = false" class="btn-ghost" />
            <x-button label="{{ __('Yes, Proceed') }}" class="btn-warning" wire:click="toggleStatus" spinner="toggleStatus" />
        </x-slot:actions>
    </x-modal>

    <x-modal wire:model="deleteModalOpen" title="{{ __('Confirm Deletion') }}" separator>
        <div class="py-4 text-base-content/80">
            {{ __('Are you sure you want to permanently delete this product and all its images? This action cannot be undone.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModalOpen = false" class="btn-ghost" />
            <x-button label="{{ __('Yes, Delete') }}" class="btn-error" wire:click="deleteRecord" spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>
</div>
