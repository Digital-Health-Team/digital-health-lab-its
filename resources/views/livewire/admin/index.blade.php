<div class="space-y-8">
    {{-- HEADER --}}
    <x-header title="Dashboard" separator progress-indicator>
        <x-slot:middle class="!justify-start">
            <span class="text-gray-500">{{ $greeting }}, <strong>{{ auth()->user()->name }}</strong>. Berikut
                ringkasan portal berita hari ini.</span>
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Buat Berita Baru" icon="o-pencil-square" link="{{ route('admin.news') }}"
                class="btn-primary" />
        </x-slot:actions>
    </x-header>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Views --}}
        <x-stat title="Total Pembaca" value="{{ number_format($stats['total_views']) }}" icon="o-eye"
            class="bg-base-100 shadow-sm" />

        {{-- Berita Published --}}
        <x-stat title="Berita Tayang" value="{{ $stats['published'] }}" icon="o-newspaper"
            class="text-success bg-base-100 shadow-sm" description="Dari {{ $stats['total_news'] }} total artikel" />

        {{-- Pending Draft --}}
        <x-stat title="Menunggu Review" value="{{ $stats['draft'] }}" icon="o-document-text"
            class="text-warning bg-base-100 shadow-sm" description="Status Draft" />

        {{-- Total Users --}}
        <x-stat title="Total Pengguna" value="{{ $stats['total_users'] }}" icon="o-users"
            class="text-info bg-base-100 shadow-sm" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KOLOM KIRI (LEBAR): DATA TERBARU --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Berita Terbaru --}}
            <x-card title="Artikel Terbaru" separator>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Penulis</th>
                                <th>Status</th>
                                <th>Tgl</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestNews as $news)
                                <tr>
                                    <td>
                                        <div class="font-bold truncate w-48" title="{{ $news->title }}">
                                            {{ $news->title }}</div>
                                    </td>
                                    <td>
                                        <div class="badge badge-ghost badge-sm">{{ $news->category->name }}</div>
                                    </td>
                                    <td>{{ $news->author->name }}</td>
                                    <td>
                                        @if ($news->status == 'published')
                                            <x-icon name="o-check-circle" class="w-5 h-5 text-success" />
                                        @elseif($news->status == 'draft')
                                            <x-icon name="o-clock" class="w-5 h-5 text-warning" />
                                        @else
                                            <x-icon name="o-archive-box" class="w-5 h-5 text-error" />
                                        @endif
                                    </td>
                                    <td class="text-xs text-gray-500">{{ $news->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-gray-500">Belum ada berita.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            {{-- User Terbaru --}}
            <x-card title="Pengguna Baru Bergabung" separator>
                @foreach ($latestUsers as $user)
                    <x-list-item :item="$user" no-hover>
                        <x-slot:avatar>
                            {{-- Memanggil helper initials() yang sudah kita buat di Model User --}}
                            <div class="avatar placeholder">
                                <div class="bg-neutral text-neutral-content rounded-full w-10">
                                    <span class="text-xs">{{ $user->initials() }}</span>
                                </div>
                            </div>
                        </x-slot:avatar>
                        <x-slot:value>
                            {{ $user->name }}
                        </x-slot:value>
                        <x-slot:sub-value>
                            {{ $user->email }}
                        </x-slot:sub-value>
                        <x-slot:actions>
                            <span class="text-xs text-gray-400">{{ $user->created_at->format('d M Y') }}</span>
                        </x-slot:actions>
                    </x-list-item>
                @endforeach
            </x-card>

        </div>

        {{-- KOLOM KANAN (SEMPIT): POPULAR / TRENDING --}}
        <div>
            <x-card title="Trending Top 5" subtitle="Berdasarkan total views" separator
                class="bg-base-100 border-l-4 border-l-primary">
                <div class="space-y-4">
                    @foreach ($popularNews as $index => $news)
                        <div class="flex items-start gap-3">
                            <div class="font-black text-2xl text-base-300">#{{ $index + 1 }}</div>
                            <div class="flex-1">
                                <div class="font-bold line-clamp-2 text-sm">{{ $news->title }}</div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-xs text-gray-500">{{ $news->category->name }}</span>
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
                    @endforeach

                    @if ($popularNews->isEmpty())
                        <div class="text-center text-sm text-gray-500">Belum ada data trending.</div>
                    @endif
                </div>
            </x-card>

            {{-- Quick Links / Info Server (Optional) --}}
            <x-card title="Sistem Info" class="mt-8 bg-gray-50">
                <div class="text-xs space-y-2">
                    <div class="flex justify-between">
                        <span>Laravel Version:</span>
                        <span class="font-mono">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Server Time:</span>
                        <span class="font-mono">{{ now()->format('H:i') }}</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
