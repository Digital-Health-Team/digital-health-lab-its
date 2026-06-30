<div class="text-slate-700 dark:text-[#F8FAFC]">
    {{-- ═══ BACK + HEADER ═══ --}}
    <a href="{{ route('admin.order-center') }}" wire:navigate
        class="inline-flex items-center gap-2 mb-4 text-sm text-slate-500 dark:text-[#94A3B8] hover:text-cyan-600 dark:hover:text-[#22D3EE] transition-colors">
        <x-icon name="o-arrow-left" class="w-4 h-4" />
        {{ __('Back to Order Center') }}
    </a>

    <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-[#F8FAFC]">
                {{ __('Order') }} #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
            </h1>
            <p class="text-slate-500 dark:text-[#94A3B8] mt-1">
                {{ $booking->user?->profile?->full_name ?? $booking->user?->name }}
                <span class="opacity-50">•</span>
                {{ $booking->service?->name ?? __('Custom Request') }}
            </p>
        </div>
        @php
            $statusColors = [
                'pending' => 'bg-slate-100 text-slate-600 dark:bg-slate-500/10 dark:text-slate-300',
                'negotiating' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
            ];
            $statusClass = $statusColors[$booking->current_status] ?? 'bg-cyan-100 text-cyan-700 dark:bg-[#0A3D7A]/50 dark:text-[#22D3EE]';
        @endphp
        <span class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider {{ $statusClass }}">
            {{ str_replace('_', ' ', $booking->current_status) }}
        </span>
    </div>

    {{-- ═══ TABS ═══ --}}
    <div class="bg-white dark:bg-[#031026] border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl overflow-hidden shadow-sm">
        <div class="flex gap-1 p-2 bg-slate-50 dark:bg-[#021022] border-b border-slate-200 dark:border-[#0A3D7A]/40 overflow-x-auto">
            @php
                $tabs = [
                    'brief' => ['label' => __('Brief'), 'icon' => 'o-document-text'],
                    'chat' => ['label' => __('Consultation'), 'icon' => 'o-chat-bubble-left-right'],
                    'payments' => ['label' => __('Payments'), 'icon' => 'o-banknotes'],
                    'progress' => ['label' => __('Progress'), 'icon' => 'o-rocket-launch'],
                ];
            @endphp
            @foreach ($tabs as $key => $tab)
                <button wire:click="$set('activeTab', '{{ $key }}')"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold whitespace-nowrap transition-colors cursor-pointer
                        {{ $activeTab === $key
                            ? 'bg-cyan-500/10 text-cyan-600 dark:bg-[#0A3D7A]/50 dark:text-[#22D3EE]'
                            : 'text-slate-500 dark:text-[#94A3B8] hover:bg-slate-100 dark:hover:bg-[#062E5C]/40' }}">
                    <x-icon name="{{ $tab['icon'] }}" class="w-4 h-4" />
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        <div class="p-5 sm:p-6">
            {{-- ════════════════════ BRIEF ════════════════════ --}}
            @if ($activeTab === 'brief')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-[#94A3B8]">{{ __('Project Brief') }}</h3>
                        <div class="rounded-xl border border-slate-200 dark:border-[#0A3D7A]/40 bg-slate-50 dark:bg-[#021022] p-4 text-sm leading-relaxed whitespace-pre-line">
                            {{ $booking->brief_description }}
                        </div>

                        {{-- Service-type specific fields --}}
                        @if ($booking->reference_photo_path || $booking->model_file_path || $booking->material_preference || $booking->filament_width || $booking->scan_purpose || $booking->object_dimensions)
                            <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-[#94A3B8] mt-2">{{ __('Request Details') }}</h3>
                            <dl class="rounded-xl border border-slate-200 dark:border-[#0A3D7A]/40 divide-y divide-slate-200 dark:divide-[#0A3D7A]/40 text-sm">
                                @if ($booking->material_preference)
                                    <div class="flex justify-between px-4 py-3">
                                        <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Material') }}</dt>
                                        <dd class="font-semibold">{{ $booking->material_preference }}</dd>
                                    </div>
                                @endif
                                @if ($booking->filament_width)
                                    <div class="flex justify-between px-4 py-3">
                                        <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Filament Width') }}</dt>
                                        <dd class="font-semibold">{{ $booking->filament_width }}</dd>
                                    </div>
                                @endif
                                @if ($booking->scan_purpose)
                                    <div class="flex justify-between px-4 py-3">
                                        <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Scan Purpose') }}</dt>
                                        <dd class="font-semibold">{{ $booking->scan_purpose }}</dd>
                                    </div>
                                @endif
                                @if ($booking->object_dimensions)
                                    @php $dims = is_array($booking->object_dimensions) ? $booking->object_dimensions : json_decode($booking->object_dimensions, true); @endphp
                                    <div class="flex justify-between px-4 py-3">
                                        <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Object Size') }}</dt>
                                        <dd class="font-semibold font-mono text-right">
                                            {{ ($dims['length'] ?? '-') }} × {{ ($dims['width'] ?? '-') }} × {{ ($dims['height'] ?? '-') }} cm
                                        </dd>
                                    </div>
                                @endif
                            </dl>

                            {{-- Reference Photo --}}
                            @if ($booking->reference_photo_path)
                                <div class="mt-3">
                                    <p class="text-xs text-slate-400 dark:text-[#94A3B8] mb-2 font-semibold uppercase tracking-widest">{{ __('Reference Photo') }}</p>
                                    <a href="{{ asset('storage/'.$booking->reference_photo_path) }}" target="_blank"
                                        class="block w-32 h-32 rounded-xl overflow-hidden border border-slate-200 dark:border-[#0A3D7A]/40">
                                        <img src="{{ asset('storage/'.$booking->reference_photo_path) }}" class="w-full h-full object-cover" alt="Reference photo">
                                    </a>
                                </div>
                            @endif

                            {{-- Model File --}}
                            @if ($booking->model_file_path)
                                <div class="mt-3">
                                    <p class="text-xs text-slate-400 dark:text-[#94A3B8] mb-2 font-semibold uppercase tracking-widest">{{ __('3D Model File') }}</p>
                                    <a href="{{ asset('storage/'.$booking->model_file_path) }}" target="_blank"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-cyan-50 dark:bg-[#0A3D7A]/30 text-cyan-600 dark:text-[#22D3EE] border border-cyan-200 dark:border-[#0A3D7A]/40 text-sm font-semibold hover:bg-cyan-100 dark:hover:bg-[#0A3D7A]/50 transition-colors">
                                        <x-icon name="o-arrow-down-tray" class="w-4 h-4" />
                                        {{ __('Download Model File') }}
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-[#94A3B8]">{{ __('Details') }}</h3>
                        <dl class="rounded-xl border border-slate-200 dark:border-[#0A3D7A]/40 divide-y divide-slate-200 dark:divide-[#0A3D7A]/40 text-sm">
                            <div class="flex justify-between px-4 py-3">
                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Service') }}</dt>
                                <dd class="font-semibold text-right">{{ $booking->service?->name ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between px-4 py-3">
                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Type') }}</dt>
                                <dd class="font-semibold capitalize">{{ $booking->service?->service_type ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between px-4 py-3">
                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Customer') }}</dt>
                                <dd class="font-semibold text-right">{{ $booking->user?->email }}</dd>
                            </div>
                            <div class="flex justify-between px-4 py-3">
                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Weight') }}</dt>
                                <dd class="font-semibold">{{ $booking->slicer_weight_grams ? $booking->slicer_weight_grams.' g' : '-' }}</dd>
                            </div>
                            <div class="flex justify-between px-4 py-3">
                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Print Time') }}</dt>
                                <dd class="font-semibold">{{ $booking->slicer_print_time_minutes ? $booking->slicer_print_time_minutes.' min' : '-' }}</dd>
                            </div>
                            <div class="flex justify-between px-4 py-3">
                                <dt class="text-slate-500 dark:text-[#94A3B8]">{{ __('Agreed Price') }}</dt>
                                <dd class="font-bold text-cyan-600 dark:text-[#22D3EE]">Rp {{ number_format((int) $booking->agreed_price, 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @endif

            {{-- ════════════════════ CHAT ════════════════════ --}}
            @if ($activeTab === 'chat')
                <div class="flex flex-col h-[60vh]">

                    {{-- New-message toast (Alpine, auto-dismisses after 3s) --}}
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
                        <x-icon name="o-chat-bubble-left-ellipsis" class="w-4 h-4 animate-bounce" />
                        {{ __('New message from customer!') }}
                    </div>

                    {{-- Chat header --}}
                    <div class="flex items-center gap-2 mb-3 pb-3 border-b border-slate-100 dark:border-[#0A3D7A]/40">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('Live consultation') }}</span>
                        <span class="ml-auto text-xs text-slate-400 dark:text-slate-500">{{ $booking->messages->count() }} {{ __('messages') }}</span>
                    </div>

                    {{-- Thread --}}
                    <div x-data
                         x-init="$el.scrollTop = $el.scrollHeight"
                         x-on:chat-new-message.window="$nextTick(() => { $el.scrollTop = $el.scrollHeight })"
                         class="flex-1 overflow-y-auto space-y-3 pr-1 custom-scrollbar"
                         id="chat-thread">
                        @forelse ($booking->messages->sortBy('created_at') as $message)
                            @php
                                $isAdmin = $message->sender_id === auth()->id();
                                $initials = collect(explode(' ', $message->sender?->name ?? 'U'))->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->join('');
                            @endphp
                            <div wire:key="msg-{{ $message->id }}" class="flex items-end gap-2 {{ $isAdmin ? 'justify-end' : 'justify-start' }}">

                                {{-- Avatar (customer, left) --}}
                                @if (!$isAdmin)
                                    <div class="shrink-0 h-7 w-7 rounded-full bg-slate-200 dark:bg-[#0A3D7A]/60 flex items-center justify-center text-[10px] font-bold text-slate-500 dark:text-slate-300">
                                        {{ $initials }}
                                    </div>
                                @endif

                                <div class="max-w-[72%] {{ $isAdmin ? 'rounded-2xl rounded-br-sm' : 'rounded-2xl rounded-bl-sm' }} px-4 py-2.5
                                    {{ $isAdmin
                                        ? 'bg-gradient-to-br from-cyan-500 to-cyan-600 text-white shadow-md shadow-cyan-500/20'
                                        : 'bg-white dark:bg-[#0A3D7A]/50 border border-slate-200 dark:border-[#0A3D7A]/60 text-slate-700 dark:text-[#F8FAFC] shadow-sm' }}">
                                    <p class="text-[10px] font-bold uppercase tracking-wider mb-0.5 {{ $isAdmin ? 'opacity-70' : 'text-cyan-600 dark:text-[#22D3EE]' }}">
                                        {{ $message->sender?->name }}
                                    </p>
                                    <p class="text-sm whitespace-pre-line break-words">{{ $message->body }}</p>
                                    <p class="text-[10px] opacity-60 mt-1 text-right">{{ $message->created_at?->format('d M, H:i') }}</p>
                                </div>

                                {{-- Avatar (admin, right) --}}
                                @if ($isAdmin)
                                    <div class="shrink-0 h-7 w-7 rounded-full bg-cyan-500 dark:bg-[#22D3EE] flex items-center justify-center text-[10px] font-bold text-white dark:text-[#031026]">
                                        {{ $initials }}
                                    </div>
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

                    <form wire:submit="sendMessage" class="mt-4 space-y-2 border-t border-slate-200 dark:border-[#0A3D7A]/40 pt-4">
                        <x-textarea wire:model="newMessage" rows="2" placeholder="{{ __('Type a message…') }}" class="w-full" />
                        <div class="flex justify-end">
                            <x-button type="submit" icon="o-paper-airplane" class="btn-primary" spinner="sendMessage">
                                {{ __('Send') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- ════════════════════ PAYMENTS ════════════════════ --}}
            @if ($activeTab === 'payments')
                {{-- Summary --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="rounded-xl border border-slate-200 dark:border-[#0A3D7A]/40 bg-slate-50 dark:bg-[#021022] p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-400 dark:text-[#94A3B8]">{{ __('Total Agreed') }}</p>
                        <p class="text-xl font-black mt-1">Rp {{ number_format((int) $booking->agreed_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 dark:border-[#0A3D7A]/40 bg-slate-50 dark:bg-[#021022] p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-400 dark:text-[#94A3B8]">{{ __('Total Paid') }}</p>
                        <p class="text-xl font-black mt-1 text-emerald-600 dark:text-emerald-400">Rp {{ number_format($booking->total_paid, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 dark:border-[#0A3D7A]/40 bg-slate-50 dark:bg-[#021022] p-4">
                        <p class="text-xs uppercase tracking-wider text-slate-400 dark:text-[#94A3B8]">{{ __('Remaining') }}</p>
                        <p class="text-xl font-black mt-1 text-cyan-600 dark:text-[#22D3EE]">Rp {{ number_format($booking->remaining_balance, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Add termin --}}
                <form wire:submit="addTermin" class="flex flex-wrap items-end gap-3 mb-6 rounded-xl border border-slate-200 dark:border-[#0A3D7A]/40 p-4">
                    <x-input label="{{ __('Termin Name') }}" wire:model="terminName" placeholder="DP / Termin 2 / Pelunasan" class="flex-1 min-w-[180px]" />
                    <x-input label="{{ __('Amount') }}" wire:model="terminAmount" type="number" prefix="Rp" class="flex-1 min-w-[160px]" />
                    <x-button type="submit" label="{{ __('Add Termin') }}" icon="o-plus" class="btn-primary" spinner="addTermin" />
                </form>

                {{-- Termin list --}}
                <div class="space-y-3">
                    @php
                        $payColors = [
                            'pending' => 'bg-slate-100 text-slate-600 dark:bg-slate-500/10 dark:text-slate-300',
                            'awaiting_verification' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                            'paid' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                            'rejected' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
                        ];
                    @endphp
                    @forelse ($booking->payments as $payment)
                        <div wire:key="pay-{{ $payment->id }}"
                            class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 dark:border-[#0A3D7A]/40 bg-white dark:bg-[#021022] p-4">
                            <div class="flex items-center gap-4">
                                @if ($payment->payment_proof)
                                    <a href="{{ asset('storage/'.$payment->payment_proof) }}" target="_blank"
                                        class="block w-12 h-12 rounded-lg overflow-hidden border border-slate-200 dark:border-[#0A3D7A]/40">
                                        <img src="{{ asset('storage/'.$payment->payment_proof) }}" class="w-full h-full object-cover" alt="proof">
                                    </a>
                                @else
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-slate-100 dark:bg-[#0A3D7A]/30 text-slate-400">
                                        <x-icon name="o-receipt-percent" class="w-5 h-5" />
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold">{{ $payment->termin_name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-[#94A3B8]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider {{ $payColors[$payment->status] ?? '' }}">
                                    {{ str_replace('_', ' ', $payment->status) }}
                                </span>
                                @if ($payment->status !== 'paid')
                                    <x-button icon="o-arrow-up-tray" title="{{ __('Upload Proof') }}" wire:click="openProofModal({{ $payment->id }})"
                                        class="btn-sm btn-ghost" />
                                @endif
                                @if ($payment->status === 'awaiting_verification')
                                    <x-button icon="o-check" title="{{ __('Verify') }}" wire:click="verifyPayment({{ $payment->id }})"
                                        class="btn-sm bg-emerald-500 text-white border-0" spinner="verifyPayment" />
                                    <x-button icon="o-x-mark" title="{{ __('Reject') }}" wire:click="rejectPayment({{ $payment->id }})"
                                        class="btn-sm bg-rose-500 text-white border-0" spinner="rejectPayment" />
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
            @endif

            {{-- ════════════════════ PROGRESS ════════════════════ --}}
            @if ($activeTab === 'progress')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Add progress --}}
                    <form wire:submit="addProgress" class="space-y-4 rounded-xl border border-slate-200 dark:border-[#0A3D7A]/40 p-4 h-fit">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-[#94A3B8]">{{ __('Log New Update') }}</h3>
                        <x-input label="{{ __('Status Label') }}" wire:model="progressStatus" placeholder="slicing / printing / finishing" />
                        <x-input label="{{ __('Percentage') }}" wire:model="progressPercentage" type="number" suffix="%" min="0" max="100" />
                        <x-textarea label="{{ __('Notes') }}" wire:model="progressNotes" rows="3" />
                        <x-file wire:model="progressFiles" label="{{ __('Attachments') }}" multiple accept="image/*"
                            hint="{{ __('Optional photos. Max 20MB each.') }}" />
                        <x-button type="submit" label="{{ __('Post Update & Notify') }}" icon="o-paper-airplane" class="btn-primary w-full" spinner="addProgress" />
                    </form>

                    {{-- Timeline --}}
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-[#94A3B8]">{{ __('Timeline') }}</h3>
                        @forelse ($booking->progressUpdates->sortByDesc('created_at') as $update)
                            <div wire:key="prog-{{ $update->id }}" class="relative pl-6 pb-4 border-l-2 border-cyan-500/30 dark:border-[#22D3EE]/30">
                                <span class="absolute -left-[7px] top-1 w-3 h-3 rounded-full bg-cyan-500 dark:bg-[#22D3EE]"></span>
                                <div class="flex items-center justify-between gap-2">
                                    <p class="font-bold capitalize">{{ str_replace('_', ' ', $update->status_label) }}</p>
                                    <span class="text-xs font-mono text-cyan-600 dark:text-[#22D3EE]">{{ $update->percentage }}%</span>
                                </div>
                                <p class="text-sm text-slate-500 dark:text-[#94A3B8] mt-0.5">{{ $update->notes }}</p>
                                @if ($update->attachments->isNotEmpty())
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach ($update->attachments as $att)
                                            <a href="{{ asset('storage/'.$att->file_url) }}" target="_blank"
                                                class="block w-14 h-14 rounded-lg overflow-hidden border border-slate-200 dark:border-[#0A3D7A]/40">
                                                <img src="{{ asset('storage/'.$att->file_url) }}" class="w-full h-full object-cover" alt="attachment">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">{{ $update->created_at?->format('d M Y, H:i') }}</p>
                            </div>
                        @empty
                            <div class="py-10 text-center text-slate-400 dark:text-[#94A3B8]">
                                <x-icon name="o-rocket-launch" class="w-12 h-12 mx-auto mb-3 opacity-40" />
                                <p class="text-sm">{{ __('No progress logged yet.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif
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
