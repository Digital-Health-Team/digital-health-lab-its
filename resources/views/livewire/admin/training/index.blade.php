<div>
    <x-header title="{{ __('Training Workshops') }}" subtitle="{{ __('Manage workshop events and enrollments') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Create Training') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-input placeholder="{{ __('Search by title or instructor...') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" />
        <x-select wire:model.live="filterLevel"
            :options="[['id'=>'Beginner','name'=>__('Beginner')],['id'=>'Intermediate','name'=>__('Intermediate')],['id'=>'Advanced','name'=>__('Advanced')]]"
            placeholder="{{ __('All Levels') }}" />
        <x-select wire:model.live="filterStatus"
            :options="[['id'=>'active','name'=>__('Active')],['id'=>'inactive','name'=>__('Archived')]]"
            placeholder="{{ __('All Status') }}" />
    </div>

    {{-- Golden Standard: table container --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <th class="py-3 px-6 text-center w-12">#</th>
                        <th class="py-3 px-6">{{ __('Training Info') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Level / Price') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Participants') }}</th>
                        <th class="py-3 px-6">{{ __('Status') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($trainings as $training)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-6 text-center text-slate-400 dark:text-slate-500 font-mono text-xs">{{ $loop->iteration }}</td>
                            <td class="py-4 px-6 max-w-xs">
                                <div class="font-bold text-slate-800 dark:text-slate-200 truncate">{{ $training->title }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    <x-icon name="o-user" class="w-3 h-3 inline" /> {{ $training->instructor_name }}
                                </div>
                                @if($training->date)
                                    <div class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">
                                        <x-icon name="o-calendar" class="w-3 h-3 inline" /> {{ $training->date->format('d M Y, H:i') }}
                                        @if($training->location)
                                            &middot; {{ $training->location }}
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider
                                        {{ $training->level === 'Beginner' ? 'bg-sky-50 text-sky-600 border border-sky-200 dark:bg-sky-500/10 dark:text-sky-400 dark:border-sky-500/20' : '' }}
                                        {{ $training->level === 'Intermediate' ? 'bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20' : '' }}
                                        {{ $training->level === 'Advanced' ? 'bg-rose-50 text-rose-600 border border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20' : '' }}">
                                        {{ $training->level }}
                                    </span>
                                    @if($training->is_paid)
                                        <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                                            Rp {{ number_format($training->price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-xs font-semibold text-slate-400">{{ __('Free') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $training->registrations_count }}</span>
                                @if($training->max_participants)
                                    <span class="text-xs text-slate-400"> / {{ $training->max_participants }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($training->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">{{ __('Active') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">{{ __('Archived') }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.trainings.show', $training->slug) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors">
                                        {{ __('Participants') }} <x-icon name="o-arrow-right" class="w-3.5 h-3.5" />
                                    </a>
                                    <button wire:click="edit({{ $training->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Edit') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    <button wire:click="confirmDelete({{ $training->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-rose-100 hover:text-rose-600 hover:border-rose-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-rose-500/20 dark:hover:text-rose-400 dark:hover:border-rose-500/30 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Delete') }}">
                                        <x-icon name="o-trash" class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <x-icon name="o-academic-cap" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                                <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('No training workshops found.') }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ __('Create your first training to get started.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">{{ $trainings->links() }}</div>
    </div>

    {{-- Create / Edit Drawer --}}
    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit Training') : __('Create Training') }}" right separator class="w-full lg:w-[600px]">
        <x-form wire:submit="save">
            {{-- Basic Info --}}
            <div class="space-y-4">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 pt-2">{{ __('Basic Info') }}</p>
                <x-input label="{{ __('Title') }}" wire:model="title" required />
                <x-input label="{{ __('Title (English)') }}" wire:model="title_en" hint="{{ __('Optional — falls back to Indonesian when empty.') }}" />
                <x-input label="{{ __('Subtitle') }}" wire:model="subtitle" />
                <x-input label="{{ __('Subtitle (English)') }}" wire:model="subtitle_en" hint="{{ __('Optional — falls back to Indonesian when empty.') }}" />
                <x-textarea label="{{ __('Description') }}" wire:model="description" rows="3" />
                <x-textarea label="{{ __('Description (English)') }}" wire:model="description_en" rows="3" hint="{{ __('Optional — falls back to Indonesian when empty.') }}" />
                <x-input label="{{ __('Thumbnail URL') }}" wire:model="thumbnail_url" placeholder="https://..." />
            </div>

            {{-- Schedule --}}
            <div class="space-y-4 mt-6">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Schedule') }}</p>
                <x-input label="{{ __('Date & Time') }}" wire:model="date" type="datetime-local" required />
                <x-input label="{{ __('Location') }}" wire:model="location" placeholder="{{ __('e.g. Lab A or Online via Zoom') }}" />
                <x-input label="{{ __('Location (English)') }}" wire:model="location_en" hint="{{ __('Optional — falls back to Indonesian when empty.') }}" />
                <x-input label="{{ __('Max Participants') }}" wire:model="max_participants" type="number" min="1" placeholder="{{ __('Leave blank for unlimited') }}" />
            </div>

            {{-- Details --}}
            <div class="space-y-4 mt-6">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Details') }}</p>
                <x-select label="{{ __('Level') }}" wire:model="level" required
                    :options="[['id'=>'Beginner','name'=>'Beginner'],['id'=>'Intermediate','name'=>'Intermediate'],['id'=>'Advanced','name'=>'Advanced']]" />
                <x-input label="{{ __('Duration') }}" wire:model="duration" placeholder="{{ __('e.g. 5h 30m') }}" />
                <x-input label="{{ __('Language') }}" wire:model="language" />
                <div class="grid grid-cols-2 gap-4">
                    <x-input label="{{ __('Price (IDR)') }}" wire:model="price" type="number" min="0" required />
                    <div class="space-y-2 pt-1">
                        <x-checkbox label="{{ __('Paid Event') }}" wire:model="is_paid" />
                        <x-checkbox label="{{ __('Active') }}" wire:model="is_active" />
                        <x-checkbox label="{{ __('Featured (Staff Pick)') }}" wire:model="is_featured" />
                    </div>
                </div>
            </div>

            {{-- Instructor --}}
            <div class="space-y-4 mt-6">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Instructor') }}</p>
                <x-input label="{{ __('Instructor Name') }}" wire:model="instructor_name" required />
                <x-input label="{{ __('Instructor Title') }}" wire:model="instructor_title" placeholder="{{ __('e.g. Senior Biomedical Engineer') }}" />
                <x-input label="{{ __('Instructor Title (English)') }}" wire:model="instructor_title_en" hint="{{ __('Optional — falls back to Indonesian when empty.') }}" />
                <x-textarea label="{{ __('Instructor Bio') }}" wire:model="instructor_bio" rows="2" />
                <x-textarea label="{{ __('Instructor Bio (English)') }}" wire:model="instructor_bio_en" rows="2" hint="{{ __('Optional — falls back to Indonesian when empty.') }}" />
                <x-input label="{{ __('Instructor Avatar URL') }}" wire:model="instructor_avatar_url" placeholder="https://..." />
            </div>

            {{-- What You Will Learn --}}
            <div class="mt-6">
                <hr class="border-slate-200 dark:border-slate-700 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('What You Will Learn') }}</p>
                    <x-button label="{{ __('+ Add Item') }}" wire:click.prevent="addWhatYouWillLearnItem" class="btn-xs btn-ghost" />
                </div>
                @foreach($what_you_will_learn as $i => $item)
                    <div class="flex gap-2 items-center mb-2" wire:key="wyll-{{ $i }}">
                        <x-icon name="o-check-circle" class="w-4 h-4 text-emerald-500 shrink-0" />
                        <x-input wire:model="what_you_will_learn.{{ $i }}" class="flex-1"
                            placeholder="{{ __('e.g. Understand FDM vs resin printing') }}" />
                        @if(count($what_you_will_learn) > 1)
                            <x-button icon="o-trash" wire:click.prevent="removeWhatYouWillLearnItem({{ $i }})"
                                class="btn-xs btn-ghost btn-circle text-rose-400" />
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- What You Will Learn (English) --}}
            <div class="mt-6">
                <hr class="border-slate-200 dark:border-slate-700 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('What You Will Learn (English)') }}</p>
                    <x-button label="{{ __('+ Add Item') }}" wire:click.prevent="addEnItem('what_you_will_learn_en')" class="btn-xs btn-ghost" />
                </div>
                @foreach($what_you_will_learn_en as $i => $item)
                    <div class="flex gap-2 items-center mb-2" wire:key="wyll-en-{{ $i }}">
                        <x-icon name="o-check-circle" class="w-4 h-4 text-slate-400 shrink-0" />
                        <x-input wire:model="what_you_will_learn_en.{{ $i }}" class="flex-1"
                            placeholder="{{ __('e.g. Understand FDM vs resin printing') }}" />
                        @if(count($what_you_will_learn_en) > 1)
                            <x-button icon="o-trash" wire:click.prevent="removeEnItem('what_you_will_learn_en', {{ $i }})"
                                class="btn-xs btn-ghost btn-circle text-rose-400" />
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Includes --}}
            <div class="mt-6">
                <hr class="border-slate-200 dark:border-slate-700 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('This Course Includes') }}</p>
                    <x-button label="{{ __('+ Add Item') }}" wire:click.prevent="addIncludesItem" class="btn-xs btn-ghost" />
                </div>
                @foreach($includes as $i => $item)
                    <div class="flex gap-2 items-center mb-2" wire:key="inc-{{ $i }}">
                        <x-icon name="o-document-text" class="w-4 h-4 text-blue-400 shrink-0" />
                        <x-input wire:model="includes.{{ $i }}" class="flex-1"
                            placeholder="{{ __('e.g. 5h on-demand video') }}" />
                        @if(count($includes) > 1)
                            <x-button icon="o-trash" wire:click.prevent="removeIncludesItem({{ $i }})"
                                class="btn-xs btn-ghost btn-circle text-rose-400" />
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- This Course Includes (English) --}}
            <div class="mt-6">
                <hr class="border-slate-200 dark:border-slate-700 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('This Course Includes (English)') }}</p>
                    <x-button label="{{ __('+ Add Item') }}" wire:click.prevent="addEnItem('includes_en')" class="btn-xs btn-ghost" />
                </div>
                @foreach($includes_en as $i => $item)
                    <div class="flex gap-2 items-center mb-2" wire:key="inc-en-{{ $i }}">
                        <x-icon name="o-document-text" class="w-4 h-4 text-slate-400 shrink-0" />
                        <x-input wire:model="includes_en.{{ $i }}" class="flex-1"
                            placeholder="{{ __('e.g. 5h on-demand video') }}" />
                        @if(count($includes_en) > 1)
                            <x-button icon="o-trash" wire:click.prevent="removeEnItem('includes_en', {{ $i }})"
                                class="btn-xs btn-ghost btn-circle text-rose-400" />
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Curriculum --}}
            <div class="mt-6">
                <hr class="border-slate-200 dark:border-slate-700 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Curriculum') }}</p>
                    <x-button label="{{ __('+ Add Module') }}" wire:click.prevent="addModule" class="btn-xs btn-ghost" />
                </div>
                @foreach($curriculum_modules as $mi => $mod)
                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-3 mb-3" wire:key="mod-{{ $mi }}">
                        {{-- Module name --}}
                        <div class="flex gap-2 items-center mb-3">
                            <x-icon name="o-academic-cap" class="w-4 h-4 text-indigo-400 shrink-0" />
                            <x-input wire:model="curriculum_modules.{{ $mi }}.module" class="flex-1 font-semibold"
                                placeholder="{{ __('Module name, e.g. Module 1 — Foundations') }}" />
                            @if(count($curriculum_modules) > 1)
                                <x-button icon="o-trash" wire:click.prevent="removeModule({{ $mi }})"
                                    class="btn-xs btn-ghost btn-circle text-rose-400" />
                            @endif
                        </div>
                        {{-- Lessons --}}
                        @foreach($mod['lessons'] as $li => $lesson)
                            <div class="flex gap-2 items-center mb-1.5 ml-6" wire:key="mod-{{ $mi }}-lesson-{{ $li }}">
                                <x-icon name="o-play-circle" class="w-3.5 h-3.5 text-slate-400 shrink-0" />
                                <x-input wire:model="curriculum_modules.{{ $mi }}.lessons.{{ $li }}" class="flex-1 text-sm"
                                    placeholder="{{ __('Lesson title') }}" />
                                @if(count($mod['lessons']) > 1)
                                    <x-button icon="o-trash" wire:click.prevent="removeLesson({{ $mi }}, {{ $li }})"
                                        class="btn-xs btn-ghost btn-circle text-rose-300" />
                                @endif
                            </div>
                        @endforeach
                        <x-button label="{{ __('+ Add Lesson') }}" wire:click.prevent="addLesson({{ $mi }})"
                            class="btn-xs btn-ghost ml-6 mt-1" />
                    </div>
                @endforeach
            </div>

            {{-- Curriculum (English) --}}
            <div class="mt-6">
                <hr class="border-slate-200 dark:border-slate-700 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Curriculum (English)') }}</p>
                    <x-button label="{{ __('+ Add Module') }}" wire:click.prevent="addEnModule" class="btn-xs btn-ghost" />
                </div>
                @foreach($curriculum_modules_en as $mi => $mod)
                    <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-3 mb-3" wire:key="mod-en-{{ $mi }}">
                        <div class="flex gap-2 items-center mb-3">
                            <x-icon name="o-academic-cap" class="w-4 h-4 text-indigo-400 shrink-0" />
                            <x-input wire:model="curriculum_modules_en.{{ $mi }}.module" class="flex-1 font-semibold"
                                placeholder="{{ __('Module name, e.g. Module 1 — Foundations') }}" />
                            @if(count($curriculum_modules_en) > 1)
                                <x-button icon="o-trash" wire:click.prevent="removeEnModule({{ $mi }})"
                                    class="btn-xs btn-ghost btn-circle text-rose-400" />
                            @endif
                        </div>
                        @foreach($mod['lessons'] as $li => $lesson)
                            <div class="flex gap-2 items-center mb-1.5 ml-6" wire:key="mod-en-{{ $mi }}-lesson-{{ $li }}">
                                <x-icon name="o-play-circle" class="w-3.5 h-3.5 text-slate-400 shrink-0" />
                                <x-input wire:model="curriculum_modules_en.{{ $mi }}.lessons.{{ $li }}" class="flex-1 text-sm"
                                    placeholder="{{ __('Lesson title') }}" />
                                @if(count($mod['lessons']) > 1)
                                    <x-button icon="o-trash" wire:click.prevent="removeEnLesson({{ $mi }}, {{ $li }})"
                                        class="btn-xs btn-ghost btn-circle text-rose-300" />
                                @endif
                            </div>
                        @endforeach
                        <x-button label="{{ __('+ Add Lesson') }}" wire:click.prevent="addEnLesson({{ $mi }})"
                            class="btn-xs btn-ghost ml-6 mt-1" />
                    </div>
                @endforeach
            </div>

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" wire:click="$set('drawerOpen', false)" />
                <x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- Delete Confirmation Modal --}}
    <x-modal wire:model="deleteModalOpen" title="{{ __('Delete Training') }}">
        <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Are you sure? Trainings with confirmed participants cannot be deleted.') }}</p>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" wire:click="$set('deleteModalOpen', false)" />
            <x-button label="{{ __('Delete') }}" class="btn-error" wire:click="deleteRecord" spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>

    {{-- Toggle Status Confirmation Modal --}}
    <x-modal wire:model="toggleModalOpen" title="{{ __('Toggle Status') }}">
        <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Are you sure you want to change this training\'s status?') }}</p>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" wire:click="$set('toggleModalOpen', false)" />
            <x-button label="{{ __('Confirm') }}" class="btn-primary" wire:click="toggleStatus" spinner="toggleStatus" />
        </x-slot:actions>
    </x-modal>
</div>
