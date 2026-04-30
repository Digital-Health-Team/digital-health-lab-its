<div>
    {{-- HEADER --}}
    <x-header title="{{ __('User Management') }}" subtitle="{{ __('Manage all system users, roles, and profiles') }}"
        separator>
        <x-slot:actions>
            <x-button label="{{ __('Add User') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- UNIFIED CARD (TABS + FILTERS + TABLE) --}}
    <x-card class="p-0 overflow-hidden shadow-sm border border-base-200 bg-base-100">

        {{-- 1. INTEGRATED TABS --}}
        <div class="flex overflow-x-auto border-b border-base-200 bg-base-100 px-2 scrollbar-hide">
            <button wire:click="$set('activeTab', 'all')"
                class="px-6 py-4 text-sm font-semibold whitespace-nowrap border-b-2 outline-none transition-colors duration-200 {{ $activeTab === 'all' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300' }}">
                {{ __('All Users') }}
            </button>

            @foreach ($roles as $role)
                <button wire:click="$set('activeTab', '{{ $role->name }}')"
                    class="px-6 py-4 text-sm font-semibold whitespace-nowrap border-b-2 outline-none transition-colors duration-200 {{ $activeTab === $role->name ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300' }}">
                    {{ ucwords(str_replace('_', ' ', $role->name)) }}
                </button>
            @endforeach
        </div>

        {{-- 2. INTEGRATED FILTER BAR --}}
        <div class="p-4 bg-base-200/30 border-b border-base-200">
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
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-100">
                        <th class="w-12 text-center text-xs uppercase tracking-wider text-gray-500">#</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('User') }}</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Role & Status') }}</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Institution Details') }}</th>
                        <th class="text-xs uppercase tracking-wider text-gray-500">{{ __('Joined') }}</th>
                        <th class="text-right text-xs uppercase tracking-wider text-gray-500">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $primaryPhoto = $user->attachments->first();
                        @endphp
                        <tr wire:key="user-{{ $user->id }}" class="hover:bg-base-200/50 transition-colors">
                            <td class="text-center text-gray-400 font-medium text-sm">
                                {{ $loop->iteration + ($users->firstItem() - 1) }}
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <x-avatar :image="$primaryPhoto ? asset('storage/' . $primaryPhoto->file_url) : null" class="!w-10 !h-10 border border-base-300 shadow-sm"
                                        icon="o-user" />
                                    <div>
                                        <div class="font-bold text-base-content">
                                            {{ $user->profile?->full_name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-col gap-1 items-start">
                                    <span
                                        class="badge badge-info badge-sm text-white font-semibold uppercase tracking-wider text-[10px]">
                                        {{ str_replace('_', ' ', $user->role->name) }}
                                    </span>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <div
                                            class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                                        </div>
                                        <span
                                            class="text-xs {{ $user->is_active ? 'text-green-600 font-medium' : 'text-red-500 font-medium' }}">
                                            {{ $user->is_active ? __('Active') : __('Inactive') }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($user->profile?->university || $user->profile?->department)
                                    <div class="text-sm font-medium text-base-content">
                                        {{ $user->profile?->university ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->profile?->department ?? '-' }}</div>
                                    @if ($user->profile?->nim || $user->profile?->nik)
                                        <div
                                            class="text-[11px] text-gray-400 mt-1 font-mono bg-base-200 px-1.5 py-0.5 rounded-md inline-block">
                                            ID: {{ $user->profile->nim ?? $user->profile->nik }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic text-sm">{{ __('No data') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="text-right flex justify-end gap-1">
                                <x-button icon="o-pencil-square" wire:click="edit({{ $user->id }})"
                                    class="btn-sm btn-circle btn-ghost text-blue-500 hover:bg-blue-50"
                                    tooltip="{{ __('Edit User') }}" />
                                @if ($user->id !== auth()->id())
                                    <x-button icon="{{ $user->is_active ? 'o-no-symbol' : 'o-check-circle' }}"
                                        wire:click="confirmToggle({{ $user->id }})"
                                        class="btn-sm btn-circle btn-ghost {{ $user->is_active ? 'text-red-500 hover:bg-red-50' : 'text-green-500 hover:bg-green-50' }}"
                                        tooltip="{{ $user->is_active ? __('Deactivate') : __('Activate') }}" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <x-icon name="o-users" class="w-12 h-12 mb-3 opacity-30" />
                                    <p class="text-base">{{ __('No users found matching your filters and tabs.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 4. INTEGRATED PAGINATION --}}
        @if ($users->hasPages())
            <div class="p-4 border-t border-base-200 bg-base-50">
                {{ $users->links() }}
            </div>
        @endif
    </x-card>

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
