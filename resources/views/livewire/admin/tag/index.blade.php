<div class="space-y-8">
    {{-- HEADER & TABLE UTAMA (Sama seperti sebelumnya) --}}
    <x-header title="Manajemen Tags" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" placeholder="Cari Tag..." wire:model.live.debounce="search" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Tambah Tag" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    <x-card>
        <x-table :headers="[
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Nama Tag'],
            ['key' => 'slug', 'label' => 'Slug URL'],
            ['key' => 'news_count', 'label' => 'Digunakan', 'class' => 'text-center'],
        ]" :rows="$tags" striped>

            @scope('cell_name', $tag)
                <span class="font-bold text-gray-700">#{{ $tag->name }}</span>
            @endscope

            @scope('cell_slug', $tag)
                <span class="font-mono text-xs text-gray-500">{{ $tag->slug }}</span>
            @endscope

            @scope('cell_news_count', $tag)
                <div class="flex justify-center">
                    @if ($tag->news_count > 0)
                        <div class="badge badge-secondary badge-outline text-xs font-bold">{{ $tag->news_count }} Artikel
                        </div>
                    @else
                        <div class="text-xs text-gray-400">-</div>
                    @endif
                </div>
            @endscope

            @scope('actions', $tag)
                <div class="flex gap-1 justify-end">
                    <x-button icon="o-eye" wire:click="showDetail({{ $tag->id }})" class="btn-sm btn-ghost text-info"
                        spinner tooltip="Lihat Berita Terkait" />
                    <x-button icon="o-pencil" wire:click="edit({{ $tag->id }})" class="btn-sm btn-ghost text-warning"
                        spinner tooltip="Edit" />
                    <x-button icon="o-trash" wire:click="confirmDelete({{ $tag->id }})"
                        class="btn-sm btn-ghost text-error" spinner tooltip="Hapus" />
                </div>
            @endscope
        </x-table>

        <div class="mt-4">{{ $tags->links() }}</div>
    </x-card>

    {{-- CREATE / EDIT DRAWER (Sama seperti sebelumnya) --}}
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

    {{-- DRAWER DETAIL TAG (UPDATED) --}}
    <x-drawer wire:model="detailDrawer" title="Detail Penggunaan Tag" class="w-11/12 lg:w-1/2" right separator
        with-close-button>

        @if ($selectedTag)
            {{-- Header Info Tag --}}
            <div class="mb-6 flex justify-between items-start">
                <div>
                    <h3 class="text-2xl font-bold">#{{ $selectedTag->name }}</h3>
                    <p class="text-sm text-gray-500 font-mono mt-1">/tag/{{ $selectedTag->slug }}</p>
                </div>
                <div class="badge badge-lg badge-neutral">
                    Total: {{ $selectedTag->news->count() }} Artikel
                </div>
            </div>

            {{-- Table List Berita --}}
            @if ($selectedTag->news->count() > 0)
                <div class="overflow-x-auto bg-base-100 rounded-lg border border-base-200">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Judul Berita</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selectedTag->news as $news)
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
                                            @if ($news->status == 'published')
                                                <x-button icon="o-globe-alt" link="{{ url('/news/' . $news->slug) }}"
                                                    external class="btn-xs btn-ghost" tooltip="Lihat di Web" />
                                            @endif

                                            {{-- Shortcut 2: Edit di Admin (Smart Search) --}}
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
                    <p>Tag ini belum digunakan di berita manapun.</p>
                </div>
            @endif
        @endif

        <x-slot:actions>
            <x-button label="Tutup" @click="$wire.detailDrawer = false" />
        </x-slot:actions>
    </x-drawer>

    {{-- DELETE MODAL (Sama seperti sebelumnya) --}}
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
