<div class="space-y-8">
    {{-- HEADER --}}
    <x-header title="Manajemen Tags" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" placeholder="Cari Tag..." wire:model.live.debounce="search" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Tambah Tag" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- TABLE CARD --}}
    <x-card>
        <x-table :headers="[
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Nama Tag'],
            ['key' => 'slug', 'label' => 'Slug URL'],
            ['key' => 'news_count', 'label' => 'Digunakan', 'class' => 'text-center'],
        ]" :rows="$tags" striped>

            {{-- Custom Cell: Name --}}
            @scope('cell_name', $tag)
                <span class="font-bold text-gray-700">#{{ $tag->name }}</span>
            @endscope

            {{-- Custom Cell: Slug --}}
            @scope('cell_slug', $tag)
                <span class="font-mono text-xs text-gray-500">{{ $tag->slug }}</span>
            @endscope

            {{-- Custom Cell: Usage Count --}}
            @scope('cell_news_count', $tag)
                <div class="flex justify-center">
                    @if ($tag->news_count > 0)
                        <div class="badge badge-secondary badge-outline text-xs">{{ $tag->news_count }} Artikel</div>
                    @else
                        <div class="text-xs text-gray-400">-</div>
                    @endif
                </div>
            @endscope

            {{-- Actions --}}
            @scope('actions', $tag)
                <div class="flex gap-1 justify-end">
                    <x-button icon="o-pencil" wire:click="edit({{ $tag->id }})" class="btn-sm btn-ghost text-warning"
                        spinner />
                    <x-button icon="o-trash" wire:click="confirmDelete({{ $tag->id }})"
                        class="btn-sm btn-ghost text-error" spinner />
                </div>
            @endscope

        </x-table>

        <div class="mt-4">
            {{ $tags->links() }}
        </div>
    </x-card>

    {{-- CREATE / EDIT DRAWER --}}
    <x-drawer wire:model="drawer" title="{{ $isEditing ? 'Edit Tag' : 'Tambah Tag Baru' }}" class="w-11/12 lg:w-1/3"
        right separator with-close-button>
        <x-form wire:submit="save">
            <div class="space-y-4">
                <x-input label="Nama Tag" wire:model="name" placeholder="Contoh: Breaking News, Viral..."
                    icon="o-hashtag" hint="Simbol # tidak perlu ditulis" />
            </div>

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.drawer = false" />
                <x-button label="{{ $isEditing ? 'Update' : 'Simpan' }}" class="btn-primary" type="submit"
                    spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- DELETE MODAL --}}
    <x-modal wire:model="deleteModal" title="Hapus Tag?" subtitle="Tag ini akan dihapus dari sistem.">
        <div class="space-y-3">
            <p>Apakah anda yakin ingin menghapus tag ini?</p>

            @if ($usageCount > 0)
                <div class="alert alert-warning text-sm shadow-sm">
                    <x-icon name="o-exclamation-triangle" />
                    <div>
                        <span class="font-bold">Perhatian:</span> Tag ini sedang digunakan di <b>{{ $usageCount }}
                            artikel berita</b>.
                        <br>
                        Menghapus tag ini akan menghilangkannya dari artikel-artikel tersebut (Artikel tidak akan
                        terhapus).
                    </div>
                </div>
            @endif
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.deleteModal = false" />
            <x-button label="Tetap Hapus" class="btn-error" wire:click="delete" spinner />
        </x-slot:actions>
    </x-modal>
</div>
