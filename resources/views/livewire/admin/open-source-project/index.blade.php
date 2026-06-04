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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <x-input placeholder="{{ __('Search title or creator...') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" />
                <x-select wire:model.live="filterStatus" :options="[['id'=>'pending','name'=>__('Pending')], ['id'=>'approved','name'=>__('Approved')], ['id'=>'rejected','name'=>__('Rejected')]]" placeholder="{{ __('All Status') }}" icon="o-funnel" />
                <x-select wire:model.live="filterCategory" :options="$categories" option-label="name" option-value="id" placeholder="{{ __('All Categories') }}" icon="o-tag" />
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
                                <div class="font-bold text-slate-800 dark:text-slate-200">{{ $project->title }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                        {{ str_replace('_', ' ', $project->category) }}
                                    </span>
                                </div>
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
                                        {{-- Approve: emerald variant of standard button --}}
                                        <button wire:click="updateStatus({{ $project->id }}, 'approved')"
                                            class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-emerald-100 hover:text-emerald-600 hover:border-emerald-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-emerald-500/20 dark:hover:text-emerald-400 dark:hover:border-emerald-500/30 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                            title="{{ __('Approve') }}">
                                            <x-icon name="o-check" class="w-4 h-4" />
                                        </button>
                                    @endif
                                    @if($project->status !== 'rejected')
                                        {{-- Reject: amber variant --}}
                                        <button wire:click="updateStatus({{ $project->id }}, 'rejected')"
                                            class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-amber-100 hover:text-amber-600 hover:border-amber-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-amber-500/20 dark:hover:text-amber-400 dark:hover:border-amber-500/30 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                            title="{{ __('Reject') }}">
                                            <x-icon name="o-x-mark" class="w-4 h-4" />
                                        </button>
                                    @endif
                                    <div class="w-px h-5 bg-slate-200 dark:bg-slate-700 mx-0.5"></div>
                                    {{-- Golden Standard: standard icon button --}}
                                    <button wire:click="edit({{ $project->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Edit / View Files') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    {{-- Golden Standard: danger button --}}
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
                            <td colspan="6" class="text-center py-16">
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

        if ($editingId && $existing_files) {
            foreach ($existing_files as $f) {
                $ext = pathinfo($f->file_url, PATHINFO_EXTENSION);
                $galleryItems[] = $categorizeFile($f->file_type, $ext, asset('storage/' . $f->file_url), basename($f->file_url));
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

        {{-- BUNGKUS DENGAN x-data AGAR DATA JSON AMAN DARI ERROR HTML --}}
        <div x-data="{ gallery: @js($galleryItems) }">
            <x-form wire:submit="save">
                <x-choices label="{{ __('Project Creator') }}" wire:model="user_id" :options="$availableUsers" option-label="name" option-value="id" single searchable required />
                <x-input label="{{ __('Project Title') }}" wire:model="title" required />
                <x-select label="{{ __('Category') }}" wire:model="category" :options="$categories" option-label="name" option-value="id" required />

                <hr class="border-base-200 my-4">
                <div class="font-bold mb-2">{{ __('Project Files (Click to Preview)') }}</div>

                {{-- THUMBNAILS EXISTING FILES --}}
                @if($editingId && count($existing_files) > 0)
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        @foreach($existing_files as $file)
                            @php
                                $ext = pathinfo($file->file_url, PATHINFO_EXTENSION);
                                $cat = $categorizeFile($file->file_type, $ext, '', basename($file->file_url));
                            @endphp

                            <div class="relative group rounded-lg overflow-hidden border border-base-300 aspect-square bg-base-200 flex flex-col items-center justify-center">

                                {{-- Trigger Click memanggil array 'gallery' yg aman --}}
                                <div @click="$dispatch('open-lightbox', { index: {{ $itemIndexCounter }}, items: gallery })"
                                     class="w-full h-full cursor-pointer flex flex-col items-center justify-center text-center p-2">
                                    @if($cat['type'] === 'image')
                                        <img src="{{ asset('storage/' . $file->file_url) }}" class="absolute inset-0 w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    @else
                                        <x-icon name="{{ $cat['icon'] }}" class="w-8 h-8 text-primary mb-1" />
                                        <div class="text-[10px] text-gray-500 break-all line-clamp-2 w-full">{{ $cat['name'] }}</div>
                                    @endif
                                </div>
                                @php $itemIndexCounter++; @endphp

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
                                        <div class="text-[9px] font-medium text-primary px-1 break-all line-clamp-2 w-full">{{ $cat['name'] }}</div>
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
