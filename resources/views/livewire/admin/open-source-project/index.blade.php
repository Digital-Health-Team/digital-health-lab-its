<div>
    {{-- HEADER --}}
    <x-header title="{{ __('Open Source Projects') }}" subtitle="{{ __('Manage individual public project submissions') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add Project') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- FILTER BAR --}}
    <x-card class="p-0 overflow-hidden shadow-sm border border-base-200 bg-base-100">
        <div class="p-4 bg-base-200/30 border-b border-base-200">
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
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-100">
                        <th class="w-12 text-center text-xs uppercase tracking-wider text-gray-500">#</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Project Title & Category') }}</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Creator') }}</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Files') }}</th>
                        <th class="text-center text-xs uppercase tracking-wider text-gray-500">{{ __('Moderation') }}</th>
                        <th class="text-right text-xs uppercase tracking-wider text-gray-500">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr wire:key="osp-{{ $project->id }}" class="hover:bg-base-200/50 transition-colors">
                            <td class="text-center text-gray-400 font-medium text-sm">{{ $loop->iteration + ($projects->firstItem() - 1) }}</td>
                            <td>
                                <div class="font-bold text-base-content text-base">{{ $project->title }}</div>
                                <div class="text-xs text-gray-500"><span class="badge badge-outline badge-sm mt-1">{{ str_replace('_', ' ', $project->category) }}</span></div>
                            </td>
                            <td>
                                <div class="font-semibold text-sm">{{ $project->user->profile?->full_name ?? $project->user->email }}</div>
                                <div class="text-[10px] text-gray-400 font-mono">{{ $project->created_at->format('d M Y') }}</div>
                            </td>
                            <td>
                                <div class="flex items-center gap-1 text-sm font-medium text-gray-500">
                                    <x-icon name="o-paper-clip" class="w-4 h-4" /> {{ $project->attachments->count() }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="badge {{ match($project->status) { 'approved' => 'badge-success', 'rejected' => 'badge-error', default => 'badge-warning' } }} text-white uppercase text-[10px] font-bold tracking-wider mb-1">
                                    {{ $project->status }}
                                </div>
                                @if($project->status !== 'pending' && $project->validator)
                                    <div class="text-[10px] text-gray-400 leading-tight">By: {{ $project->validator->name }}</div>
                                @endif
                            </td>
                            <td class="text-right flex justify-end gap-1">
                                @if($project->status !== 'approved')
                                    <x-button icon="o-check" wire:click="updateStatus({{ $project->id }}, 'approved')" class="btn-sm btn-circle btn-ghost text-green-500 hover:bg-green-50" tooltip="{{ __('Approve') }}" />
                                @endif
                                @if($project->status !== 'rejected')
                                    <x-button icon="o-x-mark" wire:click="updateStatus({{ $project->id }}, 'rejected')" class="btn-sm btn-circle btn-ghost text-orange-500 hover:bg-orange-50" tooltip="{{ __('Reject') }}" />
                                @endif
                                <div class="w-px h-6 bg-base-300 mx-1 my-auto"></div>
                                <x-button icon="o-pencil-square" wire:click="edit({{ $project->id }})" class="btn-sm btn-circle btn-ghost text-blue-500 hover:bg-blue-50" tooltip="{{ __('Edit / View Files') }}" />
                                <x-button icon="o-trash" wire:click="confirmDelete({{ $project->id }})" class="btn-sm btn-circle btn-ghost text-red-500 hover:bg-red-50" tooltip="{{ __('Delete') }}" />
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-16 text-gray-400">{{ __('No open source projects found.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($projects->hasPages())
            <div class="p-4 border-t border-base-200 bg-base-50">{{ $projects->links() }}</div>
        @endif
    </x-card>

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
