<div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 font-sans bg-base-100 text-base-content">
    {{-- Internal Styles --}}
    <style>
        @keyframes fade-in-up { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        @keyframes slide-in-left { 0% { opacity: 0; transform: translateX(-30px); } 100% { opacity: 1; transform: translateX(0); } }
        @keyframes text-gradient { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fade-in { 0% { opacity: 0; } 100% { opacity: 1; } }
    </style>

    {{-- LEFT SIDE: VISUAL & VALUE PROPOSITION (7 Cols) --}}
    <div class="hidden lg:flex lg:col-span-7 relative bg-slate-900 text-white flex-col justify-between overflow-hidden p-12 lg:p-16">
        <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2564&auto=format&fit=crop"
             alt="Productivity Background"
             class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay" />
        <div class="absolute inset-0 bg-gradient-to-tr from-[#5b2c9d]/90 via-[#2d1b4e]/80 to-[#e65c00]/40"></div>

        <div class="relative z-10 flex items-center gap-3">
            <x-app-logo-icon class="w-10 h-10 object-contain drop-shadow-lg" />
            <span class="font-bold text-2xl tracking-tight">{{ env('APP_NAME') }}</span>
        </div>

        <div class="relative z-10 space-y-8 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-xs font-bold tracking-wide text-orange-200 animate-[fade-in-up_0.8s_ease-out_both]">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                </span>
                Laravel 12 Hybrid Action-Oriented Template
            </div>

            <h1 class="text-5xl lg:text-7xl font-extrabold leading-none tracking-tight">
                <span class="block animate-[slide-in-left_0.8s_cubic-bezier(0.16,1,0.3,1)_0.2s_both]">
                    Hybrid Action-Oriented Template.
                </span>
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-pink-500 to-purple-500 animate-[text-gradient_3s_ease_infinite] bg-[size:200%_auto] mt-2 animate-[slide-in-left_0.8s_cubic-bezier(0.16,1,0.3,1)_0.4s_both]">
                    By Gretiva
                </span>
            </h1>

            <p class="text-lg text-slate-300 leading-relaxed max-w-xl animate-[fade-in-up_0.8s_ease-out_0.6s_both]">
                Save your development time
            </p>
        </div>

        <div class="relative z-10 text-xs text-slate-500 font-mono animate-[fade-in_1s_ease-out_1s_both]">
            © {{ date('Y') }} {{ env('APP_NAME') }}. Engineered for productivity.
        </div>
    </div>

    {{-- RIGHT SIDE: LOGIN FORM (5 Cols) --}}
    <div class="lg:col-span-5 flex flex-col justify-center items-center p-8 lg:p-16 bg-white dark:bg-base-100">
        <div class="w-full max-w-sm space-y-8">
            <div class="lg:hidden flex items-center gap-2 mb-8">
                 <x-app-logo-icon class="w-10 h-10 object-contain" />
                <span class="font-bold text-xl">{{ env('APP_NAME') }}</span>
            </div>

            <div class="space-y-2">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Welcome back</h2>
                <p class="text-slate-500">Please enter your details to access your workspace.</p>
            </div>

            <x-form wire:submit="login" no-separator class="space-y-5">
                {{-- Email Input --}}
                <div class="space-y-1">
                    <x-input
                        label="Work Email"
                        wire:model="email"
                        icon="o-envelope"
                        placeholder="you@company.com"
                        class="rounded-xl border-slate-200 focus:border-[#5b2c9d] focus:ring-[#5b2c9d]"
                    />
                </div>

                {{-- Password Input with Toggle --}}
                <div class="space-y-1 relative" x-data="{ show: false }">
                    <x-input
                        label="Password"
                        wire:model="password"
                        x-bind:type="show ? 'text' : 'password'"
                        icon="o-lock-closed"
                        placeholder="••••••••"
                        class="rounded-xl border-slate-200 focus:border-[#5b2c9d] focus:ring-[#5b2c9d] pr-10"
                    />
                    {{-- Toggle Button --}}
                    <button type="button" @click="show = !show" class="absolute right-4 bottom-3 text-slate-400 hover:text-[#5b2c9d] transition focus:outline-none">
                        <x-icon name="o-eye" x-show="!show" class="w-5 h-5" />
                        <x-icon name="o-eye-slash" x-show="show" class="w-5 h-5" style="display: none;" />
                    </button>
                </div>

                <div class="flex items-center justify-between">
                    <x-checkbox label="Keep me logged in" wire:model="remember" class="checkbox-primary checkbox-sm [--chkbg:#5b2c9d] [--chkfg:white]" />
                    <a href="{{ route('password.request') }}" class="text-sm font-bold text-[#5b2c9d] hover:text-[#4a2380]" wire:navigate>
                        Forgot password?
                    </a>
                </div>

                <div class="pt-2">
                    <x-button
                        label="Login"
                        type="submit"
                        class="w-full rounded-xl font-bold shadow-lg shadow-purple-500/20 normal-case text-base bg-gradient-to-r from-[#5b2c9d] to-[#e65c00] border-none hover:opacity-90 text-white"
                        icon-right="o-arrow-right"
                        spinner="login"
                    />
                    <div class="pt-4 text-center">
                        <p class="text-slate-600 dark:text-slate-400 text-sm">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="font-bold text-[#5b2c9d] hover:text-[#4a2380] transition-colors" wire:navigate>
                                Create one
                            </a>
                        </p>
                    </div>
                </div>
            </x-form>
        </div>
    </div>
</div>
