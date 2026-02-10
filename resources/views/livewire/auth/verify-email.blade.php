<div class="min-h-screen flex flex-col justify-center items-center bg-base-200">
    <x-card class="w-full max-w-md bg-base-100 shadow-xl">

        <div class="text-center mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-primary/10 mb-4">
                <x-icon name="o-envelope" class="w-8 h-8 text-primary" />
            </div>

            <h2 class="text-2xl font-bold mb-2">{{ __('Verifikasi Email Anda') }}</h2>

            <p class="text-gray-500 text-sm">
                {{ __('Thank you for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-success text-center">
                {{ __('A new verification link has been sent to your email address.') }}
            </div>
        @endif

        <div class="flex flex-col gap-3 mt-4">
            {{-- Resend Button --}}
            <x-button label="{{ __('Resend Verification Email') }}" wire:click="resend" class="btn-primary w-full"
                spinner />

            {{-- Logout Button --}}
            <x-button label="{{ __('Logout') }}" wire:click="logout" class="btn-ghost w-full" />
        </div>

    </x-card>
</div>
