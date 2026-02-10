<div class="space-y-8">
    {{-- HEADER --}}
    <x-header title="Kategori Berita" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" placeholder="Cari Kategori..." wire:model.live.debounce="search" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Tambah Kategori" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- TABLE CARD --}}
    <x-card>
        <x-table :headers="[
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Nama Kategori'],
            ['key' => 'slug', 'label' => 'URL Slug'],
            ['key' => 'news_count', 'label' => 'Jumlah Artikel', 'class' => 'text-center'],
        ]" :rows="$categories" striped>

            {{-- Custom Cell: Name --}}
            @scope('cell_name', $category)
                <span class="font-bold text-lg">{{ $category->name }}</span>
            @endscope

            {{-- Custom Cell: Slug --}}
            @scope('cell_slug', $category)
                <span class="font-mono text-sm bg-base-200 px-2 py-1 rounded">{{ $category->slug }}</span>
            @endscope

            {{-- Custom Cell: News Count --}}
            @scope('cell_news_count', $category)
                @if ($category->news_count > 0)
                    <div class="badge badge-info">{{ $category->news_count }} Berita</div>
                @else
                    <div class="badge badge-ghost opacity-50">Kosong</div>
                @endif
            @endscope

            {{-- Actions --}}
            @scope('actions', $category)
                <div class="flex gap-1 justify-end">
                    <x-button icon="o-pencil" wire:click="edit({{ $category->id }})" class="btn-sm btn-ghost text-warning"
                        spinner />
                    <x-button icon="o-trash" wire:click="confirmDelete({{ $category->id }})"
                        class="btn-sm btn-ghost text-error" spinner />
                </div>
            @endscope

        </x-table>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </x-card>

    {{-- CREATE / EDIT DRAWER --}}
    <x-drawer wire:model="drawer" title="{{ $isEditing ? 'Edit Kategori' : 'Tambah Kategori' }}"
        class="w-11/12 lg:w-1/3" right separator with-close-button>
        <x-form wire:submit="save">
            <div class="space-y-4">
                <x-input label="Nama Kategori" wire:model="name" placeholder="Contoh: Politik, Olahraga..."
                    icon="o-tag" hint="Slug URL akan dibuat otomatis dari nama ini" />
            </div>

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.drawer = false" />
                <x-button label="{{ $isEditing ? 'Update' : 'Simpan' }}" class="btn-primary" type="submit"
                    spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- DELETE MODAL --}}
    <x-modal wire:model="deleteModal" title="Hapus Kategori?" subtitle="Tindakan ini permanen.">
        <div class="space-y-3">
            <p>Apakah anda yakin ingin menghapus kategori ini?</p>

            @if ($newsCountToDelete > 0)
                <div class="alert alert-warning text-sm">
                    <x-icon name="o-exclamation-triangle" />
                    <span>
                        <b>PERINGATAN:</b> Kategori ini memiliki <b>{{ $newsCountToDelete }} berita</b> terkait.
                        Menghapus kategori ini dapat menyebabkan berita tersebut terhapus atau kehilangan kategori.
                    </span>
                </div>
            @endif
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.deleteModal = false" />
            <x-button label="Tetap Hapus" class="btn-error" wire:click="delete" spinner />
        </x-slot:actions>
    </x-modal>
</div>
