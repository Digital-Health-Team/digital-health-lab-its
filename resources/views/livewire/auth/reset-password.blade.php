<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
    <div class="hidden lg:block relative h-full w-full">
        <img src="https://images.unsplash.com/photo-1555421689-d68471e189f2?q=80&w=2070&auto=format&fit=crop"
            alt="Background" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/20"></div>
    </div>

    <div class="flex flex-col justify-center items-center p-8 lg:p-16 bg-base-100 text-base-content">
        <div class="w-full max-w-md space-y-6">

            <div class="text-center">
                <h2 class="text-2xl font-bold">Buat Password Baru</h2>
                <p class="text-sm text-gray-500 mt-2">Silakan masukkan password baru untuk akun {{ $email }}</p>
            </div>

            <x-form wire:submit="resetPassword" no-separator>
                <x-input label="Email" wire:model="email" icon="o-envelope" readonly class="bg-gray-100" />

                <x-input label="Password Baru" wire:model="password" type="password" icon="o-key"
                    placeholder="Minimal 8 karakter" />

                <x-input label="Konfirmasi Password" wire:model="password_confirmation" type="password"
                    icon="o-check-circle" />

                <x-button label="Reset Password" type="submit" class="btn-primary w-full mt-4"
                    spinner="resetPassword" />
            </x-form>
        </div>
    </div>
</div>
