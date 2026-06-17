<div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 font-sans bg-[#F8F9FA] text-[#1E293B]">
    {{-- Internal Styles --}}
    <style>
        @keyframes fade-in-up { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        @keyframes slide-in-left { 0% { opacity: 0; transform: translateX(-30px); } 100% { opacity: 1; transform: translateX(0); } }
        @keyframes text-gradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fade-in { 0% { opacity: 0; } 100% { opacity: 1; } }
    </style>

    {{-- LEFT SIDE (7 Cols) --}}
    <div class="hidden lg:flex lg:col-span-7 relative bg-[#00426D] text-white flex-col justify-between overflow-hidden p-12 lg:p-16">
        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=2564&auto=format&fit=crop"
            alt="Digital Health Lab Background"
            class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-luminosity" />
        <div class="absolute inset-0 bg-gradient-to-tr from-[#00426D]/95 via-[#00426D]/80 to-[#00A8B5]/50"></div>

        <div class="relative z-10 flex items-center gap-3">
            <x-app-logo-icon class="w-10 h-10 object-contain drop-shadow-lg" />
            <span class="font-bold text-2xl tracking-tight">{{ env('APP_NAME', 'Digital Health Lab') }}</span>
        </div>

        <div class="relative z-10 space-y-8 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-xs font-bold tracking-wide text-[#FFC72C] animate-[fade-in-up_0.8s_ease-out_both]">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#FFC72C] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#FFC72C]"></span>
                </span>
                ITS Digital Health Innovation Hub
            </div>

            <h1 class="text-5xl lg:text-7xl font-extrabold leading-none tracking-tight">
                <span class="block animate-[slide-in-left_0.8s_cubic-bezier(0.16,1,0.3,1)_0.2s_both]">
                    Mulai perjalanan
                </span>
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-[#00A8B5] via-[#48c9d4] to-[#FFC72C] animate-[text-gradient_3s_ease_infinite] bg-[size:200%_auto] mt-2 animate-[slide-in-left_0.8s_cubic-bezier(0.16,1,0.3,1)_0.4s_both]">
                    inovasimu.
                </span>
            </h1>

            <p class="text-lg text-slate-300 leading-relaxed max-w-xl animate-[fade-in-up_0.8s_ease-out_0.6s_both]">
                Bergabunglah dengan ekosistem riset dan inovasi teknologi kesehatan bersama mahasiswa dan profesional ITS.
            </p>
        </div>

        <div class="relative z-10 text-xs text-slate-400 font-mono animate-[fade-in_1s_ease-out_1s_both]">
            © {{ date('Y') }} Institut Teknologi Sepuluh Nopember. All rights reserved.
        </div>
    </div>

    {{-- RIGHT SIDE: REGISTER FORM (5 Cols) --}}
    <div class="lg:col-span-5 flex flex-col justify-center items-center p-8 lg:p-12 bg-[#FFFFFF] shadow-2xl z-10 overflow-y-auto">
        <div class="w-full max-w-md space-y-6">

            {{-- Mobile Logo --}}
            <div class="lg:hidden flex items-center gap-2 mb-4 text-[#00426D]">
                <x-app-logo-icon class="w-10 h-10 object-contain" />
                <span class="font-bold text-xl">{{ env('APP_NAME', 'Digital Health Lab') }}</span>
            </div>

            {{-- Title --}}
            <div class="space-y-1">
                <h2 class="text-3xl font-bold text-[#1E293B] tracking-tight">Buat akun baru</h2>
                <p class="text-slate-500 text-sm">Lengkapi data di bawah untuk mendaftar.</p>
            </div>

            {{-- Step Indicator (pure Blade, CSS transition on the connector bar) --}}
            <div class="flex items-center">
                {{-- Step 1 circle --}}
                <div class="flex flex-col items-center gap-1 flex-shrink-0">
                    <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold transition-all duration-300
                        {{ $currentStep >= 1 ? 'bg-[#00426D] text-white border-[#00426D]' : 'bg-white text-slate-400 border-slate-300' }}">
                        @if($currentStep > 1)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            1
                        @endif
                    </div>
                    <span class="text-xs font-semibold transition-colors duration-300 {{ $currentStep >= 1 ? 'text-[#00426D]' : 'text-slate-400' }}">
                        Akun Utama
                    </span>
                </div>

                {{-- Animated connector --}}
                <div class="flex-1 mx-3 mb-4">
                    <div class="h-0.5 w-full bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-[#00426D] rounded-full transition-all duration-500 ease-out
                            {{ $currentStep >= 2 ? 'w-full' : 'w-0' }}"></div>
                    </div>
                </div>

                {{-- Step 2 circle --}}
                <div class="flex flex-col items-center gap-1 flex-shrink-0">
                    <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-xs font-bold transition-all duration-300
                        {{ $currentStep >= 2 ? 'bg-[#00426D] text-white border-[#00426D]' : 'bg-white text-slate-400 border-slate-300' }}">
                        2
                    </div>
                    <span class="text-xs font-semibold transition-colors duration-300 {{ $currentStep >= 2 ? 'text-[#00426D]' : 'text-slate-400' }}">
                        Data Profil
                    </span>
                </div>
            </div>

            {{-- 2-Step Form --}}
            <x-form wire:submit="register" no-separator>

                @if($currentStep === 1)
                {{-- ============== STEP 1: Akun Utama ============== --}}
                <div wire:key="step-1" wire:transition class="space-y-4">

                    {{-- Foto Profil --}}
                    <div x-data>
                        <label class="block text-sm font-semibold text-[#1E293B] mb-2">
                            Foto Profil <span class="text-xs font-normal text-slate-400">(opsional)</span>
                        </label>
                        <div class="flex items-center gap-3">
                            {{-- Avatar preview --}}
                            <div class="w-11 h-11 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                                @if ($profilePhoto)
                                    <img src="{{ $profilePhoto->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover" />
                                @else
                                    <x-icon name="o-user" class="w-5 h-5 text-slate-400" />
                                @endif
                            </div>

                            {{-- Button + hint --}}
                            <div class="flex-1 space-y-1.5">
                                <button type="button" @click="$refs.photoInput.click()"
                                    class="w-full inline-flex items-center justify-center gap-2 h-10 px-4 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:border-[#00426D] hover:text-[#00426D] hover:bg-[#00426D]/5 active:scale-[0.99] transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-[#00426D]/30 shadow-sm">
                                    <x-icon name="{{ $profilePhoto ? 'o-arrow-path' : 'o-arrow-up-tray' }}" class="w-4 h-4" />
                                    {{ $profilePhoto ? 'Ganti Foto' : 'Pilih Foto Profil' }}
                                </button>
                                @if ($profilePhoto)
                                    <p class="text-xs text-emerald-600 font-medium pl-0.5">✓ Foto berhasil dipilih</p>
                                @else
                                    <p class="text-xs text-slate-400 pl-0.5">JPG, PNG · Maks 2MB</p>
                                @endif
                                @error('profilePhoto')
                                    <p class="text-red-500 text-xs pl-0.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <input type="file" x-ref="photoInput" wire:model="profilePhoto" accept="image/*" class="hidden" />
                    </div>

                    {{-- Nama Lengkap --}}
                    <x-input
                        label="Nama Lengkap"
                        wire:model="name"
                        icon="o-user"
                        placeholder="Tari Namaga"
                        required
                        class="rounded-xl border-slate-200 focus:border-[#00426D] focus:ring-[#00426D]" />

                    {{-- Email + Peran --}}
                    <div class="grid grid-cols-2 gap-3">
                        <x-input
                            label="Alamat Email"
                            wire:model="email"
                            type="email"
                            icon="o-envelope"
                            placeholder="nama@its.ac.id"
                            required
                            class="rounded-xl border-slate-200 focus:border-[#00426D] focus:ring-[#00426D]" />

                        <div class="form-control w-full">
                            <label class="label pb-1 px-1">
                                <span class="label-text font-semibold text-[#1E293B]">Peran</span>
                                <span class="label-text-alt text-red-500 font-semibold">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <x-icon name="o-shield-check" class="w-4 h-4 text-slate-400" />
                                </div>
                                <select wire:model="role_id"
                                    class="w-full h-12 pl-9 pr-8 rounded-xl border border-slate-200 bg-white text-sm focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] appearance-none cursor-pointer {{ $role_id ? 'text-slate-700' : 'text-slate-400' }}">
                                    <option value="" class="text-slate-400">Pilih peran</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" class="text-slate-700">
                                            {{ $role->name === 'mahasiswa' ? 'Mahasiswa' : 'Publik' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                    <x-icon name="o-chevron-down" class="w-4 h-4 text-slate-400" />
                                </div>
                            </div>
                            @error('role_id')
                                <p class="label py-0 px-1">
                                    <span class="label-text-alt text-red-500">{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Kata Sandi --}}
                    <div class="relative" x-data="{ show: false }">
                        <x-input
                            label="Kata Sandi"
                            wire:model="password"
                            x-bind:type="show ? 'text' : 'password'"
                            icon="o-lock-closed"
                            placeholder="••••••••"
                            class="rounded-xl border-slate-200 focus:border-[#00426D] focus:ring-[#00426D] pr-10" />
                        <button type="button" @click="show = !show"
                            class="absolute right-4 bottom-9 text-slate-400 hover:text-[#00A8B5] transition focus:outline-none">
                            <x-icon name="o-eye" x-show="!show" class="w-5 h-5" />
                            <x-icon name="o-eye-slash" x-show="show" class="w-5 h-5" style="display: none;" />
                        </button>
                        <p class="text-xs text-slate-400 mt-1">Minimal 6 karakter</p>
                    </div>

                    {{-- Lanjutkan --}}
                    <div class="pt-2 space-y-4">
                        <x-button
                            label="Lanjutkan"
                            wire:click="nextStep"
                            type="button"
                            class="w-full rounded-xl font-bold shadow-lg shadow-[#00426D]/20 normal-case text-base bg-gradient-to-r from-[#00426D] to-[#00A8B5] border-none hover:opacity-90 text-white"
                            icon-right="o-arrow-right"
                            spinner="nextStep" />

                        <div class="text-center">
                            <p class="text-slate-500 text-sm">
                                Sudah punya akun?
                                <a href="{{ route('login') }}"
                                    class="font-bold text-[#00426D] hover:text-[#00A8B5] transition-colors" wire:navigate>
                                    Masuk
                                </a>
                            </p>
                        </div>
                    </div>

                </div>

                @else
                {{-- ============== STEP 2: Data Profil ============== --}}
                <div wire:key="step-2" wire:transition class="space-y-4">

                    {{-- Step 2 Header --}}
                    <div class="space-y-1">
                        <h3 class="text-lg font-bold text-[#1E293B]">
                            {{ $isMahasiswaSelected ? 'Data Profil Mahasiswa' : 'Data Profil' }}
                        </h3>
                        @if($isMahasiswaSelected)
                            <p class="text-xs font-semibold text-[#00426D] uppercase tracking-wider">
                                NIM, NIK, Universitas, dan Fakultas wajib diisi
                            </p>
                        @else
                            <p class="text-xs font-semibold text-[#00426D] uppercase tracking-wider">
                                NIK wajib diisi
                            </p>
                        @endif
                    </div>

                    {{-- NIM + NIK --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-[#1E293B]">
                                NIM @if($isMahasiswaSelected)<span class="text-red-500">*</span>@endif
                            </label>
                            <input type="text" wire:model="nim" placeholder="5031201013"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] placeholder-slate-400" />
                            @error('nim')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-400">Untuk Mahasiswa</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-[#1E293B]">NIK <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nik" placeholder="3578XXXXXXXXXXXXXX"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] placeholder-slate-400" />
                            @error('nik')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Universitas + Fakultas --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-[#1E293B]">
                                Universitas @if($isMahasiswaSelected)<span class="text-red-500">*</span>@endif
                            </label>
                            <input type="text" wire:model="university" placeholder="ITS"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] placeholder-slate-400" />
                            @error('university')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-[#1E293B]">
                                Fakultas @if($isMahasiswaSelected)<span class="text-red-500">*</span>@endif
                            </label>
                            <input type="text" wire:model="faculty" placeholder="FTEIC"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] placeholder-slate-400" />
                            @error('faculty')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Departemen + Nomor Telepon --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-[#1E293B]">Departemen</label>
                            <input type="text" wire:model="department" placeholder="Teknologi Kedokteran"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] placeholder-slate-400" />
                            @error('department')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-[#1E293B]">Nomor Telepon</label>
                            <input type="text" wire:model="phone" placeholder="(+62) 214 5535 187"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] placeholder-slate-400" />
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Alamat Lengkap --}}
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-[#1E293B]">Alamat Lengkap</label>
                        <textarea wire:model="address" rows="3"
                            placeholder="Kpg. Ciwastra No. 956, Manado 31385, Banten"
                            class="w-full rounded-xl border border-slate-200 bg-white text-sm text-slate-700 px-3 py-2.5 focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] placeholder-slate-400 resize-none"></textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Step 2 Buttons --}}
                    <div class="pt-2 grid grid-cols-2 gap-3">
                        <x-button
                            label="Kembali"
                            wire:click="prevStep"
                            type="button"
                            class="w-full rounded-xl font-bold normal-case text-base border border-slate-200 text-slate-600 bg-white hover:bg-slate-50"
                            icon="o-arrow-left" />

                        <x-button
                            label="Daftar"
                            type="submit"
                            class="w-full rounded-xl font-bold shadow-lg shadow-[#00426D]/20 normal-case text-base bg-gradient-to-r from-[#00426D] to-[#00A8B5] border-none hover:opacity-90 text-white"
                            icon-right="o-arrow-right"
                            spinner="register" />
                    </div>

                </div>
                @endif

            </x-form>

        </div>
    </div>
</div>
