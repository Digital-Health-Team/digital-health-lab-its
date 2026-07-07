<div class="space-y-5 animate-[fade-in_0.4s_ease-out]">

    {{-- ============================================ --}}
    {{-- PAGE HEADER                                   --}}
    {{-- ============================================ --}}
    <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ __('Tools') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            {{ __('Catalogue physical tools and equipment stored across all labs.') }}
        </p>
    </div>

    {{-- ============================================ --}}
    {{-- POS LAYOUT                                    --}}
    {{-- ============================================ --}}
    <div class="flex gap-5 items-start">

        {{-- ─── LEFT: browse panel ──────────────────── --}}
        <div class="{{ $activeTool ? 'flex-1 min-w-0' : 'w-full' }} space-y-4">

            {{-- Sub-header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                        <x-icon name="o-wrench-screwdriver" class="w-4 h-4 text-indigo-500 dark:text-indigo-400" />
                        {{ __('All Tools') }}
                        @if($tools->total() > 0)
                            <span class="text-xs font-medium text-slate-400 dark:text-slate-500">({{ $tools->total() }})</span>
                        @endif
                    </h2>
                </div>
                <button wire:click="create"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold
                           bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-400 text-white shadow-sm transition-colors cursor-pointer shrink-0">
                    <x-icon name="o-plus" class="w-4 h-4" />
                    {{ __('Add Tool') }}
                </button>
            </div>

            {{-- Search --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-icon name="o-magnifying-glass" class="w-4 h-4 text-slate-400 dark:text-slate-500" />
                </div>
                <input type="text" wire:model.live.debounce.500ms="search"
                    placeholder="{{ __('Search by tool name...') }}"
                    class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700
                           text-sm text-slate-700 dark:text-slate-300 placeholder-slate-400 dark:placeholder-slate-600
                           focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400 dark:focus:border-indigo-500 transition-shadow shadow-sm" />
            </div>

            {{-- CARD GRID --}}
            <div class="grid grid-cols-2 xl:grid-cols-3 gap-3">
                @forelse($tools as $tool)
                    @php $isSelected = $activeTool?->id === $tool->id; @endphp
                    <div wire:click="viewTool({{ $tool->id }})"
                        wire:key="tool-card-{{ $tool->id }}"
                        class="group bg-white dark:bg-slate-900 rounded-2xl border transition-all duration-150 cursor-pointer overflow-hidden
                               {{ $isSelected
                                    ? 'border-indigo-500 dark:border-indigo-400 ring-2 ring-indigo-500/20 dark:ring-indigo-400/20 shadow-md'
                                    : 'border-slate-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-600/50 hover:shadow-md' }}">

                        {{-- Thumbnail --}}
                        <div class="aspect-[4/3] bg-slate-100 dark:bg-slate-800 relative overflow-hidden">
                            @if($tool->primaryAttachment)
                                <img src="{{ $tool->primaryAttachment->file_url }}"
                                    alt="{{ $tool->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <x-icon name="o-wrench-screwdriver" class="w-10 h-10 text-slate-300 dark:text-slate-600" />
                                </div>
                            @endif
                        </div>

                        {{-- Card body --}}
                        <div class="px-4 pt-3 pb-2">
                            <p class="font-black text-slate-800 dark:text-slate-100 text-sm leading-tight truncate">
                                {{ $tool->name }}
                            </p>
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider
                                         bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/30 truncate max-w-full">
                                {{ $tool->lab->name }}
                            </span>
                        </div>

                        {{-- Card footer --}}
                        <div class="border-t border-slate-100 dark:border-slate-800 px-4 py-2 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400 dark:text-slate-500">
                                {{ $tool->created_at->format('d M Y') }}
                            </span>
                            <div class="flex items-center gap-1" wire:click.stop>
                                <button wire:click="edit({{ $tool->id }})"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:text-indigo-400 dark:hover:bg-indigo-500/10 transition-colors cursor-pointer"
                                    title="{{ __('Edit') }}">
                                    <x-icon name="o-pencil-square" class="w-3.5 h-3.5" />
                                </button>
                                <button wire:click="confirmDelete({{ $tool->id }})"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:text-rose-400 dark:hover:bg-rose-500/10 transition-colors cursor-pointer"
                                    title="{{ __('Delete') }}">
                                    <x-icon name="o-trash" class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 xl:col-span-3 py-20 text-center">
                        <x-icon name="o-wrench-screwdriver" class="w-14 h-14 mx-auto mb-3 text-slate-300 dark:text-slate-700" />
                        <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('No tools found.') }}</p>
                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">{{ __('Try adjusting your search or add a new tool.') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($tools->hasPages())
                <div class="pt-2">
                    {{ $tools->links() }}
                </div>
            @endif

        </div>

        {{-- ─── RIGHT: detail panel ──────────────────── --}}
        @if($activeTool)
        <div class="w-80 xl:w-96 shrink-0 sticky top-20">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">

                {{-- PANEL HEADER --}}
                <div class="flex items-start justify-between bg-slate-50 dark:bg-slate-800/50 px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <div class="min-w-0 flex-1 pr-3">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 dark:text-indigo-400">
                            {{ $activeTool->lab->name }}
                        </p>
                        <h3 class="font-black text-slate-800 dark:text-slate-100 text-lg leading-tight truncate">
                            {{ $activeTool->name }}
                        </h3>
                    </div>
                    <button wire:click="clearTool"
                        class="shrink-0 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200 dark:hover:text-slate-300 dark:hover:bg-slate-700 transition-colors cursor-pointer mt-0.5"
                        title="{{ __('Close') }}">
                        <x-icon name="o-x-mark" class="w-4 h-4" />
                    </button>
                </div>

                {{-- IMAGE GALLERY --}}
                <div class="p-4 space-y-3">
                    @if($activeTool->attachments->count())
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($activeTool->attachments as $attachment)
                                <div class="relative group/photo rounded-xl overflow-hidden aspect-square bg-slate-100 dark:bg-slate-800
                                            {{ $attachment->is_primary ? 'ring-2 ring-indigo-500 dark:ring-indigo-400' : '' }}">
                                    <img src="{{ $attachment->file_url }}"
                                        alt="{{ $attachment->file_name }}"
                                        class="w-full h-full object-cover" />
                                    {{-- overlay actions --}}
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover/photo:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        @if(! $attachment->is_primary)
                                            <button wire:click="setPrimary({{ $attachment->id }})"
                                                class="p-1.5 rounded-lg bg-white/20 hover:bg-white/30 text-white text-[10px] font-bold cursor-pointer"
                                                title="{{ __('Set as primary') }}">
                                                <x-icon name="o-star" class="w-3.5 h-3.5" />
                                            </button>
                                        @endif
                                        <button wire:click="deletePhoto({{ $attachment->id }})"
                                            wire:confirm="{{ __('Remove this photo?') }}"
                                            class="p-1.5 rounded-lg bg-rose-500/80 hover:bg-rose-600 text-white cursor-pointer"
                                            title="{{ __('Delete photo') }}">
                                            <x-icon name="o-trash" class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                    @if($attachment->is_primary)
                                        <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded text-[9px] font-bold bg-indigo-600 text-white uppercase tracking-wider">
                                            {{ __('Primary') }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-dashed border-slate-200 dark:border-slate-700">
                            <x-icon name="o-photo" class="w-8 h-8 mx-auto mb-2 text-slate-300 dark:text-slate-600" />
                            <p class="text-xs text-slate-400 dark:text-slate-500">{{ __('No photos yet') }}</p>
                        </div>
                    @endif

                    {{-- Upload photos --}}
                    @if($showPhotoUpload)
                        <div class="space-y-2">
                            <input type="file" wire:model="newPhotos" multiple accept="image/*"
                                class="block w-full text-xs text-slate-500 dark:text-slate-400
                                       file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                       file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700
                                       dark:file:bg-indigo-500/10 dark:file:text-indigo-400
                                       hover:file:bg-indigo-100 cursor-pointer" />
                            <div class="flex gap-2">
                                <button wire:click="addPhotos"
                                    class="flex-1 py-2 rounded-lg text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white transition-colors cursor-pointer">
                                    <span wire:loading.remove wire:target="addPhotos">{{ __('Upload') }}</span>
                                    <span wire:loading wire:target="addPhotos">{{ __('Uploading...') }}</span>
                                </button>
                                <button wire:click="$set('showPhotoUpload', false); $set('newPhotos', [])"
                                    class="px-3 py-2 rounded-lg text-xs font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                                    {{ __('Cancel') }}
                                </button>
                            </div>
                        </div>
                    @else
                        <button wire:click="$set('showPhotoUpload', true)"
                            class="w-full py-2 rounded-lg border border-dashed border-slate-300 dark:border-slate-700 text-xs font-semibold
                                   text-slate-500 dark:text-slate-400 hover:border-indigo-400 hover:text-indigo-600 dark:hover:border-indigo-500 dark:hover:text-indigo-400 transition-colors cursor-pointer">
                            <x-icon name="o-arrow-up-tray" class="w-3.5 h-3.5 inline mr-1" />
                            {{ __('Add Photos') }}
                        </button>
                    @endif
                </div>

                {{-- META INFO --}}
                <div class="border-t border-slate-100 dark:border-slate-800 px-5 py-4 space-y-3">
                    <div class="flex items-center gap-3">
                        <x-icon name="o-building-office-2" class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" />
                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 dark:text-slate-500">{{ __('Location') }}</p>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $activeTool->lab->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-icon name="o-user" class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" />
                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 dark:text-slate-500">{{ __('Added By') }}</p>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $activeTool->creator->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-icon name="o-calendar-days" class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" />
                        <div>
                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 dark:text-slate-500">{{ __('Added On') }}</p>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                {{ $activeTool->created_at->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @endif

    </div>{{-- end POS flex --}}

    {{-- ============================================ --}}
    {{-- CREATE / EDIT MODAL                           --}}
    {{-- ============================================ --}}
    <x-modal wire:model="formModal" class="backdrop-blur-sm">
        <div class="bg-base-100 dark:bg-[#031026] border border-base-200 dark:border-[#0A3D7A]/50 p-6 rounded-2xl shadow-xl max-w-md mx-auto">

            <div class="mb-6">
                <h3 class="text-xl font-bold text-base-content dark:text-[#F8FAFC]">
                    {{ $editingId ? __('Edit Tool') : __('Add New Tool') }}
                </h3>
                <p class="text-sm text-base-content/60 dark:text-[#94A3B8] mt-1">
                    {{ __('Record a tool or piece of equipment stored in a lab.') }}
                </p>
            </div>

            <form wire:submit="save" class="space-y-5">

                {{-- Tool Name --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        {{ __('Tool Name') }} <span class="text-error">*</span>
                    </label>
                    <input type="text" wire:model="toolName" required
                        placeholder="{{ __('e.g. 3D Printer FDM, Soldering Iron...') }}"
                        class="w-full px-3 py-2.5 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                               text-base-content dark:text-[#F8FAFC] placeholder-slate-400 shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 transition-shadow" />
                    @error('toolName') <span class="block text-xs text-error mt-1.5">{{ $message }}</span> @enderror
                </div>

                {{-- Lab --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        {{ __('Location (Lab)') }} <span class="text-error">*</span>
                    </label>
                    <select wire:model="labId" required
                        class="w-full px-3 py-2.5 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                               text-base-content dark:text-[#F8FAFC] shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-400 transition-shadow">
                        <option value="0">{{ __('Select Lab...') }}</option>
                        @foreach($labOptions as $opt)
                            <option value="{{ $opt->id }}">{{ $opt->name }}</option>
                        @endforeach
                    </select>
                    @error('labId') <span class="block text-xs text-error mt-1.5">{{ $message }}</span> @enderror
                </div>

                {{-- Photos --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                        {{ __('Photos') }}
                        <span class="text-xs font-normal text-slate-400 ml-1">{{ __('(optional, max 5 MB each)') }}</span>
                    </label>
                    <input type="file" wire:model="photos" multiple accept="image/*"
                        class="block w-full text-sm text-slate-500 dark:text-slate-400
                               file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                               file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700
                               dark:file:bg-indigo-500/10 dark:file:text-indigo-400
                               hover:file:bg-indigo-100 cursor-pointer" />
                    <div wire:loading wire:target="photos" class="text-xs text-indigo-500 mt-1">{{ __('Uploading...') }}</div>
                    @error('photos.*') <span class="block text-xs text-error mt-1.5">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-slate-200 dark:border-slate-800">
                    <button type="button" wire:click="$set('formModal', false)"
                        class="px-5 py-2.5 rounded-lg text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-sm font-semibold
                               bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition-colors cursor-pointer">
                        <span wire:loading.remove wire:target="save">{{ $editingId ? __('Update Tool') : __('Add Tool') }}</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <x-icon name="o-arrow-path" class="w-4 h-4 animate-spin" /> {{ __('Saving...') }}
                        </span>
                    </button>
                </div>
            </form>

        </div>
    </x-modal>

    {{-- ============================================ --}}
    {{-- DELETE CONFIRMATION MODAL                      --}}
    {{-- ============================================ --}}
    <x-modal wire:model="deleteModal" class="backdrop-blur-sm">
        <div class="bg-base-100 dark:bg-[#031026] border border-base-200 dark:border-[#0A3D7A]/50 p-6 rounded-2xl shadow-xl max-w-md mx-auto">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-error/10 flex items-center justify-center shrink-0 border border-error/30">
                    <x-icon name="o-exclamation-triangle" class="w-6 h-6 text-error" />
                </div>
                <h3 class="text-xl font-bold text-base-content dark:text-[#F8FAFC]">{{ __('Confirm Deletion') }}</h3>
            </div>
            <p class="text-sm text-base-content/60 dark:text-[#94A3B8] leading-relaxed mb-6">
                {{ __('Are you sure? This will permanently delete the tool and all its photos.') }}
            </p>
            <div class="flex justify-end gap-3">
                <button wire:click="$set('deleteModal', false)"
                    class="px-4 py-2 rounded-lg text-sm font-medium text-base-content/60 hover:bg-base-200/50 transition-colors cursor-pointer">
                    {{ __('Cancel') }}
                </button>
                <button wire:click="deleteRecord"
                    class="px-4 py-2 rounded-lg text-sm font-semibold bg-error hover:bg-error/80 text-white transition-colors cursor-pointer">
                    <span wire:loading.remove wire:target="deleteRecord">{{ __('Delete Tool') }}</span>
                    <span wire:loading wire:target="deleteRecord">{{ __('Deleting...') }}</span>
                </button>
            </div>
        </div>
    </x-modal>

</div>
