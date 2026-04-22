<div>
    {{-- HEADER --}}
    <x-header title="{{ __('Product Portfolio') }}" subtitle="{{ __('Manage made-by-order 3D catalog and references') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add Product') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- INTEGRATED CARD (FILTER + TABLE) --}}
    <x-card class="p-0 overflow-hidden shadow-sm border border-base-200 bg-base-100">

        {{-- FILTER BAR --}}
        <div class="p-4 bg-base-200/30 border-b border-base-200">
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
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-100">
                        <th class="w-12 text-center text-xs uppercase tracking-wider text-gray-500">#</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Product') }}</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Estimated Price Range') }}</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Status') }}</th>
                        <th class="text-right text-xs uppercase tracking-wider text-gray-500">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php
                            $primaryPhoto = $product->attachments->first();
                        @endphp
                        <tr wire:key="prod-{{ $product->id }}" class="hover:bg-base-200/50 transition-colors">
                            <td class="text-center text-gray-400 font-medium text-sm">
                                {{ $loop->iteration + ($products->firstItem() - 1) }}
                            </td>
                            <td>
                                <div class="flex items-center gap-4">
                                    {{-- Product Image Thumbnail --}}
                                    <div class="w-14 h-14 rounded-lg bg-base-200 flex items-center justify-center border border-base-300 overflow-hidden shrink-0 shadow-sm">
                                        @if($primaryPhoto)
                                            <img src="{{ asset('storage/' . $primaryPhoto->file_url) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                                        @else
                                            <x-icon name="o-cube" class="w-6 h-6 text-gray-400" />
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-base-content text-base">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-500 line-clamp-1 max-w-xs">{{ $product->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-mono text-sm font-semibold text-base-content/80">
                                    Rp {{ number_format($product->price_min, 0, ',', '.') }} - Rp {{ number_format($product->price_max, 0, ',', '.') }}
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full {{ $product->is_active ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                    <span class="text-xs {{ $product->is_active ? 'text-green-600 font-medium' : 'text-red-500 font-medium' }}">
                                        {{ $product->is_active ? __('Active') : __('Inactive') }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-right flex justify-end gap-1">
                                <x-button icon="o-pencil-square" wire:click="edit({{ $product->id }})" class="btn-sm btn-circle btn-ghost text-blue-500 hover:bg-blue-50" tooltip="{{ __('Edit Product') }}" />
                                <x-button icon="{{ $product->is_active ? 'o-no-symbol' : 'o-check-circle' }}"
                                          wire:click="confirmToggle({{ $product->id }})"
                                          class="btn-sm btn-circle btn-ghost {{ $product->is_active ? 'text-orange-500 hover:bg-orange-50' : 'text-green-500 hover:bg-green-50' }}"
                                          tooltip="{{ $product->is_active ? __('Deactivate') : __('Activate') }}" />
                                <x-button icon="o-trash" wire:click="confirmDelete({{ $product->id }})" class="btn-sm btn-circle btn-ghost text-red-500 hover:bg-red-50" tooltip="{{ __('Delete Product') }}" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-16">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <x-icon name="o-photo" class="w-12 h-12 mb-3 opacity-30" />
                                    <p class="text-base">{{ __('No products found matching your filters.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-base-200 bg-base-50">
                {{ $products->links() }}
            </div>
        @endif
    </x-card>

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
