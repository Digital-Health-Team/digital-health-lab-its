<div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 font-sans bg-[#F8F9FA] text-[#1E293B]">
    <style>
        @keyframes fade-in-up { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        @keyframes slide-in-left { 0% { opacity: 0; transform: translateX(-30px); } 100% { opacity: 1; transform: translateX(0); } }
        @keyframes text-gradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fade-in { 0% { opacity: 0; } 100% { opacity: 1; } }
    </style>

    {{-- LEFT SIDE: VISUAL & VALUE PROPOSITION (7 Cols) --}}
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
                {{ __('Secure Account Recovery') }}
            </div>

            <h1 class="text-5xl lg:text-7xl font-extrabold leading-none tracking-tight">
                <span class="block animate-[slide-in-left_0.8s_cubic-bezier(0.16,1,0.3,1)_0.2s_both]">
                    {{ __('Get back to your') }}
                </span>
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-[#00A8B5] via-[#48c9d4] to-[#FFC72C] animate-[text-gradient_3s_ease_infinite] bg-[size:200%_auto] mt-2 animate-[slide-in-left_0.8s_cubic-bezier(0.16,1,0.3,1)_0.4s_both]">
                    {{ __('Workspace') }}
                </span>
            </h1>

            <p class="text-lg text-slate-300 leading-relaxed max-w-xl animate-[fade-in-up_0.8s_ease-out_0.6s_both]">
                {{ __('We\'ll help you securely reset your password so you can get back to managing your projects.') }}
            </p>
        </div>

        <div class="relative z-10 text-xs text-slate-400 font-mono animate-[fade-in_1s_ease-out_1s_both]">
            © {{ date('Y') }} Institut Teknologi Sepuluh Nopember. {{ __('All rights reserved.') }}
        </div>
    </div>

    {{-- RIGHT SIDE: FORGOT PASSWORD FORM (5 Cols) --}}
    <div class="lg:col-span-5 flex flex-col justify-center items-center p-8 lg:p-16 bg-[#FFFFFF] shadow-2xl z-10">
        <div class="w-full max-w-sm space-y-8">
            <div class="lg:hidden flex items-center gap-2 mb-8 text-[#00426D]">
                <x-app-logo-icon class="w-10 h-10 object-contain" />
                <span class="font-bold text-xl">{{ env('APP_NAME', 'Digital Health Lab') }}</span>
            </div>

            <div class="space-y-2">
                <h2 class="text-3xl font-bold text-[#1E293B] tracking-tight">{{ __('Forgot Password?') }}</h2>
                <p class="text-slate-500">{{ __('No worries! Enter your email and we will send you a reset link.') }}</p>
            </div>

            <x-form wire:submit="sendLink" no-separator class="space-y-5">
                <div class="space-y-1">
                    <x-input :label="__('Registered Email')" wire:model="email" icon="o-envelope"
                        placeholder="you@its.ac.id"
                        class="rounded-xl border-slate-200 focus:border-[#00426D] focus:ring-[#00426D]" />
                </div>

                <div class="pt-2">
                    <x-button :label="__('Send Reset Link')" type="submit"
                        class="w-full rounded-xl font-bold shadow-lg shadow-[#00426D]/20 normal-case text-base bg-gradient-to-r from-[#00426D] to-[#00A8B5] border-none hover:opacity-90 text-white"
                        icon-right="o-paper-airplane" spinner="sendLink" />

                    <div class="pt-6 text-center">
                        <a href="{{ route('login') }}"
                            class="text-sm font-bold text-[#00A8B5] hover:text-[#00426D] flex items-center justify-center gap-2 transition"
                            wire:navigate>
                            <x-icon name="o-arrow-left" class="w-4 h-4" /> {{ __('Back to Login') }}
                        </a>
                    </div>
                </div>
            </x-form>
        </div>
    </div>
</div>
