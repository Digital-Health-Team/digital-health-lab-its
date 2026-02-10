<div class="space-y-8">
    {{-- HEADER --}}
    <x-header title="Manajemen Berita" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" placeholder="Cari..." wire:model.live.debounce="search" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Buat Berita" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- TABLE --}}
    <x-card>
        <x-table :headers="[
            ['key' => 'title', 'label' => 'Judul Berita'],
            ['key' => 'category.name', 'label' => 'Kategori'],
            ['key' => 'date_occurred', 'label' => 'Tgl Kejadian'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'updated_at', 'label' => 'Terakhir Update'],
        ]" :rows="$newsList" striped>

            {{-- Custom Row: Title --}}
            @scope('cell_title', $news)
                <div class="flex flex-col">
                    <div class="font-bold line-clamp-1 w-64" title="{{ $news->title }}">{{ $news->title }}</div>
                    <div class="flex gap-1 mt-1">
                        @if ($news->is_breaking)
                            <div class="badge badge-error badge-xs text-white gap-1 font-bold animate-pulse">
                                <x-icon name="o-fire" class="w-3 h-3" /> HOT / BREAKING
                            </div>
                        @endif
                        @if ($news->is_headline)
                            <div class="badge badge-warning badge-xs gap-1">
                                <x-icon name="o-star" class="w-3 h-3" /> Headline
                            </div>
                        @endif
                    </div>
                </div>
            @endscope

            {{-- Custom Row: Date Occurred --}}
            @scope('cell_date_occurred', $news)
                <div class="flex items-center gap-2 text-xs font-mono">
                    <x-icon name="o-calendar" class="w-4 h-4 text-gray-400" />
                    {{ $news->date_occurred ? $news->date_occurred->format('d M Y') : '-' }}
                </div>
            @endscope

            {{-- Custom Row: Status --}}
            @scope('cell_status', $news)
                <span class="badge {{ $news->status == 'published' ? 'badge-success' : 'badge-ghost' }} badge-sm">
                    {{ ucfirst($news->status) }}
                </span>
            @endscope

            {{-- Actions Column --}}
            @scope('actions', $news)
                <div class="flex gap-1 justify-end">

                    {{-- 1. Tombol LIHAT DETAIL (Preview) --}}
                    <x-button icon="o-eye" wire:click="showDetail({{ $news->id }})" class="btn-sm btn-ghost"
                        tooltip="Lihat Preview" />

                    {{-- 2. Tombol PUBLISH CEPAT (Hanya jika Draft) --}}
                    @if ($news->status === 'draft')
                        <x-button icon="o-paper-airplane" wire:click="quickPublish({{ $news->id }})"
                            wire:confirm="Langsung terbitkan berita ini?" class="btn-sm btn-ghost text-success"
                            tooltip="Terbitkan Sekarang" />
                    @endif

                    <x-button icon="o-pencil" wire:click="edit({{ $news->id }})" class="btn-sm btn-ghost text-warning"
                        tooltip="Edit" />

                    {{-- 4. Tombol HAPUS --}}
                    <x-button icon="o-trash" wire:click="confirmDelete({{ $news->id }})"
                        class="btn-sm btn-ghost text-error" tooltip="Hapus" />
                    {{-- Indikator Terkunci --}}
                    <div class="tooltip tooltip-left flex items-center" data-tip="Sudah Terbit (Terkunci)">
                        <x-icon name="o-lock-closed" class="w-4 h-4 text-gray-300 mx-2" />
                    </div>
                </div>
            @endscope
        </x-table>
        <div class="mt-4">{{ $newsList->links() }}</div>
    </x-card>

    {{-- DETAIL DRAWER (PREVIEW) --}}
    <x-drawer wire:model="detailDrawer" title="Preview Berita" class="w-11/12 lg:w-1/2" right separator>
        @if ($selectedNews)
            <div class="space-y-6 pb-10">

                {{-- 1. GAMBAR (CAROUSEL) --}}
                @if ($selectedNews->images->count() > 0)
                    <div class="carousel w-full rounded-xl shadow-md bg-base-200">
                        @foreach ($selectedNews->images as $index => $img)
                            <div id="detail_slide{{ $index }}" class="carousel-item relative w-full h-64">
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    class="w-full h-full object-cover" />

                                {{-- Badge Cover --}}
                                @if ($img->is_primary)
                                    <div class="absolute top-2 left-2 badge badge-primary font-bold shadow-sm">Cover
                                        Image</div>
                                @endif

                                {{-- Navigasi Carousel (Hanya jika gambar > 1) --}}
                                @if ($selectedNews->images->count() > 1)
                                    <div
                                        class="absolute flex justify-between transform -translate-y-1/2 left-2 right-2 top-1/2">
                                        <a href="#detail_slide{{ $index - 1 < 0 ? $selectedNews->images->count() - 1 : $index - 1 }}"
                                            class="btn btn-circle btn-sm bg-black/40 border-none text-white hover:bg-black/60">❮</a>
                                        <a href="#detail_slide{{ $index + 1 >= $selectedNews->images->count() ? 0 : $index + 1 }}"
                                            class="btn btn-circle btn-sm bg-black/40 border-none text-white hover:bg-black/60">❯</a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    {{-- Dots Indikator --}}
                    @if ($selectedNews->images->count() > 1)
                        <div class="flex justify-center w-full py-2 gap-2">
                            @foreach ($selectedNews->images as $index => $img)
                                <a href="#detail_slide{{ $index }}"
                                    class="w-2 h-2 rounded-full bg-gray-300 hover:bg-primary transition"></a>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="h-48 bg-base-200 rounded-xl flex flex-col items-center justify-center text-gray-400">
                        <x-icon name="o-photo" class="w-10 h-10 mb-2" />
                        <span>Tidak ada gambar</span>
                    </div>
                @endif

                {{-- 2. HEADER INFO --}}
                <div class="space-y-3">
                    {{-- Kategori & Tanggal --}}
                    <div class="flex flex-wrap gap-2 items-center text-xs text-gray-500">
                        <span class="badge badge-neutral font-bold">{{ $selectedNews->category->name }}</span>

                        <span class="flex items-center gap-1">
                            <x-icon name="o-calendar" class="w-4 h-4" />
                            Kejadian:
                            {{ $selectedNews->date_occurred ? $selectedNews->date_occurred->format('d M Y') : '-' }}
                        </span>

                        <span class="flex items-center gap-1 ml-auto">
                            <x-icon name="o-eye" class="w-4 h-4" />
                            {{ number_format($selectedNews->views_count) }} Views
                        </span>
                    </div>

                    {{-- Judul Besar --}}
                    <h1 class="text-2xl font-black leading-tight text-gray-800">{{ $selectedNews->title }}</h1>

                    {{-- Author & Status --}}
                    <div class="flex justify-between items-center border-b pb-4 border-base-200">
                        <div class="flex items-center gap-3">
                            <div class="avatar placeholder">
                                <div class="bg-neutral text-neutral-content rounded-full w-8">
                                    <span>{{ substr($selectedNews->author->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold">{{ $selectedNews->author->name }}</span>
                                <span class="text-[10px] text-gray-400">
                                    Dibuat: {{ $selectedNews->created_at->format('d M Y H:i') }}
                                </span>
                            </div>
                        </div>

                        {{-- Status Badges --}}
                        <div class="flex flex-col items-end gap-1">
                            @if ($selectedNews->status == 'published')
                                <div class="badge badge-success text-white gap-1">
                                    <x-icon name="o-check" class="w-3 h-3" /> Published
                                </div>
                            @elseif($selectedNews->status == 'draft')
                                <div class="badge badge-warning gap-1">
                                    <x-icon name="o-pencil" class="w-3 h-3" /> Draft
                                </div>
                            @else
                                <div class="badge badge-error text-white gap-1">Archived</div>
                            @endif
                        </div>
                    </div>

                    {{-- Special Flags (Headline/Breaking) --}}
                    @if ($selectedNews->is_breaking || $selectedNews->is_headline)
                        <div class="flex gap-2">
                            @if ($selectedNews->is_breaking)
                                <div class="alert alert-error py-2 text-white text-sm flex items-center shadow-sm">
                                    <x-icon name="o-fire" class="w-5 h-5 animate-pulse" />
                                    <span class="font-bold">BREAKING NEWS</span>
                                </div>
                            @endif
                            @if ($selectedNews->is_headline)
                                <div class="alert alert-warning py-2 text-sm flex items-center shadow-sm">
                                    <x-icon name="o-star" class="w-5 h-5" />
                                    <span class="font-bold">Pilihan Editor (Headline)</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- 3. KONTEN BERITA --}}
                <div class="prose max-w-none text-gray-700 leading-relaxed bg-base-100 p-1">
                    {{-- Render HTML dengan aman --}}
                    {!! $selectedNews->content !!}
                </div>

                {{-- 4. FOOTER (TAGS) --}}
                @if ($selectedNews->tags->count() > 0)
                    <div class="pt-4 border-t border-base-200">
                        <div class="text-sm font-bold text-gray-400 mb-2 flex items-center gap-1">
                            <x-icon name="o-tag" class="w-4 h-4" /> Tags Terkait:
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($selectedNews->tags as $tag)
                                <span class="badge badge-outline text-xs">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        @endif

        {{-- ACTIONS FOOTER --}}
        <x-slot:actions>
            <div class="flex justify-between w-full">
                <x-button label="Tutup" @click="$wire.detailDrawer = false" class="btn-ghost" />

                @if ($selectedNews && $selectedNews->status !== 'published')
                    <x-button label="Edit Berita Ini" icon="o-pencil-square" class="btn-warning btn-outline"
                        wire:click="edit({{ $selectedNews->id }})" />
                @endif
            </div>
        </x-slot:actions>
    </x-drawer>

    {{-- === DRAWER FORM === --}}
    <x-drawer wire:model="drawer" title="{{ $isEditing ? 'Edit Berita' : 'Tulis Berita Baru' }}"
        class="w-11/12 lg:w-1/2" right separator with-close-button>
        <x-form wire:submit="checkBeforeSave">

            <div class="space-y-5">

                {{-- SECTION 1: KONTEN UTAMA --}}
                <div class="card bg-base-100 border border-base-200 shadow-sm p-4 space-y-4">
                    <h3 class="font-bold text-sm text-gray-500 border-b pb-2">Konten Utama</h3>
                    <x-input label="Judul Berita" wire:model="title" placeholder="Masukkan judul yang menarik..." />
                    <div class="grid grid-cols-2 gap-4">
                        <x-select label="Kategori" :options="$categories" wire:model="category_id"
                            placeholder="Pilih..." />
                        <x-datetime label="Tanggal Kejadian" wire:model="date_occurred" type="date"
                            icon="o-calendar" />
                    </div>
                    <x-textarea label="Isi Berita" wire:model="content" rows="12"
                        placeholder="Tulis konten..." />
                    <x-choices label="Tags" wire:model="selectedTags" :options="$allTags" />
                </div>

                {{-- SECTION 2: GALERI FOTO --}}
                <div class="card bg-base-100 border border-base-200 shadow-sm p-4">
                    <div class="flex justify-between items-center mb-3 border-b pb-2">
                        <label class="font-bold text-sm text-gray-500">Galeri Foto</label>
                        @php $totalImages = count($existingPhotos) + count($photos); @endphp
                        <span class="badge {{ $totalImages > 5 ? 'badge-error' : 'badge-neutral' }} badge-sm">
                            {{ $totalImages }} / 5
                        </span>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mb-3">
                        {{-- 1. GAMBAR LAMA (DATABASE - EDIT ONLY) --}}
                        @if ($isEditing && count($existingPhotos) > 0)
                            @foreach ($existingPhotos as $img)
                                <div class="relative group h-24" wire:key="old-{{ $img->id }}">
                                    <img src="{{ asset('storage/' . $img->image_path) }}"
                                        class="w-full h-full object-cover rounded-lg border" />
                                    @if ($img->is_primary)
                                        <div
                                            class="absolute bottom-0 w-full bg-black/60 text-white text-[10px] text-center rounded-b-lg">
                                            Cover</div>
                                    @endif

                                    {{-- TOMBOL HAPUS DATABASE --}}
                                    <button type="button" wire:click="deleteExistingImage({{ $img->id }})"
                                        wire:confirm="Hapus gambar ini secara permanen dari database?"
                                        class="absolute top-1 right-1 btn btn-circle btn-xs btn-error text-white z-10 border-white border shadow hover:scale-110 transition">
                                        <x-icon name="o-trash" class="w-3 h-3" />
                                    </button>
                                </div>
                            @endforeach
                        @endif

                        {{-- 2. GAMBAR BARU (UPLOAD - CREATE & EDIT) --}}
                        @foreach ($photos as $idx => $photo)
                            <div class="relative group h-24" wire:key="new-{{ $idx }}">
                                <img src="{{ $photo->temporaryUrl() }}"
                                    class="w-full h-full object-cover rounded-lg border-2 border-success" />
                                <div
                                    class="absolute bottom-0 w-full bg-success text-white text-[10px] text-center rounded-b-lg">
                                    Baru</div>

                                {{-- TOMBOL HAPUS PREVIEW (TANPA AKSES DB) --}}
                                <button type="button" wire:click="removeUpload({{ $idx }})"
                                    class="absolute top-1 right-1 btn btn-circle btn-xs btn-error text-white z-10 border-white border shadow hover:scale-110 transition">
                                    <x-icon name="o-x-mark" class="w-3 h-3" />
                                </button>
                            </div>
                        @endforeach
                    </div>

                    @if ($totalImages < 5)
                        <x-file label="Tambah Foto" wire:model="photos" multiple accept="image/*" />
                    @else
                        <div class="text-error text-xs text-center font-bold">Batas maksimum tercapai.</div>
                    @endif
                    @error('photos.*')
                        <span class="text-error text-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- SECTION 3: PENGATURAN PUBLIKASI --}}
                <div
                    class="card bg-base-200 border-l-4 {{ $status === 'published' ? 'border-success' : 'border-warning' }} p-4">
                    <h3 class="font-bold text-sm text-gray-700 mb-3">Pengaturan Publikasi</h3>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <label class="cursor-pointer flex items-center gap-2">
                                <input type="radio" wire:model.live="status" value="draft"
                                    class="radio radio-warning radio-sm" />
                                <span class="text-sm font-medium">Simpan Draft</span>
                            </label>
                            <label class="cursor-pointer flex items-center gap-2">
                                <input type="radio" wire:model.live="status" value="published"
                                    class="radio radio-success radio-sm" />
                                <span class="text-sm font-medium">Publish Sekarang</span>
                            </label>
                        </div>
                        <div class="divider my-0"></div>
                        <div class="flex justify-between items-center">
                            <label class="cursor-pointer label gap-2 justify-start">
                                <span class="label-text font-bold">Headline?</span>
                                <input type="checkbox" wire:model="is_headline"
                                    class="toggle toggle-warning toggle-sm" />
                            </label>
                            <label
                                class="cursor-pointer label gap-2 justify-start bg-red-100 px-3 py-1 rounded-lg border border-red-200">
                                <span class="label-text font-bold text-red-600 flex items-center gap-1">
                                    <x-icon name="o-fire" class="w-4 h-4" /> HOT / Breaking?
                                </span>
                                <input type="checkbox" wire:model="is_breaking"
                                    class="toggle toggle-error toggle-sm" />
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.drawer = false" />
                <x-button label="{{ $status === 'published' ? 'Terbitkan' : 'Simpan' }}"
                    class="{{ $status === 'published' ? 'btn-success text-white' : 'btn-primary' }}" type="submit"
                    spinner="checkBeforeSave"
                    icon="{{ $status === 'published' ? 'o-paper-airplane' : 'o-check' }}" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- MODAL KONFIRMASI PUBLISH --}}
    <x-modal wire:model="publishConfirmModal" title="Konfirmasi Publish" persistent>
        <div class="text-center space-y-3">
            <div class="text-5xl">🚀</div>
            <h3 class="font-bold text-lg">Anda yakin ingin menerbitkan berita ini?</h3>
            <p class="text-sm text-gray-500">Berita akan langsung muncul di halaman depan website.</p>
            @if ($is_breaking)
                <div class="alert alert-error text-white text-sm py-2">
                    <x-icon name="o-fire" class="w-5 h-5" /> Ditandai sebagai <b>BREAKING NEWS</b>
                </div>
            @endif
        </div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.publishConfirmModal = false" />
            <x-button label="Ya, Terbitkan!" class="btn-success text-white" wire:click="processSave" spinner />
        </x-slot:actions>
    </x-modal>

    {{-- MODAL DELETE BARU (LEBIH BAGUS) --}}
    <x-modal wire:model="deleteModal" persistent>
        <div class="text-center p-4">
            {{-- Icon Sampah Besar --}}
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 mb-4">
                <x-icon name="o-trash" class="h-8 w-8 text-red-600" />
            </div>

            <h3 class="text-lg font-bold text-gray-900">Hapus Berita Ini?</h3>

            <div class="mt-2">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus berita ini secara permanen? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
        </div>

        <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
            <x-button label="Ya, Hapus Permanen" class="btn-error text-white w-full sm:col-start-2"
                wire:click="delete" spinner />
            <x-button label="Batal" class="btn-ghost w-full sm:col-start-1 mt-3 sm:mt-0"
                @click="$wire.deleteModal = false" />
        </div>
    </x-modal>
</div>
