<div>
    <x-header title="{{ __('Lab Structure') }}" subtitle="{{ __('Manage team hierarchy and display order') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add Member') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    <div class="mb-6 max-w-sm">
        <x-input placeholder="{{ __('Search name or position...') }}" wire:model.live.debounce="search" icon="o-magnifying-glass" />
    </div>

    {{-- Golden Standard: table container --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                {{-- Golden Standard: thead row --}}
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <th class="py-3 px-6 text-center w-20">{{ __('Order') }}</th>
                        <th class="py-3 px-6">{{ __('Member Name') }}</th>
                        <th class="py-3 px-6">{{ __('Position') }}</th>
                        <th class="py-3 px-6">{{ __('Account Link') }}</th>
                        <th class="py-3 px-6">{{ __('Status') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                {{-- Golden Standard: tbody --}}
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($members as $member)
                        {{-- Golden Standard: row hover --}}
                        <tr wire:key="mem-{{ $member->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-6 text-center">
                                <span class="font-mono font-bold text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 px-2 py-1 rounded-md">#{{ $member->display_order }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-indigo-600 dark:text-indigo-400">{{ $member->name }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $member->position }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if($member->user)
                                    <div class="flex items-center gap-2">
                                        <x-avatar :image="$member->user->profile_photo ? asset('storage/'.$member->user->profile_photo) : null" class="!w-6 !h-6" />
                                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $member->user->email }}</span>
                                    </div>
                                @else
                                    <span class="text-xs italic text-slate-400 dark:text-slate-500">{{ __('No account linked') }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                {{-- Toggle status: standard button with semantic color --}}
                                <button wire:click="toggleStatus({{ $member->id }})"
                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border transition-colors cursor-pointer
                                           {{ $member->is_active
                                               ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20 hover:bg-emerald-100 dark:hover:bg-emerald-500/20'
                                               : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                                    {{ $member->is_active ? __('Visible') : __('Hidden') }}
                                </button>
                            </td>
                            <td class="py-4 px-6">
                                {{-- Golden Standard: action button container --}}
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Golden Standard: standard icon button --}}
                                    <button wire:click="edit({{ $member->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Edit') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    {{-- Golden Standard: danger button --}}
                                    <button wire:click="confirmDelete({{ $member->id }})"
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
                                <x-icon name="o-users" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                                <p class="text-slate-500 dark:text-slate-400">{{ __('No members found.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Golden Standard: pagination footer --}}
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">{{ $members->links() }}</div>
    </div>

    {{-- DRAWER FORM --}}
    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit Member') : __('Add New Member') }}" right separator with-close-button class="w-11/12 lg:w-1/3">
        <x-form wire:submit="save">
            <x-choices
                label="{{ __('Link to System Account (Optional)') }}"
                wire:model.live="user_id"
                :options="$availableUsers"
                option-label="name"
                option-value="id"
                single searchable
                placeholder="{{ __('Search user...') }}"
                hint="{{ __('Linking will auto-suggest the name.') }}"
            />

            <x-input label="{{ __('Full Name / Display Name') }}" wire:model="name" required />
            <x-input label="{{ __('Position / Title') }}" wire:model="position" placeholder="e.g. Kepala Laboratorium" required />
            <x-input label="{{ __('Display Order') }}" wire:model="display_order" type="number" hint="{{ __('Lower numbers appear first (e.g., 1 is top priority)') }}" required />

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.drawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save Member') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- MODAL DELETE --}}
    <x-modal wire:model="deleteModalOpen" title="{{ __('Confirm Deletion') }}">
        <div class="py-4">{{ __('Are you sure you want to remove this person from the lab structure?') }}</div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModalOpen = false" />
            <x-button label="{{ __('Delete') }}" class="btn-error" wire:click="deleteRecord" />
        </x-slot:actions>
    </x-modal>
</div>
