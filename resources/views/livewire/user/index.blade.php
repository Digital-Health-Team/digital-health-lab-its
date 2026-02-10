<div class="space-y-8">
    {{-- HEADER --}}
    <x-header title="Dashboard Penulis" separator progress-indicator>
        <x-slot:middle class="!justify-start">
            <div class="text-gray-500">
                {{ $greeting }}, <span class="font-bold text-gray-800">{{ auth()->user()->name }}</span>.
                Semangat berkarya hari ini!
            </div>
        </x-slot:middle>
        <x-slot:actions>
            {{-- Shortcut ke halaman tulis berita --}}
            <x-button label="Tulis Berita" icon="o-pencil-square" link="{{ route('user.news.index') }}"
                class="btn-primary" />
        </x-slot:actions>
    </x-header>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Views --}}
        <x-stat title="Total Pembaca" value="{{ number_format($stats['views']) }}" icon="o-eye"
            class="bg-base-100 shadow-sm border-l-4 border-info" description="Akumulasi semua tulisan" />

        {{-- Card 2: Published (Approved) --}}
        <x-stat title="Berita Terbit" value="{{ $stats['published'] }}" icon="o-check-circle"
            class="bg-base-100 shadow-sm border-l-4 border-success" description="Sudah tayang di publik" />

        {{-- Card 3: Draft (Pending) --}}
        <x-stat title="Menunggu Review" value="{{ $stats['draft'] }}" icon="o-clock"
            class="bg-base-100 shadow-sm border-l-4 border-warning" description="Draft / Dalam peninjauan" />

        {{-- Card 4: Rejected/Archived --}}
        <x-stat title="Diarsipkan"
            value="{{ number_format($stats['total'] - ($stats['published'] + $stats['draft'])) }}" icon="o-archive-box"
            class="bg-base-100 shadow-sm border-l-4 border-error" description="Ditolak atau ditarik" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KOLOM KIRI (LEBAR): STATUS TERBARU --}}
        <div class="lg:col-span-2">
            <x-card title="Status Pengajuan Terakhir" separator>
                @if ($recentNews->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>Judul Berita</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentNews as $news)
                                    <tr>
                                        <td>
                                            <div class="font-bold line-clamp-1" title="{{ $news->title }}">
                                                {{ $news->title }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="badge badge-ghost badge-sm">{{ $news->category->name }}</div>
                                        </td>
                                        <td>
                                            @if ($news->status == 'published')
                                                <div class="badge badge-success badge-sm gap-1">
                                                    <x-icon name="o-check" class="w-3 h-3" /> Terbit
                                                </div>
                                            @elseif($news->status == 'draft')
                                                <div class="badge badge-warning badge-sm gap-1">
                                                    <x-icon name="o-clock" class="w-3 h-3" /> Review
                                                </div>
                                            @else
                                                <div class="badge badge-error badge-sm gap-1">
                                                    <x-icon name="o-x-mark" class="w-3 h-3" /> Arsip
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-xs text-gray-500">
                                            {{ $news->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <x-button label="Lihat Semua Tulisan" link="{{ route('user.news.index') }}"
                            class="btn-ghost btn-sm" icon-right="o-arrow-right" />
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <x-icon name="o-pencil" class="w-12 h-12 mx-auto mb-2 opacity-50" />
                        <p>Anda belum menulis berita apapun.</p>
                        <x-button label="Mulai Menulis" link="{{ route('user.news.index') }}"
                            class="btn-primary btn-sm mt-3" />
                    </div>
                @endif
            </x-card>
        </div>

        {{-- KOLOM KANAN (SEMPIT): TULISAN TERPOPULER --}}
        <div>
            <x-card title="Tulisan Terpopuler Anda" subtitle="Berdasarkan jumlah pembaca" separator class="bg-base-100">
                <div class="space-y-4">
                    @forelse($popularNews as $index => $news)
                        <div class="flex items-start gap-3">
                            <div class="font-black text-2xl text-base-300">#{{ $index + 1 }}</div>
                            <div class="flex-1">
                                <div class="font-bold line-clamp-2 text-sm">{{ $news->title }}</div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-xs text-gray-500">{{ $news->created_at->format('d M Y') }}</span>
                                    <div class="flex items-center gap-1 text-xs font-bold text-primary">
                                        <x-icon name="o-eye" class="w-3 h-3" />
                                        {{ number_format($news->views_count) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (!$loop->last)
                            <hr class="border-base-200" />
                        @endif
                    @empty
                        <div class="text-center text-sm text-gray-500 py-4">
                            Belum ada data popularitas.
                        </div>
                    @endforelse
                </div>
            </x-card>

            {{-- Tips Card --}}
            <div class="alert alert-success text-sm mt-6 shadow-sm">
                <x-icon name="o-light-bulb" />
                <div>
                    <span class="font-bold">Tips Penulis:</span>
                    <br>
                    Judul yang menarik dan penggunaan gambar berkualitas meningkatkan peluang berita Anda dibaca lebih
                    banyak orang.
                </div>
            </div>
        </div>
    </div>
</div>
