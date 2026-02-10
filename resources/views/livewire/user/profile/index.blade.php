<div class="space-y-8">
    <x-header title="Edit Profil" separator />

    <x-form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- KOLOM KIRI: INFO DASAR --}}
            <div class="space-y-4">
                <x-card title="Informasi Dasar" separator class="bg-base-100">
                    <div class="space-y-4">
                        {{-- Avatar Preview (Optional, visual only for now) --}}
                        <div class="flex items-center gap-4 mb-4">
                            <x-avatar :image="'https://ui-avatars.com/api/?name=' .
                                urlencode($name) .
                                '&color=7F9CF5&background=EBF4FF'" class="!w-16 !h-16 rounded-full" />
                            <div>
                                <div class="font-bold">{{ $name }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Kontributor' }}</div>
                            </div>
                        </div>

                        <x-input label="Nama Lengkap" wire:model="name" icon="o-user" />
                        <x-input label="Alamat Email" wire:model="email" icon="o-envelope" type="email" />
                    </div>
                </x-card>
            </div>

            {{-- KOLOM KANAN: GANTI PASSWORD --}}
            <div class="space-y-4">
                <x-card title="Keamanan" subtitle="Kosongkan jika tidak ingin mengganti password" separator
                    class="bg-base-100">
                    <div class="space-y-4">
                        <x-input label="Password Baru" wire:model="password" icon="o-key" type="password"
                            hint="Minimal 6 karakter" />

                        <x-input label="Konfirmasi Password" wire:model="password_confirmation" icon="o-check-circle"
                            type="password" />

                        <div class="alert alert-warning text-xs mt-4 flex items-start">
                            <x-icon name="o-exclamation-triangle" class="w-5 h-5 shrink-0" />
                            <span>Pastikan password anda kuat dan unik. Jangan bagikan password kepada siapapun.</span>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="flex justify-end mt-6">
            <x-button label="Simpan Perubahan" class="btn-primary" type="submit" spinner="save" icon="o-check" />
        </div>
    </x-form>
</div>
