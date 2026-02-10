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
                    <div class="badge badge-info font-bold">{{ $category->news_count }} Berita</div>
                @else
                    <div class="badge badge-ghost opacity-50">Kosong</div>
                @endif
            @endscope

            {{-- Actions --}}
            @scope('actions', $category)
                <div class="flex gap-1 justify-end">
                    {{-- TOMBOL DETAIL (MATA) --}}
                    <x-button icon="o-eye" wire:click="showDetail({{ $category->id }})" class="btn-sm btn-ghost text-info"
                        spinner tooltip="Lihat Daftar Berita" />

                    <x-button icon="o-pencil" wire:click="edit({{ $category->id }})" class="btn-sm btn-ghost text-warning"
                        spinner tooltip="Edit Kategori" />

                    <x-button icon="o-trash" wire:click="confirmDelete({{ $category->id }})"
                        class="btn-sm btn-ghost text-error" spinner tooltip="Hapus" />
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

    {{-- DRAWER DETAIL BERITA (BARU) --}}
    <x-drawer wire:model="detailDrawer" title="Detail Kategori" class="w-11/12 lg:w-1/2" right separator
        with-close-button>
        @if ($selectedCategory)
            <div class="mb-6 flex justify-between items-start">
                <div>
                    <h3 class="text-2xl font-bold">{{ $selectedCategory->name }}</h3>
                    <p class="text-sm text-gray-500 font-mono mt-1">/{{ $selectedCategory->slug }}</p>
                </div>
                <div class="badge badge-lg badge-neutral">
                    Total: {{ $selectedCategory->news->count() }} Artikel
                </div>
            </div>

            @if ($selectedCategory->news->count() > 0)
                <div class="overflow-x-auto bg-base-100 rounded-lg border border-base-200">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Berita</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selectedCategory->news as $news)
                                <tr>
                                    <td class="w-1/2">
                                        <div class="font-bold line-clamp-2 text-sm">{{ $news->title }}</div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ $news->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($news->status == 'published')
                                            <div class="badge badge-success badge-sm gap-1">
                                                <x-icon name="o-check" class="w-3 h-3" /> Tayang
                                            </div>
                                        @elseif($news->status == 'draft')
                                            <div class="badge badge-ghost badge-sm gap-1">
                                                <x-icon name="o-pencil" class="w-3 h-3" /> Draft
                                            </div>
                                        @else
                                            <div class="badge badge-error badge-sm">Arsip</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="font-mono text-xs flex items-center gap-1">
                                            <x-icon name="o-eye" class="w-3 h-3 opacity-50" />
                                            {{ number_format($news->views_count) }}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="flex justify-end gap-1">
                                            {{-- Shortcut 1: Lihat di Web (Public) --}}
                                            {{-- Asumsi route 'news.show' ada. Ubah jika beda --}}
                                            @if ($news->status == 'published')
                                                <x-button icon="o-globe-alt" link="{{ url('/news/' . $news->slug) }}"
                                                    external class="btn-xs btn-ghost" tooltip="Lihat di Web" />
                                            @endif

                                            {{-- Shortcut 2: Edit di Admin (Smart Search) --}}
                                            {{-- Mengarahkan ke halaman News Admin dan otomatis cari judulnya --}}
                                            {{-- <x-button icon="o-pencil-square"
                                                link="{{ route('admin.news', ['search' => $news->title]) }}"
                                                class="btn-xs btn-ghost text-warning" tooltip="Edit Berita" /> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div
                    class="flex flex-col items-center justify-center py-10 text-gray-400 border-2 border-dashed rounded-xl">
                    <x-icon name="o-newspaper" class="w-12 h-12 mb-2 opacity-50" />
                    <p>Belum ada berita di kategori ini.</p>
                </div>
            @endif
        @endif

        <x-slot:actions>
            <x-button label="Tutup" @click="$wire.detailDrawer = false" />
        </x-slot:actions>
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
