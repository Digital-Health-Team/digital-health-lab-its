<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
    <!-- Left Side: Image -->
    <div class="hidden lg:block relative h-full w-full">
        <img 
            src="https://images.unsplash.com/photo-1637979909766-ccf55518a928?q=80&w=834&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
            alt="Background" 
            class="absolute inset-0 w-full h-full object-cover"
        />
        <div class="absolute inset-0 bg-black/10"></div>
    </div>

    <!-- Right Side: Form -->
    <div class="flex flex-col justify-center items-center p-8 lg:p-16 bg-base-100 text-base-content">
        <div class="w-full max-w-md space-y-8">
            <!-- Header -->
            <div class="text-center">
                {{-- Placeholder Logo or Icon --}}
                <div class="flex justify-center mb-6">
                    <div class="bg-primary p-4 rounded-full">
                         <x-icon name="o-book-open" class="w-10 h-10 text-primary-content" />
                    </div>
                </div>

                <h2 class="text-2xl font-bold">Masuk ke Platform LogBook MBKM</h2>
                <p class="text-sm text-gray-500 mt-2">Masukkan Email dan Password untuk masuk</p>
            </div>

            <!-- Form -->
            <x-form wire:submit="login" no-separator>
                <x-input label="Email" wire:model="email" icon="o-envelope" placeholder="email@example.com" />
                <x-input label="Password" wire:model="password" type="password" icon="o-key" placeholder="********" />

                <div class="flex items-center justify-between mt-4">
                    <x-checkbox label="Ingat saya" wire:model="remember" />
                </div>

                <x-button label="Masuk" type="submit" class="btn-primary w-full mt-6" spinner="login" />
            </x-form>
        </div>
    </div>
</div>
