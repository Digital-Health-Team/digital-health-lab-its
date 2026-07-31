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
    {{-- Golden Standard: table container --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                {{-- Golden Standard: thead row --}}
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <th class="py-3 px-6 text-center w-12">#</th>
                        <th class="py-3 px-6">{{ __('Page') }}</th>
                        <th class="py-3 px-6">{{ __('Section Key') }}</th>
                        <th class="py-3 px-6">{{ __('Content Preview') }}</th>
                        <th class="py-3 px-6">{{ __('Last Updated') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                {{-- Golden Standard: tbody --}}
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($sections as $section)
                        {{-- Golden Standard: row hover --}}
                        <tr wire:key="sec-{{ $section->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-6 text-center text-slate-400 dark:text-slate-500 font-mono text-xs">{{ $loop->iteration + ($sections->firstItem() - 1) }}</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20">
                                    {{ str_replace('_', ' ', $section->page_name) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-mono text-sm text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-2 py-1 rounded-md inline-block">
                                    {{ $section->section_key }}
                                </div>
                            </td>
                            <td class="py-4 px-6 max-w-md">
                                <div class="text-sm text-slate-500 dark:text-slate-400 truncate" title="{{ $section->content }}">
                                    {{ $section->content }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $section->updated_at->format('d M Y, H:i') }}</div>
                                <div class="text-[10px] text-slate-400 dark:text-slate-500">{{ __('By') }}: {{ $section->updater?->name ?? 'System' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                {{-- Golden Standard: action button container --}}
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Golden Standard: standard icon button --}}
                                    <button wire:click="edit({{ $section->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Edit') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    {{-- Golden Standard: danger button --}}
                                    <button wire:click="confirmDelete({{ $section->id }})"
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
                                <x-icon name="o-document-text" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                                <p class="text-slate-500 dark:text-slate-400">{{ __('No page sections found.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Golden Standard: pagination footer --}}
        @if($sections->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">{{ $sections->links() }}</div>
        @endif
    </div>

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
