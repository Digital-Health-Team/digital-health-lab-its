<div class="space-y-8">
    {{-- HEADER --}}
    <x-header title="Persetujuan Berita" subtitle="Daftar antrian berita yang perlu ditinjau" separator
        progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" placeholder="Cari draft..." wire:model.live.debounce="search" />
        </x-slot:middle>
    </x-header>

    {{-- ALERT INFO --}}
    @if ($drafts->total() > 0)
        <div class="alert alert-warning text-sm shadow-sm flex items-center gap-2">
            <x-icon name="o-bell" class="w-5 h-5 animate-bounce" />
            <span>Ada <strong>{{ $drafts->total() }} berita</strong> menunggu persetujuan anda.</span>
        </div>
    @else
        <div class="alert alert-success text-sm shadow-sm flex items-center gap-2">
            <x-icon name="o-check-circle" class="w-5 h-5" />
            <span>Kerja bagus! Tidak ada antrian berita saat ini.</span>
        </div>
    @endif

    {{-- TABLE --}}
    <x-card>
        <x-table :headers="[
            ['key' => 'id', 'label' => '#'],
            ['key' => 'title', 'label' => 'Judul & Preview'],
            ['key' => 'author.name', 'label' => 'Penulis'],
            ['key' => 'category.name', 'label' => 'Kategori'],
            ['key' => 'date_occurred', 'label' => 'Tgl Kejadian'],
            ['key' => 'created_at', 'label' => 'Tgl Submit'],
        ]" :rows="$drafts" striped>

            {{-- Custom Cell: Title & Image --}}
            @scope('cell_title', $news)
                <div class="flex gap-3 items-start cursor-pointer hover:text-primary transition"
                    wire:click="showDetail({{ $news->id }})">
                    <div class="avatar">
                        <div class="w-12 h-12 rounded bg-base-200">
                            {{-- Gunakan Relation Images --}}
                            @if ($news->images->count() > 0)
                                <img src="{{ asset('storage/' . $news->images->first()->image_path) }}" />
                            @else
                                <span class="text-xs flex items-center justify-center h-full text-gray-400">IMG</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="font-bold line-clamp-1" title="{{ $news->title }}">{{ $news->title }}</div>
                        <div class="text-xs text-gray-500 line-clamp-1">{{ Str::limit(strip_tags($news->content), 60) }}
                        </div>

                        {{-- Indikator Request dari User --}}
                        <div class="flex gap-1 mt-1">
                            @if ($news->is_breaking)
                                <span class="badge badge-xs badge-error badge-outline">Req: Breaking</span>
                            @endif
                            @if ($news->is_headline)
                                <span class="badge badge-xs badge-warning badge-outline">Req: Headline</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endscope

            {{-- Custom Cell: Author --}}
            @scope('cell_author.name', $news)
                <div class="flex items-center gap-2">
                    <x-avatar :image="'https://ui-avatars.com/api/?name=' .
                        urlencode($news->author->name) .
                        '&color=7F9CF5&background=EBF4FF'" class="!w-6 !h-6" />
                    <span class="text-xs font-semibold">{{ $news->author->name }}</span>
                </div>
            @endscope

            {{-- Custom Cell: Category --}}
            @scope('cell_category.name', $news)
                <div class="badge badge-ghost badge-sm">{{ $news->category->name }}</div>
            @endscope

            {{-- Custom Cell: Date Occurred --}}
            @scope('cell_date_occurred', $news)
                <div class="text-xs font-mono text-gray-500">
                    {{ $news->date_occurred ? $news->date_occurred->format('d/m/Y') : '-' }}
                </div>
            @endscope

            {{-- Custom Cell: Created At --}}
            @scope('cell_created_at', $news)
                <div class="flex flex-col text-xs">
                    <span class="font-bold">{{ $news->created_at->diffForHumans() }}</span>
                    <span class="text-gray-400">{{ $news->created_at->format('H:i') }}</span>
                </div>
            @endscope

            {{-- Actions --}}
            @scope('actions', $news)
                <div class="flex gap-1 justify-end">
                    <x-button icon="o-eye" wire:click="showDetail({{ $news->id }})" class="btn-sm btn-ghost"
                        tooltip="Review" />
                    <x-button icon="o-check" wire:click="confirmApprove({{ $news->id }})"
                        class="btn-sm btn-success text-white" tooltip="Setujui" />
                    <x-button icon="o-x-mark" wire:click="confirmReject({{ $news->id }})"
                        class="btn-sm btn-ghost text-error" tooltip="Tolak" />
                </div>
            @endscope

        </x-table>
        <div class="mt-4">{{ $drafts->links() }}</div>
    </x-card>

    {{-- DRAWER DETAIL (REVIEW MODE) --}}
    <x-drawer wire:model="detailDrawer" title="Review Berita" class="w-11/12 lg:w-1/2" right separator
        with-close-button>
        @if ($selectedNews)
            <div class="space-y-6 pb-24">

                {{-- Carousel Gambar --}}
                @if ($selectedNews->images->count() > 0)
                    <div class="carousel w-full rounded-xl shadow-md bg-base-200">
                        @foreach ($selectedNews->images as $index => $img)
                            <div id="review_slide{{ $index }}" class="carousel-item relative w-full h-64">
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    class="w-full h-full object-cover" />
                                @if ($img->is_primary)
                                    <div class="absolute top-2 left-2 badge badge-primary shadow">Cover</div>
                                @endif
                                @if ($selectedNews->images->count() > 1)
                                    <div
                                        class="absolute flex justify-between transform -translate-y-1/2 left-2 right-2 top-1/2">
                                        <a href="#review_slide{{ $index - 1 < 0 ? $selectedNews->images->count() - 1 : $index - 1 }}"
                                            class="btn btn-circle btn-sm bg-black/30 border-none text-white">❮</a>
                                        <a href="#review_slide{{ $index + 1 >= $selectedNews->images->count() ? 0 : $index + 1 }}"
                                            class="btn btn-circle btn-sm bg-black/30 border-none text-white">❯</a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Header Info --}}
                <div>
                    <div class="flex gap-2 mb-2">
                        <span class="badge badge-primary">{{ $selectedNews->category->name }}</span>
                        @if ($selectedNews->date_occurred)
                            <span class="badge badge-ghost flex gap-1">
                                <x-icon name="o-calendar" class="w-3 h-3" />
                                {{ $selectedNews->date_occurred->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-black text-gray-800">{{ $selectedNews->title }}</h1>
                    <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
                        <x-icon name="o-user" class="w-4 h-4" />
                        <span>{{ $selectedNews->author->name }}</span>
                        <span>&bull;</span>
                        <span>{{ $selectedNews->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="prose max-w-none p-1">
                    {!! nl2br(e($selectedNews->content)) !!}
                </div>

                {{-- Request Info --}}
                <div class="bg-base-200 p-4 rounded-lg space-y-2">
                    <h3 class="font-bold text-sm text-gray-500 uppercase">Permintaan Penulis</h3>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2">
                            <x-icon name="o-star"
                                class="w-4 h-4 {{ $selectedNews->is_headline ? 'text-warning' : 'text-gray-400' }}" />
                            <span
                                class="{{ $selectedNews->is_headline ? 'font-bold' : 'text-gray-400' }}">Headline</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-icon name="o-fire"
                                class="w-4 h-4 {{ $selectedNews->is_breaking ? 'text-error' : 'text-gray-400' }}" />
                            <span class="{{ $selectedNews->is_breaking ? 'font-bold' : 'text-gray-400' }}">Breaking
                                News</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sticky Footer --}}
            <div class="absolute bottom-0 left-0 right-0 p-4 bg-base-100 border-t flex justify-between gap-4 z-20">
                <x-button label="Tolak" class="btn-error btn-outline flex-1" icon="o-x-mark"
                    wire:click="confirmReject({{ $selectedNews->id }})" />
                <x-button label="Proses Persetujuan" class="btn-success text-white flex-1" icon="o-check"
                    wire:click="confirmApprove({{ $selectedNews->id }})" />
            </div>
        @endif
    </x-drawer>

    {{-- MODAL APPROVE DENGAN SETTING --}}
    <x-modal wire:model="approveModal" title="Konfirmasi Penerbitan" persistent>
        <div class="space-y-4">
            <div class="alert alert-info text-sm">
                <x-icon name="o-information-circle" />
                <span>Anda dapat mengubah status prioritas berita sebelum menerbitkan.</span>
            </div>

            <div class="bg-base-200 p-4 rounded-lg space-y-4">
                {{-- Toggle Headline --}}
                <div class="flex justify-between items-center">
                    <div>
                        <div class="font-bold flex items-center gap-2">
                            <x-icon name="o-star" class="w-5 h-5 text-warning" /> Headline
                        </div>
                        <div class="text-xs text-gray-500">Tampilkan di slider utama?</div>
                    </div>
                    <x-toggle wire:model="setHeadline" class="toggle-warning" />
                </div>

                <div class="divider my-0"></div>

                {{-- Toggle Breaking --}}
                <div class="flex justify-between items-center">
                    <div>
                        <div class="font-bold flex items-center gap-2 text-error">
                            <x-icon name="o-fire" class="w-5 h-5" /> Breaking / Hot
                        </div>
                        <div class="text-xs text-gray-500">Berita sangat penting/darurat?</div>
                    </div>
                    <x-toggle wire:model="setBreaking" class="toggle-error" />
                </div>
            </div>

            <p class="text-sm text-gray-500 text-center">
                Berita akan langsung <strong>Published</strong> ke publik dengan waktu sekarang.
            </p>
        </div>

        <x-slot:actions>
            <x-button label="Batal" @click="$wire.approveModal = false" />
            <x-button label="Ya, Terbitkan Sekarang" class="btn-success text-white" wire:click="approve" spinner />
        </x-slot:actions>
    </x-modal>

    {{-- MODAL REJECT --}}
    <x-modal wire:model="rejectModal" title="Tolak Berita">
        <div class="text-center">
            <x-icon name="o-archive-box-x-mark" class="w-16 h-16 text-error mx-auto mb-2" />
            <h3 class="font-bold text-lg">Kembalikan ke Penulis?</h3>
            <p class="text-sm text-gray-500 mt-2">
                Status berita akan diubah menjadi <b>Archived</b>. Penulis perlu membuat ulang atau mengedit draft
                mereka.
            </p>
        </div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.rejectModal = false" />
            <x-button label="Tolak & Arsipkan" class="btn-error text-white" wire:click="reject" spinner />
        </x-slot:actions>
    </x-modal>
</div>
