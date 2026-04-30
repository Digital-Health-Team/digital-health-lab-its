@once
    {{-- Script untuk 3D Viewer hanya di-load sekali --}}
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
@endonce

<div x-data="{
        lightboxOpen: false,
        activeIndex: 0,
        items: [],
        openViewer(index, data) {
            this.items = data;
            this.activeIndex = index;
            this.lightboxOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeViewer() {
            this.lightboxOpen = false;
            document.body.style.overflow = '';
            // Hentikan video jika ditutup
            let vids = document.querySelectorAll('.omni-video');
            vids.forEach(v => v.pause());
        },
        next() {
            if (this.items.length > 1) {
                this.activeIndex = (this.activeIndex === this.items.length - 1) ? 0 : this.activeIndex + 1;
            }
        },
        prev() {
            if (this.items.length > 1) {
                this.activeIndex = (this.activeIndex === 0) ? this.items.length - 1 : this.activeIndex - 1;
            }
        }
    }"
    @open-lightbox.window="openViewer($event.detail.index, $event.detail.items)"
    @keydown.escape.window="closeViewer()"
    @keydown.right.window="if(lightboxOpen) next()"
    @keydown.left.window="if(lightboxOpen) prev()">

    <template x-teleport="body">
        <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[99999] bg-black/95 backdrop-blur-md flex items-center justify-center" x-transition.opacity>

            {{-- Navigasi Atas --}}
            <div class="absolute top-0 left-0 w-full p-4 flex justify-between items-center bg-gradient-to-b from-black/80 to-transparent z-50">
                <div class="text-white/80 text-sm font-mono tracking-wider truncate px-4" x-text="items[activeIndex]?.name"></div>
                <button @click="closeViewer()" class="text-white/50 hover:text-white p-2 transition-colors">
                    <x-icon name="o-x-mark" class="w-8 h-8" />
                </button>
            </div>

            {{-- Tombol Prev --}}
            <button x-show="items.length > 1" @click.stop="prev()" class="absolute left-2 md:left-6 top-1/2 -translate-y-1/2 text-white/30 hover:text-white p-4 z-50 transition-colors">
                <x-icon name="o-chevron-left" class="w-12 h-12" />
            </button>

            {{-- Tombol Next --}}
            <button x-show="items.length > 1" @click.stop="next()" class="absolute right-2 md:right-6 top-1/2 -translate-y-1/2 text-white/30 hover:text-white p-4 z-50 transition-colors">
                <x-icon name="o-chevron-right" class="w-12 h-12" />
            </button>

            {{-- OMNI-VIEWER RENDERER --}}
            <div class="w-full h-full pt-16 pb-20 px-12 flex items-center justify-center" @click.self="closeViewer()">
                <template x-if="items[activeIndex]">
                    <div class="w-full h-full flex items-center justify-center">

                        {{-- 1. IMAGE --}}
                        <template x-if="items[activeIndex].type === 'image'">
                            <img :src="items[activeIndex].url" @click.stop="next()" class="max-w-full max-h-full object-contain cursor-pointer select-none rounded-lg shadow-2xl" />
                        </template>

                        {{-- 2. VIDEO --}}
                        <template x-if="items[activeIndex].type === 'video'">
                            <video :src="items[activeIndex].url" controls class="omni-video max-w-full max-h-full rounded-lg shadow-2xl"></video>
                        </template>

                        {{-- 3. PDF --}}
                        <template x-if="items[activeIndex].type === 'pdf'">
                            <div class="w-full max-w-5xl h-full bg-white rounded-xl overflow-hidden shadow-2xl">
                                <iframe :src="items[activeIndex].url" class="w-full h-full border-0"></iframe>
                            </div>
                        </template>

                        {{-- 4. 3D MODEL --}}
                        <template x-if="items[activeIndex].type === '3d'">
                            <div class="w-full max-w-4xl h-full bg-gradient-to-tr from-gray-900 to-gray-800 rounded-xl overflow-hidden shadow-2xl relative">
                                <model-viewer :src="items[activeIndex].url" auto-rotate camera-controls shadow-intensity="1" environment-image="neutral" class="w-full h-full"></model-viewer>
                                <div class="absolute bottom-4 left-0 w-full text-center text-white/50 text-xs pointer-events-none">{{ __('Use mouse/touch to rotate and zoom the 3D model') }}</div>
                            </div>
                        </template>

                        {{-- 5. OTHER / FALLBACK --}}
                        <template x-if="items[activeIndex].type === 'other'">
                            <div class="flex flex-col items-center justify-center text-center max-w-md bg-white/10 p-10 rounded-2xl backdrop-blur-sm border border-white/20">
                                <x-icon name="o-document-arrow-down" class="w-24 h-24 text-primary mb-6" />
                                <h3 class="text-xl font-bold text-white mb-2">{{ __('File Preview Not Available') }}</h3>
                                <p class="text-white/70 text-sm mb-6">{{ __('This file type cannot be previewed natively in the browser. You can download or open it using an external application.') }}</p>
                                <a :href="items[activeIndex].url" target="_blank" download class="btn btn-primary w-full shadow-lg">
                                    <x-icon name="o-arrow-down-tray" class="w-5 h-5 mr-2" /> {{ __('Download / Open File') }}
                                </a>
                            </div>
                        </template>

                    </div>
                </template>
            </div>

            {{-- Counter/Indicator --}}
            <div x-show="items.length > 1" class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/80 font-mono text-sm tracking-widest bg-black/50 px-5 py-2 rounded-full border border-white/10 shadow-lg backdrop-blur-sm">
                <span x-text="activeIndex + 1" class="font-bold"></span> / <span x-text="items.length"></span>
            </div>
        </div>
    </template>
</div>
