<div>
    {{-- Back + Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.trainings') }}"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors mb-4">
            <x-icon name="o-arrow-left" class="w-3.5 h-3.5" /> {{ __('Back to Trainings') }}
        </a>

        <x-header title="{{ $training->title }}" separator>
            <x-slot:subtitle>
                <div class="flex items-center gap-3 flex-wrap mt-1">
                    @if($training->date)
                        <span class="inline-flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                            <x-icon name="o-calendar" class="w-3.5 h-3.5" /> {{ $training->date->format('d M Y, H:i') }}
                        </span>
                    @endif
                    @if($training->location)
                        <span class="inline-flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                            <x-icon name="o-map-pin" class="w-3.5 h-3.5" /> {{ $training->location }}
                        </span>
                    @endif
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                        <x-icon name="o-users" class="w-3.5 h-3.5" /> {{ $training->registrations_count }} {{ __('Registered') }}
                        @if($training->max_participants)
                            / {{ $training->max_participants }} {{ __('max') }}
                        @endif
                    </span>
                    @if($training->is_paid)
                        <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                            Rp {{ number_format($training->price, 0, ',', '.') }}
                        </span>
                    @else
                        <span class="text-xs font-semibold text-slate-400">{{ __('Free') }}</span>
                    @endif
                </div>
            </x-slot:subtitle>
        </x-header>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <x-input placeholder="{{ __('Search by name, email, or phone...') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" />
        <x-select wire:model.live="filterStatus"
            :options="[['id'=>'pending','name'=>__('Pending')],['id'=>'confirmed','name'=>__('Confirmed')],['id'=>'cancelled','name'=>__('Cancelled')]]"
            placeholder="{{ __('All Status') }}" />
    </div>

    {{-- Registrations Table --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                        <th class="py-3 px-6 text-center w-12">#</th>
                        <th class="py-3 px-6">{{ __('Participant') }}</th>
                        <th class="py-3 px-6">{{ __('Phone') }}</th>
                        <th class="py-3 px-6">{{ __('Preferred Session') }}</th>
                        <th class="py-3 px-6">{{ __('Registered At') }}</th>
                        <th class="py-3 px-6">{{ __('Status') }}</th>
                        <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($registrations as $reg)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="py-4 px-6 text-center text-slate-400 dark:text-slate-500 font-mono text-xs">{{ $loop->iteration }}</td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $reg->full_name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $reg->email }}</div>
                                @if($reg->additional_notes)
                                    <div class="text-xs text-slate-400 italic mt-0.5 max-w-xs truncate">{{ $reg->additional_notes }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm text-slate-600 dark:text-slate-400">{{ $reg->phone_number }}</td>
                            <td class="py-4 px-6 text-sm text-slate-500 dark:text-slate-400">
                                {{ $reg->preferred_session ?: '—' }}
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-500 dark:text-slate-400">
                                {{ $reg->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-6">
                                @if($reg->status === 'confirmed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">{{ __('Confirmed') }}</span>
                                @elseif($reg->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">{{ __('Pending') }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">{{ __('Cancelled') }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    @if($reg->status !== 'confirmed')
                                        <button wire:click="confirmUpdateStatus({{ $reg->id }}, 'confirmed')"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:hover:bg-emerald-500/20 dark:border-emerald-500/20 transition-colors cursor-pointer">
                                            <x-icon name="o-check" class="w-3 h-3" /> {{ __('Confirm') }}
                                        </button>
                                    @endif
                                    @if($reg->status !== 'cancelled')
                                        <button wire:click="confirmUpdateStatus({{ $reg->id }}, 'cancelled')"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-slate-100 text-slate-600 hover:bg-rose-100 hover:text-rose-600 hover:border-rose-200 border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-rose-500/20 dark:hover:text-rose-400 dark:hover:border-rose-500/30 dark:border-slate-700 transition-colors cursor-pointer">
                                            <x-icon name="o-x-mark" class="w-3 h-3" /> {{ __('Cancel') }}
                                        </button>
                                    @endif
                                    @if($reg->status === 'cancelled')
                                        <button wire:click="confirmUpdateStatus({{ $reg->id }}, 'pending')"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-amber-50 text-amber-600 hover:bg-amber-100 border border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20 dark:border-amber-500/20 transition-colors cursor-pointer">
                                            <x-icon name="o-arrow-path" class="w-3 h-3" /> {{ __('Restore') }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16">
                                <x-icon name="o-users" class="w-12 h-12 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                                <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('No participants yet.') }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ __('Participants will appear here once they register.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900">{{ $registrations->links() }}</div>
    </div>

    {{-- Status Change Confirmation Modal --}}
    <x-modal wire:model="confirmStatusModalOpen" title="{{ __('Update Registration Status') }}">
        <p class="text-sm text-slate-600 dark:text-slate-400">
            {{ __('Are you sure you want to mark this registration as') }}
            <strong class="capitalize">{{ $pendingStatus }}</strong>?
        </p>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" wire:click="$set('confirmStatusModalOpen', false)" />
            <x-button label="{{ __('Confirm') }}" class="btn-primary" wire:click="applyStatusUpdate" spinner="applyStatusUpdate" />
        </x-slot:actions>
    </x-modal>
</div>
