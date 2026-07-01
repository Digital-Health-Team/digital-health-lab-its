{{-- Admin: Order Detail — dynamic per service_type: printing | design | scanning --}}
<div x-data="{ activeTab: '{{ $activeTab }}' }" x-cloak class="text-slate-700 dark:text-[#F8FAFC]">

    @php
        $svcType = $booking->service?->service_type;
        $statusColors = [
            'pending'     => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
            'negotiating' => 'bg-cyan-100 text-cyan-700 dark:bg-[#0A3D7A]/50 dark:text-[#22D3EE]',
            'completed'   => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
            'cancelled'   => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
        ];
        $statusClass = $statusColors[$booking->current_status] ?? 'bg-slate-100 text-slate-600 dark:bg-[#0A3D7A]/30 dark:text-[#94A3B8]';
        $dims = null;
        if ($booking->object_dimensions) {
            $dims = is_array($booking->object_dimensions)
                ? $booking->object_dimensions
                : json_decode($booking->object_dimensions, true);
        }
        $dimsFormatted = $dims
            ? ($dims['length'] ?? '-') . ' × ' . ($dims['width'] ?? '-') . ' × ' . ($dims['height'] ?? '-') . ' cm'
            : null;
    @endphp

    {{-- ═══ BACK LINK ═══ --}}
    <a href="{{ route('admin.order-center') }}" wire:navigate
        class="inline-flex items-center gap-2 mb-6 text-sm text-slate-500 dark:text-[#94A3B8] hover:text-cyan-600 dark:hover:text-[#22D3EE] transition-colors">
        <x-icon name="o-arrow-left" class="w-4 h-4" />
        {{ __('Back to Order Center') }}
    </a>

    {{-- ═══ PAGE HEADER ═══ --}}
    <div class="flex flex-wrap items-center gap-3 mb-8">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#00A8B5] to-[#00426D] flex items-center justify-center text-white shadow-sm">
            <x-icon name="o-cube" class="w-6 h-6" />
        </div>
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black text-slate-800 dark:text-[#F8FAFC]">
                    INV-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                </h1>
                <span class="px-3 py-1 rounded-lg text-[11px] font-bold uppercase tracking-widest {{ $statusClass }}">
                    {{ str_replace('_', ' ', $booking->current_status) }}
                </span>
            </div>
            <p class="text-sm text-slate-500 dark:text-[#94A3B8] mt-0.5">
                {{ $booking->user?->profile?->full_name ?? $booking->user?->name }}
                <span class="opacity-40 mx-1">•</span>
                {{ $booking->service?->name ?? __('Custom Request') }}
                @if ($svcType)
                    <span class="opacity-40 mx-1">•</span>
                    <span class="capitalize">{{ $svcType }}</span>
                @endif
            </p>
        </div>
    </div>

    {{-- ═══ TWO-COLUMN LAYOUT ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- ════ LEFT: TAB WORKSPACE + BELOW-TABS (8/12) ════ --}}
        <div class="lg:col-span-8 order-2 lg:order-1 space-y-6">

            {{-- ── TAB CARD: Brief · Consultation · Payments ── --}}
            <div class="bg-white dark:bg-[#031026] rounded-2xl ring-1 ring-slate-200/50 dark:ring-white/8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)] overflow-hidden">

                {{-- Tab Bar (Alpine-driven) --}}
                <div class="flex gap-1 p-2 bg-slate-50 dark:bg-[#021022] border-b border-slate-200/70 dark:border-[#0A3D7A]/40 overflow-x-auto" role="tablist" aria-label="{{ __('Order sections') }}">
                    @foreach ([
                        'brief'    => ['label' => __('Project Brief'),  'icon' => 'o-document-text'],
                        'chat'     => ['label' => __('Consultation'),   'icon' => 'o-chat-bubble-left-right'],
                        'payments' => ['label' => __('Payments'),       'icon' => 'o-banknotes'],
                    ] as $tabKey => $tab)
                        <button @click="activeTab = '{{ $tabKey }}'"
                            role="tab"
                            id="tab-{{ $tabKey }}"
                            aria-controls="panel-{{ $tabKey }}"
                            :aria-selected="activeTab === '{{ $tabKey }}'"
                            :class="activeTab === '{{ $tabKey }}'
                                ? 'bg-cyan-500/10 text-cyan-600 dark:bg-[#0A3D7A]/60 dark:text-[#22D3EE] font-semibold border-b-2 border-cyan-500 dark:border-[#22D3EE]'
                                : 'text-slate-500 dark:text-[#94A3B8] hover:bg-slate-100 dark:hover:bg-[#062E5C]/40 font-medium border-b-2 border-transparent'"
                            class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm whitespace-nowrap transition-colors duration-150 cursor-pointer">
                            <x-icon name="{{ $tab['icon'] }}" class="w-4 h-4" />
                            {{ $tab['label'] }}
                        </button>
                    @endforeach
                </div>

                <div class="p-5 sm:p-6">

                    {{-- ════ TAB: BRIEF ════ --}}
                    <div x-show="activeTab === 'brief'" role="tabpanel" id="panel-brief" aria-labelledby="tab-brief">
                        <div class="space-y-6">

                            {{-- Notes / Brief (always shown) --}}
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-[#94A3B8] mb-3">
                                    @if ($svcType === 'printing') {{ __('Notes / Instructions') }}
                                    @elseif ($svcType === 'scanning') {{ __('Scanning Notes') }}
                                    @else {{ __('Project Brief') }}
                                    @endif
                                </p>
                                <div class="rounded-xl bg-slate-50 dark:bg-[#021022]/80 p-4 text-sm leading-relaxed whitespace-pre-line">
                                    {{ $booking->brief_description ?? __('No brief provided.') }}
                                </div>
                            </div>

                            {{-- ── PRINTING ── --}}
                            @if ($svcType === 'printing')

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-[#94A3B8] mb-3">{{ __('Print Specifications') }}</p>
                                    <dl class="divide-y divide-slate-100 dark:divide-[#0A3D7A]/30 text-sm">
                                        <div class="flex justify-between py-2.5">
                                            <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Filament Type') }}</dt>
                                            <dd class="font-semibold">{{ $booking->material_preference ?? '—' }}</dd>
                                        </div>
                                        <div class="flex justify-between py-2.5">
                                            <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Filament Color') }}</dt>
                                            <dd class="font-semibold text-slate-400 dark:text-[#94A3B8]/60 italic text-xs">{{ __('— not collected yet') }}</dd>
                                        </div>
                                        @if ($booking->filament_width)
                                            <div class="flex justify-between py-2.5">
                                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Nozzle Width') }}</dt>
                                                <dd class="font-semibold">{{ $booking->filament_width }}</dd>
                                            </div>
                                        @endif
                                        @if ($dimsFormatted)
                                            <div class="flex justify-between py-2.5">
                                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Dimensions (L × W × H)') }}</dt>
                                                <dd class="font-semibold font-mono">{{ $dimsFormatted }}</dd>
                                            </div>
                                        @endif
                                    </dl>
                                </div>

                                @if ($booking->model_file_path)
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400/70 dark:text-[#94A3B8]/70 mb-2">{{ __('Uploaded 3D Model') }}</p>
                                        <a href="{{ asset('storage/' . $booking->model_file_path) }}" target="_blank"
                                            class="inline-flex items-center gap-3 px-5 py-3 rounded-xl bg-cyan-50 dark:bg-[#0A3D7A]/30 text-cyan-700 dark:text-[#22D3EE] ring-1 ring-cyan-200 dark:ring-[#0A3D7A]/60 text-sm font-semibold hover:bg-cyan-100 dark:hover:bg-[#0A3D7A]/50 transition-colors">
                                            <x-icon name="o-arrow-down-tray" class="w-5 h-5 shrink-0" />
                                            <span>{{ basename($booking->model_file_path) }}</span>
                                            <span class="text-xs opacity-50 font-normal">.stl / .obj</span>
                                        </a>
                                    </div>
                                @endif

                                @if ($booking->reference_photo_path)
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400/70 dark:text-[#94A3B8]/70 mb-2">{{ __('Reference Photo') }}</p>
                                        <a href="{{ asset('storage/' . $booking->reference_photo_path) }}" target="_blank"
                                            class="group inline-block w-36 h-36 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                            <img src="{{ asset('storage/' . $booking->reference_photo_path) }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 motion-reduce:transition-none"
                                                loading="lazy"
                                                alt="{{ __('Reference Photo') }}">
                                        </a>
                                    </div>
                                @endif

                            {{-- ── DESIGN ── --}}
                            @elseif ($svcType === 'design')

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-[#94A3B8] mb-3">{{ __('Design Specifications') }}</p>
                                    <dl class="divide-y divide-slate-100 dark:divide-[#0A3D7A]/30 text-sm">
                                        @if ($booking->filament_width)
                                            <div class="flex justify-between py-2.5">
                                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Desired Filament Width') }}</dt>
                                                <dd class="font-semibold">{{ $booking->filament_width }}</dd>
                                            </div>
                                        @endif
                                        @if ($dimsFormatted)
                                            <div class="flex justify-between py-2.5">
                                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Dimensions (L × W × H)') }}</dt>
                                                <dd class="font-semibold font-mono">{{ $dimsFormatted }}</dd>
                                            </div>
                                        @endif
                                    </dl>
                                </div>

                                @if ($booking->reference_photo_path)
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400/70 dark:text-[#94A3B8]/70 mb-2">{{ __('Reference Photo') }}</p>
                                        <a href="{{ asset('storage/' . $booking->reference_photo_path) }}" target="_blank"
                                            class="group inline-block w-36 h-36 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                            <img src="{{ asset('storage/' . $booking->reference_photo_path) }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 motion-reduce:transition-none"
                                                loading="lazy"
                                                alt="{{ __('Reference Photo') }}">
                                        </a>
                                    </div>
                                @endif

                            {{-- ── SCANNING ── --}}
                            @elseif ($svcType === 'scanning')

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-[#94A3B8] mb-3">{{ __('Scan Specifications') }}</p>
                                    <dl class="divide-y divide-slate-100 dark:divide-[#0A3D7A]/30 text-sm">
                                        <div class="flex justify-between py-2.5">
                                            <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Scanning Purpose') }}</dt>
                                            <dd class="font-semibold">{{ $booking->scan_purpose ?? '—' }}</dd>
                                        </div>
                                        <div class="flex justify-between py-2.5">
                                            <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Scanning Location') }}</dt>
                                            <dd class="font-semibold text-slate-400 dark:text-[#94A3B8]/60 italic text-xs">{{ __('— not collected yet') }}</dd>
                                        </div>
                                        @if ($dimsFormatted)
                                            <div class="flex justify-between py-2.5">
                                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Dimensions (L × W × H)') }}</dt>
                                                <dd class="font-semibold font-mono">{{ $dimsFormatted }}</dd>
                                            </div>
                                        @endif
                                    </dl>
                                </div>

                                @if ($booking->reference_photo_path)
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400/70 dark:text-[#94A3B8]/70 mb-2">{{ __('Reference Image') }}</p>
                                        <a href="{{ asset('storage/' . $booking->reference_photo_path) }}" target="_blank"
                                            class="group inline-block w-36 h-36 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                            <img src="{{ asset('storage/' . $booking->reference_photo_path) }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 motion-reduce:transition-none"
                                                loading="lazy"
                                                alt="{{ __('Reference Image') }}">
                                        </a>
                                    </div>
                                @endif

                            {{-- ── FALLBACK ── --}}
                            @elseif ($booking->reference_photo_path || $booking->model_file_path || $booking->material_preference || $booking->filament_width || $booking->scan_purpose || $booking->object_dimensions)

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-[#94A3B8] mb-3">{{ __('Request Details') }}</p>
                                    <dl class="divide-y divide-slate-100 dark:divide-[#0A3D7A]/30 text-sm">
                                        @if ($booking->material_preference)
                                            <div class="flex justify-between py-2.5">
                                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Material') }}</dt>
                                                <dd class="font-semibold">{{ $booking->material_preference }}</dd>
                                            </div>
                                        @endif
                                        @if ($booking->filament_width)
                                            <div class="flex justify-between py-2.5">
                                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Filament Width') }}</dt>
                                                <dd class="font-semibold">{{ $booking->filament_width }}</dd>
                                            </div>
                                        @endif
                                        @if ($booking->scan_purpose)
                                            <div class="flex justify-between py-2.5">
                                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Scan Purpose') }}</dt>
                                                <dd class="font-semibold">{{ $booking->scan_purpose }}</dd>
                                            </div>
                                        @endif
                                        @if ($dimsFormatted)
                                            <div class="flex justify-between py-2.5">
                                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Object Size') }}</dt>
                                                <dd class="font-semibold font-mono">{{ $dimsFormatted }}</dd>
                                            </div>
                                        @endif
                                    </dl>
                                </div>
                                @if ($booking->reference_photo_path)
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400/70 dark:text-[#94A3B8]/70 mb-2">{{ __('Reference Photo') }}</p>
                                        <a href="{{ asset('storage/' . $booking->reference_photo_path) }}" target="_blank"
                                            class="group inline-block w-36 h-36 rounded-xl overflow-hidden shadow-sm">
                                            <img src="{{ asset('storage/' . $booking->reference_photo_path) }}" class="w-full h-full object-cover" loading="lazy" alt="{{ __('Reference Photo') }}">
                                        </a>
                                    </div>
                                @endif
                                @if ($booking->model_file_path)
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400/70 dark:text-[#94A3B8]/70 mb-2">{{ __('3D Model File') }}</p>
                                        <a href="{{ asset('storage/' . $booking->model_file_path) }}" target="_blank"
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-cyan-50 dark:bg-[#0A3D7A]/30 text-cyan-600 dark:text-[#22D3EE] ring-1 ring-cyan-200/60 dark:ring-[#0A3D7A]/40 text-sm font-semibold hover:bg-cyan-100 transition-colors">
                                            <x-icon name="o-arrow-down-tray" class="w-4 h-4" />
                                            {{ __('Download Model File') }}
                                        </a>
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>

                    {{-- ════ TAB: CONSULTATION ════ --}}
                    <div x-show="activeTab === 'chat'" role="tabpanel" id="panel-chat" aria-labelledby="tab-chat" class="flex flex-col h-[60vh]">

                        {{-- New-message toast --}}
                        <div x-data="{ show: false }"
                            x-on:chat-new-message.window="show = true; setTimeout(() => show = false, 3000)"
                            x-show="show"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="mb-3 flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-cyan-600 text-white text-sm font-semibold shadow-lg shadow-cyan-500/30"
                            style="display:none">
                            <x-icon name="o-chat-bubble-left-ellipsis" class="w-4 h-4" />
                            {{ __('New message from customer!') }}
                        </div>

                        <div class="flex items-center gap-2 mb-3 pb-3 border-b border-slate-100 dark:border-[#0A3D7A]/40">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse motion-reduce:animate-none"></span>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('Live consultation') }}</span>
                            <span class="ml-auto text-xs text-slate-400 dark:text-slate-500">{{ $booking->messages->count() }} {{ __('messages') }}</span>
                        </div>

                        <div x-data x-init="$el.scrollTop = $el.scrollHeight"
                            x-on:chat-new-message.window="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
                            class="flex-1 overflow-y-auto space-y-3 pr-1" id="chat-thread"
                            aria-live="polite" aria-label="{{ __('Consultation messages') }}">
                            @forelse ($booking->messages->sortBy('created_at') as $message)
                                @php
                                    $isAdmin = $message->sender_id === auth()->id();
                                    $initials = collect(explode(' ', $message->sender?->name ?? 'U'))
                                        ->map(fn($w) => strtoupper($w[0] ?? ''))
                                        ->take(2)->join('');
                                @endphp
                                <div wire:key="msg-{{ $message->id }}"
                                    class="flex items-end gap-2 {{ $isAdmin ? 'justify-end' : 'justify-start' }}">
                                    @if (!$isAdmin)
                                        <div class="shrink-0 h-7 w-7 rounded-full bg-slate-200 dark:bg-[#0A3D7A]/60 flex items-center justify-center text-[10px] font-bold text-slate-500 dark:text-slate-300">{{ $initials }}</div>
                                    @endif
                                    <div class="max-w-[72%] {{ $isAdmin ? 'rounded-2xl rounded-br-sm bg-gradient-to-br from-cyan-500 to-cyan-600 text-white shadow-md shadow-cyan-500/20' : 'rounded-2xl rounded-bl-sm bg-white dark:bg-[#0A3D7A]/50 ring-1 ring-slate-200/60 dark:ring-[#0A3D7A]/60 text-slate-700 dark:text-[#F8FAFC] shadow-sm' }} px-4 py-2.5">
                                        <p class="text-[10px] font-bold uppercase tracking-wider mb-0.5 {{ $isAdmin ? 'opacity-70' : 'text-cyan-600 dark:text-[#22D3EE]' }}">{{ $message->sender?->name }}</p>
                                        <p class="text-sm whitespace-pre-line break-words">{{ $message->body }}</p>
                                        <p class="text-[10px] opacity-60 mt-1 text-right">{{ $message->created_at?->format('d M, H:i') }}</p>
                                    </div>
                                    @if ($isAdmin)
                                        <div class="shrink-0 h-7 w-7 rounded-full bg-cyan-500 dark:bg-[#22D3EE] flex items-center justify-center text-[10px] font-bold text-white dark:text-[#031026]">{{ $initials }}</div>
                                    @endif
                                </div>
                            @empty
                                <div class="h-full flex flex-col items-center justify-center text-slate-400 dark:text-[#94A3B8] py-16">
                                    <x-icon name="o-chat-bubble-left-right" class="w-12 h-12 mb-3 opacity-30" />
                                    <p class="text-sm font-medium">{{ __('No messages yet.') }}</p>
                                    <p class="text-xs opacity-70 mt-1">{{ __('Start the consultation below.') }}</p>
                                </div>
                            @endforelse
                        </div>

                        <form wire:submit="sendMessage" class="mt-4 border-t border-slate-200 dark:border-[#0A3D7A]/40 pt-4 space-y-3">
                            <x-textarea wire:model="newMessage" rows="2" placeholder="{{ __('Type a message…') }}" class="w-full" />
                            <div class="flex justify-end">
                                <x-button type="submit" icon="o-paper-airplane" class="btn-primary" spinner="sendMessage">{{ __('Send') }}</x-button>
                            </div>
                        </form>
                    </div>

                    {{-- ════ TAB: PAYMENTS ════ --}}
                    <div x-show="activeTab === 'payments'" role="tabpanel" id="panel-payments" aria-labelledby="tab-payments">

                        {{-- Flat stat row --}}
                        <div class="grid grid-cols-3 divide-x divide-slate-200 dark:divide-[#0A3D7A]/40 mb-6 pb-5 border-b border-slate-200 dark:border-[#0A3D7A]/30">
                            <div class="pr-4 min-w-0 overflow-hidden">
                                <p class="text-[11px] uppercase tracking-wider text-slate-400 dark:text-[#94A3B8]">{{ __('Total Agreed') }}</p>
                                <p class="text-lg font-black mt-0.5 truncate">Rp {{ number_format((int) $booking->agreed_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="px-4 min-w-0 overflow-hidden">
                                <p class="text-[11px] uppercase tracking-wider text-slate-400 dark:text-[#94A3B8]">{{ __('Paid') }}</p>
                                <p class="text-lg font-black mt-0.5 text-emerald-600 dark:text-emerald-400 truncate">Rp {{ number_format($booking->total_paid, 0, ',', '.') }}</p>
                            </div>
                            <div class="pl-4 min-w-0 overflow-hidden">
                                <p class="text-[11px] uppercase tracking-wider text-slate-400 dark:text-[#94A3B8]">{{ __('Remaining') }}</p>
                                <p class="text-lg font-black mt-0.5 text-cyan-600 dark:text-[#22D3EE] truncate">Rp {{ number_format($booking->remaining_balance, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <form wire:submit="addTermin" class="flex flex-wrap items-end gap-3 mb-6 rounded-xl bg-slate-50/60 dark:bg-[#021022]/60 p-4">
                            <x-input label="{{ __('Termin Name') }}" wire:model="terminName" placeholder="{{ __('DP / Termin 2 / Pelunasan') }}" class="flex-1 min-w-[180px]" />
                            <x-input label="{{ __('Amount') }}" wire:model="terminAmount" type="number" prefix="Rp" class="flex-1 min-w-[160px]" />
                            <x-button type="submit" label="{{ __('Add Termin') }}" icon="o-plus" class="btn-primary" spinner="addTermin" />
                        </form>

                        @php
                            $payColors = [
                                'pending'               => 'bg-slate-100 text-slate-600 dark:bg-slate-500/10 dark:text-slate-300',
                                'awaiting_verification' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                'paid'                  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                'rejected'              => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
                            ];
                        @endphp
                        <div>
                            @forelse ($booking->payments as $payment)
                                <div wire:key="pay-{{ $payment->id }}"
                                    class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 dark:border-[#0A3D7A]/30 py-3 last:border-b-0">
                                    <div class="flex items-center gap-4">
                                        @if ($payment->payment_proof)
                                            <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank"
                                                class="block w-12 h-12 rounded-lg overflow-hidden ring-1 ring-slate-200 dark:ring-[#0A3D7A]/40">
                                                <img src="{{ asset('storage/' . $payment->payment_proof) }}" class="w-full h-full object-cover" loading="lazy" alt="{{ __('Payment proof') }}">
                                            </a>
                                        @else
                                            <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-slate-100 dark:bg-[#0A3D7A]/30 text-slate-400">
                                                <x-icon name="o-receipt-percent" class="w-5 h-5" />
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-sm">{{ $payment->termin_name }}</p>
                                            <p class="text-sm text-slate-500 dark:text-[#94A3B8]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider {{ $payColors[$payment->status] ?? '' }}">
                                            {{ str_replace('_', ' ', $payment->status) }}
                                        </span>
                                        @if ($payment->status !== 'paid')
                                            <x-button icon="o-arrow-up-tray" title="{{ __('Upload Proof') }}" aria-label="{{ __('Upload proof for :name', ['name' => $payment->termin_name]) }}" wire:click="openProofModal({{ $payment->id }})" class="btn-sm btn-ghost min-h-[44px] min-w-[44px]" />
                                        @endif
                                        @if ($payment->status === 'awaiting_verification')
                                            <x-button icon="o-check" title="{{ __('Verify') }}" aria-label="{{ __('Verify payment') }}" wire:click="verifyPayment({{ $payment->id }})" class="btn-sm bg-emerald-500 text-white border-0 min-h-[44px] min-w-[44px]" spinner="verifyPayment" />
                                            <x-button icon="o-x-mark" title="{{ __('Reject') }}" aria-label="{{ __('Reject payment') }}" wire:click="rejectPayment({{ $payment->id }})" class="btn-sm bg-rose-500 text-white border-0 min-h-[44px] min-w-[44px]" spinner="rejectPayment" />
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-10 text-center text-slate-400 dark:text-[#94A3B8]">
                                    <x-icon name="o-banknotes" class="w-12 h-12 mx-auto mb-3 opacity-40" />
                                    <p class="text-sm">{{ __('No payment termins configured yet.') }}</p>
                                </div>
                            @endforelse
                        </div>

                    </div>

                </div>
            </div>

            {{-- ── BELOW-TABS: Log Progress (left) | Timeline + Material History (right) ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Log Progress form --}}
                <div class="bg-white dark:bg-[#031026] rounded-2xl ring-1 ring-slate-200/50 dark:ring-white/8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)] p-5 h-fit">
                    <h3 class="text-sm font-medium text-slate-600 dark:text-[#94A3B8] mb-4 flex items-center gap-2">
                        <x-icon name="o-rocket-launch" class="w-4 h-4" />
                        {{ __('Log Progress Update') }}
                    </h3>
                    <x-form wire:submit="addProgress" class="space-y-4">
                        <x-select label="{{ __('Status') }}" wire:model="progressStatus"
                            :options="[
                                ['id' => 'slicing',   'name' => 'Slicing'],
                                ['id' => 'printing',  'name' => 'Printing'],
                                ['id' => 'revising',  'name' => 'Revising'],
                                ['id' => 'finishing', 'name' => 'Finishing'],
                                ['id' => 'completed', 'name' => 'Completed'],
                            ]"
                            option-label="name" option-value="id" required />
                        <div class="form-control w-full">
                            <div class="flex justify-between mb-1">
                                <label for="completion-slider" class="text-xs font-semibold text-slate-500 dark:text-[#94A3B8]">{{ __('Completion') }}</label>
                                <span class="font-bold text-cyan-600 dark:text-[#22D3EE] text-sm" aria-hidden="true">{{ $progressPercentage }}%</span>
                            </div>
                            <input type="range" id="completion-slider" min="0" max="100" wire:model.live="progressPercentage" class="range range-sm range-primary dark:range-info" step="5" aria-label="{{ __('Completion percentage: :val%', ['val' => $progressPercentage]) }}" />
                        </div>
                        <x-textarea label="{{ __('Notes') }}" wire:model="progressNotes" rows="3" required />
                        <x-file wire:model="progressFiles" label="{{ __('Attachments') }}" multiple accept="image/*" hint="{{ __('Optional photos. Max 20MB each.') }}" />
                        <x-button type="submit" label="{{ __('Post Update & Notify') }}" icon="o-paper-airplane" class="btn-primary w-full" spinner="addProgress" />
                    </x-form>
                </div>

                {{-- Timeline + Material History --}}
                <div class="space-y-5">

                    <div class="bg-white dark:bg-[#031026] rounded-2xl ring-1 ring-slate-200/50 dark:ring-white/8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)] p-5">
                        <h3 class="text-sm font-medium text-slate-600 dark:text-[#94A3B8] mb-4 flex items-center gap-2">
                            <x-icon name="o-clock" class="w-4 h-4" />
                            {{ __('Production Timeline') }}
                        </h3>
                        @forelse ($booking->progressUpdates->sortByDesc('created_at') as $update)
                            <div wire:key="prog-{{ $update->id }}" class="relative pl-6 pb-4 border-l-2 border-cyan-500/30 dark:border-[#22D3EE]/30 last:pb-0">
                                <span class="absolute -left-[7px] top-1 w-3 h-3 rounded-full bg-cyan-500 dark:bg-[#22D3EE]"></span>
                                <div class="flex items-center justify-between gap-2">
                                    <p class="font-bold capitalize text-sm">{{ str_replace('_', ' ', $update->status_label) }}</p>
                                    <span class="text-xs font-mono text-cyan-600 dark:text-[#22D3EE]">{{ $update->percentage }}%</span>
                                </div>
                                <p class="text-sm text-slate-500 dark:text-[#94A3B8] mt-0.5">{{ $update->notes }}</p>
                                @if ($update->attachments->isNotEmpty())
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach ($update->attachments as $att)
                                            <a href="{{ asset('storage/' . $att->file_url) }}" target="_blank"
                                                class="block w-14 h-14 rounded-lg overflow-hidden ring-1 ring-slate-200 dark:ring-[#0A3D7A]/40">
                                                <img src="{{ asset('storage/' . $att->file_url) }}" class="w-full h-full object-cover" loading="lazy" alt="{{ __('Progress attachment') }}">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">{{ $update->created_at?->format('d M Y, H:i') }}</p>
                            </div>
                        @empty
                            <div class="py-8 text-center text-slate-400 dark:text-[#94A3B8]">
                                <x-icon name="o-rocket-launch" class="w-10 h-10 mx-auto mb-3 opacity-40" />
                                <p class="text-sm">{{ __('No progress logged yet.') }}</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Material Usage History — printing only --}}
                    @if ($svcType === 'printing' && $booking->materialMovements->count() > 0)
                        <div class="bg-white dark:bg-[#031026] rounded-2xl ring-1 ring-slate-200/50 dark:ring-white/8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)] p-5">
                            <h3 class="text-sm font-medium text-slate-600 dark:text-[#94A3B8] mb-3 flex items-center gap-2">
                                <x-icon name="o-beaker" class="w-4 h-4" />
                                {{ __('Material Usage History') }}
                            </h3>
                            <div>
                                @foreach ($booking->materialMovements as $mov)
                                    <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-[#0A3D7A]/20 last:border-b-0 text-sm">
                                        <span class="font-semibold text-slate-700 dark:text-[#F8FAFC]">
                                            {{ $mov->material->brand->name }}
                                            <span class="text-slate-400 dark:text-[#94A3B8] ml-1 font-normal">{{ $mov->material->color->name }}</span>
                                        </span>
                                        <span class="font-mono font-black text-amber-600 dark:text-[#FFC72C] bg-amber-50 dark:bg-[#FFC72C]/10 px-2 py-0.5 rounded text-xs">
                                            −{{ $mov->quantity }}{{ $mov->material->unit }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>

        {{-- ════ RIGHT: STICKY SIDEBAR (4/12) ════ --}}
        <div class="lg:col-span-4 order-1 lg:order-2">
            <div class="lg:sticky lg:top-6 space-y-5">

                {{-- ─── CARD 0: ADMIN WORKFLOW GUIDE ─── --}}
                <div class="bg-white dark:bg-[#062E5C]/20 rounded-2xl border-t-2 border-t-amber-400/60 dark:border-t-amber-500/40 ring-1 ring-amber-200/50 dark:ring-amber-500/15 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)] p-5">
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-[#F8FAFC] mb-4 flex items-center gap-2">
                        <x-icon name="o-clipboard-document-check" class="w-4 h-4 text-amber-500 dark:text-amber-400" />
                        {{ __('Admin SOP Guide') }}
                    </h3>
                    @switch($svcType)
                        @case('printing')
                            <ol class="space-y-2.5 text-sm text-slate-600 dark:text-[#94A3B8] list-none">
                                @foreach ([
                                    __('Review 3D model file and notes in Brief tab'),
                                    __('Slice in software — record weight (g) & print time (min)'),
                                    __('Enter slicer data and confirm agreed price'),
                                    __('Deduct material from stock using the card below'),
                                    __('Post progress updates as production proceeds'),
                                    __('Mark Completed and notify customer'),
                                ] as $sopIndex => $sopStep)
                                    <li class="flex gap-3 items-start">
                                        <span class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400 text-[10px] font-black flex items-center justify-center">{{ $sopIndex + 1 }}</span>
                                        <span>{{ $sopStep }}</span>
                                    </li>
                                @endforeach
                            </ol>
                            @break
                        @case('design')
                            <ol class="space-y-2.5 text-sm text-slate-600 dark:text-[#94A3B8] list-none">
                                @foreach ([
                                    __('Review design brief and reference photo in Brief tab'),
                                    __('Confirm filament width and object dimensions'),
                                    __('Estimate hours, assess complexity, and set price'),
                                    __('Begin 3D modeling — update status to "3D Modeling"'),
                                    __('Post progress updates at key milestones'),
                                    __('Deliver design file and mark Completed'),
                                ] as $sopIndex => $sopStep)
                                    <li class="flex gap-3 items-start">
                                        <span class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400 text-[10px] font-black flex items-center justify-center">{{ $sopIndex + 1 }}</span>
                                        <span>{{ $sopStep }}</span>
                                    </li>
                                @endforeach
                            </ol>
                            @break
                        @case('scanning')
                            <ol class="space-y-2.5 text-sm text-slate-600 dark:text-[#94A3B8] list-none">
                                @foreach ([
                                    __('Review reference image and scanning purpose in Brief tab'),
                                    __('Confirm object dimensions and any transport needs'),
                                    __('Set price, include transport fee if applicable'),
                                    __('Perform 3D scan and update status accordingly'),
                                    __('Post-process scan data and validate output'),
                                    __('Deliver scan files and mark Completed'),
                                ] as $sopIndex => $sopStep)
                                    <li class="flex gap-3 items-start">
                                        <span class="shrink-0 mt-0.5 w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400 text-[10px] font-black flex items-center justify-center">{{ $sopIndex + 1 }}</span>
                                        <span>{{ $sopStep }}</span>
                                    </li>
                                @endforeach
                            </ol>
                            @break
                        @default
                            <p class="text-sm text-slate-400 dark:text-[#94A3B8]">{{ __('Assign a service type to see the SOP guide.') }}</p>
                    @endswitch
                </div>

                {{-- ─── CARD 1: ORDER CONTROLS ─── --}}
                <div class="bg-white dark:bg-[#0A3D7A]/40 rounded-2xl border-t-2 border-t-cyan-500/50 dark:border-t-[#22D3EE]/40 ring-1 ring-slate-200/50 dark:ring-[#22D3EE]/15 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)] p-5">
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-[#F8FAFC] mb-4 flex items-center gap-2">
                        <x-icon name="o-pencil-square" class="w-4 h-4 text-cyan-500 dark:text-[#22D3EE]" />
                        {{ __('Order Controls') }}
                    </h3>
                    <x-form wire:submit="saveOrderData" class="space-y-4">
                        <x-select label="{{ __('Service Type') }}" wire:model="edit_service_id"
                            :options="$availableServices" option-label="name" option-value="id" required
                            class="rounded-lg text-sm" />

                        {{-- Status — optgroups adapt per service type --}}
                        <div class="form-control w-full">
                            <label class="label pt-0 pb-1">
                                <span class="label-text text-xs font-semibold text-slate-600 dark:text-[#F8FAFC]">{{ __('Status') }} <span class="text-error">*</span></span>
                            </label>
                            <select wire:model="edit_status"
                                class="select select-bordered select-sm rounded-lg w-full font-medium bg-white dark:bg-[#031026]/50 border-slate-300 dark:border-white/10 text-slate-700 dark:text-[#F8FAFC]"
                                required>
                                @if ($svcType === 'printing')
                                    <optgroup label="— {{ __('Pre-Production') }} —" class="bg-base-100 dark:bg-[#062E5C]">
                                        <option value="pending">{{ __('Pending') }}</option>
                                        <option value="negotiating">{{ __('Negotiating') }}</option>
                                    </optgroup>
                                    <optgroup label="— {{ __('Production') }} —" class="bg-base-100 dark:bg-[#062E5C]">
                                        <option value="in_progress">{{ __('In Progress') }}</option>
                                        <option value="slicing">{{ __('Slicing') }}</option>
                                        <option value="printing">{{ __('Printing') }}</option>
                                        <option value="revising">{{ __('Revising') }}</option>
                                        <option value="finishing">{{ __('Finishing') }}</option>
                                    </optgroup>
                                    <optgroup label="— {{ __('Finalization') }} —" class="bg-base-100 dark:bg-[#062E5C]">
                                        <option value="completed">{{ __('Completed') }}</option>
                                        <option value="cancelled">{{ __('Cancelled') }}</option>
                                    </optgroup>
                                @elseif ($svcType === 'design')
                                    <optgroup label="— {{ __('Pre-Production') }} —" class="bg-base-100 dark:bg-[#062E5C]">
                                        <option value="pending">{{ __('Pending') }}</option>
                                        <option value="negotiating">{{ __('Negotiating') }}</option>
                                    </optgroup>
                                    <optgroup label="— {{ __('Design Phase') }} —" class="bg-base-100 dark:bg-[#062E5C]">
                                        <option value="in_progress">{{ __('3D Modeling') }}</option>
                                        <option value="revising">{{ __('Revising') }}</option>
                                        <option value="finishing">{{ __('Finishing') }}</option>
                                    </optgroup>
                                    <optgroup label="— {{ __('Finalization') }} —" class="bg-base-100 dark:bg-[#062E5C]">
                                        <option value="completed">{{ __('Completed') }}</option>
                                        <option value="cancelled">{{ __('Cancelled') }}</option>
                                    </optgroup>
                                @elseif ($svcType === 'scanning')
                                    <optgroup label="— {{ __('Pre-Production') }} —" class="bg-base-100 dark:bg-[#062E5C]">
                                        <option value="pending">{{ __('Pending') }}</option>
                                        <option value="negotiating">{{ __('Negotiating') }}</option>
                                    </optgroup>
                                    <optgroup label="— {{ __('Scanning Phase') }} —" class="bg-base-100 dark:bg-[#062E5C]">
                                        <option value="in_progress">{{ __('Scanning in Progress') }}</option>
                                        <option value="finishing">{{ __('Finishing') }}</option>
                                    </optgroup>
                                    <optgroup label="— {{ __('Finalization') }} —" class="bg-base-100 dark:bg-[#062E5C]">
                                        <option value="completed">{{ __('Completed') }}</option>
                                        <option value="cancelled">{{ __('Cancelled') }}</option>
                                    </optgroup>
                                @else
                                    <optgroup label="— {{ __('Pre-Production') }} —" class="bg-base-100 dark:bg-[#062E5C]">
                                        <option value="pending">{{ __('Pending') }}</option>
                                        <option value="negotiating">{{ __('Negotiating') }}</option>
                                    </optgroup>
                                    <optgroup label="— {{ __('Production') }} —" class="bg-base-100 dark:bg-[#062E5C]">
                                        <option value="in_progress">{{ __('In Progress') }}</option>
                                        <option value="slicing">{{ __('Slicing') }}</option>
                                        <option value="printing">{{ __('Printing') }}</option>
                                        <option value="revising">{{ __('Revising') }}</option>
                                        <option value="finishing">{{ __('Finishing') }}</option>
                                    </optgroup>
                                    <optgroup label="— {{ __('Finalization') }} —" class="bg-base-100 dark:bg-[#062E5C]">
                                        <option value="completed">{{ __('Completed') }}</option>
                                        <option value="cancelled">{{ __('Cancelled') }}</option>
                                    </optgroup>
                                @endif
                            </select>
                        </div>

                        <x-input label="{{ __('Force Override Price (Rp)') }}" wire:model="edit_final_price"
                            type="number" prefix="Rp"
                            hint="{{ __('Leave blank to keep existing price.') }}"
                            class="rounded-lg text-sm" />

                        <x-button label="{{ __('Save Order') }}" type="submit" icon="o-check"
                            class="btn-primary btn-sm rounded-lg w-full dark:bg-[#00A8B5] dark:hover:bg-[#00909B] text-white border-none"
                            spinner="saveOrderData" />
                    </x-form>
                </div>

                {{-- ─── CARD 2: SLICER & PRICING (service-type-aware inputs) ─── --}}
                <div class="bg-white dark:bg-[#062E5C]/30 rounded-2xl ring-1 ring-slate-200/50 dark:ring-white/8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)] p-5">
                    <h3 class="text-sm font-medium text-slate-600 dark:text-[#94A3B8] mb-4 flex items-center gap-2">
                        <x-icon name="o-calculator" class="w-4 h-4" />
                        {{ __('Slicer & Pricing') }}
                    </h3>
                    <x-form wire:submit="saveCalculation" class="space-y-4">

                        @if ($svcType === 'printing')
                            @if (!$booking->product_reference_id)
                                <div class="grid grid-cols-2 gap-3">
                                    <x-input label="{{ __('Weight (g)') }}" wire:model="slicer_weight_grams" type="number" class="rounded-lg text-sm" />
                                    <x-input label="{{ __('Time (min)') }}" wire:model="slicer_print_time_minutes" type="number" class="rounded-lg text-sm" />
                                </div>
                            @endif

                        @elseif ($svcType === 'design')
                            <div class="grid grid-cols-2 gap-3">
                                <div class="form-control">
                                    <label class="label pt-0 pb-1"><span class="label-text text-xs font-semibold text-slate-400 dark:text-[#94A3B8]/70">{{ __('Est. Hours') }}</span></label>
                                    <input type="number" disabled placeholder="—"
                                        class="input input-bordered input-sm rounded-lg w-full opacity-50 cursor-not-allowed bg-slate-50 dark:bg-[#031026]/50" />
                                </div>
                                <div class="form-control">
                                    <label class="label pt-0 pb-1"><span class="label-text text-xs font-semibold text-slate-400 dark:text-[#94A3B8]/70">{{ __('Complexity (1–5)') }}</span></label>
                                    <input type="number" min="1" max="5" disabled placeholder="—"
                                        class="input input-bordered input-sm rounded-lg w-full opacity-50 cursor-not-allowed bg-slate-50 dark:bg-[#031026]/50" />
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 dark:text-[#94A3B8]/60 italic -mt-2">{{ __('Fields coming soon — not yet in database.') }}</p>

                        @elseif ($svcType === 'scanning')
                            <div class="form-control">
                                <label class="label pt-0 pb-1"><span class="label-text text-xs font-semibold text-slate-400 dark:text-[#94A3B8]/70">{{ __('Transport Fee (Rp)') }}</span></label>
                                <input type="number" disabled placeholder="—"
                                    class="input input-bordered input-sm rounded-lg w-full opacity-50 cursor-not-allowed bg-slate-50 dark:bg-[#031026]/50" />
                            </div>
                            <p class="text-[11px] text-slate-400 dark:text-[#94A3B8]/60 italic -mt-2">{{ __('Field coming soon — not yet in database.') }}</p>

                        @endif

                        <x-input label="{{ __('Agreed / Final Price (Rp)') }}" wire:model="final_price"
                            type="number" prefix="Rp" required
                            class="rounded-lg text-sm font-bold text-cyan-600 dark:text-[#22D3EE]" />

                        <x-button label="{{ __('Set Price') }}" type="submit" icon="o-currency-dollar"
                            class="btn-primary btn-sm rounded-lg w-full dark:bg-[#0A3D7A] dark:hover:bg-[#062E5C] dark:border dark:border-[#22D3EE]/30 dark:text-[#22D3EE]"
                            spinner="saveCalculation" />
                    </x-form>
                </div>

                {{-- ─── CARD 3: MATERIAL USAGE (printing ONLY) ─── --}}
                @if ($svcType === 'printing')
                    <div class="bg-white dark:bg-[#062E5C]/30 rounded-2xl ring-1 ring-slate-200/50 dark:ring-white/8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)] p-5">
                        <h3 class="text-sm font-medium text-slate-600 dark:text-[#94A3B8] mb-4 flex items-center gap-2">
                            <x-icon name="o-beaker" class="w-4 h-4" />
                            {{ __('Material Usage') }}
                        </h3>
                        <x-form wire:submit="deductMaterial" class="space-y-3">
                            <x-select label="{{ __('Material') }}" wire:model="selectedMaterialId"
                                :options="$availableMaterials" option-label="display_name" option-value="id" searchable
                                required class="rounded-lg text-sm" />
                            <x-input label="{{ __('Qty') }}" wire:model="deductQuantity" type="number" required class="rounded-lg text-sm" />
                            <x-button label="{{ __('Deduct Stock') }}" type="submit" icon="o-minus-circle"
                                class="btn-error btn-sm rounded-lg w-full text-white border-none"
                                spinner="deductMaterial" />
                        </x-form>
                    </div>
                @endif

                {{-- ─── CARD 4: ORDER SUMMARY (read-only) ─── --}}
                <div class="bg-slate-50/50 dark:bg-white/5 rounded-2xl ring-1 ring-slate-100 dark:ring-white/5 p-5">
                    <h3 class="text-xs uppercase tracking-widest text-slate-400/80 dark:text-[#94A3B8]/70 mb-3 flex items-center gap-2">
                        <x-icon name="o-information-circle" class="w-3.5 h-3.5" />
                        {{ __('Order Summary') }}
                    </h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Customer') }}</dt>
                            <dd class="font-semibold text-right text-slate-800 dark:text-[#F8FAFC] max-w-[60%] truncate">{{ $booking->user?->email }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Service') }}</dt>
                            <dd class="font-semibold text-right text-slate-800 dark:text-[#F8FAFC] max-w-[60%]">{{ $booking->service?->name ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Type') }}</dt>
                            <dd class="font-semibold capitalize text-slate-800 dark:text-[#F8FAFC]">{{ $booking->service?->service_type ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Weight') }}</dt>
                            <dd class="font-semibold text-slate-800 dark:text-[#F8FAFC]">{{ $booking->slicer_weight_grams ? $booking->slicer_weight_grams . ' g' : '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Print Time') }}</dt>
                            <dd class="font-semibold text-slate-800 dark:text-[#F8FAFC]">{{ $booking->slicer_print_time_minutes ? $booking->slicer_print_time_minutes . ' min' : '-' }}</dd>
                        </div>
                        <div class="pt-2.5 border-t border-slate-200 dark:border-white/10 flex justify-between">
                            <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Agreed Price') }}</dt>
                            <dd class="font-black text-cyan-600 dark:text-[#22D3EE]">Rp {{ number_format((int) $booking->agreed_price, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Created') }}</dt>
                            <dd class="font-semibold text-slate-800 dark:text-[#F8FAFC]">{{ $booking->created_at?->format('d M Y') }}</dd>
                        </div>
                    </dl>
                </div>

            </div>
        </div>
    </div>

    {{-- ═══ UPLOAD PROOF MODAL ═══ --}}
    <x-modal wire:model="proofModalOpen" title="{{ __('Upload Payment Proof') }}" separator>
        <x-form wire:submit="uploadProof">
            <x-file wire:model="proofFile" label="{{ __('Transfer Proof') }}" accept="image/*"
                hint="{{ __('Image only. Max 20MB. You may upload on the customer\'s behalf.') }}" />
            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.proofModalOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Upload') }}" type="submit" class="btn-primary" spinner="uploadProof" />
            </x-slot:actions>
        </x-form>
    </x-modal>

</div>
