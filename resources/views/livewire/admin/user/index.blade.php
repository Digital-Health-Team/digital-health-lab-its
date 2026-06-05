<div>
    {{-- HEADER --}}
    <x-header title="{{ __('User Management') }}" subtitle="{{ __('Manage all system users, roles, and profiles') }}"
        separator>
        <x-slot:actions>
            <x-button label="{{ __('Add User') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- Golden Standard: table container (wraps tabs + filters + table) --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">

        {{-- 1. INTEGRATED TABS --}}
        <div class="flex overflow-x-auto border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 px-2 scrollbar-hide">
            <button wire:click="$set('activeTab', 'all')"
                class="px-6 py-4 text-sm font-semibold whitespace-nowrap border-b-2 outline-none transition-colors duration-200 {{ $activeTab === 'all' ? 'border-indigo-600 dark:border-indigo-400 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600' }}">
                {{ __('All Users') }}
            </button>

            @foreach ($roles as $role)
                <button wire:click="$set('activeTab', '{{ $role->name }}')"
                    class="px-6 py-4 text-sm font-semibold whitespace-nowrap border-b-2 outline-none transition-colors duration-200 {{ $activeTab === $role->name ? 'border-indigo-600 dark:border-indigo-400 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-600' }}">
                    {{ ucwords(str_replace('_', ' ', $role->name)) }}
                </button>
            @endforeach
        </div>

        {{-- 2. INTEGRATED FILTER BAR --}}
        <div class="p-4 bg-slate-50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-slate-800">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <x-input placeholder="{{ __('Search name or email...') }}" wire:model.live.debounce.500ms="search"
                    icon="o-magnifying-glass" />
                <x-select wire:model.live="filterStatus" :options="[['id' => 'active', 'name' => __('Active')], ['id' => 'inactive', 'name' => __('Inactive')]]" placeholder="{{ __('All Status') }}"
                    icon="o-check-circle" />
                <x-select wire:model.live="sortBy" :options="[
                    ['id' => 'latest', 'name' => __('Newest First')],
                    ['id' => 'oldest', 'name' => __('Oldest First')],
                ]" icon="o-arrows-up-down" />
                <div>
                    <x-button label="{{ __('Clear Filters') }}" wire:click="clearFilters" icon="o-x-mark"
                        class="btn-ghost w-full lg:w-auto text-gray-500" />
                </div>
            </div>
        </div>

        {{-- 3. INTEGRATED DATA TABLE --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                {{-- Golden Standard: thead row --}}
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <th class="py-3 px-6 text-center w-12">#</th>
                        <th class="py-3 px-6">{{ __('User') }}</th>
                        <th class="py-3 px-6">{{ __('Role & Status') }}</th>
                        <th class="py-3 px-6">{{ __('Institution Details') }}</th>
                        <th class="py-3 px-6">{{ __('Joined') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                {{-- Golden Standard: tbody --}}
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($users as $user)
                        @php
                            $primaryPhoto = $user->attachments->first();
                        @endphp
                        {{-- Golden Standard: row hover --}}
                        <tr wire:key="user-{{ $user->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-6 text-center text-slate-400 dark:text-slate-500 font-mono text-xs">
                                {{ $loop->iteration + ($users->firstItem() - 1) }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <x-avatar :image="$primaryPhoto ? asset('storage/' . $primaryPhoto->file_url) : null" class="!w-10 !h-10 border border-slate-200 dark:border-slate-700 shadow-sm"
                                        icon="o-user" />
                                    <div>
                                        <div class="font-bold text-slate-800 dark:text-slate-200">{{ $user->profile?->full_name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-col gap-1 items-start">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20">
                                        {{ str_replace('_', ' ', $user->role->name) }}
                                    </span>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <div class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                                        <span class="text-xs {{ $user->is_active ? 'text-emerald-600 dark:text-emerald-400 font-medium' : 'text-rose-600 dark:text-rose-400 font-medium' }}">
                                            {{ $user->is_active ? __('Active') : __('Inactive') }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if ($user->profile?->university || $user->profile?->department)
                                    <div class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $user->profile?->university ?? '-' }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $user->profile?->department ?? '-' }}</div>
                                    @if ($user->profile?->nim || $user->profile?->nik)
                                        <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 font-mono bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded-md inline-block border border-slate-200 dark:border-slate-700">
                                            ID: {{ $user->profile->nim ?? $user->profile->nik }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 italic text-sm">{{ __('No data') }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-sm text-slate-500 dark:text-slate-400">{{ $user->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="py-4 px-6">
                                {{-- Golden Standard: action button container --}}
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Golden Standard: standard icon button --}}
                                    <button wire:click="edit({{ $user->id }})"
                                        class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                        title="{{ __('Edit User') }}">
                                        <x-icon name="o-pencil-square" class="w-4 h-4" />
                                    </button>
                                    @if ($user->id !== auth()->id())
                                        {{-- Toggle: rose for deactivate, emerald for activate --}}
                                        <button wire:click="confirmToggle({{ $user->id }})"
                                            class="p-1.5 rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer
                                                   {{ $user->is_active ? 'hover:bg-rose-100 hover:text-rose-600 hover:border-rose-200 dark:hover:bg-rose-500/20 dark:hover:text-rose-400 dark:hover:border-rose-500/30' : 'hover:bg-emerald-100 hover:text-emerald-600 hover:border-emerald-200 dark:hover:bg-emerald-500/20 dark:hover:text-emerald-400 dark:hover:border-emerald-500/30' }}"
                                            title="{{ $user->is_active ? __('Deactivate') : __('Activate') }}">
                                            <x-icon name="{{ $user->is_active ? 'o-no-symbol' : 'o-check-circle' }}" class="w-4 h-4" />
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Golden Standard: empty state --}}
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <x-icon name="o-users" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                                <p class="text-slate-500 dark:text-slate-400">{{ __('No users found matching your filters and tabs.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Golden Standard: pagination footer --}}
        @if ($users->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- DRAWER FORM --}}
    <x-drawer wire:model="drawerOpen" title="{{ $editingUserId ? __('Edit User') : __('Add New User') }}"
        subtitle="{{ __('Fill in the required information below.') }}" right separator with-close-button
        class="w-11/12 lg:w-2/5">

        <x-form wire:submit="save">
            {{-- Foto Profil --}}
            <div class="flex items-center gap-4 mb-2">
                @if ($profile_photo)
                    <x-avatar :image="$profile_photo->temporaryUrl()" class="!w-16 !h-16 shadow-sm border border-base-200" />
                @elseif($existing_photo)
                    <x-avatar :image="asset('storage/' . $existing_photo)" class="!w-16 !h-16 shadow-sm border border-base-200" />
                @else
                    <x-avatar icon="o-user" class="!w-16 !h-16 bg-base-200 text-gray-400" />
                @endif
                <x-file wire:model="profile_photo" label="{{ __('Profile Photo') }}" accept="image/*"
                    hint="{{ __('Max 2MB') }}" class="flex-1" />
            </div>

            <x-input label="{{ __('Full Name') }}" wire:model="full_name" icon="o-user" required />

            <div class="grid grid-cols-2 gap-4">
                <x-input label="{{ __('Email Address') }}" wire:model="email" type="email" icon="o-envelope"
                    required />
                <x-select label="{{ __('Role') }}" wire:model="role_id" :options="$roles" option-label="name"
                    option-value="id" placeholder="{{ __('Select Role') }}" icon="o-shield-check" required />
            </div>

            <x-input label="{{ __('Password') }}" wire:model="password" type="password" icon="o-key"
                hint="{{ $editingUserId ? __('Leave blank to keep current password') : __('Minimum 6 characters') }}" />

            {{-- OPTIONAL PROFILE DATA (FIXED COLLAPSE) --}}
            <x-collapse class="bg-base-200 mt-4 border border-base-300 shadow-sm rounded-box">
                <x-slot:heading>
                    <div class="flex items-center gap-2 font-semibold text-base-content/80">
                        <x-icon name="o-identification" class="w-5 h-5" />
                        {{ __('Detailed Profile Data (Optional)') }}
                    </div>
                </x-slot:heading>

                <x-slot:content>
                    <div class="grid grid-cols-2 gap-4 mt-2">
                        <x-input label="{{ __('NIM') }}" wire:model="nim" hint="{{ __('For Students') }}" />
                        <x-input label="{{ __('NIK') }}" wire:model="nik" hint="{{ __('For Public') }}" />
                        <x-input label="{{ __('University') }}" wire:model="university" />
                        <x-input label="{{ __('Faculty') }}" wire:model="faculty" />
                        <x-input label="{{ __('Department') }}" wire:model="department" class="col-span-2" />
                        <x-input label="{{ __('Phone Number') }}" wire:model="phone" class="col-span-2" />
                        <x-textarea label="{{ __('Complete Address') }}" wire:model="address" class="col-span-2"
                            rows="2" />
                    </div>
                </x-slot:content>
            </x-collapse>

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.drawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save User') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- MODAL CONFIRMATION --}}
    <x-modal wire:model="toggleModalOpen" title="{{ __('Confirm Action') }}" separator>
        <div class="py-4 text-base-content/80">
            {{ __('Are you sure you want to change the status of this user account? Deactivated users will not be able to log in.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.toggleModalOpen = false" class="btn-ghost" />
            <x-button label="{{ __('Yes, Proceed') }}" class="btn-warning" wire:click="toggleStatus"
                spinner="toggleStatus" />
        </x-slot:actions>
    </x-modal>
</div>
