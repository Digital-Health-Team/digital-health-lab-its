<div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false" @click.outside="open = false">

    {{-- TRIGGER BUTTON --}}
    <button @click="open = !open"
        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest
               bg-base-200/50 dark:bg-[#062E5C]/60 border border-base-300 dark:border-[#0A3D7A]/40
               text-base-content/70 dark:text-[#94A3B8] hover:text-primary dark:hover:text-[#22D3EE]
               hover:border-primary/40 dark:hover:border-[#22D3EE]/40 hover:bg-primary/5 dark:hover:bg-[#0A3D7A]/30
               transition-colors cursor-pointer select-none"
        title="{{ __('Change Language') }}">
        <x-icon name="o-language" class="w-3.5 h-3.5" />
        <span>{{ strtoupper($this->current) }}</span>
        <x-icon name="o-chevron-down" class="w-2.5 h-2.5 opacity-60 transition-transform duration-150"
            ::class="open ? 'rotate-180' : ''" />
    </button>

    {{-- DROPDOWN PANEL --}}
    <div x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute right-0 mt-1.5 w-36 z-50
               bg-base-100 dark:bg-[#031026] rounded-lg shadow-xl
               border border-base-200 dark:border-[#0A3D7A]/50 overflow-hidden">

        @foreach($this->locales as $code => $meta)
            <button wire:click="switchTo('{{ $code }}')" @click="open = false"
                class="w-full flex items-center gap-2.5 px-3 py-2.5 text-sm transition-colors cursor-pointer
                       {{ $this->current === $code
                            ? 'bg-primary/10 dark:bg-[#0A3D7A]/40 text-primary dark:text-[#22D3EE] font-semibold'
                            : 'text-base-content/70 dark:text-[#94A3B8] hover:bg-base-200/60 dark:hover:bg-[#062E5C]/60 hover:text-base-content dark:hover:text-[#F8FAFC]' }}">
                <span class="font-mono text-[10px] font-black uppercase tracking-widest w-5 shrink-0
                             {{ $this->current === $code ? 'text-primary dark:text-[#22D3EE]' : 'text-base-content/40 dark:text-[#94A3B8]/60' }}">
                    {{ $code }}
                </span>
                <span>{{ $meta['native'] }}</span>
                @if($this->current === $code)
                    <x-icon name="o-check" class="w-3 h-3 ml-auto text-primary dark:text-[#22D3EE]" />
                @endif
            </button>
        @endforeach

    </div>
</div>
