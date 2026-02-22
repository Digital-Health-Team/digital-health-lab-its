@props(['images' => []])

<div x-data="{
    galleryOpen: false,
    currentImage: '',
    currentIndex: 0,
    images: @js($images), // Langsung ambil dari props Laravel ke JS

    openGallery(imgUrl, index) {
        this.galleryOpen = true;
        this.currentImage = imgUrl;
        this.currentIndex = index;
        document.body.style.overflow = 'hidden';
    },

    closeGallery() {
        this.galleryOpen = false;
        document.body.style.overflow = '';
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
}" {{-- Mendengarkan event dari mana saja di halaman ini --}}
    @open-lightbox.window="openGallery($event.detail.url, $event.detail.index)" @keydown.escape.window="closeGallery()"
    @keydown.arrow-right.window="nextImage()" @keydown.arrow-left.window="prevImage()">

    {{-- LIGHTBOX GALERI UI --}}
    <template x-teleport="body">
        <div x-show="galleryOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[9999] flex flex-col justify-center items-center touch-none overflow-hidden"
            style="display: none;" x-data="{ touchStartX: 0, touchEndX: 0 }" @touchstart="touchStartX = $event.changedTouches[0].screenX"
            @touchend="
                touchEndX = $event.changedTouches[0].screenX;
                if (touchStartX - touchEndX > 50) nextImage();
                if (touchEndX - touchStartX > 50) prevImage();
            ">
            {{-- Background Blur Layer --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-xl" @click="closeGallery()"></div>

            {{-- Top Navbar --}}
            <div
                class="absolute top-0 w-full p-4 flex justify-between items-center text-white z-50 bg-gradient-to-b from-black/60 to-transparent pointer-events-none">
                <span
                    class="text-xs md:text-sm font-mono opacity-90 tracking-widest drop-shadow-md bg-black/40 px-3 py-1 rounded-full backdrop-blur-md pointer-events-auto border border-white/10">
                    <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                </span>
                <button @click="closeGallery()"
                    class="p-2 md:p-3 bg-black/40 hover:bg-white/20 hover:scale-110 rounded-full transition-all duration-300 active:scale-95 pointer-events-auto backdrop-blur-md border border-white/10">
                    <x-icon name="o-x-mark" class="w-5 h-5 md:w-6 md:h-6" />
                </button>
            </div>

            {{-- Main Image Area --}}
            <div
                class="relative w-full h-full flex items-center justify-center p-4 pb-28 md:pb-32 z-40 pointer-events-none">

                {{-- Prev Button --}}
                <button @click.stop="prevImage()"
                    class="hidden md:flex absolute left-4 xl:left-10 p-4 rounded-full bg-black/40 hover:bg-white/20 text-white transition-all duration-300 backdrop-blur-md z-50 border border-white/10 shadow-xl active:scale-90 hover:-translate-x-1 pointer-events-auto"
                    x-show="images.length > 1">
                    <x-icon name="o-chevron-left" class="w-8 h-8" />
                </button>

                {{-- Gambar Utama --}}
                <img :src="currentImage" x-show="currentImage !== ''"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    class="max-w-full max-h-full object-contain shadow-2xl rounded-xl select-none pointer-events-auto transition-transform duration-300">

                {{-- Next Button --}}
                <button @click.stop="nextImage()"
                    class="hidden md:flex absolute right-4 xl:right-10 p-4 rounded-full bg-black/40 hover:bg-white/20 text-white transition-all duration-300 backdrop-blur-md z-50 border border-white/10 shadow-xl active:scale-90 hover:translate-x-1 pointer-events-auto"
                    x-show="images.length > 1">
                    <x-icon name="o-chevron-right" class="w-8 h-8" />
                </button>
            </div>

            {{-- Bottom Thumbnails Navigation --}}
            <div x-show="images.length > 1"
                class="absolute bottom-0 w-full p-4 flex justify-center gap-3 overflow-x-auto bg-gradient-to-t from-black/80 via-black/40 to-transparent z-50 pb-8 md:pb-6 custom-scrollbar scroll-smooth">
                <template x-for="(img, index) in images" :key="index">
                    <div @click="openGallery(img, index)"
                        class="w-14 h-14 md:w-20 md:h-20 rounded-xl overflow-hidden cursor-pointer transition-all duration-300 flex-shrink-0 relative group border-2 pointer-events-auto"
                        :class="currentIndex === index ?
                            'opacity-100 scale-110 border-primary shadow-[0_0_15px_rgba(var(--color-primary),0.5)]' :
                            'opacity-40 hover:opacity-100 border-transparent hover:border-white/30'">
                        <img :src="img"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div x-show="currentIndex === index" class="absolute inset-0 bg-primary/20"></div>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>
