<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
    <div class="hidden lg:block relative h-full w-full">
        <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=2070&auto=format&fit=crop"
            alt="Background" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/30"></div>
    </div>

    <div class="flex flex-col justify-center items-center p-8 lg:p-16 bg-base-100 text-base-content">
        <div class="w-full max-w-md space-y-6">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="bg-warning p-4 rounded-full">
                        <x-icon name="o-lock-closed" class="w-10 h-10 text-warning-content" />
                    </div>
                </div>

                <h2 class="text-2xl font-bold">Lupa Password?</h2>
                <p class="text-sm text-gray-500 mt-2">Jangan khawatir! Masukkan email anda dan kami akan mengirimkan
                    link reset.</p>
            </div>

            <x-form wire:submit="sendLink" no-separator>
                <x-input label="Email Terdaftar" wire:model="email" icon="o-envelope" placeholder="email@example.com" />

                <x-button label="Kirim Link Reset" type="submit" class="btn-warning w-full mt-4" spinner="sendLink" />
            </x-form>

            <div class="text-center mt-6">
                <a href="{{ route('login') }}"
                    class="text-sm font-bold text-gray-500 hover:text-primary flex items-center justify-center gap-2 transition"
                    wire:navigate>
                    <x-icon name="o-arrow-left" class="w-4 h-4" /> Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</div>
