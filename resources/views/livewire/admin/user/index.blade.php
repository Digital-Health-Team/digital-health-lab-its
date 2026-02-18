<div>
    {{-- HEADER --}}
    <x-header title="{{ __('User Management') }}" subtitle="{{ __('Registered accounts') }}" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" placeholder="{{ __('Search') }}..." wire:model.live.debounce="search" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="{{ __('Add User') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- CARD TABEL --}}
    <x-card class="bg-base-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Profile') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Role / Depts') }}</th> {{-- Header Digabung --}}
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Date Joined') }}</th>
                        <th class="text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr wire:key="{{ $user->id }}">
                            <th>{{ $loop->iteration + ($users->firstItem() - 1) }}</th>
                            <td>
                                <x-avatar :image="$user->profile_photo ? asset('storage/' . $user->profile_photo) : null" class="!w-10 !h-10" />
                            </td>
                            <td>
                                <div class="font-bold">{{ $user->name }}</div>
                            </td>
                            <td>
                                <div class="flex flex-col gap-1 items-start">
                                    {{-- Role Badge --}}
                                    <div
                                        class="badge {{ match ($user->role) {
                                            'super_admin' => 'badge-error text-white',
                                            'pm' => 'badge-warning text-white',
                                            'freelance' => 'badge-secondary text-white',
                                            default => 'badge-info text-white',
                                        } }} badge-sm">
                                        {{ str_replace('_', ' ', ucfirst($user->role)) }}
                                    </div>

                                    {{-- Departments Loop (Array) --}}
                                    @if (!empty($user->departments) && is_array($user->departments))
                                        <div class="flex flex-wrap gap-1 mt-1 max-w-[200px]">
                                            @foreach ($user->departments as $dept)
                                                <span
                                                    class="badge badge-ghost badge-xs text-[10px] font-semibold border-gray-300">
                                                    {{ $dept }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td><span class="text-gray-500">{{ $user->email }}</span></td>
                            <td>
                                <span class="text-xs text-gray-500">{{ $user->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="text-right">
                                <x-button icon="o-eye" link="{{ route('admin.users.show', $user->id) }}" spinner
                                    class="btn-sm btn-square btn-ghost text-blue-500" />
                                <x-button icon="o-pencil-square" wire:click="edit({{ $user->id }})"
                                    class="btn-sm btn-ghost text-blue-500" />
                                @if ($user->id !== auth()->id())
                                    <x-button icon="o-trash" wire:click="confirmDelete({{ $user->id }})"
                                        class="btn-sm btn-square btn-ghost text-red-500" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 text-gray-500">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $users->links() }}</div>
    </x-card>

    {{-- MODAL FORM --}}
    <x-modal wire:model="modalOpen" :title="$editingUserId ? __('Edit User') : __('Add User')" separator>
        <x-form wire:submit="save">

            {{-- Foto Profil --}}
            <div class="flex items-center gap-4 mb-4">
                @if ($profile_photo)
                    <x-avatar :image="$profile_photo->temporaryUrl()" class="!w-16 !h-16" />
                @elseif($existing_photo)
                    <x-avatar :image="asset('storage/' . $existing_photo)" class="!w-16 !h-16" />
                @else
                    <x-avatar icon="o-user" class="!w-16 !h-16" />
                @endif
                <x-file wire:model="profile_photo" label="{{ __('Profile Photo') }}" accept="image/*" hint="Max 2MB" />
            </div>

            <x-input label="{{ __('Name') }}" wire:model="name" icon="o-user" />
            <x-input label="{{ __('Email') }}" wire:model="email" type="email" icon="o-envelope" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Role --}}
                <x-select label="{{ __('Role') }}" wire:model="role" :options="[
                    ['id' => 'freelance', 'name' => 'Freelance'],
                    ['id' => 'staff', 'name' => 'Staff'],
                    ['id' => 'pm', 'name' => 'Project Manager'],
                    ['id' => 'super_admin', 'name' => 'Super Admin'],
                ]" icon="o-shield-check" />

                {{-- Departments (Multi Select) --}}
                {{-- Menggunakan x-choices dari MaryUI --}}
                <x-choices label="{{ __('Departments') }}" wire:model="departments" :options="$departmentsList"
                    icon="o-building-office" hint="Can select multiple departments" allow-all {{-- Membolehkan input custom jika perlu (opsional) --}} />
            </div>

            <x-input label="{{ __('Password') }}" wire:model="password" type="password" icon="o-key"
                hint="{{ $editingUserId ? __('Leave blank to keep current password') : '' }}" />

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.modalOpen = false" />
                <x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- MODAL DELETE --}}
    <x-modal-confirm wire:model="deleteModalOpen" title="{{ __('Delete User?') }}"
        text="{{ __('Are you sure you want to delete this user?') }}" confirm-text="{{ __('Yes, Delete') }}"
        method="delete" />
</div>
