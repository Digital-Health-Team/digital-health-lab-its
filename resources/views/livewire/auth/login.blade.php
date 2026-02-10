<div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 font-sans text-base-content">

    {{-- BAGIAN KIRI: VISUAL & HEADLINE (7 Kolom) --}}
    <div class="hidden lg:flex lg:col-span-7 relative bg-neutral-900 text-white flex-col justify-end overflow-hidden">
        {{-- Background Image (Nuansa Jurnalistik/City) --}}
        <img src="https://images.unsplash.com/photo-1495020689067-958852a7765e?q=80&w=2069&auto=format&fit=crop"
            alt="News Background" class="absolute inset-0 w-full h-full object-cover opacity-60" />

        {{-- Gradient Overlay agar teks terbaca --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>

        {{-- Editorial Content --}}
        <div class="relative z-10 p-16 space-y-6">
            <div
                class="inline-flex items-center gap-2 border border-white/30 bg-white/10 backdrop-blur-md px-3 py-1 rounded text-xs font-bold uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                Live Update
            </div>

            <h1 class="text-5xl font-serif font-bold leading-tight">
                "Suara yang jernih di tengah<br>kebisingan informasi."
            </h1>

            <p class="text-lg text-gray-300 max-w-lg border-l-2 border-primary pl-4">
                Platform manajemen konten terpadu untuk jurnalis modern. Tulis, sunting, dan terbitkan berita dari satu
                tempat.
            </p>

            <div class="pt-8 flex items-center justify-between border-t border-white/20">
                <div class="text-xs font-mono opacity-70">
                    © {{ date('Y') }} {{ env('APP_NAME') }} MEDIA GROUP
                </div>
                <div class="text-xs opacity-70">
                    Foto oleh Unsplash
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN KANAN: FORM LOGIN (5 Kolom) --}}
    <div class="lg:col-span-5 bg-base-100 flex flex-col justify-center p-8 lg:p-16 relative">

        {{-- Top Right: Tanggal --}}
        <div class="absolute top-8 right-8 text-right hidden sm:block">
            <div class="text-xs font-bold uppercase tracking-widest text-gray-400">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <div class="w-full max-w-sm mx-auto space-y-8">

            {{-- Header Login --}}
            <div class="space-y-2">
                <div class="flex items-center gap-2 mb-4">
                    {{-- Logo Style Kotak Hitam --}}
                    <div class="bg-black text-white px-2 py-1 font-serif font-black text-xl">
                        NEWS
                    </div>
                    <span class="font-bold text-lg tracking-tight uppercase">{{ env('APP_NAME') }}</span>
                </div>
                <h2 class="text-3xl font-serif font-bold text-base-content">Masuk Redaksi</h2>
                <p class="text-sm text-gray-500">Silakan login untuk mengakses dashboard.</p>
            </div>

            {{-- Form Component --}}
            <x-form wire:submit="login" no-separator class="space-y-5">

                <div class="space-y-1">
                    <x-input label="Email Redaksi" wire:model="email" icon="o-envelope" placeholder="nama@media.com"
                        class="rounded-none" />
                </div>

                <div class="space-y-1">
                    <x-input label="Kata Sandi" wire:model="password" type="password" icon="o-key"
                        placeholder="••••••••" class="rounded-none" />
                </div>

                {{-- Remember & Forgot --}}
                <div class="flex items-center justify-between pt-2">
                    <x-checkbox label="Ingat saya" wire:model="remember" />

                    <a href="{{ route('password.request') }}" class="text-sm font-bold text-primary hover:underline"
                        wire:navigate>
                        Lupa Password?
                    </a>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4">
                    <x-button label="MASUK SEKARANG" type="submit"
                        class="btn-neutral w-full rounded-none font-bold tracking-widest" icon-right="o-arrow-right"
                        spinner="login" />
                </div>
            </x-form>

            {{-- Footer Link --}}
            <div class="text-center pt-6 border-t border-base-200">
                <p class="text-sm text-gray-500">
                    Ingin bergabung menjadi kontributor?
                    <a href="{{ route('register') }}" class="font-bold text-base-content hover:underline ml-1"
                        wire:navigate>
                        Daftar Akun
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>
