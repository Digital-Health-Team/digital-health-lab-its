{{-- Wrapper Utama dengan x-data Gallery Logic --}}
<div class="h-[calc(100vh-6rem)] flex flex-col" x-data="{
    galleryOpen: false,
    currentImage: '',
    currentIndex: 0,
    images: [],

    // Load data gambar dari script tag JSON saat pertama kali dibuka
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

    {{-- [LOGIC MASTER DATA] Mengumpulkan semua gambar dari $thread->attachments menjadi satu array global --}}
    @php
        // Kita ambil relation revisionThreads, lalu ambil attachments-nya, filter hanya image, lalu mapping URL-nya.
        // flatMap berguna untuk menggabungkan banyak array attachments menjadi 1 array flat.
        $allAttachments = $jobdesk->revisionThreads
            ->flatMap(fn($thread) => $thread->attachments)
            ->filter(fn($att) => Str::startsWith($att->file_type, 'image'))
            ->map(fn($att) => asset('storage/' . $att->file_path))
            ->values()
            ->toArray();
    @endphp

    {{-- Simpan data master ini dalam script tag agar bersih --}}
    <script type="application/json" id="gallery-data">
        {!! json_encode($allAttachments) !!}
    </script>

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6 px-1">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.jobdesks') }}" class="hover:underline flex items-center gap-1">
                    <x-icon name="o-arrow-left" class="w-3 h-3" /> Back to List
                </a>
                <span class="opacity-50">/</span>
                <span>{{ $jobdesk->project->name }}</span>
            </div>
            <h1 class="text-2xl font-bold flex items-center gap-3">
                {{ $jobdesk->title }}
                <div
                    class="badge {{ match ($jobdesk->status) {'revision' => 'badge-error','approved' => 'badge-success',default => 'badge-ghost'} }}">
                    {{ strtoupper($jobdesk->status) }}
                </div>
            </h1>
        </div>
        <div class="text-right text-sm">
            <div class="text-gray-500">Assigned To</div>
            <div class="font-bold flex items-center justify-end gap-2">
                <x-avatar :image="$jobdesk->assignee->avatar ?? null" class="!w-6 !h-6" />
                {{ $jobdesk->assignee->name }}
            </div>
        </div>
    </div>

    {{-- LAYOUT GRID --}}
    <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-6 overflow-hidden">

        {{-- KOLOM KIRI: THREAD HISTORY --}}
        <div
            class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col overflow-hidden shadow-sm">

            <div
                class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 font-bold text-sm flex justify-between items-center">
                <span>Thread History</span>
                <span class="text-xs font-normal opacity-60">{{ $jobdesk->revisionThreads->count() }} messages</span>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-8 bg-gray-50 dark:bg-black/20">
                @if ($jobdesk->revisionThreads->count() > 0)
                    <div class="relative space-y-8">
                        {{-- Timeline Line --}}
                        <div class="absolute left-6 top-2 bottom-2 w-0.5 bg-gray-200 dark:bg-gray-700"></div>

                        @foreach ($jobdesk->revisionThreads as $thread)
                            <div class="relative pl-14 group">
                                {{-- Avatar --}}
                                <div
                                    class="absolute left-2 top-0 bg-white dark:bg-gray-800 p-1 rounded-full border border-gray-200 dark:border-gray-700 z-10">
                                    <x-avatar :image="$thread->user->avatar ?? null" class="!w-9 !h-9" />
                                </div>

                                {{-- Bubble Chat --}}
                                <div
                                    class="p-5 rounded-2xl border shadow-sm
                                    {{ $thread->is_staff_reply
                                        ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800 ml-auto mr-0 max-w-[90%]'
                                        : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 max-w-[95%]' }}">

                                    {{-- Header Bubble --}}
                                    <div
                                        class="flex justify-between items-center mb-3 pb-2 border-b border-gray-100 dark:border-gray-700/50">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-sm text-gray-800 dark:text-gray-200">{{ $thread->user->name }}</span>
                                            <span
                                                class="text-[10px] text-gray-400 dark:text-gray-500">{{ $thread->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        <span
                                            class="badge {{ $thread->is_staff_reply ? 'badge-info' : 'badge-warning' }} badge-xs text-white border-none">
                                            {{ $thread->is_staff_reply ? 'REPLY' : 'INSTRUCTION' }}
                                        </span>
                                    </div>

                                    {{-- Content --}}
                                    <div
                                        class="prose prose-sm max-w-none dark:prose-invert text-sm leading-relaxed text-gray-700 dark:text-gray-300">
                                        {!! nl2br(e($thread->content)) !!}
                                    </div>

                                    {{-- Attachments Loop --}}
                                    @if ($thread->attachments->count() > 0)
                                        <div
                                            class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/50 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                            @foreach ($thread->attachments as $att)
                                                @php
                                                    $url = asset('storage/' . $att->file_path);
                                                    // Kita cari posisi gambar ini di array global $allAttachments
                                                    // Ini kuncinya agar navigasi next/prev nyambung antar thread
                                                    $globalIndex = array_search($url, $allAttachments);
                                                @endphp

                                                {{-- Logic Gambar: Klik trigger openGallery dengan index global --}}
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
                    <div class="h-full flex flex-col items-center justify-center opacity-30 dark:opacity-20">
                        <x-icon name="o-chat-bubble-left-right" class="w-20 h-20 mb-4 dark:text-gray-500" />
                        <div class="text-lg font-semibold dark:text-gray-500">No history yet</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- KOLOM KANAN: FORM INPUT --}}
        <div
            class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col shadow-sm h-full overflow-hidden">
            <div
                class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 font-bold text-sm">
                New Instruction
            </div>

            <div class="flex-1 overflow-y-auto p-5">
                <x-form wire:submit="submitRevision" class="flex flex-col gap-5 h-full">

                    <div
                        class="alert alert-warning text-xs shadow-sm flex items-start gap-2 bg-warning/10 text-warning border-warning/20">
                        <x-icon name="o-exclamation-triangle" class="w-5 h-5 shrink-0" />
                        <span>Status will be changed to <b>REVISION</b> and notify the staff.</span>
                    </div>

                    <div class="space-y-4">
                        <x-choices label="From (PM/Admin)" wire:model="revisionPmId" :options="$pmsList"
                            option-label="name" option-value="id" single searchable search-function="searchPm"
                            icon="o-user" />

                        <x-datetime label="New Deadline" wire:model="revisionDeadline" type="datetime-local"
                            icon="o-calendar" />
                    </div>

                    <x-textarea label="Instruction / Notes" wire:model="revisionNotes"
                        placeholder="Describe the revision details clearly..." rows="8"
                        class="flex-1 font-mono text-sm bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:border-primary" />

                    <div
                        class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                        <x-file label="Attachments" wire:model="revisionFiles" accept="image/*,application/pdf" multiple
                            hint="Max 10MB per file" />
                    </div>

                    <x-button label="Send Instruction" class="btn-warning w-full shadow-md text-white" type="submit"
                        spinner="submitRevision" icon="o-paper-airplane" />
                </x-form>
            </div>
        </div>
    </div>

    {{-- LIGHTBOX GALERI (Teleport ke Body agar Fullscreen) --}}
    <template x-teleport="body">
        <div x-show="galleryOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" {{-- BACKDROP: Menggunakan bg-black/80 dan backdrop-blur-md untuk efek frosted glass --}}
            class="fixed inset-0 z-[9999] bg-black/80 backdrop-blur-md flex flex-col justify-center items-center"
            style="display: none;">

            {{-- Top Bar --}}
            <div
                class="absolute top-0 w-full p-4 flex justify-between items-center text-white z-50 bg-gradient-to-b from-black/60 to-transparent">
                <span class="text-sm font-mono opacity-80 tracking-widest drop-shadow-md">
                    IMAGE <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                </span>
                <button @click="closeGallery()" class="p-2 hover:bg-white/20 rounded-full transition group">
                    <x-icon name="o-x-mark"
                        class="w-8 h-8 group-hover:rotate-90 transition duration-300 drop-shadow-md" />
                </button>
            </div>

            {{-- Main Image Area --}}
            <div class="relative w-full h-full flex items-center justify-center p-4">

                {{-- Prev Button --}}
                <button @click="prevImage()"
                    class="absolute left-4 p-4 rounded-full bg-white/10 hover:bg-white/20 text-white transition backdrop-blur-md z-40 hidden md:block border border-white/10 shadow-lg hover:scale-110 active:scale-95">
                    <x-icon name="o-chevron-left" class="w-8 h-8" />
                </button>

                {{-- The Image --}}
                <img :src="currentImage"
                    class="max-w-full max-h-[85vh] object-contain shadow-2xl rounded-lg select-none transition-all duration-300"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-50 scale-95" x-transition:enter-end="opacity-100 scale-100">

                {{-- Next Button --}}
                <button @click="nextImage()"
                    class="absolute right-4 p-4 rounded-full bg-white/10 hover:bg-white/20 text-white transition backdrop-blur-md z-40 hidden md:block border border-white/10 shadow-lg hover:scale-110 active:scale-95">
                    <x-icon name="o-chevron-right" class="w-8 h-8" />
                </button>
            </div>

            {{-- Bottom Thumbnails --}}
            <div
                class="absolute bottom-0 w-full p-4 flex justify-center gap-2 overflow-x-auto bg-gradient-to-t from-black/80 to-transparent z-50">
                <template x-for="(img, index) in images" :key="index">
                    <div @click="openGallery(img, index)"
                        class="w-14 h-14 rounded-md border-2 overflow-hidden cursor-pointer transition-all duration-300 flex-shrink-0"
                        :class="currentIndex === index ? 'border-yellow-500 opacity-100 scale-110 ring-2 ring-yellow-500/50' :
                            'border-transparent opacity-50 hover:opacity-100'">
                        <img :src="img" class="w-full h-full object-cover">
                    </div>
                </template>
            </div>
        </div>
    </template>

</div>
