{{-- Wrapper Utama dengan x-data Gallery Logic --}}
<div class="h-[calc(100vh-6rem)] flex flex-col" x-data="{
    galleryOpen: false,
    currentImage: '',
    currentIndex: 0,
    images: [],

    initGallery() {
        if (this.images.length === 0) {
            const data = document.getElementById('gallery-data');
            if (data) {
                this.images = JSON.parse(data.textContent);
            }
        }
    },

    openGallery(imgUrl, index) {
        this.initGallery();
        this.galleryOpen = true;
        this.currentImage = imgUrl;
        this.currentIndex = index;
    },

    closeGallery() {
        this.galleryOpen = false;
    },

    nextImage() {
        if (this.currentIndex < this.images.length - 1) {
            this.currentIndex++;
            this.currentImage = this.images[this.currentIndex];
        }
    },

    prevImage() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.currentImage = this.images[this.currentIndex];
        }
    }
}" @keydown.escape.window="closeGallery()"
    @keydown.arrow-right.window="nextImage()" @keydown.arrow-left.window="prevImage()">

    {{-- LOGIC MASTER DATA & FIX TRANSLATION --}}
    @php
        // 1. Logic Attachments (Gallery)
        $allAttachments = $jobdesk->revisionThreads
            ->flatMap(fn($thread) => $thread->attachments)
            ->filter(fn($att) => Str::startsWith($att->file_type, 'image'))
            ->map(fn($att) => asset('storage/' . $att->file_path))
            ->values()
            ->toArray();

        // 2. FIX: Handle Project Name (Array to String)
        $projectName = $jobdesk->project->name;
        if (is_array($projectName)) {
            $projectName = $projectName['id'] ?? ($projectName['en'] ?? '-');
        }

        // 3. FIX: Handle Jobdesk Title/Name (Array to String)
        // Cek property 'title' dulu, jika null cek 'name'
        $taskName = $jobdesk->title ?? $jobdesk->name;
        if (is_array($taskName)) {
            $taskName = $taskName['id'] ?? ($taskName['en'] ?? '-');
        }
    @endphp

    <script type="application/json" id="gallery-data">
        {!! json_encode($allAttachments) !!}
    </script>

    {{-- HEADER (RESPONSIVE) --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 md:mb-6 px-1 gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs md:text-sm text-gray-500 mb-1">
                {{-- Link kembali ke Dashboard Staff --}}
                <a href="{{ route('user.dashboard') }}" class="hover:underline flex items-center gap-1">
                    <x-icon name="o-arrow-left" class="w-3 h-3" /> Back to Dashboard
                </a>
                <span class="opacity-50">/</span>
                {{-- FIX: Menggunakan variabel yang sudah di-process --}}
                <span class="truncate max-w-[150px] md:max-w-xs">{{ $projectName }}</span>
            </div>
            <h1 class="text-xl md:text-2xl font-bold flex flex-wrap items-center gap-2 md:gap-3">
                {{-- FIX: Menggunakan variabel yang sudah di-process --}}
                <span class="truncate max-w-[200px] md:max-w-md">{{ $taskName }}</span>
                <div
                    class="badge {{ match ($jobdesk->status) {'revision' => 'badge-error','approved' => 'badge-success','review' => 'badge-warning',default => 'badge-ghost'} }}">
                    {{ strtoupper($jobdesk->status) }}
                </div>
            </h1>
        </div>

        {{-- Creator Info --}}
        <div
            class="w-full md:w-auto flex items-center gap-3 text-sm bg-gray-50 dark:bg-gray-800 md:bg-transparent md:dark:bg-transparent p-3 md:p-0 rounded-lg border border-gray-100 dark:border-gray-700 md:border-none">
            <div class="text-right">
                <div class="text-gray-500 dark:text-gray-400 text-xs">Task Created By</div>
                <div class="font-bold dark:text-gray-200">{{ $jobdesk->creator->name }}</div>
            </div>
            <x-avatar :image="$jobdesk->creator->profile_photo ? asset('storage/' . $jobdesk->creator->profile_photo) : null" class="!w-9 !h-9" />
        </div>
    </div>

    {{-- LAYOUT GRID --}}
    <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 overflow-hidden min-h-0">

        {{-- KOLOM KIRI: THREAD HISTORY --}}
        <div
            class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col overflow-hidden shadow-sm h-full">

            <div
                class="p-3 md:p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 font-bold text-sm flex justify-between items-center shrink-0">
                <span>Discussion & Revision History</span>
                <span class="text-xs font-normal opacity-60">{{ $jobdesk->revisionThreads->count() }} messages</span>
            </div>

            <div
                class="flex-1 overflow-y-auto p-4 md:p-6 space-y-6 md:space-y-8 bg-gray-50 dark:bg-black/20 scroll-smooth">
                @if ($jobdesk->revisionThreads->count() > 0)
                    <div class="relative space-y-6 md:space-y-8">
                        <div class="absolute left-5 md:left-6 top-2 bottom-2 w-0.5 bg-gray-200 dark:bg-gray-700"></div>

                        @foreach ($jobdesk->revisionThreads as $thread)
                            <div class="relative pl-12 md:pl-14 group">
                                {{-- Avatar Logic --}}
                                <div
                                    class="absolute left-1 md:left-2 top-0 bg-white dark:bg-gray-800 p-1 rounded-full border border-gray-200 dark:border-gray-700 z-10">
                                    <x-avatar :image="$thread->user->profile_photo
                                        ? asset('storage/' . $thread->user->profile_photo)
                                        : null" class="!w-8 !h-8 md:!w-9 md:!h-9" />
                                </div>

                                {{-- Bubble Chat Logic --}}
                                <div
                                    class="p-3 md:p-5 rounded-2xl border shadow-sm w-full
                                    {{ $thread->is_staff_reply
                                        ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800 ml-auto mr-0 max-w-[95%] md:max-w-[90%]' // Staff (Right)
                                        : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 max-w-[98%] md:max-w-[95%]' }}">

                                    <div
                                        class="flex flex-wrap justify-between items-center mb-2 md:mb-3 pb-2 border-b border-gray-100 dark:border-gray-700/50 gap-2">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-sm text-gray-800 dark:text-gray-200">{{ $thread->user->name }}</span>
                                            <span
                                                class="text-[10px] text-gray-400 dark:text-gray-500">{{ $thread->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        <span
                                            class="badge {{ $thread->is_staff_reply ? 'badge-primary' : 'badge-warning' }} badge-xs text-white border-none">
                                            {{ $thread->is_staff_reply ? 'ME' : 'INSTRUCTION' }}
                                        </span>
                                    </div>

                                    <div
                                        class="prose prose-sm max-w-none dark:prose-invert text-sm leading-relaxed text-gray-700 dark:text-gray-300 break-words">
                                        {!! nl2br(e($thread->content)) !!}
                                    </div>

                                    @if ($thread->attachments->count() > 0)
                                        <div
                                            class="mt-3 md:mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/50 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 md:gap-3">
                                            @foreach ($thread->attachments as $att)
                                                @php
                                                    $url = asset('storage/' . $att->file_path);
                                                    $globalIndex = array_search($url, $allAttachments);
                                                @endphp
                                                <div @click="openGallery('{{ $url }}', {{ $globalIndex !== false ? $globalIndex : 0 }})"
                                                    class="group relative aspect-square bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 cursor-zoom-in hover:ring-2 hover:ring-primary transition">
                                                    <img src="{{ $url }}"
                                                        class="object-cover w-full h-full transform group-hover:scale-105 transition duration-500">
                                                    <div
                                                        class="absolute inset-x-0 bottom-0 bg-black/60 text-white text-[10px] p-1 truncate text-center backdrop-blur-md opacity-0 group-hover:opacity-100 transition">
                                                        {{ $att->file_name }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center opacity-30 dark:opacity-20 py-10">
                        <x-icon name="o-chat-bubble-left-right"
                            class="w-16 h-16 md:w-20 md:h-20 mb-4 dark:text-gray-500" />
                        <div class="text-lg font-semibold dark:text-gray-500">No revisions yet</div>
                        <div class="text-xs">Start the conversation below.</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- KOLOM KANAN: FORM INPUT --}}
        <div
            class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col shadow-sm h-full overflow-hidden shrink-0">
            <div
                class="p-3 md:p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 font-bold text-sm shrink-0">
                Submit Reply / Fix
            </div>

            <div class="flex-1 overflow-y-auto p-4 md:p-5">

                {{-- [LOGIC BARU] Tampilkan Pesan Blokir jika belum Check-in --}}
                @if (!$canSubmit)
                    <div class="flex flex-col items-center justify-center h-full text-center space-y-4 opacity-70">
                        <div class="bg-base-200 p-4 rounded-full">
                            <x-icon name="o-lock-closed" class="w-8 h-8 text-gray-500" />
                        </div>
                        <div>
                            <h3 class="font-bold">Access Locked</h3>
                            <p class="text-xs max-w-[200px] mx-auto mt-1">
                                Anda harus melakukan <b>Check In</b> di Dashboard terlebih dahulu untuk mengirim update
                                atau revisi.
                            </p>
                        </div>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-primary">Go to Dashboard</a>
                    </div>
                @else
                    {{-- Form Aktif (Jika sudah checkin) --}}
                    <x-form wire:submit="sendReply" class="flex flex-col gap-4 md:gap-5 h-full">
                        <div
                            class="bg-primary/5 border border-primary/20 dark:bg-primary/10 dark:border-primary/30 p-3 rounded-lg flex items-center justify-between">
                            <div class="text-xs">
                                <div class="font-bold text-primary">Is the issue resolved?</div>
                                <div class="opacity-60 text-[10px] dark:text-gray-400">Status will change to
                                    <b>REVIEW</b>.
                                </div>
                            </div>
                            <input type="checkbox" wire:model="markAsFixed" class="toggle toggle-primary toggle-sm" />
                        </div>

                        <x-textarea label="Reply Message" wire:model="replyContent"
                            placeholder="I have fixed the issue..." rows="5"
                            class="flex-1 font-mono text-sm bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:border-primary min-h-[120px]" />

                        <div
                            class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                            <x-file label="Attachments" wire:model="replyFiles"
                                accept="image/*,application/pdf,application/zip" multiple hint="Max 10MB" />
                        </div>

                        <x-button label="Send Reply" class="btn-primary w-full shadow-md text-white mb-2" type="submit"
                            spinner="sendReply" icon="o-paper-airplane" />
                    </x-form>
                @endif
            </div>
        </div>
    </div>

    {{-- LIGHTBOX GALERI (Sama persis dengan Admin) --}}
    <template x-teleport="body">
        <div x-show="galleryOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[9999] bg-black/90 backdrop-blur-md flex flex-col justify-center items-center touch-none"
            style="display: none;">

            <div
                class="absolute top-0 w-full p-4 flex justify-between items-center text-white z-50 bg-gradient-to-b from-black/80 to-transparent">
                <span class="text-xs md:text-sm font-mono opacity-80 tracking-widest drop-shadow-md">
                    <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                </span>
                <button @click="closeGallery()" class="p-3 bg-white/10 rounded-full transition active:bg-white/30">
                    <x-icon name="o-x-mark" class="w-6 h-6 md:w-8 md:h-8" />
                </button>
            </div>

            <div class="relative w-full h-full flex items-center justify-center p-2 md:p-4">
                <button @click="prevImage()"
                    class="absolute left-2 md:left-4 p-3 rounded-full bg-white/10 hover:bg-white/20 text-white transition backdrop-blur-md z-40 border border-white/10 shadow-lg active:scale-95"
                    x-show="images.length > 1">
                    <x-icon name="o-chevron-left" class="w-6 h-6 md:w-8 md:h-8" />
                </button>

                <img :src="currentImage"
                    class="max-w-full max-h-[80vh] object-contain shadow-2xl rounded-lg select-none"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-50 scale-95" x-transition:enter-end="opacity-100 scale-100">

                <button @click="nextImage()"
                    class="absolute right-2 md:right-4 p-3 rounded-full bg-white/10 hover:bg-white/20 text-white transition backdrop-blur-md z-40 border border-white/10 shadow-lg active:scale-95"
                    x-show="images.length > 1">
                    <x-icon name="o-chevron-right" class="w-6 h-6 md:w-8 md:h-8" />
                </button>
            </div>

            <div
                class="absolute bottom-0 w-full p-4 flex justify-center gap-2 overflow-x-auto bg-gradient-to-t from-black/80 to-transparent z-50 pb-8 md:pb-4">
                <template x-for="(img, index) in images" :key="index">
                    <div @click="openGallery(img, index)"
                        class="w-12 h-12 md:w-16 md:h-16 rounded-md border-2 overflow-hidden cursor-pointer transition-all duration-300 flex-shrink-0"
                        :class="currentIndex === index ?
                            'border-yellow-500 opacity-100 scale-110 ring-2 ring-yellow-500/50' :
                            'border-transparent opacity-40 hover:opacity-100'">
                        <img :src="img" class="w-full h-full object-cover">
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>
