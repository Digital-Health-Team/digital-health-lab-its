<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
    <div class="hidden lg:block relative h-full w-full">
        <img src="https://images.unsplash.com/photo-1637979909766-ccf55518a928?q=80&w=834&auto=format&fit=crop"
            alt="Background" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-black/10"></div>
    </div>

    <div class="flex flex-col justify-center items-center p-8 lg:p-16 bg-base-100 text-base-content">
        <div class="w-full max-w-md space-y-8">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="bg-primary p-4 rounded-full">
                        <x-icon name="o-book-open" class="w-10 h-10 text-primary-content" />
                    </div>
                </div>

                <h2 class="text-2xl font-bold">Masuk ke Platform {{ env('APP_NAME') }}</h2>
                <p class="text-sm text-gray-500 mt-2">Masukkan Email dan Password untuk masuk</p>
            </div>

            <x-form wire:submit="login" no-separator>
                <x-input label="Email" wire:model="email" icon="o-envelope" placeholder="email@example.com" />

                <div class="space-y-1">
                    <x-input label="Password" wire:model="password" type="password" icon="o-key"
                        placeholder="********" />
                </div>

                {{-- Row: Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between mt-2">
                    <x-checkbox label="Ingat saya" wire:model="remember" />

                    {{-- LINK FORGOT PASSWORD DISINI --}}
                    <a href="{{ route('password.request') }}" class="text-sm font-bold text-primary hover:underline"
                        wire:navigate>
                        Lupa Password?
                    </a>
                </div>

                <x-button label="Masuk" type="submit" class="btn-primary w-full mt-6" spinner="login" />
            </x-form>

            {{-- Footer: Register Link --}}
            <div class="text-center mt-6 text-sm">
                <span class="text-gray-500">Belum punya akun?</span>
                <a href="{{ route('register') }}" class="font-bold text-primary hover:underline" wire:navigate>
                    Daftar sekarang
                </a>
            </div>
        </div>
    </div>
</div>
