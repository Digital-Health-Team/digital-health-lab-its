<div>
    <x-header title="{{ __('Landing Page Content') }}"
              subtitle="{{ __('Kelola seluruh teks dan media halaman utama dari satu tempat') }}"
              separator />

    <div class="space-y-3 mt-6">

        {{-- ── HERO ────────────────────────────────────────────────────── --}}
        <x-collapse>
            <x-slot:heading>
                <div class="flex items-center gap-4">
                    {{-- Wireframe: Hero — dark bg, centered logo + lines + pill button --}}
                    <div class="w-36 h-20 rounded-lg bg-slate-800 flex flex-col items-center justify-center gap-1 shrink-0 overflow-hidden">
                        <div class="w-8 h-2 bg-white/80 rounded-sm"></div>
                        <div class="w-16 h-1 bg-white/30 rounded-sm"></div>
                        <div class="w-20 h-1 bg-white/20 rounded-sm"></div>
                        <div class="w-12 h-3 bg-cyan-500/70 rounded-full mt-1"></div>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200">{{ __('Hero Section') }}</p>
                        <p class="text-xs font-normal text-slate-400 dark:text-slate-500 mt-0.5">{{ __('Teks deskripsi, tombol CTA, dan gambar latar belakang') }}</p>
                    </div>
                </div>
            </x-slot:heading>
            <x-slot:content>
                <div class="space-y-4 p-1">
                    <x-textarea label="{{ __('Deskripsi Utama') }}" wire:model="heroDescription" rows="3"
                                hint="{{ __('Teks yang tampil di bawah logo pada bagian paling atas halaman.') }}" />
                    <x-input label="{{ __('Teks Tombol CTA') }}" wire:model="heroCtaText"
                             hint="{{ __('Label tombol ajakan bertindak, contoh: Jelajahi Lebih Lanjut') }}" />
                    <x-input label="{{ __('URL Gambar Latar') }}" wire:model="heroBgImageUrl"
                             hint="{{ __('Path atau URL gambar, contoh: /assets/images/hero_4.jpg') }}" />
                    <div class="flex justify-end pt-2">
                        <x-button label="{{ __('Simpan Hero') }}" wire:click="saveHero" class="btn-primary" spinner="saveHero" />
                    </div>
                </div>
            </x-slot:content>
        </x-collapse>

        {{-- ── ABOUT ───────────────────────────────────────────────────── --}}
        <x-collapse>
            <x-slot:heading>
                <div class="flex items-center gap-4">
                    {{-- Wireframe: About — dark bg, large text heading + paragraph lines --}}
                    <div class="w-36 h-20 rounded-lg bg-slate-800 p-2 flex flex-col gap-1 shrink-0 overflow-hidden">
                        <div class="w-20 h-3 bg-white/80 rounded-sm"></div>
                        <div class="w-16 h-3 bg-white/60 rounded-sm"></div>
                        <div class="w-24 h-1 bg-white/25 rounded-sm mt-1"></div>
                        <div class="w-20 h-1 bg-white/20 rounded-sm"></div>
                        <div class="w-24 h-1 bg-white/20 rounded-sm"></div>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200">{{ __('About Section') }}</p>
                        <p class="text-xs font-normal text-slate-400 dark:text-slate-500 mt-0.5">{{ __('Judul animasi, paragraf, dan tiga kartu kemampuan lab') }}</p>
                    </div>
                </div>
            </x-slot:heading>
            <x-slot:content>
                <div class="space-y-6 p-1">
                    {{-- Headline --}}
                    <div class="space-y-3">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Judul Animasi') }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input label="{{ __('Baris 1 Judul') }}" wire:model="aboutHeadlineLine1"
                                     hint="{{ __('Contoh: Menjembatani Inovasi') }}" />
                            <x-input label="{{ __('Baris 2 Judul') }}" wire:model="aboutHeadlineLine2"
                                     hint="{{ __('Contoh: Kesehatan dan Rekayasa.') }}" />
                        </div>
                        <x-input label="{{ __('Kata Berwarna (Aksen)') }}" wire:model="aboutHeadlineAccent"
                                 hint="{{ __('Kata tepat yang tampil dengan warna aksen cyan, contoh: Rekayasa.') }}" />
                    </div>

                    {{-- Body --}}
                    <div class="space-y-3">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Teks Isi') }}</p>
                        <x-textarea label="{{ __('Paragraf 1') }}" wire:model="aboutBody1" rows="4" />
                        <x-textarea label="{{ __('Paragraf 2') }}" wire:model="aboutBody2" rows="4" />
                    </div>

                    {{-- Capability Cards --}}
                    @foreach ([1, 2, 3] as $n)
                        <div class="space-y-3 border-t border-slate-200 dark:border-slate-700 pt-5">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                                {{ __('Kartu Kemampuan') }} {{ $n }}
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-input label="{{ __('Label Tag') }}" wire:model="cap{{ $n }}Tag"
                                         hint="{{ __('Contoh: 3D Innovation') }}" />
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Warna Aksen') }}</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color"
                                               wire:model="cap{{ $n }}Accent"
                                               class="w-10 h-9 rounded cursor-pointer border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 p-0.5" />
                                        <span class="text-xs font-mono text-slate-400 dark:text-slate-500">{{ ${'cap'.$n.'Accent'} }}</span>
                                    </div>
                                </div>
                            </div>
                            <x-input label="{{ __('Judul Kartu') }}" wire:model="cap{{ $n }}Title" />
                            <x-textarea label="{{ __('Deskripsi') }}" wire:model="cap{{ $n }}Desc" rows="2" />
                            <x-input label="{{ __('URL Gambar') }}" wire:model="cap{{ $n }}Image"
                                     hint="{{ __('URL gambar kartu, contoh: https://images.unsplash.com/...') }}" />
                        </div>
                    @endforeach

                    <div class="flex justify-end pt-2">
                        <x-button label="{{ __('Simpan About') }}" wire:click="saveAbout" class="btn-primary" spinner="saveAbout" />
                    </div>
                </div>
            </x-slot:content>
        </x-collapse>

        {{-- ── SERVICES ────────────────────────────────────────────────── --}}
        <x-collapse>
            <x-slot:heading>
                <div class="flex items-center gap-4">
                    {{-- Wireframe: Services — light bg, heading + 3 colored card blocks --}}
                    <div class="w-36 h-20 rounded-lg bg-slate-100 dark:bg-slate-700 p-2 flex flex-col gap-1.5 shrink-0 overflow-hidden">
                        <div class="w-16 h-1.5 bg-slate-400/50 rounded-sm mx-auto"></div>
                        <div class="flex gap-1 flex-1">
                            <div class="flex-1 bg-blue-700/70 rounded"></div>
                            <div class="flex-1 bg-teal-600/70 rounded"></div>
                            <div class="flex-1 bg-rose-700/70 rounded"></div>
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200">{{ __('Services Section') }}</p>
                        <p class="text-xs font-normal text-slate-400 dark:text-slate-500 mt-0.5">{{ __('Judul seksi dan tiga kartu layanan utama lab') }}</p>
                    </div>
                </div>
            </x-slot:heading>
            <x-slot:content>
                <div class="space-y-6 p-1">
                    {{-- Intro --}}
                    <div class="space-y-3">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Judul Seksi') }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input label="{{ __('Judul (tebal)') }}" wire:model="servicesHeading"
                                     hint="{{ __('Contoh: Tiga Pilar Inovasi') }}" />
                            <x-input label="{{ __('Sub-judul (miring)') }}" wire:model="servicesSubheading"
                                     hint="{{ __('Contoh: Laboratorium Kami.') }}" />
                        </div>
                        <x-textarea label="{{ __('Teks Pengantar') }}" wire:model="servicesBody" rows="3" />
                    </div>

                    {{-- Service Cards --}}
                    @foreach ([1, 2, 3] as $n)
                        <div class="space-y-3 border-t border-slate-200 dark:border-slate-700 pt-5">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                                {{ __('Kartu Layanan') }} {{ $n }}
                            </p>
                            <x-input label="{{ __('Judul Kartu') }}" wire:model="svc{{ $n }}Title" />
                            <x-textarea label="{{ __('Deskripsi Singkat') }}" wire:model="svc{{ $n }}Body" rows="2" />
                            <x-input label="{{ __('URL Ilustrasi') }}" wire:model="svc{{ $n }}Image"
                                     hint="{{ __('Path ke gambar ilustrasi kartu.') }}" />
                            <x-select
                                label="{{ __('Warna Kartu') }}"
                                wire:model="svc{{ $n }}Gradient"
                                :options="$gradientOptions"
                                option-label="name"
                                option-value="id"
                                placeholder="{{ __('Pilih warna...') }}" />
                        </div>
                    @endforeach

                    <div class="flex justify-end pt-2">
                        <x-button label="{{ __('Simpan Services') }}" wire:click="saveServices" class="btn-primary" spinner="saveServices" />
                    </div>
                </div>
            </x-slot:content>
        </x-collapse>

        {{-- ── COLLABORATION ───────────────────────────────────────────── --}}
        <x-collapse>
            <x-slot:heading>
                <div class="flex items-center gap-4">
                    {{-- Wireframe: Collaboration — light bg, alternating text + tilted photo prints --}}
                    <div class="w-36 h-20 rounded-lg bg-slate-100 dark:bg-slate-700 p-2 flex flex-col gap-1.5 shrink-0 overflow-hidden">
                        <div class="flex gap-1.5 flex-1 items-center">
                            <div class="flex-1 flex flex-col gap-1">
                                <div class="w-10 h-2 bg-slate-500/70 rounded-sm"></div>
                                <div class="w-12 h-1 bg-slate-400/40 rounded-sm"></div>
                            </div>
                            <div class="w-9 h-6 bg-cyan-600/50 rounded rotate-3"></div>
                        </div>
                        <div class="flex gap-1.5 flex-1 items-center">
                            <div class="w-9 h-6 bg-blue-700/50 rounded -rotate-3"></div>
                            <div class="flex-1 flex flex-col gap-1">
                                <div class="w-10 h-2 bg-slate-500/70 rounded-sm"></div>
                                <div class="w-12 h-1 bg-slate-400/40 rounded-sm"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200">{{ __('Collaboration Section') }}</p>
                        <p class="text-xs font-normal text-slate-400 dark:text-slate-500 mt-0.5">{{ __('Judul seksi dan empat catatan kemitraan beserta dokumentasinya') }}</p>
                    </div>
                </div>
            </x-slot:heading>
            <x-slot:content>
                <div class="space-y-6 p-1">
                    {{-- Intro --}}
                    <div class="space-y-3">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Judul Seksi') }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input label="{{ __('Judul (tebal)') }}" wire:model="collabHeading"
                                     hint="{{ __('Contoh: Dalam Kolaborasi') }}" />
                            <x-input label="{{ __('Sub-judul (miring)') }}" wire:model="collabSubheading"
                                     hint="{{ __('Contoh: Bersama Mitra.') }}" />
                        </div>
                        <x-textarea label="{{ __('Teks Pengantar') }}" wire:model="collabBody" rows="3" />
                    </div>

                    {{-- Partner Chapters --}}
                    @foreach ([1, 2, 3, 4] as $n)
                        <div class="space-y-3 border-t border-slate-200 dark:border-slate-700 pt-5">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                                {{ __('Mitra') }} {{ $n }}
                            </p>
                            <x-input label="{{ __('Nama Lengkap Institusi') }}" wire:model="chap{{ $n }}Name"
                                     hint="{{ __('Nama resmi mitra, contoh: RSUD Dr. Soetomo') }}" />
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-input label="{{ __('Baris 1 Nama (tebal)') }}" wire:model="chap{{ $n }}NameLine1"
                                         hint="{{ __('Contoh: RSUD') }}" />
                                <x-input label="{{ __('Baris 2 Nama (miring)') }}" wire:model="chap{{ $n }}NameLine2"
                                         hint="{{ __('Contoh: Dr. Soetomo') }}" />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-input label="{{ __('Jenis Kolaborasi') }}" wire:model="chap{{ $n }}Type"
                                         hint="{{ __('Contoh: Kemitraan Klinis') }}" />
                                <x-input label="{{ __('Periode') }}" wire:model="chap{{ $n }}Period"
                                         hint="{{ __('Contoh: 2023—Sekarang') }}" />
                            </div>
                            <x-textarea label="{{ __('Deskripsi') }}" wire:model="chap{{ $n }}Desc" rows="2"
                                        hint="{{ __('Satu-dua kalimat tentang kerja sama yang berjalan. Foto dokumentasi dikelola melalui menu Page Sections.') }}" />
                        </div>
                    @endforeach

                    <div class="flex justify-end pt-2">
                        <x-button label="{{ __('Simpan Collaboration') }}" wire:click="saveCollaboration" class="btn-primary" spinner="saveCollaboration" />
                    </div>
                </div>
            </x-slot:content>
        </x-collapse>

        {{-- ── WISDOM ──────────────────────────────────────────────────── --}}
        <x-collapse>
            <x-slot:heading>
                <div class="flex items-center gap-4">
                    {{-- Wireframe: Wisdom — dark bg, left text lines + right quote card --}}
                    <div class="w-36 h-20 rounded-lg bg-slate-800 p-2 flex gap-2 shrink-0 overflow-hidden">
                        <div class="flex flex-col gap-1 flex-1 justify-center">
                            <div class="w-full h-1.5 bg-white/50 rounded-sm"></div>
                            <div class="w-4/5 h-1.5 bg-white/40 rounded-sm"></div>
                            <div class="w-full h-1.5 bg-cyan-400/70 rounded-sm"></div>
                        </div>
                        <div class="w-14 bg-slate-700 rounded p-1 flex flex-col gap-1">
                            <div class="text-cyan-400/70 text-xs font-bold leading-none">"</div>
                            <div class="w-full h-1 bg-white/20 rounded-sm"></div>
                            <div class="w-3/4 h-1 bg-white/20 rounded-sm"></div>
                            <div class="w-full h-1 bg-white/20 rounded-sm"></div>
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200">{{ __('Wisdom Section') }}</p>
                        <p class="text-xs font-normal text-slate-400 dark:text-slate-500 mt-0.5">{{ __('Kutipan inspiratif dan data pembicara/penulis') }}</p>
                    </div>
                </div>
            </x-slot:heading>
            <x-slot:content>
                <div class="space-y-4 p-1">
                    <div class="space-y-3">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Judul Seksi') }}</p>
                        <x-input label="{{ __('Baris 1 Judul') }}" wire:model="wisdomHeadingLine1"
                                 hint="{{ __('Contoh: We believe in the art of') }}" />
                        <x-input label="{{ __('Baris 2 Judul (Aksen)') }}" wire:model="wisdomHeadingLine2"
                                 hint="{{ __('Baris ini tampil dengan warna aksen. Contoh: Sharing Wisdom.') }}" />
                    </div>
                    <x-textarea label="{{ __('Kutipan') }}" wire:model="wisdomQuote" rows="4" />
                    <div class="space-y-3">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ __('Sumber Kutipan') }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <x-input label="{{ __('Nama Lengkap') }}" wire:model="wisdomAttrName" />
                            <x-input label="{{ __('Jabatan / Peran') }}" wire:model="wisdomAttrRole" />
                            <x-input label="{{ __('Inisial') }}" wire:model="wisdomAttrInitials"
                                     hint="{{ __('2–3 huruf kapital untuk ikon avatar, contoh: DK') }}" />
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <x-button label="{{ __('Simpan Wisdom') }}" wire:click="saveWisdom" class="btn-primary" spinner="saveWisdom" />
                    </div>
                </div>
            </x-slot:content>
        </x-collapse>

        {{-- ── ARTICLES ────────────────────────────────────────────────── --}}
        <x-collapse>
            <x-slot:heading>
                <div class="flex items-center gap-4">
                    {{-- Wireframe: Articles — light bg, featured cover + ruled index rows --}}
                    <div class="w-36 h-20 rounded-lg bg-slate-100 dark:bg-slate-700 p-2 flex gap-2 shrink-0 overflow-hidden">
                        <div class="w-10 h-full bg-blue-800/70 rounded-sm"></div>
                        <div class="flex-1 flex flex-col justify-center gap-1.5">
                            <div class="w-full h-px bg-slate-400/60"></div>
                            <div class="w-14 h-1.5 bg-slate-500/70 rounded-sm"></div>
                            <div class="w-full h-px bg-slate-400/60"></div>
                            <div class="w-12 h-1.5 bg-slate-500/70 rounded-sm"></div>
                            <div class="w-full h-px bg-slate-400/60"></div>
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200">{{ __('Articles Section') }}</p>
                        <p class="text-xs font-normal text-slate-400 dark:text-slate-500 mt-0.5">{{ __('Judul seksi indeks publikasi di halaman utama') }}</p>
                    </div>
                </div>
            </x-slot:heading>
            <x-slot:content>
                <div class="space-y-4 p-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input label="{{ __('Judul (tebal)') }}" wire:model="articlesHeading"
                                 hint="{{ __('Contoh: Dari Meja Riset') }}" />
                        <x-input label="{{ __('Sub-judul (miring)') }}" wire:model="articlesSubheading"
                                 hint="{{ __('Contoh: Publikasi Terbaru.') }}" />
                    </div>
                    <x-textarea label="{{ __('Teks Pengantar') }}" wire:model="articlesBody" rows="3"
                                hint="{{ __('Entri artikel unggulan & indeks dikelola melalui menu Page Sections (kunci articles_featured / articles_entry_1..4).') }}" />
                    <div class="flex justify-end pt-2">
                        <x-button label="{{ __('Simpan Articles') }}" wire:click="saveArticles" class="btn-primary" spinner="saveArticles" />
                    </div>
                </div>
            </x-slot:content>
        </x-collapse>

        {{-- ── CTA ─────────────────────────────────────────────────────── --}}
        <x-collapse>
            <x-slot:heading>
                <div class="flex items-center gap-4">
                    {{-- Wireframe: CTA — midnight bg, centered heading + ECG line + button --}}
                    <div class="w-36 h-20 rounded-lg bg-slate-900 flex flex-col items-center justify-center gap-1.5 shrink-0 overflow-hidden">
                        <div class="w-16 h-2 bg-white/80 rounded-sm"></div>
                        <div class="w-12 h-2 bg-cyan-400/70 rounded-sm"></div>
                        <div class="w-24 h-px bg-cyan-400/50"></div>
                        <div class="w-14 h-3 bg-blue-700/80 rounded-full mt-0.5"></div>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200">{{ __('CTA Section') }}</p>
                        <p class="text-xs font-normal text-slate-400 dark:text-slate-500 mt-0.5">{{ __('Ajakan penutup: judul, teks, dan label tombol daftar/produk') }}</p>
                    </div>
                </div>
            </x-slot:heading>
            <x-slot:content>
                <div class="space-y-4 p-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input label="{{ __('Judul (tebal)') }}" wire:model="ctaHeading"
                                 hint="{{ __('Contoh: Masih Ingin Tahu') }}" />
                        <x-input label="{{ __('Sub-judul (miring)') }}" wire:model="ctaSubheading"
                                 hint="{{ __('Contoh: Lebih Dalam?') }}" />
                    </div>
                    <x-textarea label="{{ __('Teks Pengantar') }}" wire:model="ctaBody" rows="3" />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input label="{{ __('Label Tombol Utama') }}" wire:model="ctaPrimaryLabel"
                                 hint="{{ __('Menuju halaman pendaftaran. Contoh: Daftar Sekarang') }}" />
                        <x-input label="{{ __('Label Tautan Sekunder') }}" wire:model="ctaSecondaryLabel"
                                 hint="{{ __('Menuju halaman produk. Contoh: Jelajahi Produk') }}" />
                    </div>
                    <div class="flex justify-end pt-2">
                        <x-button label="{{ __('Simpan CTA') }}" wire:click="saveCta" class="btn-primary" spinner="saveCta" />
                    </div>
                </div>
            </x-slot:content>
        </x-collapse>

        {{-- ── CONTACT ─────────────────────────────────────────────────── --}}
        <x-collapse>
            <x-slot:heading>
                <div class="flex items-center gap-4">
                    {{-- Wireframe: Contact — dark bg, left heading+icons + right form block --}}
                    <div class="w-36 h-20 rounded-lg bg-slate-800 p-2 flex gap-2 shrink-0 overflow-hidden">
                        <div class="flex flex-col gap-1.5 flex-1 justify-center">
                            <div class="w-14 h-1.5 bg-white/60 rounded-sm"></div>
                            <div class="flex items-center gap-1">
                                <div class="w-1.5 h-1.5 rounded-full bg-cyan-400/70 shrink-0"></div>
                                <div class="w-10 h-1 bg-white/25 rounded-sm"></div>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-1.5 h-1.5 rounded-full bg-cyan-400/70 shrink-0"></div>
                                <div class="w-12 h-1 bg-white/25 rounded-sm"></div>
                            </div>
                            <div class="flex items-center gap-1">
                                <div class="w-1.5 h-1.5 rounded-full bg-cyan-400/70 shrink-0"></div>
                                <div class="w-9 h-1 bg-white/25 rounded-sm"></div>
                            </div>
                        </div>
                        <div class="w-14 bg-slate-700 rounded p-1 flex flex-col gap-1">
                            <div class="w-full h-2 bg-white/10 rounded-sm"></div>
                            <div class="w-full h-2 bg-white/10 rounded-sm"></div>
                            <div class="flex-1 bg-white/10 rounded-sm"></div>
                            <div class="w-10 h-2 bg-cyan-500/40 rounded-sm ml-auto"></div>
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200">{{ __('Contact Section') }}</p>
                        <p class="text-xs font-normal text-slate-400 dark:text-slate-500 mt-0.5">{{ __('Teks ajakan, email, WhatsApp, dan Instagram') }}</p>
                    </div>
                </div>
            </x-slot:heading>
            <x-slot:content>
                <div class="space-y-4 p-1">
                    <x-textarea label="{{ __('Teks Pengantar') }}" wire:model="contactCopy" rows="2"
                                hint="{{ __('Paragraf singkat di atas saluran kontak.') }}" />
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-input label="{{ __('Email') }}" wire:model="contactEmail"
                                 hint="{{ __('Contoh: idig@its.ac.id') }}" />
                        <x-input label="{{ __('WhatsApp') }}" wire:model="contactWhatsapp"
                                 hint="{{ __('Nomor dengan kode negara, contoh: +62 31 5994251') }}" />
                        <x-input label="{{ __('Instagram') }}" wire:model="contactInstagram"
                                 hint="{{ __('Contoh: @idig.htech') }}" />
                    </div>
                    <div class="flex justify-end pt-2">
                        <x-button label="{{ __('Simpan Contact') }}" wire:click="saveContact" class="btn-primary" spinner="saveContact" />
                    </div>
                </div>
            </x-slot:content>
        </x-collapse>

        {{-- ── FOOTER ──────────────────────────────────────────────────── --}}
        <x-collapse>
            <x-slot:heading>
                <div class="flex items-center gap-4">
                    {{-- Wireframe: Footer — very dark bg, 4-column grid --}}
                    <div class="w-36 h-20 rounded-lg bg-slate-950 p-2 flex gap-1.5 shrink-0 overflow-hidden">
                        <div class="flex-1 flex flex-col gap-1">
                            <div class="w-6 h-2.5 bg-white/40 rounded-sm"></div>
                            <div class="w-10 h-1 bg-white/15 rounded-sm"></div>
                            <div class="w-8 h-1 bg-white/15 rounded-sm"></div>
                        </div>
                        <div class="flex-1 flex flex-col gap-1">
                            <div class="w-7 h-1 bg-white/30 rounded-sm"></div>
                            <div class="w-9 h-1 bg-white/15 rounded-sm"></div>
                            <div class="w-8 h-1 bg-white/15 rounded-sm"></div>
                        </div>
                        <div class="flex-1 flex flex-col gap-1">
                            <div class="w-6 h-1 bg-white/30 rounded-sm"></div>
                            <div class="w-8 h-1 bg-white/15 rounded-sm"></div>
                            <div class="w-6 h-1 bg-white/15 rounded-sm"></div>
                        </div>
                        <div class="flex-1 flex flex-col gap-1">
                            <div class="w-6 h-1 bg-white/30 rounded-sm"></div>
                            <div class="flex gap-1 flex-wrap mt-0.5">
                                <div class="w-3 h-3 rounded-full bg-white/20"></div>
                                <div class="w-3 h-3 rounded-full bg-white/20"></div>
                                <div class="w-3 h-3 rounded-full bg-white/20"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-700 dark:text-slate-200">{{ __('Footer') }}</p>
                        <p class="text-xs font-normal text-slate-400 dark:text-slate-500 mt-0.5">{{ __('Tagline, alamat, kontak, dan tautan media sosial') }}</p>
                    </div>
                </div>
            </x-slot:heading>
            <x-slot:content>
                <div class="space-y-4 p-1">
                    <x-textarea label="{{ __('Tagline') }}" wire:model="footerTagline" rows="2" />
                    <x-textarea label="{{ __('Alamat') }}" wire:model="footerAddress" rows="2"
                                hint="{{ __('Tekan Enter untuk memisahkan menjadi dua baris.') }}" />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input label="{{ __('Telepon') }}" wire:model="footerPhone" />
                        <x-input label="{{ __('Email') }}" wire:model="footerEmail" />
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 pt-2">{{ __('URL Media Sosial') }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input label="{{ __('YouTube') }}" wire:model="footerYoutube"
                                 hint="{{ __('URL lengkap channel YouTube') }}" />
                        <x-input label="{{ __('Instagram') }}" wire:model="footerInstagram"
                                 hint="{{ __('URL profil Instagram') }}" />
                        <x-input label="{{ __('Facebook') }}" wire:model="footerFacebook"
                                 hint="{{ __('URL halaman Facebook') }}" />
                        <x-input label="{{ __('LinkedIn') }}" wire:model="footerLinkedin"
                                 hint="{{ __('URL halaman LinkedIn') }}" />
                    </div>
                    <div class="flex justify-end pt-2">
                        <x-button label="{{ __('Simpan Footer') }}" wire:click="saveFooter" class="btn-primary" spinner="saveFooter" />
                    </div>
                </div>
            </x-slot:content>
        </x-collapse>

    </div>
</div>
