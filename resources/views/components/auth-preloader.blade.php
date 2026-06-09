<div
    x-data="{ hidden: false }"
    x-show="!hidden"
    x-init="setTimeout(() => hidden = true, 3100)"
    class="fixed inset-0 z-[9999] pointer-events-auto"
    aria-hidden="true"
    style="display: block;"
>
    {{-- 6 vertical curtains --}}
    @foreach(range(0, 5) as $i)
    <div
        class="absolute top-0 bottom-0 bg-[#00426D]"
        style="
            left: {{ ($i / 6) * 100 }}%;
            width: calc({{ number_format(100 / 6, 4) }}% + 2px);
            animation: auth-curtain-up 0.7s cubic-bezier(0.76, 0, 0.24, 1) {{ number_format(2.1 + $i * 0.05, 2) }}s forwards;
        "
    ></div>
    @endforeach

    {{-- Centered content --}}
    <div class="absolute inset-0 flex flex-col items-center justify-center gap-6 pointer-events-none">

        {{-- Logo --}}
        <img
            src="{{ asset('assets/images/logo_idig_htech_white.png') }}"
            alt="IDIG Lab"
            class="auth-preloader-logo w-auto object-contain"
            style="height: clamp(5rem, 14vw, 9rem); filter: drop-shadow(0 0 16px rgba(0,168,181,0.6));"
        />

        {{-- Progress bar track --}}
        <div class="relative w-56 md:w-80 h-px bg-[#00426D]/50 border border-[#00A8B5]/30 overflow-hidden">
            <div class="auth-preloader-bar absolute top-0 left-0 h-full bg-[#FFC72C]"></div>
        </div>

        {{-- HUD decorators --}}
        <span class="absolute bottom-8 right-8 text-[#00A8B5]/50 font-mono text-xs tracking-widest uppercase">
            SYS.INIT.SEQ // ACTIVE
        </span>
        <span class="absolute top-8 left-8 text-[#00A8B5]/50 font-mono text-xs tracking-widest uppercase">
            V. 2.0.4 // IDIG
        </span>
    </div>
</div>
