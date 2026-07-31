<div>
    {{-- HEADER --}}
    <x-header title="{{ __('Open Source Projects') }}" subtitle="{{ __('Manage individual public project submissions') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add Project') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- Golden Standard: table container (wraps filter + table) --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-4 bg-slate-50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                <x-input placeholder="{{ __('Search title or creator...') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" />
                <x-select wire:model.live="filterStatus" :options="[['id'=>'pending','name'=>__('Pending')], ['id'=>'approved','name'=>__('Approved')], ['id'=>'rejected','name'=>__('Rejected')]]" placeholder="{{ __('All Status') }}" icon="o-funnel" />
                <x-select wire:model.live="filterCategory" :options="$categories" option-label="name" option-value="id" placeholder="{{ __('All Categories') }}" icon="o-tag" />
                <x-select wire:model.live="filterListingType" :options="$listingTypes" option-label="name" option-value="id" placeholder="{{ __('All Types') }}" icon="o-squares-2x2" />
                <x-select wire:model.live="sortBy" :options="[['id'=>'latest','name'=>__('Newest First')], ['id'=>'oldest','name'=>__('Oldest First')]]" icon="o-arrows-up-down" />
                <div>
                    <x-button label="{{ __('Clear') }}" wire:click="clearFilters" icon="o-x-mark" class="btn-ghost w-full lg:w-auto text-gray-500" />
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
                        <th class="py-3 px-6">{{ __('Project Title & Category') }}</th>
                        <th class="py-3 px-6">{{ __('Type') }}</th>
                        <th class="py-3 px-6">{{ __('Creator') }}</th>
                        <th class="py-3 px-6">{{ __('Files') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Moderation') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                {{-- Golden Standard: tbody --}}
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($projects as $project)
                        {{-- Golden Standard: row hover --}}
                        <tr wire:key="osp-{{ $project->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-6 text-center text-slate-400 dark:text-slate-500 font-mono text-xs">{{ $loop->iteration + ($projects->firstItem() - 1) }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-1.5">
                                    @if($project->is_featured)
                                        <x-icon name="o-star" class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" />
                                    @endif
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $project->title }}</span>
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex items-center gap-1.5">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                        {{ str_replace('_', ' ', $project->category) }}
                                    </span>
                                    @if($project->version)
                                        <span class="text-slate-400 font-mono text-[10px]">{{ $project->version }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($project->listing_type)
                                    @php
                                        $typeColors = [
                                            'journals' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-500/20',
                                            'products' => 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-200 dark:border-purple-500/20',
                                            'powerpoint' => 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border-orange-200 dark:border-orange-500/20',
                                            'downloadable' => 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 border-teal-200 dark:border-teal-500/20',
                                            'read_only' => 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-600',
                                        ];
                                        $typeColor = $typeColors[$project->listing_type] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                        $typeLabel = match($project->listing_type) {
                                            'journals' => 'Journal',
                                            'products' => 'Product',
                                            'powerpoint' => 'PowerPoint',
                                            'downloadable' => 'Downloadable',
                                            'read_only' => 'Read-Only',
                                            default => $project->listing_type,
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $typeColor }}">
                                        {{ $typeLabel }}
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-600 text-xs">—</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm">{{ $project->user->profile?->full_name ?? $project->user->email }}</div>
                                <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono">{{ $project->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-1 text-sm font-medium text-slate-500 dark:text-slate-400">
                                    <x-icon name="o-paper-clip" class="w-4 h-4" /> {{ $project->attachments->count() }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @php
                                    $statusClass = match($project->status) {
                                        'approved' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20',
                                        'rejected' => 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-500/20',
                                        default    => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-500/20',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $statusClass }}">
                                    {{ $project->status }}
                                </span>
                                @if($project->status !== 'pending' && $project->validator)
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 leading-tight mt-1">By: {{ $project->validator->name }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                {{-- Golden Standard: action button container --}}
                                <div class="flex items-center justify-end gap-2">
                                    @if($project->status !== 'approved')
                                        <button wire:click="updateStatus({{ $project->id }}, 'approved')"
                                            class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-emerald-100 hover:text-emerald-600 hover:border-emerald-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-emerald-500/20 dark:hover:text-emerald-400 dark:hover:border-emerald-500/30 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                            title="{{ __('Approve') }}">
                                            <x-icon name="o-check" class="w-4 h-4" />
                                        </button>
                                    @endif
                                    @if($project->status !== 'rejected')
                                        <button wire:click="updateStatus({{ $project->id }}, 'rejected')"
                                            class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-amber-100 hover:text-amber-600 hover:border-amber-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-amber-500/20 dark:hover:text-amber-400 dark:hover:border-amber-500/30 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                            title="{{ __('Reject') }}">
                                            <x-icon name="o-x-mark" class="w-4 h-4" />
                                        </button>
                                    @endif
                                    <div class="w-px h-5 bg-slate-200 dark:bg-slate-700 mx-0.5"></div>
                                    <button wire:click="edit({{ $project->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Edit / View Files') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    <button wire:click="confirmDelete({{ $project->id }})"
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
                            <td colspan="7" class="text-center py-16">
                                <x-icon name="o-inbox" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                                <p class="text-slate-500 dark:text-slate-400">{{ __('No open source projects found.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Golden Standard: pagination footer --}}
        @if($projects->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">{{ $projects->links() }}</div>
        @endif
    </div>

    {{-- ================================================================= --}}
    {{-- LOGIKA PEMBENTUKAN ARRAY UNTUK OMNI-VIEWER                        --}}
    {{-- ================================================================= --}}
    @php
        $galleryItems = [];

        $categorizeFile = function($mime, $ext, $url, $name) {
            $ext = strtolower($ext ?? '');
            $type = 'other';
            $icon = 'o-document-text';

            if (str_starts_with($mime ?? '', 'image/')) {
                $type = 'image'; $icon = 'o-photo';
            } elseif (str_starts_with($mime ?? '', 'video/')) {
                $type = 'video'; $icon = 'o-video-camera';
            } elseif ($mime === 'application/pdf' || $ext === 'pdf') {
                $type = 'pdf'; $icon = 'o-document-chart-bar';
            } elseif (in_array($ext, ['glb', 'gltf', 'obj', 'stl'])) {
                $type = '3d'; $icon = 'o-cube';
            }

            return ['type' => $type, 'url' => $url, 'name' => $name, 'icon' => $icon];
        };

        $resolveFileUrl = fn($fileUrl) => str_starts_with($fileUrl, 'http') ? $fileUrl : asset('storage/' . $fileUrl);

        if ($editingId && $existing_files) {
            foreach ($existing_files as $f) {
                $ext = pathinfo($f->file_url, PATHINFO_EXTENSION);
                $displayName = $f->file_name ?: basename($f->file_url);
                $galleryItems[] = $categorizeFile($f->file_type, $ext, $resolveFileUrl($f->file_url), $displayName);
            }
        }

        if ($new_files) {
            foreach ($new_files as $f) {
                $ext = $f->getClientOriginalExtension() ?: $f->guessExtension();
                try {
                    $url = $f->temporaryUrl();
                } catch (\Exception $e) {
                    $url = '#';
                }
                $galleryItems[] = $categorizeFile($f->getMimeType(), $ext, $url, $f->getClientOriginalName());
            }
        }
        $itemIndexCounter = 0;
    @endphp

    {{-- DRAWER FORM --}}
    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit & Preview Project') : __('Add Project') }}" right separator with-close-button class="w-11/12 lg:w-2/5">

        <div x-data="{ gallery: @js($galleryItems) }">
            <x-form wire:submit="save">

                {{-- ===== SECTION: Basic Info ===== --}}
                <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3 mt-1">{{ __('Basic Information') }}</div>

                <x-choices label="{{ __('Project Creator') }}" wire:model="user_id" :options="$availableUsers" option-label="name" option-value="id" single searchable required />
                <x-input label="{{ __('Project Title') }}" wire:model="title" required />
                <x-input label="{{ __('Project Title (English)') }}" wire:model="title_en" hint="{{ __('Optional — falls back to Indonesian when empty.') }}" />
                <x-input label="{{ __('Slug') }}" wire:model="slug" hint="{{ __('Auto-generated from title. Editable.') }}" placeholder="e.g. craniosynostosis-ct-detection" />

                <div class="grid grid-cols-2 gap-4">
                    <x-select label="{{ __('Domain Category') }}" wire:model="category" :options="$categories" option-label="name" option-value="id" required />
                    <x-select label="{{ __('Listing Type') }}" wire:model="listing_type" :options="$listingTypes" option-label="name" option-value="id" required />
                </div>

                {{-- ===== SECTION: Display & Listing ===== --}}
                <hr class="border-base-200 my-4">
                <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">{{ __('Display & Listing') }}</div>

                <x-textarea label="{{ __('Caption') }}" wire:model="caption" rows="2" hint="{{ __('Short description shown on listing cards.') }}" placeholder="e.g. AI-assisted cranial suture analysis from CT scans." />
                <x-textarea label="{{ __('Caption (English)') }}" wire:model="caption_en" rows="2" hint="{{ __('Optional — falls back to Indonesian when empty.') }}" />
                <x-input label="{{ __('Cover Color (Tailwind gradient)') }}" wire:model="cover_color" placeholder="bg-gradient-to-br from-amber-100 to-amber-300" hint="{{ __('Used as fallback when no image is uploaded.') }}" />

                <div class="flex items-center gap-3 mt-2">
                    <input type="checkbox" wire:model="is_featured" id="is_featured" class="checkbox checkbox-warning" />
                    <label for="is_featured" class="text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                        {{ __('Featured project') }}
                        <span class="text-xs text-slate-400 ml-1">({{ __('shows star badge in admin') }})</span>
                    </label>
                </div>

                {{-- ===== SECTION: Metadata ===== --}}
                <hr class="border-base-200 my-4">
                <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">{{ __('Metadata') }}</div>

                <div class="grid grid-cols-2 gap-4">
                    <x-select label="{{ __('License') }}" wire:model="license" :options="$licenses" option-label="name" option-value="id" />
                    <x-input label="{{ __('Version') }}" wire:model="version" placeholder="e.g. v1.2.0" />
                </div>
                <x-input label="{{ __('Format') }}" wire:model="format" placeholder="e.g. ZIP (Python project)" />

                {{-- ===== SECTION: Description ===== --}}
                <hr class="border-base-200 my-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Description Paragraphs') }}</div>
                    <x-button label="{{ __('+ Add Paragraph') }}" wire:click.prevent="addDescriptionItem" class="btn-xs btn-ghost" />
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
                @foreach($description_en as $i => $item)
                    <div class="flex gap-2 items-start mb-2" wire:key="desc-en-{{ $i }}">
                        <x-textarea wire:model="description_en.{{ $i }}" rows="3" class="flex-1" placeholder="{{ __('Paragraph :n', ['n' => $i + 1]) }}" />
                        @if(count($description_en) > 1)
                            <x-button icon="o-trash" wire:click.prevent="removeEnItem('description_en', {{ $i }})" class="btn-xs btn-ghost btn-circle text-rose-400 mt-1" />
                        @endif
                    </div>
                @endforeach

                {{-- ===== SECTION: Highlights ===== --}}
                <hr class="border-base-200 my-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Highlights') }}</div>
                    <x-button label="{{ __('+ Add') }}" wire:click.prevent="addHighlightItem" class="btn-xs btn-ghost" />
                </div>
                @foreach($highlights as $i => $item)
                    <div class="flex gap-2 items-center mb-2" wire:key="hl-{{ $i }}">
                        <x-icon name="o-check-circle" class="w-4 h-4 text-emerald-500 flex-shrink-0" />
                        <x-input wire:model="highlights.{{ $i }}" class="flex-1" placeholder="{{ __('Highlight :n', ['n' => $i + 1]) }}" />
                        @if(count($highlights) > 1)
                            <x-button icon="o-trash" wire:click.prevent="removeHighlightItem({{ $i }})" class="btn-xs btn-ghost btn-circle text-rose-400" />
                        @endif
                    </div>
                @endforeach

                <div class="flex items-center justify-between mb-3 mt-4">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Highlights (English)') }}</div>
                    <x-button label="{{ __('+ Add') }}" wire:click.prevent="addEnItem('highlights_en')" class="btn-xs btn-ghost" />
                </div>
                @foreach($highlights_en as $i => $item)
                    <div class="flex gap-2 items-center mb-2" wire:key="hl-en-{{ $i }}">
                        <x-icon name="o-check-circle" class="w-4 h-4 text-slate-400 flex-shrink-0" />
                        <x-input wire:model="highlights_en.{{ $i }}" class="flex-1" placeholder="{{ __('Highlight :n', ['n' => $i + 1]) }}" />
                        @if(count($highlights_en) > 1)
                            <x-button icon="o-trash" wire:click.prevent="removeEnItem('highlights_en', {{ $i }})" class="btn-xs btn-ghost btn-circle text-rose-400" />
                        @endif
                    </div>
                @endforeach

                {{-- ===== SECTION: What's Included ===== --}}
                <hr class="border-base-200 my-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __("What's Included") }}</div>
                    <x-button label="{{ __('+ Add') }}" wire:click.prevent="addIncludesItem" class="btn-xs btn-ghost" />
                </div>
                @foreach($includes as $i => $item)
                    <div class="flex gap-2 items-center mb-2" wire:key="inc-{{ $i }}">
                        <x-icon name="o-document-text" class="w-4 h-4 text-blue-400 flex-shrink-0" />
                        <x-input wire:model="includes.{{ $i }}" class="flex-1" placeholder="{{ __('Item :n', ['n' => $i + 1]) }}" />
                        @if(count($includes) > 1)
                            <x-button icon="o-trash" wire:click.prevent="removeIncludesItem({{ $i }})" class="btn-xs btn-ghost btn-circle text-rose-400" />
                        @endif
                    </div>
                @endforeach

                <div class="flex items-center justify-between mb-3 mt-4">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __("What's Included (English)") }}</div>
                    <x-button label="{{ __('+ Add') }}" wire:click.prevent="addEnItem('includes_en')" class="btn-xs btn-ghost" />
                </div>
                @foreach($includes_en as $i => $item)
                    <div class="flex gap-2 items-center mb-2" wire:key="inc-en-{{ $i }}">
                        <x-icon name="o-document-text" class="w-4 h-4 text-slate-400 flex-shrink-0" />
                        <x-input wire:model="includes_en.{{ $i }}" class="flex-1" placeholder="{{ __('Item :n', ['n' => $i + 1]) }}" />
                        @if(count($includes_en) > 1)
                            <x-button icon="o-trash" wire:click.prevent="removeEnItem('includes_en', {{ $i }})" class="btn-xs btn-ghost btn-circle text-rose-400" />
                        @endif
                    </div>
                @endforeach

                {{-- ===== SECTION: Files ===== --}}
                <hr class="border-base-200 my-4">
                <div class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-3">{{ __('Project Files (Click to Preview)') }}</div>

                {{-- THUMBNAILS EXISTING FILES --}}
                @if($editingId && count($existing_files) > 0)
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        @foreach($existing_files as $file)
                            @php
                                $ext = pathinfo($file->file_url, PATHINFO_EXTENSION);
                                $cat = $categorizeFile($file->file_type, $ext, '', $file->file_name ?: basename($file->file_url));
                                $imgSrc = $resolveFileUrl($file->file_url);
                            @endphp

                            <div class="relative group rounded-lg overflow-hidden border border-base-300 aspect-square bg-base-200 flex flex-col items-center justify-center">
                                <div @click="$dispatch('open-lightbox', { index: {{ $itemIndexCounter }}, items: gallery })"
                                     class="w-full h-full cursor-pointer flex flex-col items-center justify-center text-center p-2">
                                    @if($cat['type'] === 'image')
                                        <img src="{{ $imgSrc }}" class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    @else
                                        <x-icon name="{{ $cat['icon'] }}" class="w-8 h-8 text-primary mb-1" />
                                        <div class="text-[10px] text-gray-500 break-all line-clamp-2 w-full">{{ $cat['name'] }}</div>
                                    @endif
                                </div>
                                @php $itemIndexCounter++; @endphp

                                @if($file->file_size)
                                    <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-[9px] px-1 py-0.5 text-center truncate">{{ $file->file_size }}</div>
                                @endif

                                <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <x-button icon="o-trash" wire:click.prevent="removeExistingFile({{ $file->id }})" class="btn-xs btn-circle btn-error text-white shadow-md" tooltip="{{ __('Delete File') }}" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- UPLOAD NEW FILES --}}
                <x-file wire:model="new_files" label="{{ __('Upload New Files') }}" multiple hint="{{ __('Support 3D, Video, PDF, Images, etc.') }}" />

                {{-- THUMBNAILS NEW UPLOADS --}}
                @if($new_files && count($new_files) > 0)
                    <div class="grid grid-cols-3 gap-3 mt-4">
                        @foreach($new_files as $index => $file)
                            @php
                                $ext = $file->getClientOriginalExtension() ?: $file->guessExtension();
                                $cat = $categorizeFile($file->getMimeType(), $ext, '', $file->getClientOriginalName());
                            @endphp

                            <div class="relative group rounded-lg overflow-hidden border border-primary/50 aspect-square bg-base-100 flex flex-col items-center justify-center p-1 shadow-sm">
                                <div @click="$dispatch('open-lightbox', { index: {{ $itemIndexCounter }}, items: gallery })"
                                     class="w-full h-full cursor-pointer flex flex-col items-center justify-center text-center">
                                    @if($cat['type'] === 'image')
                                        <img src="{{ $file->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover rounded hover:scale-105 transition-transform duration-300">
                                    @else
                                        <x-icon name="{{ $cat['icon'] }}" class="w-8 h-8 text-primary mb-1" />
                                        <div class="text-[9px] font-medium text-primary px-1 break-all line-clamp-2 w-full">{{ $file->getClientOriginalName() }}</div>
                                    @endif
                                </div>
                                @php $itemIndexCounter++; @endphp

                                <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <x-button icon="o-x-mark" wire:click.prevent="removeNewFile({{ $index }})" class="btn-xs btn-circle btn-error text-white shadow-md" tooltip="{{ __('Cancel Upload') }}" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <x-slot:actions>
                    <x-button label="{{ __('Cancel') }}" @click="$wire.drawerOpen = false" class="btn-ghost" />
                    <x-button label="{{ __('Save Project') }}" class="btn-primary" type="submit" spinner="save" />
                </x-slot:actions>
            </x-form>
        </div>
    </x-drawer>

    {{-- MODAL DELETE --}}
    <x-modal wire:model="deleteModalOpen" title="{{ __('Confirm Deletion') }}" separator>
        <div class="py-4 text-base-content/80">
            {{ __('Are you sure you want to permanently delete this open source project and all its attached files? This action cannot be undone.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModalOpen = false" class="btn-ghost" />
            <x-button label="{{ __('Yes, Delete') }}" class="btn-error" wire:click="deleteRecord" spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>

    {{-- PANGGIL KOMPONEN GLOBAL LIGHTBOX --}}
    <x-omni-lightbox />
</div>
