<div>
    {{-- HEADER --}}
    <x-header title="{{ __('Page Sections (CMS)') }}" subtitle="{{ __('Manage dynamic texts and contents for the landing page') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add New Content') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- FILTER BAR --}}
    <x-card class="p-0 overflow-hidden shadow-sm border border-base-200 bg-base-100 mb-6">
        <div class="p-4 bg-base-200/30">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <x-input placeholder="{{ __('Search key or content...') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" />
                <x-select wire:model.live="filterPage" :options="$availablePages" option-label="name" option-value="id" placeholder="{{ __('All Pages') }}" icon="o-document" />
                <div>
                    <x-button label="{{ __('Clear') }}" wire:click="clearFilters" icon="o-x-mark" class="btn-ghost" />
                </div>
            </div>
        </div>
    </x-card>

    {{-- DATA TABLE --}}
    <x-card class="p-0 overflow-hidden shadow-sm border border-base-200 bg-base-100">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-100">
                        <th class="w-12 text-center text-xs uppercase tracking-wider text-gray-500">#</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Page') }}</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Section Key') }}</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Content Preview') }}</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Last Updated') }}</th>
                        <th class="text-right text-xs uppercase tracking-wider text-gray-500">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $section)
                        <tr wire:key="sec-{{ $section->id }}" class="hover:bg-base-200/50 transition-colors">
                            <td class="text-center text-gray-400 font-medium text-sm">{{ $loop->iteration + ($sections->firstItem() - 1) }}</td>
                            <td>
                                <span class="badge badge-outline badge-primary font-bold uppercase tracking-wider text-[10px]">
                                    {{ str_replace('_', ' ', $section->page_name) }}
                                </span>
                            </td>
                            <td>
                                <div class="font-mono text-sm text-base-content bg-base-200 px-2 py-1 rounded inline-block">
                                    {{ $section->section_key }}
                                </div>
                            </td>
                            <td class="max-w-md">
                                <div class="text-sm text-gray-500 truncate" title="{{ $section->content }}">
                                    {{ $section->content }}
                                </div>
                            </td>
                            <td>
                                <div class="text-sm font-semibold">{{ $section->updated_at->format('d M Y, H:i') }}</div>
                                <div class="text-[10px] text-gray-400">{{ __('By') }}: {{ $section->updater?->name ?? 'System' }}</div>
                            </td>
                            <td class="text-right flex justify-end gap-1">
                                <x-button icon="o-pencil-square" wire:click="edit({{ $section->id }})" class="btn-sm btn-circle btn-ghost text-blue-500 hover:bg-blue-50" tooltip="{{ __('Edit') }}" />
                                <x-button icon="o-trash" wire:click="confirmDelete({{ $section->id }})" class="btn-sm btn-circle btn-ghost text-red-500 hover:bg-red-50" tooltip="{{ __('Delete') }}" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <x-icon name="o-document-text" class="w-12 h-12 mb-3 opacity-30" />
                                    <p class="text-base">{{ __('No page sections found.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sections->hasPages())
            <div class="p-4 border-t border-base-200 bg-base-50">{{ $sections->links() }}</div>
        @endif
    </x-card>

    {{-- DRAWER FORM --}}
    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit Content') : __('Add New Content') }}" right separator with-close-button class="w-11/12 lg:w-2/5">
        <x-form wire:submit="save">

            <div class="grid grid-cols-2 gap-4">
                <x-input label="{{ __('Page Name') }}" wire:model="page_name" placeholder="e.g., home, about_us" hint="{{ __('Will be auto-slugged') }}" required />
                <x-input label="{{ __('Section Key') }}" wire:model="section_key" placeholder="e.g., hero_title, mission_text" hint="{{ __('Must be unique per page') }}" required />
            </div>

            <div class="bg-base-200/50 p-4 rounded-xl border border-base-300 mt-2 mb-4">
                <div class="text-xs text-gray-500 mb-2 font-mono">
                    <span class="font-bold">{{ __('Usage Reference:') }}</span><br>
                    PageSection::where('page_name', '<span x-text="$wire.page_name || \'home\'"></span>')->where('section_key', '<span x-text="$wire.section_key || \'key\'"></span>')->first()->content ?? ''
                </div>
            </div>

            <x-textarea label="{{ __('Content Text') }}" wire:model="content" rows="8" placeholder="{{ __('Type your content here...') }}" required />

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.drawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save Content') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- MODAL DELETE --}}
    <x-modal wire:model="deleteModalOpen" title="{{ __('Confirm Deletion') }}" separator>
        <div class="py-4 text-base-content/80">
            {{ __('Are you sure you want to permanently delete this content section? The frontend might break if it relies on this specific key.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModalOpen = false" class="btn-ghost" />
            <x-button label="{{ __('Yes, Delete') }}" class="btn-error" wire:click="deleteRecord" spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>
</div>
