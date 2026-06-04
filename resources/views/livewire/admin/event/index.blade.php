<div>
    <x-header title="{{ __('Events & Exhibitions') }}" subtitle="{{ __('Manage innovation events') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Create Event') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-input placeholder="{{ __('Search event...') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" />
        <x-select wire:model.live="filterStatus" :options="[['id'=>'active','name'=>__('Active')], ['id'=>'inactive','name'=>__('Archived')]]" placeholder="{{ __('All Status') }}" />
    </div>

    {{-- Golden Standard: table container --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                {{-- Golden Standard: thead row --}}
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <th class="py-3 px-6 text-center w-12">#</th>
                        <th class="py-3 px-6">{{ __('Event Info') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Stats') }}</th>
                        <th class="py-3 px-6">{{ __('Status') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                {{-- Golden Standard: tbody --}}
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($events as $event)
                        {{-- Golden Standard: row hover --}}
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-6 text-center text-slate-400 dark:text-slate-500 font-mono text-xs">{{ $loop->iteration }}</td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800 dark:text-slate-200">{{ $event->name }} ({{ $event->year }})</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 italic mt-0.5">"{{ $event->theme_title }}"</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-3 text-xs font-semibold">
                                    <span class="text-indigo-500 dark:text-indigo-400"><x-icon name="o-user-group" class="w-4 h-4 inline" /> {{ $event->teams_count }} {{ __('Teams') }}</span>
                                    <span class="text-purple-500 dark:text-purple-400"><x-icon name="o-academic-cap" class="w-4 h-4 inline" /> {{ $event->projects_count }} {{ __('Projects') }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($event->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">{{ __('Active') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">{{ __('Archived') }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                {{-- Golden Standard: action button container --}}
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Manage: link button with standard format --}}
                                    <a href="{{ route('admin.events.show', $event->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors">
                                        {{ __('Manage') }} <x-icon name="o-arrow-right" class="w-3.5 h-3.5" />
                                    </a>
                                    {{-- Golden Standard: standard icon button --}}
                                    <button wire:click="edit({{ $event->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Edit') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    {{-- Golden Standard: danger button --}}
                                    <button wire:click="confirmDelete({{ $event->id }})"
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
                            <td colspan="5" class="text-center py-16">
                                <x-icon name="o-calendar" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                                <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('No events found.') }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ __('Create your first event to get started.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Golden Standard: pagination footer --}}
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">{{ $events->links() }}</div>
    </div>

    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit Event') : __('Create Event') }}" right separator>
        <x-form wire:submit="save">
            <x-input label="{{ __('Event Name') }}" wire:model="name" required />
            <x-input label="{{ __('Year') }}" wire:model="year" type="number" required />
            <x-textarea label="{{ __('Theme') }}" wire:model="theme_title" required />
            <x-slot:actions>
                <x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- Modal Delete & Toggle omitted for brevity, standard MaryUI Modals --}}
    <x-modal wire:model="deleteModalOpen" title="{{ __('Delete Event') }}">
        {{ __('Cannot delete event with registered teams.') }}
        <x-slot:actions><x-button label="{{ __('Delete') }}" class="btn-error" wire:click="deleteRecord" spinner="deleteRecord" /></x-slot:actions>
    </x-modal>
</div>
