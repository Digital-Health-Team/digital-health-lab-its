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
                {{-- HEADER MANUAL --}}
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Date Joined') }}</th>
                        <th class="text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                {{-- BODY MANUAL --}}
                <tbody>
                    @forelse($users as $user)
                        <tr wire:key="{{ $user->id }}">
                            <th>{{ $loop->iteration + ($users->firstItem() - 1) }}</th>

                            {{-- Nama --}}
                            <td>
                                <div class="font-bold">{{ $user->name }}</div>
                            </td>

                            {{-- Email --}}
                            <td>
                                <span class="text-gray-500">{{ $user->email }}</span>
                            </td>

                            {{-- Role (Badge Manual) --}}
                            <td>
                                @if ($user->role == 'super_admin')
                                    <div class="badge badge-error text-white">Super Admin</div>
                                @elseif($user->role == 'pm')
                                    <div class="badge badge-warning text-white">Project Manager</div>
                                @else
                                    <div class="badge badge-info text-white">Staff</div>
                                @endif
                            </td>

                            {{-- Tanggal --}}
                            <td>
                                <span class="text-xs text-gray-500">
                                    {{ $user->created_at->format('d M Y') }}
                                </span>
                            </td>
                            <td class="text-right">
                                {{-- Tombol Edit --}}
                                <x-button icon="o-pencil-square" wire:click="edit({{ $user->id }})"
                                    class="..." />

                                {{-- Tombol Delete (Sekarang memanggil confirmDelete, bukan delete langsung) --}}
                                @if ($user->id !== auth()->id())
                                    <x-button icon="o-trash" wire:click="confirmDelete({{ $user->id }})"
                                        class="btn-sm btn-square btn-ghost text-red-500" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500">
                                Data tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION MANUAL --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </x-card>

    {{-- MODAL FORM (Tetap Sama) --}}
    <x-modal wire:model="modalOpen" :title="$editingUserId ? __('Edit User') : __('Add User')" separator>
        <x-form wire:submit="save">
            <x-input label="{{ __('Name') }}" wire:model="name" icon="o-user" />
            <x-input label="{{ __('Email') }}" wire:model="email" type="email" icon="o-envelope" />

            <x-select label="{{ __('Role') }}" wire:model="role" :options="[
                ['id' => 'staff', 'name' => 'Staff'],
                ['id' => 'pm', 'name' => 'Project Manager'],
                ['id' => 'super_admin', 'name' => 'Super Admin'],
            ]" icon="o-shield-check" />

            <x-input label="{{ __('Password') }}" wire:model="password" type="password" icon="o-key"
                hint="{{ $editingUserId ? __('Leave blank to keep current password') : '' }}" />

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.modalOpen = false" />
                <x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- ================================================= --}}
    {{-- MODAL DELETE COMPONENT (Reusable) --}}
    {{-- ================================================= --}}
    <x-modal-confirm wire:model="deleteModalOpen" title="{{ __('Delete User?') }}"
        text="{{ __('Are you sure you want to delete this user? This action cannot be undone.') }}"
        confirm-text="{{ __('Yes, Delete') }}" method="delete" />
</div>
