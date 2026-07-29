<div>
    <x-header title="{{ __('Team Sections') }}" subtitle="{{ __('Manage landing page chapters — leaders, members, and gallery images') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add Section') }}" icon="o-plus" class="btn-primary" wire:click="createSection" />
        </x-slot:actions>
    </x-header>

    {{-- Section Cards --}}
    <div class="space-y-6">
        @forelse($sections as $section)
            <div wire:key="sec-{{ $section->id }}"
                 class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">

                {{-- Card Header --}}
                <div class="flex items-center justify-between px-6 py-4 bg-slate-50 dark:bg-slate-800/40 border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <span class="font-mono font-bold text-xs bg-[#082A55] text-white px-2.5 py-1 rounded-md">
                            #{{ str_pad($section->sort_order, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-slate-100">{{ $section->label_en }}</h3>
                            <p class="text-xs text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ $section->label_id }}</p>
                        </div>
                        @if(!$section->is_active)
                            <span class="text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 px-2 py-0.5 rounded-md">
                                {{ __('Hidden') }}
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <x-button label="{{ __('Edit Section') }}" icon="o-pencil-square" size="sm"
                            wire:click="editSection({{ $section->id }})"
                            class="btn-ghost btn-sm border border-slate-200 dark:border-slate-700" />
                        <x-button label="{{ __('Edit Leader') }}" icon="o-user-circle" size="sm"
                            wire:click="editLeader({{ $section->id }})"
                            class="btn-ghost btn-sm border border-slate-200 dark:border-slate-700" />
                    </div>
                </div>

                {{-- Leader Preview --}}
                @if($section->leader)
                    <div class="px-6 py-4 flex items-center gap-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden shrink-0">
                            @if($section->leader->photo_url)
                                <img src="{{ asset('storage/'.$section->leader->photo_url) }}" alt="{{ $section->leader->name_full }}" class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xs font-bold text-slate-500 dark:text-slate-400">
                                    {{ $section->leader->initials }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-slate-800 dark:text-slate-100">{{ $section->leader->name_full }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">{{ $section->leader->role_id }} · {{ $section->leader->role_en }}</p>
                        </div>
                    </div>
                @else
                    <div class="px-6 py-3 border-b border-slate-100 dark:border-slate-800">
                        <p class="text-xs italic text-slate-400 dark:text-slate-500">{{ __('No leader set — click "Edit Leader" to add one.') }}</p>
                    </div>
                @endif

                {{-- Members + Gallery row --}}
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-100 dark:divide-slate-800">

                    {{-- Members column --}}
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                                {{ __('Members') }} ({{ $section->members->count() }})
                            </h4>
                            <x-button label="{{ __('Add Member') }}" icon="o-plus" size="sm"
                                wire:click="createMember({{ $section->id }})"
                                class="btn-ghost btn-sm border border-slate-200 dark:border-slate-700" />
                        </div>

                        @if($section->members->isEmpty())
                            <p class="text-xs italic text-slate-400 dark:text-slate-500">{{ __('No members yet.') }}</p>
                        @else
                            <ul class="space-y-2">
                                @foreach($section->members as $member)
                                    <li wire:key="mem-{{ $member->id }}" class="flex items-center gap-3 group">
                                        <span class="font-mono text-xs text-slate-400 dark:text-slate-500 w-5 shrink-0">
                                            {{ str_pad($member->sort_order, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <div class="w-7 h-7 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden shrink-0">
                                            @if($member->photo_url)
                                                <img src="{{ asset('storage/'.$member->photo_url) }}" alt="{{ $member->name_full }}" class="w-full h-full object-cover" />
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-[10px] font-bold text-slate-500 dark:text-slate-400">
                                                    {{ $member->initials }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 flex-1 min-w-0 truncate">{{ $member->name_full }}</span>
                                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button wire:click="editMember({{ $member->id }})"
                                                class="p-1 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer"
                                                title="{{ __('Edit') }}">
                                                <x-icon name="o-pencil-square" class="w-3.5 h-3.5" />
                                            </button>
                                            <button wire:click="confirmDeletePerson({{ $member->id }})"
                                                class="p-1 rounded-md hover:bg-rose-50 dark:hover:bg-rose-500/20 text-slate-400 hover:text-rose-500 transition-colors cursor-pointer"
                                                title="{{ __('Delete') }}">
                                                <x-icon name="o-trash" class="w-3.5 h-3.5" />
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    {{-- Gallery column --}}
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                                {{ __('Gallery Photos') }} ({{ $section->attachments->count() }})
                            </h4>
                            <x-button label="{{ __('Manage') }}" icon="o-photo" size="sm"
                                wire:click="openGallery({{ $section->id }})"
                                class="btn-ghost btn-sm border border-slate-200 dark:border-slate-700" />
                        </div>

                        @if($section->attachments->isEmpty())
                            <p class="text-xs italic text-slate-400 dark:text-slate-500">{{ __('No gallery photos yet.') }}</p>
                        @else
                            <div class="flex flex-wrap gap-2">
                                @foreach($section->attachments->take(8) as $att)
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-200 dark:bg-slate-700 border border-slate-200 dark:border-slate-700">
                                        <img src="{{ $att->file_url }}" alt="" class="w-full h-full object-cover" />
                                    </div>
                                @endforeach
                                @if($section->attachments->count() > 8)
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-xs font-bold text-slate-500 dark:text-slate-400">
                                        +{{ $section->attachments->count() - 8 }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-20 text-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl">
                <x-icon name="o-user-group" class="w-12 h-12 mb-3 text-slate-300 dark:text-slate-600" />
                <p class="text-slate-500 dark:text-slate-400">{{ __('No team sections yet. Add your first section.') }}</p>
            </div>
        @endforelse
    </div>

    {{-- ══════════════════════════════════════════════════════
         DRAWER 1 — Edit Section
         ══════════════════════════════════════════════════════ --}}
    <x-drawer wire:model="sectionDrawerOpen"
        title="{{ $editingSectionId ? __('Edit Section') : __('Add New Section') }}"
        right separator with-close-button class="w-11/12 lg:w-1/3">
        <x-form wire:submit="saveSection">
            <x-input label="{{ __('Label (Indonesian)') }}" wire:model="labelId"
                placeholder="e.g. KEPALA LABORATORIUM" required />
            <x-input label="{{ __('Label (English)') }}" wire:model="labelEn"
                placeholder="e.g. Head of Laboratory" required />
            <x-input label="{{ __('Sort Order') }}" wire:model="sortOrder" type="number"
                hint="{{ __('Lower numbers appear first. This sets the chapter number.') }}" required />
            <x-toggle label="{{ __('Active (visible on landing page)') }}" wire:model="isActive" />

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.sectionDrawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="saveSection" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- ══════════════════════════════════════════════════════
         DRAWER 2 — Edit Leader
         ══════════════════════════════════════════════════════ --}}
    <x-drawer wire:model="leaderDrawerOpen" title="{{ __('Edit Leader') }}"
        right separator with-close-button class="w-11/12 lg:w-1/3">
        <x-form wire:submit="saveLeader">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Animated Name Display') }}</p>
                <p class="text-xs text-slate-400 dark:text-slate-500">{{ __('The name appears in two large animated lines on the landing page.') }}</p>
            </div>
            <x-input label="{{ __('Display Line 1') }}" wire:model="leaderLine1"
                placeholder="e.g. Djoko" required />
            <x-input label="{{ __('Display Line 2') }}" wire:model="leaderLine2"
                placeholder='e.g. Kuswanto.' required />

            <x-input label="{{ __('Full Name') }}" wire:model="leaderNameFull"
                placeholder="e.g. Djoko Kuswanto, S.T., M.Biotech." required />
            <x-input label="{{ __('Role (Indonesian)') }}" wire:model="leaderRoleId"
                placeholder="e.g. Kepala Laboratorium IDIG" required />
            <x-input label="{{ __('Role (English)') }}" wire:model="leaderRoleEn"
                placeholder="e.g. Head of IDIG Laboratory" required />
            <x-textarea label="{{ __('Bio / Description') }}" wire:model="leaderBio"
                placeholder="{{ __('Short description shown below the name...') }}" rows="3" />
            <x-input label="{{ __('Initials') }}" wire:model="leaderInitials"
                placeholder="e.g. JK" hint="{{ __('2–3 chars, used as photo fallback') }}" maxlength="4" required />

            <div class="space-y-1 pt-2">
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Profile Page') }}</p>
                <p class="text-xs text-slate-400 dark:text-slate-500">{{ __('Shown on the public team member profile page.') }}</p>
            </div>
            <x-input label="{{ __('Email') }}" wire:model="leaderEmail" type="email"
                placeholder="e.g. name@its.ac.id" />
            <x-input label="{{ __('LinkedIn URL') }}" wire:model="leaderLinkedin"
                placeholder="https://linkedin.com/in/..." />
            <x-input label="{{ __('Instagram URL') }}" wire:model="leaderInstagram"
                placeholder="https://instagram.com/..." />
            <x-input label="{{ __('Expertise Tags') }}" wire:model="leaderExpertise"
                placeholder="e.g. Biosignal Acquisition, Wearable Sensors"
                hint="{{ __('Comma-separated.') }}" />
            <x-textarea label="{{ __('Completed Projects') }}" wire:model="leaderProjects" rows="3"
                placeholder="Prosthetic Hand v2 | Custom EMG-controlled prosthetic prototype | https://..."
                hint="{{ __('One project per line: Title | Description | URL (URL optional).') }}" />
            <x-textarea label="{{ __('Education / Certifications') }}" wire:model="leaderEducation" rows="2"
                placeholder="S.T. Teknik Biomedis, Institut Teknologi Sepuluh Nopember (2015)"
                hint="{{ __('One entry per line.') }}" />

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Profile Photo') }}</label>
                <input type="file" wire:model="leaderPhoto" accept="image/*"
                    class="block w-full text-sm text-slate-500 dark:text-slate-400
                           file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0
                           file:text-sm file:font-semibold file:bg-[#082A55] file:text-white
                           hover:file:bg-[#0A3D7A] cursor-pointer" />
                <div wire:loading wire:target="leaderPhoto" class="mt-1 text-xs text-slate-400">{{ __('Uploading...') }}</div>
                @if($leaderPhoto && !$errors->has('leaderPhoto'))
                    <img src="{{ $leaderPhoto->temporaryUrl() }}" class="mt-2 w-20 h-20 rounded-full object-cover border border-slate-200" alt="preview" />
                @endif
                @error('leaderPhoto') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.leaderDrawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save Leader') }}" class="btn-primary" type="submit" spinner="saveLeader" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- ══════════════════════════════════════════════════════
         DRAWER 3 — Add / Edit Member
         ══════════════════════════════════════════════════════ --}}
    <x-drawer wire:model="memberDrawerOpen"
        title="{{ $editingMemberId ? __('Edit Member') : __('Add Member') }}"
        right separator with-close-button class="w-11/12 lg:w-1/3">
        <x-form wire:submit="saveMember">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Animated Name Display') }}</p>
                <p class="text-xs text-slate-400 dark:text-slate-500">{{ __('Appears in the member card hover detail.') }}</p>
            </div>
            <x-input label="{{ __('Display Line 1') }}" wire:model="memberLine1"
                placeholder="e.g. Jordan" required />
            <x-input label="{{ __('Display Line 2') }}" wire:model="memberLine2"
                placeholder='e.g. Jonathan S.' required />

            <x-input label="{{ __('Full Name') }}" wire:model="memberNameFull"
                placeholder="e.g. Jordan Jonathan Susanto" required />
            <x-input label="{{ __('Role / Description') }}" wire:model="memberRoleId"
                placeholder="e.g. Anggota Riset IDIG" required />
            <x-textarea label="{{ __('Bio') }}" wire:model="memberBio"
                placeholder="{{ __('Short bio shown on hover...') }}" rows="3" />
            <x-input label="{{ __('Initials') }}" wire:model="memberInitials"
                placeholder="e.g. JJ" hint="{{ __('2–3 chars, used as photo fallback') }}" maxlength="4" required />
            <x-input label="{{ __('Display Order') }}" wire:model="memberOrder" type="number"
                hint="{{ __('Lower numbers appear first in the member list') }}" required />

            <div class="space-y-1 pt-2">
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">{{ __('Profile Page') }}</p>
                <p class="text-xs text-slate-400 dark:text-slate-500">{{ __('Shown on the public team member profile page.') }}</p>
            </div>
            <x-input label="{{ __('Email') }}" wire:model="memberEmail" type="email"
                placeholder="e.g. name@its.ac.id" />
            <x-input label="{{ __('LinkedIn URL') }}" wire:model="memberLinkedin"
                placeholder="https://linkedin.com/in/..." />
            <x-input label="{{ __('Instagram URL') }}" wire:model="memberInstagram"
                placeholder="https://instagram.com/..." />
            <x-input label="{{ __('Expertise Tags') }}" wire:model="memberExpertise"
                placeholder="e.g. Biosignal Acquisition, Wearable Sensors"
                hint="{{ __('Comma-separated.') }}" />
            <x-input label="{{ __('Unit') }}" wire:model="memberUnits"
                placeholder="e.g. Manekin, InTech"
                hint="{{ __('Comma-separated. Manekin, Implant, Rehab, InTech, Administrative.') }}" />
            <x-input label="{{ __('Department') }}" wire:model="memberDepartments"
                placeholder="e.g. CAD, CAM, Electronics"
                hint="{{ __('Comma-separated. CAD, CAM, CAE, Electronics, Informatics, Publikasi, Patent, Social Media, Sekretariat.') }}" />
            <x-textarea label="{{ __('PIC (Hospital & Clinicians)') }}" wire:model="memberPic" rows="3"
                placeholder="RSUA&#10;dr. Putri&#10;dr. Gamma"
                hint="{{ __('One entry per line — names contain commas, so this is not comma-separated.') }}" />
            <x-textarea label="{{ __('Completed Projects') }}" wire:model="memberProjects" rows="3"
                placeholder="Prosthetic Hand v2 | Custom EMG-controlled prosthetic prototype | https://..."
                hint="{{ __('One project per line: Title | Description | URL (URL optional).') }}" />
            <x-textarea label="{{ __('Education / Certifications') }}" wire:model="memberEducation" rows="2"
                placeholder="S.T. Teknik Biomedis, Institut Teknologi Sepuluh Nopember (2024)"
                hint="{{ __('One entry per line.') }}" />

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Profile Photo') }}</label>
                <input type="file" wire:model="memberPhoto" accept="image/*"
                    class="block w-full text-sm text-slate-500 dark:text-slate-400
                           file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0
                           file:text-sm file:font-semibold file:bg-[#082A55] file:text-white
                           hover:file:bg-[#0A3D7A] cursor-pointer" />
                <div wire:loading wire:target="memberPhoto" class="mt-1 text-xs text-slate-400">{{ __('Uploading...') }}</div>
                @if($memberPhoto && !$errors->has('memberPhoto'))
                    <img src="{{ $memberPhoto->temporaryUrl() }}" class="mt-2 w-16 h-16 rounded-full object-cover border border-slate-200" alt="preview" />
                @endif
                @error('memberPhoto') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.memberDrawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save Member') }}" class="btn-primary" type="submit" spinner="saveMember" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- ══════════════════════════════════════════════════════
         DRAWER 4 — Gallery
         ══════════════════════════════════════════════════════ --}}
    <x-drawer wire:model="galleryDrawerOpen" title="{{ __('Gallery Photos') }}"
        right separator with-close-button class="w-11/12 lg:w-2/5">

        @if($gallerySection)
            {{-- Existing photos grid --}}
            @if($gallerySection->attachments->isNotEmpty())
                <div class="mb-6">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-3">
                        {{ __('Current Photos') }} ({{ $gallerySection->attachments->count() }})
                    </p>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($gallerySection->attachments as $att)
                            <div wire:key="att-{{ $att->id }}" class="relative group aspect-square rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-700 border border-slate-200 dark:border-slate-700">
                                <img src="{{ $att->file_url }}" alt="" class="w-full h-full object-cover" />
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    <span class="text-white text-xs font-semibold">#{{ $att->sort_order + 1 }}</span>
                                    <button wire:click="confirmDeletePhoto({{ $att->id }})"
                                        class="p-1.5 rounded-lg bg-rose-500 text-white hover:bg-rose-600 transition-colors cursor-pointer">
                                        <x-icon name="o-trash" class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                                @if($att->is_primary)
                                    <span class="absolute top-1 left-1 text-[10px] font-bold bg-amber-400 text-amber-900 px-1.5 py-0.5 rounded-md">
                                        ★
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-2 text-xs text-slate-400 dark:text-slate-500">{{ __('Photos fill collage slots in order (#1 first). ★ = center/featured slot.') }}</p>
                </div>
            @else
                <p class="text-sm text-slate-400 dark:text-slate-500 italic mb-6">{{ __('No photos yet. Upload some below.') }}</p>
            @endif

            {{-- Upload new photos --}}
            <x-form wire:submit="saveGallery">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">{{ __('Upload Photos') }}</label>
                    <input type="file" wire:model="galleryPhotos" accept="image/*" multiple
                        class="block w-full text-sm text-slate-500 dark:text-slate-400
                               file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0
                               file:text-sm file:font-semibold file:bg-[#082A55] file:text-white
                               hover:file:bg-[#0A3D7A] cursor-pointer" />
                    <div wire:loading wire:target="galleryPhotos" class="mt-1 text-xs text-slate-400">{{ __('Processing...') }}</div>
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                        {{ __('Select multiple files. Each photo fills the next available collage slot.') }}
                    </p>
                    @error('galleryPhotos.*') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <x-slot:actions>
                    <x-button label="{{ __('Close') }}" @click="$wire.galleryDrawerOpen = false" class="btn-ghost" />
                    <x-button label="{{ __('Upload') }}" class="btn-primary" type="submit" spinner="saveGallery"
                        :disabled="empty($galleryPhotos)" />
                </x-slot:actions>
            </x-form>
        @endif
    </x-drawer>

    {{-- Delete Person Confirmation --}}
    <x-modal wire:model="deletePersonModalOpen" title="{{ __('Confirm Deletion') }}">
        <div class="py-4">{{ __('Are you sure you want to remove this person? Their profile photo will also be deleted.') }}</div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deletePersonModalOpen = false" />
            <x-button label="{{ __('Delete') }}" class="btn-error" wire:click="deletePerson" />
        </x-slot:actions>
    </x-modal>

    {{-- Delete Photo Confirmation --}}
    <x-modal wire:model="deletePhotoModalOpen" title="{{ __('Delete Photo') }}">
        <div class="py-4">{{ __('Remove this photo from the gallery? This cannot be undone.') }}</div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deletePhotoModalOpen = false" />
            <x-button label="{{ __('Delete') }}" class="btn-error" wire:click="deletePhoto" />
        </x-slot:actions>
    </x-modal>
</div>
