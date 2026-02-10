<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
    <div class="hidden lg:block relative h-full w-full">
        <img src="https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=2070&auto=format&fit=crop"
            alt="Background Register" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/20"></div>
    </div>

    <div class="flex flex-col justify-center items-center p-8 lg:p-16 bg-base-100 text-base-content">
        <div class="w-full max-w-md space-y-6">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="bg-secondary p-4 rounded-full">
                        <x-icon name="o-user-plus" class="w-10 h-10 text-secondary-content" />
                    </div>
                </div>

                <h2 class="text-2xl font-bold">Buat Akun Baru</h2>
                <p class="text-sm text-gray-500 mt-2">Bergabunglah dengan komunitas {{ env('APP_NAME') }}</p>
            </div>

            <x-form wire:submit="register" no-separator>
                <x-input label="Nama Lengkap" wire:model="name" icon="o-user" placeholder="John Doe" />

                <x-input label="Email" wire:model="email" icon="o-envelope" placeholder="email@example.com" />

                <x-input label="Password" wire:model="password" type="password" icon="o-key"
                    placeholder="Minimal 6 karakter" />

                <x-input label="Konfirmasi Password" wire:model="password_confirmation" type="password"
                    icon="o-check-circle" placeholder="Ulangi password" />

                <x-button label="Daftar Sekarang" type="submit" class="btn-primary w-full mt-4" spinner="register" />
            </x-form>

            <div class="text-center mt-6 text-sm">
                <span class="text-gray-500">Sudah punya akun?</span>
                <a href="{{ route('login') }}" class="font-bold text-primary hover:underline" wire:navigate>
                    Masuk disini
                </a>
            </div>
        </div>
    </div>
</div>
