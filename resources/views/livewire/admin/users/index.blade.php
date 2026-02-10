<div class="space-y-8">
    {{-- HEADER --}}
    <x-header title="Manajemen Pengguna" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" placeholder="Cari Nama / Email..." wire:model.live.debounce="search" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Tambah User" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- TABLE CARD --}}
    <x-card>
        {{-- Filter Role --}}
        <div class="flex justify-between items-center mb-4">
            <div class="w-1/3">
                <x-select label="Filter Role" :options="[
                    ['id' => 'all', 'name' => 'Semua Role'],
                    ['id' => 'admin', 'name' => 'Admin'],
                    ['id' => 'user', 'name' => 'User Standard'],
                ]" wire:model.live="filterRole" />
            </div>
        </div>

        <x-table :headers="[
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Nama User'],
            ['key' => 'role', 'label' => 'Role'],
            ['key' => 'created_at', 'label' => 'Bergabung'],
        ]" :rows="$users" striped>

            {{-- Custom Cell: Name & Email --}}
            @scope('cell_name', $user)
                <div class="flex items-center gap-3">
                    <x-avatar :image="'https://ui-avatars.com/api/?name=' .
                        urlencode($user->name) .
                        '&color=7F9CF5&background=EBF4FF'" class="!w-10 !rounded-full" />
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-800">{{ $user->name }}</span>
                        <span class="text-xs text-gray-500">{{ $user->email }}</span>
                    </div>
                </div>
            @endscope

            {{-- Custom Cell: Role --}}
            @scope('cell_role', $user)
                @if ($user->role == 'admin')
                    <x-badge value="ADMIN" class="badge-primary" />
                @else
                    <x-badge value="USER" class="badge-ghost" />
                @endif
            @endscope

            {{-- Custom Cell: Created At --}}
            @scope('cell_created_at', $user)
                {{ $user->created_at->format('d M Y') }}
            @endscope

            {{-- Actions --}}
            @scope('actions', $user)
                <div class="flex gap-1">
                    <x-button icon="o-pencil" wire:click="edit({{ $user->id }})" class="btn-sm btn-ghost text-warning"
                        spinner />
                    {{-- Disable delete button if self --}}
                    @if ($user->id !== auth()->id())
                        <x-button icon="o-trash" wire:click="confirmDelete({{ $user->id }})"
                            class="btn-sm btn-ghost text-error" spinner />
                    @endif
                </div>
            @endscope

        </x-table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </x-card>

    {{-- CREATE / EDIT DRAWER --}}
    <x-drawer wire:model="drawer" title="{{ $isEditing ? 'Edit User' : 'Tambah User Baru' }}" class="w-11/12 lg:w-1/3"
        right separator with-close-button>
        <x-form wire:submit="save">
            <div class="space-y-4">
                <x-input label="Nama Lengkap" wire:model="name" icon="o-user" />
                <x-input label="Alamat Email" wire:model="email" icon="o-envelope" type="email" />

                <x-select label="Role Akses" wire:model="role" :options="[['id' => 'user', 'name' => 'User Standard'], ['id' => 'admin', 'name' => 'Administrator']]" />

                <div class="divider">Keamanan</div>

                <x-input label="Password" wire:model="password" icon="o-key" type="password"
                    hint="{{ $isEditing ? 'Kosongkan jika tidak ingin mengubah password' : 'Minimal 6 karakter' }}" />
            </div>

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.drawer = false" />
                <x-button label="{{ $isEditing ? 'Update' : 'Simpan' }}" class="btn-primary" type="submit"
                    spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- DELETE MODAL --}}
    <x-modal wire:model="deleteModal" title="Hapus User?" subtitle="User akan dihapus secara permanen.">
        <div>Apakah anda yakin ingin menghapus user ini?</div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.deleteModal = false" />
            <x-button label="Hapus" class="btn-error" wire:click="delete" spinner />
        </x-slot:actions>
    </x-modal>
</div>
