<div class="space-y-6 pb-10">

    {{-- HEADER --}}
    <div class="border-b border-slate-200 dark:border-slate-800 pb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Incoming Orders') }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Verify material availability before orders enter production.') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- LEFT: Orders awaiting material check --}}
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between gap-4 px-6 py-4 border-b border-slate-200 dark:border-[#0A3D7A]/40">
                    <div>
                        <h2 class="font-bold text-slate-800 dark:text-[#F8FAFC]">{{ __('Awaiting Material Check') }}</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Orders in Review Brief / Check Material stage') }}</p>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search order / customer…') }}"
                        class="w-52 px-3 py-2 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                               text-slate-700 dark:text-slate-200 placeholder-slate-400
                               focus:outline-none focus:ring-2 focus:ring-indigo-500/50" />
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-[#0A3D7A]/40 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                                <th class="py-3 px-6">{{ __('Order') }}</th>
                                <th class="py-3 px-6">{{ __('Customer') }}</th>
                                <th class="py-3 px-6">{{ __('Material Check') }}</th>
                                <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-[#0A3D7A]/30 text-slate-700 dark:text-slate-300">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#062E5C]/60 transition-colors {{ $activeBooking?->id === $booking->id ? 'bg-indigo-50/60 dark:bg-indigo-500/5' : '' }}">
                                    <td class="py-3 px-6">
                                        <div class="font-mono font-bold text-indigo-600 dark:text-cyan-400 text-xs">
                                            INV-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $booking->service->name ?? '-' }}</div>
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="font-semibold text-slate-800 dark:text-[#F8FAFC]">{{ $booking->user->name ?? '-' }}</div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500">{{ $booking->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="py-3 px-6">
                                        @if($booking->isMaterialVerified())
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                                <x-icon name="o-check-badge" class="w-3 h-3" /> {{ __('Verified') }}
                                            </span>
                                        @elseif($booking->material_flagged_at)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20">
                                                <x-icon name="o-exclamation-triangle" class="w-3 h-3" /> {{ __('Unavailable') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                                {{ __('Not Checked') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="flex items-center justify-end gap-2">
                                            <button wire:click="viewBooking({{ $booking->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                                       bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400
                                                       hover:bg-indigo-50 hover:text-indigo-600
                                                       dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400
                                                       border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer">
                                                <x-icon name="o-eye" class="w-3.5 h-3.5" /> {{ __('Review') }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12">
                                        <x-icon name="o-inbox" class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-600" />
                                        <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('No orders awaiting material check.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-3 border-t border-slate-200 dark:border-[#0A3D7A]/40">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>

        {{-- RIGHT: Detail + stock snapshot --}}
        <div class="lg:col-span-2 space-y-4">
            @if($activeBooking)
                <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-[#0A3D7A]/40">
                        <h3 class="font-bold text-sm text-slate-800 dark:text-[#F8FAFC]">
                            {{ __('Order Brief') }} — <span class="font-mono text-indigo-600 dark:text-cyan-400">INV-{{ str_pad($activeBooking->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </h3>
                        <button wire:click="clearBooking" class="p-1.5 rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer">
                            <x-icon name="o-x-mark" class="w-4 h-4" />
                        </button>
                    </div>

                    <div class="p-5 space-y-4 text-sm">
                        {{-- Verification state banner --}}
                        @if($activeBooking->isMaterialVerified())
                            <div class="flex items-start gap-2 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-xs">
                                <x-icon name="o-check-badge" class="w-4 h-4 shrink-0 mt-0.5" />
                                <div>
                                    {{ __('Verified by :name on :date', ['name' => $activeBooking->materialVerifier?->name ?? '-', 'date' => $activeBooking->material_verified_at->format('d M Y, H:i')]) }}
                                </div>
                            </div>
                        @elseif($activeBooking->material_flagged_at)
                            <div class="flex items-start gap-2 p-3 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-700 dark:text-rose-400 text-xs">
                                <x-icon name="o-exclamation-triangle" class="w-4 h-4 shrink-0 mt-0.5" />
                                <div>
                                    <span class="font-bold">{{ __('Flagged unavailable') }}</span> — {{ $activeBooking->material_flag_note }}
                                </div>
                            </div>
                        @endif

                        <div>
                            <div class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-widest mb-1">{{ __('Brief') }}</div>
                            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $activeBooking->brief_description ?: '-' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <div class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-widest mb-1">{{ __('Material Preference') }}</div>
                                <p class="text-slate-700 dark:text-slate-300">{{ $activeBooking->material_preference ?: '-' }}</p>
                            </div>
                            <div>
                                <div class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-widest mb-1">{{ __('Filament Width') }}</div>
                                <p class="text-slate-700 dark:text-slate-300">{{ $activeBooking->filament_width ?: '-' }}</p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        @can('verifyMaterial', $activeBooking)
                            <div class="flex items-center gap-2 pt-2">
                                <button wire:click="verify({{ $activeBooking->id }})" wire:loading.attr="disabled"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                                           bg-emerald-600 text-white hover:bg-emerald-700 transition-colors cursor-pointer">
                                    <x-icon name="o-check-badge" class="w-4 h-4" />
                                    {{ __('Materials Available') }}
                                </button>
                                <button wire:click="$set('flagModal', true)"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold
                                           bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30
                                           hover:bg-rose-100 dark:hover:bg-rose-500/20 transition-colors cursor-pointer">
                                    <x-icon name="o-exclamation-triangle" class="w-4 h-4" />
                                    {{ __('Unavailable') }}
                                </button>
                            </div>
                        @endcan
                    </div>
                </div>

                {{-- Stock snapshot --}}
                <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-[#0A3D7A]/40">
                        <h3 class="font-bold text-sm text-slate-800 dark:text-[#F8FAFC]">{{ __('Current Stock') }}</h3>
                        <input type="text" wire:model.live.debounce.300ms="stockSearch" placeholder="{{ __('Filter by material / brand…') }}"
                            class="mt-2 w-full px-3 py-2 rounded-lg text-xs bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                                   text-slate-700 dark:text-slate-200 placeholder-slate-400
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/50" />
                    </div>
                    <div class="p-4 space-y-2 max-h-80 overflow-y-auto">
                        @forelse($stocks as $stock)
                            <div class="flex justify-between items-center p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50">
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-bold text-slate-700 dark:text-[#F8FAFC] truncate">
                                        {{ $stock->rawMaterial->brand->name ?? '' }} {{ $stock->rawMaterial->name ?? '-' }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500">
                                        {{ $stock->color->name ?? '-' }} · {{ $stock->lab->name ?? '-' }}
                                    </div>
                                </div>
                                <div class="text-right ml-3 shrink-0">
                                    <div class="font-mono font-black text-sm {{ $stock->quantity <= 100 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">{{ $stock->quantity }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $stock->rawMaterial->unit ?? '' }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-sm text-slate-400 dark:text-slate-500">
                                {{ __('No matching stock entries.') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-[#062E5C]/40 border border-dashed border-slate-300 dark:border-[#0A3D7A]/60 rounded-2xl p-10 text-center">
                    <x-icon name="o-cursor-arrow-rays" class="w-10 h-10 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                    <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('Select an order to review its brief and check stock availability.') }}</p>
                </div>
            @endif
        </div>

    </div>

    {{-- FLAG MODAL --}}
    <x-modal wire:model="flagModal" title="{{ __('Flag Materials Unavailable') }}">
        <div class="space-y-4">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Explain what is missing or insufficient. The lab team will be notified and the order stays in the material check stage.') }}
            </p>
            <x-textarea wire:model="flagNote" label="{{ __('Reason') }}" rows="4" required />
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.flagModal = false" />
            <x-button label="{{ __('Flag Order') }}" class="btn-error" wire:click="flag" spinner="flag" />
        </x-slot:actions>
    </x-modal>

</div>
