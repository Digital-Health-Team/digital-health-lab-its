<div class="space-y-8">
    {{-- HEADER & STATS --}}
    <x-header title="Manajemen Berita" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" placeholder="Cari Judul..." wire:model.live.debounce="search" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Buat Berita" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <x-stat title="Total Berita" value="{{ $stats['total'] }}" icon="o-newspaper" />
        <x-stat title="Published" value="{{ $stats['published'] }}" icon="o-check-circle" class="text-success" />
        <x-stat title="Draft" value="{{ $stats['draft'] }}" icon="o-pencil-square" class="text-warning" />
        <x-stat title="Total Views" value="{{ number_format($stats['views']) }}" icon="o-eye" />
    </div>

    {{-- FILTER & TABLE CARD --}}
    <x-card>
        {{-- Custom Filters --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <x-select label="Filter Kategori" :options="$categories" wire:model.live="filterCategory"
                placeholder="Semua Kategori" />
            <x-select label="Status" :options="[
                ['id' => 'all', 'name' => 'Semua'],
                ['id' => 'published', 'name' => 'Published'],
                ['id' => 'draft', 'name' => 'Draft'],
            ]" wire:model.live="filterStatus" />
            <div class="flex items-end">
                <x-button label="Reset Filter" icon="o-arrow-path"
                    wire:click="$set('search', ''); $set('filterCategory', ''); $set('filterStatus', 'all')"
                    class="btn-ghost w-full" />
            </div>
        </div>

        {{-- TABLE DATA --}}
        <x-table :headers="[
            ['key' => 'id', 'label' => '#'],
            ['key' => 'title', 'label' => 'Judul Berita'],
            ['key' => 'category.name', 'label' => 'Kategori'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'views_count', 'label' => 'Views'],
            ['key' => 'created_at', 'label' => 'Tanggal'],
        ]" :rows="$newsList" wire:model="selected" striped>

            {{-- Custom Row: Title & Thumbnail --}}
            @scope('cell_title', $news)
                <div class="flex items-center gap-3">
                    <div class="avatar">
                        <div class="w-12 rounded">
                            <img
                                src="{{ $news->thumbnail ? asset('storage/' . $news->thumbnail->image_path) : 'https://via.placeholder.com/150' }}" />
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div class="font-bold">{{ Str::limit($news->title, 40) }}</div>
                        <div class="text-xs opacity-50">{{ $news->author->name }}</div>
                        @if ($news->is_headline)
                            <span class="badge badge-xs badge-warning mt-1">Headline</span>
                        @endif
                    </div>
                </div>
            @endscope

            {{-- Custom Row: Status --}}
            @scope('cell_status', $news)
                @if ($news->status == 'published')
                    <div class="badge badge-success gap-2">Published</div>
                @elseif($news->status == 'draft')
                    <div class="badge badge-ghost gap-2">Draft</div>
                @else
                    <div class="badge badge-error gap-2">Archived</div>
                @endif
            @endscope

            {{-- Custom Row: Actions --}}
            @scope('actions', $news)
                <div class="flex gap-1">
                    <x-button icon="o-eye" wire:click="showDetail({{ $news->id }})" spinner
                        class="btn-sm btn-ghost" />
                    <x-button icon="o-pencil" wire:click="edit({{ $news->id }})" spinner
                        class="btn-sm btn-ghost text-warning" />
                    <x-button icon="o-trash" wire:click="confirmDelete({{ $news->id }})" spinner
                        class="btn-sm btn-ghost text-error" />
                </div>
            @endscope

        </x-table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $newsList->links() }}
        </div>
    </x-card>

    {{-- CREATE / EDIT DRAWER --}}
    <x-drawer wire:model="drawer" title="{{ $isEditing ? 'Edit Berita' : 'Buat Berita Baru' }}"
        class="w-11/12 lg:w-1/2" right separator with-close-button>
        <x-form wire:submit="save">

            <div class="grid grid-cols-1 gap-4">
                {{-- Image Upload --}}
                <div class="flex justify-center mb-4">
                    @if ($thumbnail)
                        <img src="{{ $thumbnail->temporaryUrl() }}"
                            class="w-full h-48 object-cover rounded-lg border" />
                    @elseif($isEditing && $editingId && \App\Models\News::find($editingId)->thumbnail)
                        <img src="{{ asset('storage/' . \App\Models\News::find($editingId)->thumbnail->image_path) }}"
                            class="w-full h-48 object-cover rounded-lg border" />
                    @else
                        <div class="w-full h-48 bg-base-200 rounded-lg flex items-center justify-center text-gray-400">
                            No Image
                        </div>
                    @endif
                </div>
                <x-file label="Thumbnail Berita" wire:model="thumbnail" accept="image/png, image/jpeg" />

                {{-- Basic Info --}}
                <x-input label="Judul" wire:model="title" placeholder="Masukkan judul berita..." />

                <div class="grid grid-cols-2 gap-4">
                    <x-select label="Kategori" :options="$categories" wire:model="category_id"
                        placeholder="Pilih Kategori" />
                    <x-select label="Status" :options="[
                        ['id' => 'draft', 'name' => 'Draft'],
                        ['id' => 'published', 'name' => 'Publish Now'],
                        ['id' => 'archived', 'name' => 'Archive'],
                    ]" wire:model="status" />
                </div>

                {{-- Tags (Multi Select) --}}
                {{-- Note: MaryUI choices butuh array options yg kompatibel --}}
                <x-choices label="Tags" wire:model="selectedTags" :options="$allTags" />

                <x-checkbox label="Jadikan Headline / Pilihan Editor?" wire:model="is_headline" />

                {{-- Content --}}
                <x-textarea label="Konten Berita" wire:model="content" rows="10"
                    placeholder="Tulis isi berita di sini..." hint="Gunakan format teks yang rapi" />
            </div>

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.drawer = false" />
                <x-button label="{{ $isEditing ? 'Update' : 'Simpan' }}" class="btn-primary" type="submit"
                    spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- DETAIL DRAWER --}}
    <x-drawer wire:model="detailDrawer" title="Preview Berita" class="w-11/12 lg:w-1/2" right separator
        with-close-button>
        @if ($selectedNews)
            <div class="space-y-4">
                @if ($selectedNews->thumbnail)
                    <img src="{{ asset('storage/' . $selectedNews->thumbnail->image_path) }}"
                        class="w-full rounded-lg shadow-sm" />
                @endif

                <div>
                    <div class="flex gap-2 mb-2">
                        <div class="badge badge-primary">{{ $selectedNews->category->name ?? '-' }}</div>
                        <div class="badge badge-ghost">{{ $selectedNews->created_at->format('d M Y') }}</div>
                    </div>
                    <h2 class="text-2xl font-bold">{{ $selectedNews->title }}</h2>
                    <div class="text-sm text-gray-500 mt-1">Oleh: {{ $selectedNews->author->name }}</div>
                </div>

                <div class="divider"></div>

                <div class="prose max-w-none">
                    {!! nl2br(e($selectedNews->content)) !!}
                </div>

                <div class="mt-4 pt-4 border-t">
                    <span class="font-bold text-sm mr-2">Tags:</span>
                    @foreach ($selectedNews->tags as $tag)
                        <span class="badge badge-outline text-xs">#{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </x-drawer>

    {{-- DELETE CONFIRMATION --}}
    <x-modal wire:model="deleteModal" title="Hapus Berita?" subtitle="Tindakan ini tidak dapat dibatalkan">
        <div>Berita beserta gambar dan komentar terkait akan dihapus permanen.</div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.deleteModal = false" />
            <x-button label="Ya, Hapus" class="btn-error" wire:click="delete" spinner />
        </x-slot:actions>
    </x-modal>
</div>
