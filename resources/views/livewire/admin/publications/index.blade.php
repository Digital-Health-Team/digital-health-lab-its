<div>
    {{-- HEADER --}}
    <x-header title="{{ __('Publications') }}" subtitle="{{ __('Manage journal articles, research papers, and publications') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add Publication') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- TABLE CONTAINER --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-4 bg-slate-50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                <x-input placeholder="{{ __('Search title, author, journal…') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" class="lg:col-span-2" />
                <x-select wire:model.live="filterStatus" :options="$statuses" option-label="name" option-value="id" placeholder="{{ __('All Statuses') }}" icon="o-check-badge" />
                <x-select wire:model.live="filterCategory" :options="$categories" option-label="name" option-value="id" placeholder="{{ __('All Categories') }}" icon="o-tag" />
                <x-select wire:model.live="sortBy" :options="$sortOptions" option-label="name" option-value="id" icon="o-arrows-up-down" />
                <div>
                    <x-button label="{{ __('Clear') }}" wire:click="clearFilters" icon="o-x-mark" class="btn-ghost w-full lg:w-auto text-gray-500" />
                </div>
            </div>
        </div>

        {{-- DATA TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <th class="py-3 px-6 text-center w-12">#</th>
                        <th class="py-3 px-6">{{ __('Title & Category') }}</th>
                        <th class="py-3 px-6">{{ __('Author') }}</th>
                        <th class="py-3 px-6">{{ __('Journal / PMID') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Free') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Featured') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Views') }}</th>
                        <th class="py-3 px-6">{{ __('Published') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Status') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($publications as $pub)
                        <tr wire:key="pub-{{ $pub->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-6 text-center text-slate-400 dark:text-slate-500 font-mono text-xs">{{ $loop->iteration + ($publications->firstItem() - 1) }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-1.5">
                                    @if($pub->is_featured)
                                        <x-icon name="o-star" class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" />
                                    @endif
                                    <span class="font-bold text-slate-800 dark:text-slate-200 line-clamp-1">{{ $pub->title }}</span>
                                </div>
                                @php
                                    $catColors = [
                                        'Journals'  => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-500/20',
                                        'Papers'    => 'bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 border-violet-200 dark:border-violet-500/20',
                                    ];
                                    $catColor = $catColors[$pub->category] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center mt-1 px-1.5 py-0.5 rounded text-[10px] font-semibold border {{ $catColor }}">{{ $pub->category }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm">{{ $pub->author }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if($pub->journal)
                                    <div class="text-sm text-slate-700 dark:text-slate-300 line-clamp-1">{{ $pub->journal }}</div>
                                @endif
                                @if($pub->pmid)
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono mt-0.5">PMID: {{ $pub->pmid }}</div>
                                @endif
                                @if(!$pub->journal && !$pub->pmid)
                                    <span class="text-slate-400 dark:text-slate-600 text-xs">—</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($pub->is_free_access)
                                    <x-icon name="o-check-circle" class="w-5 h-5 text-emerald-500 mx-auto" />
                                @else
                                    <x-icon name="o-minus-circle" class="w-5 h-5 text-slate-300 dark:text-slate-600 mx-auto" />
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($pub->is_featured)
                                    <x-icon name="o-star" class="w-5 h-5 text-amber-400 mx-auto" />
                                @else
                                    <x-icon name="o-minus-circle" class="w-5 h-5 text-slate-300 dark:text-slate-600 mx-auto" />
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="text-sm font-mono text-slate-600 dark:text-slate-400">{{ number_format($pub->view_count) }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm text-slate-600 dark:text-slate-400 font-mono text-xs">
                                    {{ $pub->published_at?->format('d M Y') ?? '—' }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @php
                                    $statusClass = match($pub->status) {
                                        'approved' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20',
                                        'rejected' => 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-500/20',
                                        default    => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-500/20',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $statusClass }}">
                                    {{ $pub->status }}
                                </span>
                                @if($pub->withdrawal_requested_at)
                                    <div class="inline-flex items-center gap-1 mt-1 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border bg-orange-50 dark:bg-orange-500/10 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-500/20">
                                        <x-icon name="o-arrow-uturn-left" class="w-3 h-3" />
                                        {{ __('Removal requested') }}
                                    </div>
                                @endif
                                @if($pub->user)
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 leading-tight mt-1">{{ __('Submitted by') }}: {{ $pub->user->name }}</div>
                                @endif
                                @if($pub->status !== 'pending' && $pub->validator)
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 leading-tight mt-1">By: {{ $pub->validator->name }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    @if($pub->status !== 'approved')
                                        <button wire:click="updateStatus({{ $pub->id }}, 'approved')"
                                            class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-emerald-100 hover:text-emerald-600 hover:border-emerald-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-emerald-500/20 dark:hover:text-emerald-400 dark:hover:border-emerald-500/30 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                            title="{{ __('Approve') }}">
                                            <x-icon name="o-check" class="w-4 h-4" />
                                        </button>
                                    @endif
                                    @if($pub->status !== 'rejected')
                                        <button wire:click="updateStatus({{ $pub->id }}, 'rejected')"
                                            class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-amber-100 hover:text-amber-600 hover:border-amber-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-amber-500/20 dark:hover:text-amber-400 dark:hover:border-amber-500/30 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                            title="{{ __('Reject') }}">
                                            <x-icon name="o-x-mark" class="w-4 h-4" />
                                        </button>
                                    @endif
                                    <div class="w-px h-5 bg-slate-200 dark:bg-slate-700 mx-0.5"></div>
                                    <button wire:click="edit({{ $pub->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Edit') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    <button wire:click="confirmDelete({{ $pub->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-rose-100 hover:text-rose-600 hover:border-rose-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-rose-500/20 dark:hover:text-rose-400 dark:hover:border-rose-500/30 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Delete') }}">
                                        <x-icon name="o-trash" class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-16">
                                <x-icon name="o-inbox" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                                <p class="text-slate-500 dark:text-slate-400">{{ __('No publications found.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($publications->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">{{ $publications->links() }}</div>
        @endif
    </div>

    {{-- DRAWER FORM --}}
    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit Publication') : __('Add Publication') }}" right separator with-close-button class="w-11/12 lg:w-2/5">
        <x-form wire:submit="save">

            {{-- Basic Info --}}
            <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 mt-1">{{ __('Basic Information') }}</div>

            <x-input label="{{ __('Title') }}" wire:model="title" required />
            <x-input label="{{ __('Title (English)') }}" wire:model="title_en" hint="{{ __('Optional — falls back to Indonesian when empty.') }}" />
            <x-input label="{{ __('Author(s)') }}" wire:model="author" placeholder="e.g. Budi Santoso, et al." required />
            <x-input label="{{ __('Slug') }}" wire:model="slug" hint="{{ __('Auto-generated from title. Editable.') }}" placeholder="e.g. prosthetic-arm-fdm-2025" />

            <div class="grid grid-cols-2 gap-4">
                <x-select label="{{ __('Category') }}" wire:model="category" :options="$categories" option-label="name" option-value="id" required />
                <x-input label="{{ __('Published Date') }}" wire:model="published_at" type="date" />
            </div>

            {{-- Content --}}
            <hr class="border-base-200 my-4">
            <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">{{ __('Content') }}</div>

            <x-textarea label="{{ __('Abstract') }}" wire:model="abstract" rows="4" placeholder="{{ __('Short summary of the publication…') }}" />
            <x-textarea label="{{ __('Abstract (English)') }}" wire:model="abstract_en" rows="4" hint="{{ __('Optional — falls back to Indonesian when empty.') }}" />

            <div class="flex items-center justify-between mb-3 mt-4">
                <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Description Paragraphs') }}</div>
                <x-button label="{{ __('+ Add') }}" wire:click.prevent="addDescriptionItem" class="btn-xs btn-ghost" />
            </div>
            @foreach($description as $i => $para)
                <div class="flex gap-2 items-start mb-2" wire:key="desc-{{ $i }}">
                    <x-textarea wire:model="description.{{ $i }}" rows="3" class="flex-1" placeholder="{{ __('Paragraph :n', ['n' => $i + 1]) }}" />
                    @if(count($description) > 1)
                        <x-button icon="o-trash" wire:click.prevent="removeDescriptionItem({{ $i }})" class="btn-xs btn-ghost btn-circle text-rose-400 mt-1" />
                    @endif
                </div>
            @endforeach

            <div class="flex items-center justify-between mb-3 mt-4">
                <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Description Paragraphs (English)') }}</div>
                <x-button label="{{ __('+ Add') }}" wire:click.prevent="addEnItem('description_en')" class="btn-xs btn-ghost" />
            </div>
            @foreach($description_en as $i => $para)
                <div class="flex gap-2 items-start mb-2" wire:key="desc-en-{{ $i }}">
                    <x-textarea wire:model="description_en.{{ $i }}" rows="3" class="flex-1" placeholder="{{ __('Paragraph :n', ['n' => $i + 1]) }}" />
                    @if(count($description_en) > 1)
                        <x-button icon="o-trash" wire:click.prevent="removeEnItem('description_en', {{ $i }})" class="btn-xs btn-ghost btn-circle text-rose-400 mt-1" />
                    @endif
                </div>
            @endforeach

            {{-- Keywords --}}
            <hr class="border-base-200 my-4">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Keywords') }}</div>
                <x-button label="{{ __('+ Add') }}" wire:click.prevent="addKeywordItem" class="btn-xs btn-ghost" />
            </div>
            @foreach($keywords as $i => $kw)
                <div class="flex gap-2 items-center mb-2" wire:key="kw-{{ $i }}">
                    <x-input wire:model="keywords.{{ $i }}" class="flex-1" placeholder="{{ __('Keyword :n', ['n' => $i + 1]) }}" />
                    @if(count($keywords) > 1)
                        <x-button icon="o-trash" wire:click.prevent="removeKeywordItem({{ $i }})" class="btn-xs btn-ghost btn-circle text-rose-400" />
                    @endif
                </div>
            @endforeach

            <div class="flex items-center justify-between mb-3 mt-4">
                <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Keywords (English)') }}</div>
                <x-button label="{{ __('+ Add') }}" wire:click.prevent="addEnItem('keywords_en')" class="btn-xs btn-ghost" />
            </div>
            @foreach($keywords_en as $i => $kw)
                <div class="flex gap-2 items-center mb-2" wire:key="kw-en-{{ $i }}">
                    <x-input wire:model="keywords_en.{{ $i }}" class="flex-1" placeholder="{{ __('Keyword :n', ['n' => $i + 1]) }}" />
                    @if(count($keywords_en) > 1)
                        <x-button icon="o-trash" wire:click.prevent="removeEnItem('keywords_en', {{ $i }})" class="btn-xs btn-ghost btn-circle text-rose-400" />
                    @endif
                </div>
            @endforeach

            {{-- Reference Links --}}
            <hr class="border-base-200 my-4">
            <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">{{ __('Reference Links') }}</div>

            <x-input label="{{ __('DOI') }}" wire:model="doi" placeholder="e.g. 10.1234/example.2025.001" />
            <x-input label="{{ __('Journal Name') }}" wire:model="journal" placeholder="e.g. IEEE Transactions on Medical Imaging" />
            <x-input label="{{ __('PMID') }}" wire:model="pmid" placeholder="e.g. 38123456" />

            {{-- Files --}}
            <hr class="border-base-200 my-4">
            <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">{{ __('Files') }}</div>

            {{-- Existing thumbnail --}}
            @if($editingId && $existingThumbnailUrl)
                <div class="mb-3">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">{{ __('Current Thumbnail') }}</div>
                    <img src="{{ $existingThumbnailUrl }}" alt="Thumbnail" class="h-24 w-auto rounded-lg object-cover border border-slate-200 dark:border-slate-700">
                </div>
            @endif
            <x-file wire:model="thumbnail_file" label="{{ $editingId ? __('Replace Thumbnail') : __('Thumbnail Image') }}" hint="{{ __('JPEG, PNG, WebP — max 4 MB') }}" accept="image/*" />

            @if($thumbnail_file)
                <div class="mt-2">
                    <img src="{{ $thumbnail_file->temporaryUrl() }}" alt="Preview" class="h-24 w-auto rounded-lg object-cover border border-primary/30">
                </div>
            @endif

            {{-- Existing PDF --}}
            @if($editingId && $existingPdfPath)
                <div class="mt-3 mb-1 flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                    <x-icon name="o-document-text" class="w-4 h-4 text-rose-400" />
                    <span>{{ __('Current PDF:') }} <span class="font-mono text-xs">{{ $existingPdfPath }}</span></span>
                </div>
            @endif
            <x-file wire:model="pdf_file" label="{{ $editingId ? __('Replace PDF') : __('PDF File') }}" hint="{{ __('PDF only — max 50 MB') }}" accept=".pdf,application/pdf" />

            {{-- Flags --}}
            <hr class="border-base-200 my-4">
            <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">{{ __('Settings') }}</div>

            <div class="flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <input type="checkbox" wire:model="is_free_access" id="is_free_access" class="checkbox checkbox-success" />
                    <label for="is_free_access" class="text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                        {{ __('Free access') }}
                        <span class="text-xs text-slate-400 ml-1">({{ __('shows Free PDF badge') }})</span>
                    </label>
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" wire:model="is_featured" id="is_featured" class="checkbox checkbox-warning" />
                    <label for="is_featured" class="text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                        {{ __('Featured') }}
                        <span class="text-xs text-slate-400 ml-1">({{ __('star badge in table') }})</span>
                    </label>
                </div>
            </div>

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.drawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save Publication') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- DELETE MODAL --}}
    <x-modal wire:model="deleteModalOpen" title="{{ __('Confirm Deletion') }}" separator>
        <div class="py-4 text-base-content/80">
            {{ __('Are you sure you want to permanently delete this publication and its associated files? This action cannot be undone.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModalOpen = false" class="btn-ghost" />
            <x-button label="{{ __('Yes, Delete') }}" class="btn-error" wire:click="deleteRecord" spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>
</div>
