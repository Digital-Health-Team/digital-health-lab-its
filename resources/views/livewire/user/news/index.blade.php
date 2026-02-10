<div class="space-y-8">
    {{-- HEADER --}}
    <x-header title="Tulisan Saya" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" placeholder="Cari tulisan..." wire:model.live.debounce="search" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Tulis Berita" icon="o-pencil-square" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- ALERT --}}
    <div class="alert alert-info text-sm shadow-sm flex items-center gap-2">
        <x-icon name="o-information-circle" class="w-5 h-5" />
        <span>Tulisan Anda akan berstatus <b>Draft</b> hingga disetujui oleh Admin.</span>
    </div>

    {{-- TABLE --}}
    <x-card>
        <div class="mb-4 w-full md:w-1/3">
            <x-select label="Filter Status" :options="[
                ['id' => 'all', 'name' => 'Semua'],
                ['id' => 'published', 'name' => 'Sudah Terbit'],
                ['id' => 'draft', 'name' => 'Menunggu Review/Draft'],
                ['id' => 'archived', 'name' => 'Ditolak/Arsip'],
            ]" wire:model.live="filterStatus" />
        </div>

        <x-table :headers="[
            ['key' => 'title', 'label' => 'Judul Berita'],
            ['key' => 'category.name', 'label' => 'Kategori'],
            ['key' => 'date_occurred', 'label' => 'Tgl Kejadian'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'created_at', 'label' => 'Dibuat'],
        ]" :rows="$newsList" striped>

            {{-- Title & Cover --}}
            @scope('cell_title', $news)
                <div class="flex items-center gap-3">
                    <div class="avatar">
                        <div class="w-10 h-10 rounded bg-base-200">
                            @if ($news->images->count() > 0)
                                <img src="{{ asset('storage/' . $news->images->first()->image_path) }}" />
                            @else
                                <div class="flex items-center justify-center h-full text-xs text-gray-400">IMG</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div class="font-bold line-clamp-1 w-64" title="{{ $news->title }}">{{ $news->title }}</div>
                        {{-- Indikator --}}
                        <div class="flex gap-1 mt-1">
                            @if ($news->is_breaking)
                                <span class="badge badge-xs badge-error badge-outline">Hot News</span>
                            @endif
                            @if ($news->is_headline)
                                <span class="badge badge-xs badge-warning badge-outline">Headline</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endscope

            {{-- Date Occurred --}}
            @scope('cell_date_occurred', $news)
                <div class="text-xs font-mono">
                    {{ $news->date_occurred ? $news->date_occurred->format('d M Y') : '-' }}
                </div>
            @endscope

            {{-- Status --}}
            @scope('cell_status', $news)
                @if ($news->status == 'published')
                    <div class="badge badge-success badge-sm gap-1"><x-icon name="o-check" class="w-3 h-3" /> Terbit</div>
                @elseif($news->status == 'draft')
                    <div class="badge badge-warning badge-sm gap-1"><x-icon name="o-clock" class="w-3 h-3" /> Review</div>
                @else
                    <div class="badge badge-error badge-sm">Arsip</div>
                @endif
            @endscope

            {{-- Created At --}}
            @scope('cell_created_at', $news)
                {{ $news->created_at->format('d M Y') }}
            @endscope

            {{-- Actions --}}
            @scope('actions', $news)
                <div class="flex gap-1 justify-end">
                    <x-button icon="o-eye" wire:click="showDetail({{ $news->id }})" class="btn-sm btn-ghost"
                        tooltip="Lihat" />

                    @if ($news->status !== 'published')
                        <x-button icon="o-pencil" wire:click="edit({{ $news->id }})"
                            class="btn-sm btn-ghost text-warning" tooltip="Revisi" />
                        {{-- <x-button icon="o-trash" wire:click="confirmDelete({{ $news->id }})"
                            class="btn-sm btn-ghost text-error" tooltip="Hapus" /> --}}
                    @else
                        <div class="tooltip tooltip-left" data-tip="Terkunci (Sudah Terbit)">
                            <x-icon name="o-lock-closed" class="w-4 h-4 text-gray-300 mx-3" />
                        </div>
                    @endif
                </div>
            @endscope
        </x-table>
        <div class="mt-4">{{ $newsList->links() }}</div>
    </x-card>

    {{-- DRAWER FORM (CREATE / EDIT) --}}
    <x-drawer wire:model="drawer" title="{{ $isEditing ? 'Revisi Tulisan' : 'Tulis Berita Baru' }}"
        class="w-11/12 lg:w-1/2" right separator with-close-button>
        <x-form wire:submit="save">
            <div class="space-y-6">

                {{-- SECTION: MANAJEMEN GAMBAR --}}
                <div class="bg-base-200 p-4 rounded-xl border border-base-300">
                    <div class="flex justify-between items-center mb-3">
                        <label class="font-bold text-sm">Galeri Foto</label>
                        @php $totalImages = count($existingPhotos) + count($photos); @endphp
                        <span class="badge {{ $totalImages >= 5 ? 'badge-error' : 'badge-neutral' }} badge-sm">
                            Total: {{ $totalImages }} / 5 (Max)
                        </span>
                    </div>

                    {{-- Grid Container --}}
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        {{-- 1. GAMBAR LAMA (DATABASE) --}}
                        @if ($isEditing && count($existingPhotos) > 0)
                            @foreach ($existingPhotos as $img)
                                <div class="relative group w-full h-24" wire:key="existing-img-{{ $img->id }}">
                                    <img src="{{ asset('storage/' . $img->image_path) }}"
                                        class="w-full h-full object-cover rounded-lg border border-gray-400 shadow-sm" />

                                    @if ($img->is_primary)
                                        <div
                                            class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[10px] text-center py-1 rounded-b-lg">
                                            Cover</div>
                                    @endif

                                    <button type="button" wire:click="deleteExistingImage({{ $img->id }})"
                                        wire:confirm="Hapus gambar ini?"
                                        class="absolute top-1 right-1 btn btn-circle btn-xs btn-error text-white shadow-md z-20 border border-white opacity-90 hover:opacity-100 transition">
                                        <x-icon name="o-x-mark" class="w-3 h-3" />
                                    </button>
                                </div>
                            @endforeach
                        @endif

                        {{-- 2. GAMBAR BARU (PREVIEW) --}}
                        @if ($photos)
                            @foreach ($photos as $index => $photo)
                                <div class="relative group w-full h-24" wire:key="new-img-{{ $index }}">
                                    <img src="{{ $photo->temporaryUrl() }}"
                                        class="w-full h-full object-cover rounded-lg border-2 border-success shadow-sm" />
                                    <div
                                        class="absolute bottom-0 left-0 right-0 bg-success/90 text-white text-[10px] text-center py-1 rounded-b-lg">
                                        Baru</div>

                                    <button type="button" wire:click="removeUpload({{ $index }})"
                                        class="absolute top-1 right-1 btn btn-circle btn-xs btn-error text-white shadow-md z-20 border border-white opacity-90 hover:opacity-100 transition">
                                        <x-icon name="o-x-mark" class="w-3 h-3" />
                                    </button>
                                </div>
                            @endforeach
                        @endif

                        {{-- Empty State --}}
                        @if (count($existingPhotos) == 0 && count($photos) == 0)
                            <div
                                class="col-span-3 flex flex-col items-center justify-center h-24 border-2 border-dashed border-gray-400 rounded-lg text-gray-500 text-xs bg-base-100">
                                <x-icon name="o-photo" class="w-6 h-6 mb-1 opacity-50" />
                                Belum ada foto
                            </div>
                        @endif
                    </div>

                    {{-- UPLOAD INPUT --}}
                    @if ($totalImages < 5)
                        <x-file label="Upload Foto Baru" wire:model="photos"
                            accept="image/png, image/jpeg, image/jpg, image/webp" multiple
                            hint="Format: JPG/PNG. Maks 1MB/file." />
                    @else
                        <div class="alert alert-warning text-sm shadow-sm flex items-center gap-2">
                            <x-icon name="o-exclamation-triangle" class="w-5 h-5" />
                            <span>Batas maksimum 5 gambar tercapai. Hapus gambar untuk menambah baru.</span>
                        </div>
                    @endif

                    @error('photos')
                        <span class="text-error text-xs mt-2 block font-bold">{{ $message }}</span>
                    @enderror
                    @error('photos.*')
                        <span class="text-error text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- FORM FIELDS --}}
                <x-input label="Judul Berita" wire:model="title" placeholder="Judul yang menarik..." />

                <div class="grid grid-cols-2 gap-4">
                    <x-select label="Kategori" :options="$categories" wire:model="category_id"
                        placeholder="Pilih Kategori" />
                    <x-datetime label="Tanggal Kejadian" wire:model="date_occurred" icon="o-calendar"
                        type="date" />
                </div>

                <x-choices label="Tags" wire:model="selectedTags" :options="$allTags" />

                <x-textarea label="Isi Berita" wire:model="content" rows="15" placeholder="Tulis konten..."
                    hint="Minimal 20 karakter" />
            </div>

            <x-slot:actions>
                <x-button label="Batal" @click="$wire.drawer = false" />
                <x-button label="Simpan Draft" class="btn-primary" type="submit" spinner="save"
                    icon="o-document-plus" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- DETAIL DRAWER (PREVIEW RAPI) --}}
    <x-drawer wire:model="detailDrawer" title="Preview Berita" class="w-11/12 lg:w-1/2" right separator>
        @if ($selectedNews)
            <div class="space-y-6 pb-20"> {{-- Padding bottom extra agar tidak tertutup footer --}}

                {{-- 1. GAMBAR (CAROUSEL) --}}
                @if ($selectedNews->images->count() > 0)
                    <div class="carousel w-full rounded-xl shadow-md bg-base-200 border border-base-300">
                        @foreach ($selectedNews->images as $index => $img)
                            <div id="u_slide{{ $selectedNews->id . $index }}"
                                class="carousel-item relative w-full h-72">
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    class="w-full h-full object-cover" />

                                @if ($img->is_primary)
                                    <div class="absolute top-2 left-2 badge badge-primary font-bold shadow-sm">Cover
                                        Image</div>
                                @endif

                                @if ($selectedNews->images->count() > 1)
                                    <div
                                        class="absolute flex justify-between transform -translate-y-1/2 left-2 right-2 top-1/2">
                                        <a href="#u_slide{{ $selectedNews->id . ($index - 1 < 0 ? $selectedNews->images->count() - 1 : $index - 1) }}"
                                            class="btn btn-circle btn-sm bg-black/40 border-none text-white hover:bg-black/60">❮</a>
                                        <a href="#u_slide{{ $selectedNews->id . ($index + 1 >= $selectedNews->images->count() ? 0 : $index + 1) }}"
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
                                <a href="#u_slide{{ $selectedNews->id . $index }}"
                                    class="w-2 h-2 rounded-full bg-gray-300 hover:bg-primary transition"></a>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div
                        class="h-48 bg-base-200 rounded-xl flex flex-col items-center justify-center text-gray-400 border border-dashed border-gray-400">
                        <x-icon name="o-photo" class="w-12 h-12 mb-2 opacity-50" />
                        <span>Tidak ada gambar</span>
                    </div>
                @endif

                {{-- 2. HEADER INFO --}}
                <div class="space-y-4">
                    {{-- Kategori & Tanggal --}}
                    <div class="flex flex-wrap gap-2 items-center text-xs text-gray-500">
                        <span class="badge badge-neutral font-bold">{{ $selectedNews->category->name }}</span>

                        <div class="flex items-center gap-1 bg-base-200 px-2 py-1 rounded">
                            <x-icon name="o-calendar" class="w-3 h-3 text-primary" />
                            <span>Kejadian:
                                {{ $selectedNews->date_occurred ? $selectedNews->date_occurred->format('d M Y') : '-' }}</span>
                        </div>

                        <div class="flex items-center gap-1 ml-auto">
                            <x-icon name="o-eye" class="w-4 h-4" />
                            <span>{{ number_format($selectedNews->views_count) }}</span>
                        </div>
                    </div>

                    {{-- Judul --}}
                    <h1 class="text-2xl font-black leading-tight text-gray-800">{{ $selectedNews->title }}</h1>

                    {{-- Status Bar --}}
                    <div class="flex items-center justify-between p-3 bg-base-100 border border-base-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <div class="avatar placeholder">
                                <div class="bg-neutral text-neutral-content rounded-full w-8">
                                    <span>{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="text-xs">
                                <div class="font-bold">Penulis</div>
                                <div class="text-gray-500">{{ $selectedNews->author->name }}</div>
                            </div>
                        </div>

                        <div>
                            @if ($selectedNews->status == 'published')
                                <div class="badge badge-success text-white gap-1 p-3">
                                    <x-icon name="o-check-circle" class="w-4 h-4" /> Terbit
                                </div>
                            @elseif($selectedNews->status == 'draft')
                                <div class="badge badge-warning gap-1 p-3">
                                    <x-icon name="o-clock" class="w-4 h-4" /> Menunggu Review
                                </div>
                            @else
                                <div class="badge badge-error text-white gap-1 p-3">
                                    <x-icon name="o-x-circle" class="w-4 h-4" /> Arsip/Ditolak
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- 3. KONTEN BERITA --}}
                <div class="divider text-xs text-gray-400">ISI BERITA</div>
                <div class="prose max-w-none text-gray-700 leading-relaxed bg-white p-2 rounded-lg">
                    {!! nl2br(e($selectedNews->content)) !!}
                </div>

                {{-- 4. FOOTER (TAGS) --}}
                @if ($selectedNews->tags->count() > 0)
                    <div class="pt-4 border-t border-base-200">
                        <div
                            class="text-xs font-bold text-gray-400 mb-2 flex items-center gap-1 uppercase tracking-wide">
                            <x-icon name="o-tag" class="w-3 h-3" /> Tags
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($selectedNews->tags as $tag)
                                <span
                                    class="badge badge-outline text-xs hover:bg-base-200">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        @endif

        {{-- ACTIONS FOOTER --}}
        <x-slot:actions>
            <div class="flex justify-between w-full gap-4">
                <x-button label="Tutup" @click="$wire.detailDrawer = false" class="btn-ghost flex-1" />

                @if ($selectedNews && $selectedNews->status !== 'published')
                    <x-button label="Edit Berita Ini" icon="o-pencil-square" class="btn-warning flex-1"
                        wire:click="edit({{ $selectedNews->id }})" />
                @endif
            </div>
        </x-slot:actions>
    </x-drawer>

    {{-- DELETE MODAL --}}
    <x-modal wire:model="deleteModal" title="Hapus Draft?">
        <div>Draft ini akan dihapus permanen.</div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.deleteModal = false" />
            <x-button label="Hapus" class="btn-error" wire:click="delete" />
        </x-slot:actions>
    </x-modal>
</div>
