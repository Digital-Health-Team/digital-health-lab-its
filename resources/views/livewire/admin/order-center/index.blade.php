<div class="space-y-8 animate-[fade-in_0.4s_ease-out]">

    {{-- HELPER UNTUK WARNA STATUS (Adaptif Light/Dark) --}}
    @php
        $getStatusBadge = function ($status) {
            $class = match ($status) {
                'pending' => 'bg-warning text-warning-content dark:bg-[#FCD34D] dark:text-[#031026] border-transparent',
                'negotiating' => 'bg-info text-info-content dark:bg-[#67E8F9] dark:text-[#031026] border-transparent',
                'in_progress', 'slicing' => 'bg-primary text-primary-content dark:bg-[#0A3D7A] dark:text-[#F8FAFC] dark:border dark:border-[#22D3EE]/30',
                'printing' => 'bg-neutral text-neutral-content dark:bg-[#00426D] dark:text-[#F8FAFC] border-transparent',
                'finishing' => 'bg-accent text-accent-content dark:bg-[#00A8B5] dark:text-[#F8FAFC] border-transparent',
                'completed' => 'bg-success text-success-content dark:bg-emerald-500 dark:text-white border-transparent',
                'revising', 'cancelled' => 'bg-error text-error-content dark:bg-red-500 dark:text-white border-transparent',
                default => 'bg-base-300 text-base-content dark:bg-[#475569] dark:text-white border-transparent',
            };
            return "<div class='badge {$class} rounded-md font-bold uppercase text-[9px] tracking-widest px-3 py-2 shadow-sm'>" .
                str_replace('_', ' ', $status) .
                '</div>';
        };
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1
                class="text-3xl lg:text-4xl font-black text-base-content dark:text-[#F8FAFC] tracking-tight font-['Plus_Jakarta_Sans']">
                {{ __('Order Center') }}
            </h1>
            <p class="text-base-content/70 dark:text-[#94A3B8] mt-2 font-medium">
                {{ __('Manage lab print requests, operations, and financial recap.') }}
            </p>
        </div>
        <x-button label="{{ __('Create Manual Order') }}" icon="o-plus"
            class="btn-primary dark:bg-[#00426D] dark:hover:bg-[#0D5A9E] text-white border-none rounded-lg px-6 shadow-sm"
            wire:click="createOrder" />
    </div>

    {{-- FINANCIAL SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Card 1: Revenue Today --}}
        <div
            class="bg-base-100 dark:bg-[#062E5C]/40 dark:backdrop-blur-xl border border-base-200 dark:border-white/10 rounded-2xl p-6 shadow-sm dark:shadow-xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 transform group-hover:scale-110 transition-transform">
                <x-icon name="o-banknotes" class="w-24 h-24 text-primary dark:text-[#22D3EE]" /></div>
            <div class="relative z-10">
                <h3
                    class="text-[11px] font-bold text-base-content/60 dark:text-[#94A3B8] uppercase tracking-widest mb-1">
                    {{ __('Realized Today') }}</h3>
                <div class="text-3xl font-black text-primary dark:text-[#22D3EE] font-mono">Rp
                    {{ number_format($revenueToday, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Card 2: Revenue This Month --}}
        <div
            class="bg-primary text-primary-content dark:bg-gradient-to-br dark:from-[#0A3D7A] dark:to-[#062E5C] border border-primary/20 dark:border-[#22D3EE]/20 rounded-2xl p-6 shadow-sm dark:shadow-[0_0_30px_rgba(34,211,238,0.1)] relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 transform group-hover:scale-110 transition-transform">
                <x-icon name="o-chart-bar" class="w-24 h-24 text-primary-content dark:text-[#22D3EE]" /></div>
            <div class="relative z-10">
                <h3
                    class="text-[11px] font-bold text-primary-content/80 dark:text-white/70 uppercase tracking-widest mb-1">
                    {{ __('Revenue This Month') }}</h3>
                <div class="text-3xl font-black text-primary-content dark:text-[#F8FAFC] font-mono">Rp
                    {{ number_format($revenueThisMonth, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Card 3: Projected Revenue --}}
        <div
            class="bg-base-100 dark:bg-[#062E5C]/40 dark:backdrop-blur-xl border border-base-200 dark:border-white/10 rounded-2xl p-6 shadow-sm dark:shadow-xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-5 transform group-hover:scale-110 transition-transform">
                <x-icon name="o-clock" class="w-24 h-24 text-warning dark:text-[#FFC72C]" /></div>
            <div class="relative z-10">
                <h3
                    class="text-[11px] font-bold text-base-content/60 dark:text-[#94A3B8] uppercase tracking-widest mb-1">
                    {{ __('Projected Income (WIP)') }}</h3>
                <div class="text-3xl font-black text-warning dark:text-[#FCD34D] font-mono">Rp
                    {{ number_format($projectedRevenue, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- ADVANCED FILTERS & SEARCH --}}
    <div
        class="bg-base-100 dark:bg-[#062E5C]/30 dark:backdrop-blur-md border border-base-200 dark:border-white/10 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="label pt-0 pb-1"><span
                        class="label-text text-xs font-semibold text-base-content/70 dark:text-[#94A3B8]">{{ __('Search Invoice / Name') }}</span></label>
                <input type="text" wire:model.live.debounce="search" placeholder="..."
                    class="input input-bordered w-full rounded-lg bg-base-100 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-[#F8FAFC] focus:border-primary dark:focus:border-[#22D3EE]" />
            </div>
            <div class="flex-1 w-full">
                <label class="label pt-0 pb-1"><span
                        class="label-text text-xs font-semibold text-base-content/70 dark:text-[#94A3B8]">{{ __('Start Date') }}</span></label>
                <input type="date" wire:model.live="startDate"
                    class="input input-bordered w-full rounded-lg bg-base-100 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-[#F8FAFC] focus:border-primary dark:focus:border-[#22D3EE]" />
            </div>
            <div class="flex-1 w-full">
                <label class="label pt-0 pb-1"><span
                        class="label-text text-xs font-semibold text-base-content/70 dark:text-[#94A3B8]">{{ __('End Date') }}</span></label>
                <input type="date" wire:model.live="endDate"
                    class="input input-bordered w-full rounded-lg bg-base-100 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-[#F8FAFC] focus:border-primary dark:focus:border-[#22D3EE]" />
            </div>
            <div class="flex-1 w-full">
                <label class="label pt-0 pb-1"><span
                        class="label-text text-xs font-semibold text-base-content/70 dark:text-[#94A3B8]">{{ __('Service') }}</span></label>
                <select wire:model.live="filterService"
                    class="select select-bordered w-full rounded-lg bg-base-100 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-[#F8FAFC] focus:border-primary dark:focus:border-[#22D3EE]">
                    <option value="">{{ __('All') }}</option>
                    @foreach($availableServices as $svc) <option value="{{ $svc->id }}">{{ $svc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 w-full">
                <label class="label pt-0 pb-1"><span
                        class="label-text text-xs font-semibold text-base-content/70 dark:text-[#94A3B8]">{{ __('Status') }}</span></label>
                <select wire:model.live="filterStatus"
                    class="select select-bordered w-full rounded-lg bg-base-100 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-[#F8FAFC] focus:border-primary dark:focus:border-[#22D3EE]">
                    <option value="">{{ __('All') }}</option>
                    <option value="completed">{{ __('Completed') }}</option>
                    <option value="in_progress">{{ __('In Progress') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                </select>
            </div>
            <div>
                <button wire:click="clearFilters"
                    class="btn btn-ghost rounded-lg text-error dark:text-[#22D3EE] hover:bg-base-200 dark:hover:bg-[#22D3EE]/10 px-4"
                    title="{{ __('Clear Filters') }}">
                    <x-icon name="o-x-mark" class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>

    {{-- MAIN TABLE --}}
    <div
        class="bg-base-100 dark:bg-[#062E5C]/40 dark:backdrop-blur-xl border border-base-200 dark:border-white/10 rounded-2xl p-0 overflow-hidden shadow-sm dark:shadow-xl">
        <div class="overflow-x-auto">
            <table class="table w-full text-sm">
                <thead>
                    <tr
                        class="border-b border-base-200 dark:border-white/10 text-base-content/70 dark:text-[#94A3B8] bg-base-200/50 dark:bg-[#031026]/40 uppercase text-[10px] tracking-widest font-bold">
                        <th class="py-4 pl-6">{{ __('Date') }}</th>
                        <th>{{ __('Invoice') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-right">{{ __('Price') }}</th>
                        <th class="text-center pr-6">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="text-base-content dark:text-[#F8FAFC]">
                    @forelse($bookings as $booking)
                        <tr wire:key="book-{{ $booking->id }}"
                            class="border-b border-base-200 dark:border-white/5 hover:bg-base-200/50 dark:hover:bg-white/5 transition-colors group">
                            <td class="pl-6 font-mono text-xs text-primary dark:text-[#A5F3FC]">
                                {{ $booking->created_at->format('d M Y') }}<br><span
                                    class="text-[10px] text-base-content/50 dark:text-[#94A3B8]">{{ $booking->created_at->format('H:i') }}</span>
                            </td>
                            <td class="font-mono font-bold text-primary dark:text-[#22D3EE] cursor-pointer hover:underline"
                                wire:click="manageOrder({{ $booking->id }})">
                                INV-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="font-semibold">{{ $booking->user->name ?? 'User' }}</div>
                                <div class="text-[10px] text-base-content/50 dark:text-[#94A3B8]">
                                    {{ $booking->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                @if ($booking->product_reference_id)
                                    <div
                                        class="badge rounded-md badge-secondary dark:bg-[#36213E] dark:text-white border-transparent text-[10px] font-bold px-3 py-2">
                                        <x-icon name="o-star" class="w-3 h-3 mr-1" /> Custom</div>
                                @else
                                    <div
                                        class="badge rounded-md badge-primary dark:bg-[#0A3D7A] dark:text-white border-transparent text-[10px] font-bold px-3 py-2">
                                        <x-icon name="o-cube" class="w-3 h-3 mr-1" /> Service</div>
                                @endif
                            </td>
                            <td>{!! $getStatusBadge($booking->current_status) !!}</td>
                            <td class="text-right font-mono font-bold">
                                @if ($booking->agreed_price)
                                    <span class="text-success dark:text-emerald-400">Rp
                                        {{ number_format($booking->agreed_price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-warning dark:text-[#FCD34D] text-xs italic">{{ __('Needs Nego') }}</span>
                                @endif
                            </td>
                            <td class="text-center pr-6">
                                <div
                                    class="flex items-center justify-end gap-1 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                    <button wire:click="manageOrder({{ $booking->id }})"
                                        class="btn btn-sm btn-circle btn-ghost text-primary dark:text-[#22D3EE] hover:bg-base-200 dark:hover:bg-[#22D3EE]/20"
                                        title="{{ __('Manage') }}"><x-icon name="o-cog-8-tooth" class="w-4 h-4" /></button>
                                    <button wire:click="editOrder({{ $booking->id }})"
                                        class="btn btn-sm btn-circle btn-ghost text-info dark:text-[#A5F3FC] hover:bg-base-200 dark:hover:bg-[#A5F3FC]/20"
                                        title="{{ __('Edit') }}"><x-icon name="o-pencil-square" class="w-4 h-4" /></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-base-content/50 dark:text-[#94A3B8]">
                                {{ __('No data found for the selected filter.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
                {{-- FOOTER REKAP FILTER --}}
                @if($bookings->count() > 0)
                    <tfoot class="bg-base-200/50 dark:bg-[#031026]/60 border-t border-base-200 dark:border-white/10">
                        <tr>
                            <td colspan="5"
                                class="text-right py-4 font-bold text-base-content/70 dark:text-[#94A3B8] uppercase tracking-widest text-[11px]">
                                {{ __('Total (Current Page)') }}:</td>
                            <td class="text-right font-mono font-black text-lg text-primary dark:text-[#22D3EE]">Rp
                                {{ number_format($totalFilterRevenue, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
        <div class="p-4 border-t border-base-200 dark:border-white/5 bg-base-100 dark:bg-[#031026]/40">
            {{ $bookings->links() }}</div>
    </div>

    {{-- DRAWER: FORM CREATE / EDIT --}}
    <x-drawer wire:model="crudDrawerOpen"
        title="{{ $editingId ? __('Edit Core Order Data') : __('Create Manual Order') }}"
        class="w-11/12 md:w-1/2 lg:w-1/3 bg-base-100 dark:bg-[#031026] text-base-content dark:text-[#F8FAFC]" right
        separator>
        <x-form wire:submit="saveCoreOrder">
            @if (!$editingId)
                <div
                    class="bg-base-200 dark:bg-[#062E5C]/50 p-5 rounded-xl border border-base-300 dark:border-white/10 mb-4">
                    <x-toggle label="{{ __('Create New Customer') }}" wire:model.live="isNewUser" right
                        class="toggle-info text-sm font-bold text-base-content dark:text-[#F8FAFC]" />
                    <div class="mt-5">
                        @if ($isNewUser)
                            <div class="space-y-4 animate-[fade-in_0.3s_ease-out]">
                                <x-input label="{{ __('Full Name') }}" wire:model="newUserName" icon="o-user" required
                                    class="rounded-lg bg-base-100 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-white" />
                                <x-input label="{{ __('Email Address') }}" wire:model="newUserEmail" type="email"
                                    icon="o-envelope" required
                                    class="rounded-lg bg-base-100 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-white" />
                                <x-input label="{{ __('WhatsApp Number') }}" wire:model="newUserPhone"
                                    icon="o-device-phone-mobile" required hint="{{ __('Used for progress notification') }}"
                                    class="rounded-lg bg-base-100 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-white" />
                            </div>
                        @else
                            <div class="animate-[fade-in_0.3s_ease-out]">
                                <x-choices label="{{ __('Select Customer') }}" wire:model="crud_user_id"
                                    :options="$availableUsers" option-label="name" option-value="id" single searchable required
                                    class="rounded-lg bg-base-100 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-white" />
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div
                    class="mb-4 p-5 bg-base-200 dark:bg-[#062E5C]/50 rounded-xl border border-base-300 dark:border-white/10 flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-full bg-base-100 dark:bg-[#031026] flex items-center justify-center border border-base-300 dark:border-white/10">
                        <x-icon name="o-user" class="w-6 h-6 text-primary dark:text-[#22D3EE]" /></div>
                    <div>
                        <div
                            class="text-[10px] text-base-content/50 dark:text-[#94A3B8] font-bold uppercase tracking-widest">
                            {{ __('Customer') }}</div>
                        <div class="font-bold text-lg text-base-content dark:text-white">
                            {{ \App\Models\ServiceBooking::find($editingId)?->user->name ?? '-' }}</div>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <x-select label="{{ __('Service Type') }}" wire:model="crud_service_id" :options="$availableServices"
                    option-label="name" option-value="id" required
                    class="rounded-lg bg-base-100 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-white" />

                <div class="form-control w-full">
                    <label class="label pt-0 pb-1"><span
                            class="label-text font-semibold text-base-content dark:text-[#F8FAFC]">{{ __('Status') }}
                            <span class="text-error dark:text-red-400">*</span></span></label>
                    <select wire:model="crud_status"
                        class="select select-bordered rounded-lg w-full font-medium bg-base-100 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-[#F8FAFC]"
                        required>
                        <option value="" disabled>{{ __('Select Status...') }}</option>
                        <optgroup label="–– {{ __('PRE-PRODUCTION / DEAL') }} ––"
                            class="bg-base-200 text-base-content dark:bg-[#062E5C] dark:text-[#22D3EE]">
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="negotiating">{{ __('Negotiating') }}</option>
                        </optgroup>
                        <optgroup label="–– {{ __('PRODUCTION PHASES') }} ––"
                            class="bg-base-200 text-base-content dark:bg-[#062E5C] dark:text-[#22D3EE]">
                            <option value="in_progress">{{ __('In Progress') }}</option>
                            <option value="slicing">{{ __('Slicing') }}</option>
                            <option value="printing">{{ __('Printing') }}</option>
                            <option value="revising">{{ __('Revising / Troubleshooting') }}</option>
                            <option value="finishing">{{ __('Finishing') }}</option>
                        </optgroup>
                        <optgroup label="–– {{ __('FINALIZATION') }} ––"
                            class="bg-base-200 text-base-content dark:bg-[#062E5C] dark:text-[#22D3EE]">
                            <option value="completed">{{ __('Completed') }}</option>
                            <option value="cancelled">{{ __('Cancelled') }}</option>
                        </optgroup>
                    </select>
                </div>

                @if ($editingId)
                    <x-input label="{{ __('Agreed Price Force Override (Rp)') }}" wire:model="crud_final_price"
                        type="number" prefix="Rp" hint="{{ __('Leave blank or 0 if pending.') }}"
                        class="rounded-lg bg-base-100 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-white" />
                @endif
            </div>

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.crudDrawerOpen = false"
                    class="btn-ghost rounded-lg text-base-content/70 dark:text-[#94A3B8] hover:text-base-content dark:hover:text-white" />
                <x-button label="{{ __('Save Order') }}" type="submit"
                    class="btn-primary rounded-lg dark:bg-[#22D3EE] dark:text-[#031026] dark:hover:bg-[#67E8F9] border-none px-8"
                    spinner="saveCoreOrder" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- MODAL DELETE --}}
    <x-modal wire:model="deleteModalOpen" title="{{ __('Confirm Deletion') }}" separator
        class="bg-base-100 dark:bg-[#031026] text-base-content dark:text-white">
        <div class="py-4 text-base-content/70 dark:text-[#94A3B8]">
            {{ __('Are you sure you want to delete this order? Associated materials will NOT be automatically refunded.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModalOpen = false"
                class="btn-ghost rounded-lg text-base-content dark:text-white" />
            <x-button label="{{ __('Yes, Delete') }}" class="btn-error rounded-lg text-white" wire:click="deleteRecord"
                spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>

    {{-- DRAWER: OPERATIONAL DASHBOARD (TABS) --}}
    <x-drawer wire:model="manageDrawerOpen" right
        class="w-11/12 lg:w-4/5 bg-base-200 dark:bg-[#031026] text-base-content dark:text-white" without-close-button>
        @if ($activeBooking)
            <div
                class="flex justify-between items-center bg-base-100 dark:bg-[#062E5C]/60 dark:backdrop-blur-xl px-8 py-6 border-b border-base-300 dark:border-white/10 shadow-sm dark:shadow-md sticky top-0 z-40">
                <div class="flex items-center gap-5">
                    <div
                        class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary to-info dark:from-[#00A8B5] dark:to-[#00426D] flex items-center justify-center text-white shadow-sm dark:shadow-[0_0_20px_rgba(0,168,181,0.3)]">
                        <x-icon name="{{ $activeBooking->product_reference_id ? 'o-star' : 'o-cube' }}" class="w-7 h-7" />
                    </div>
                    <div>
                        <div class="flex items-center gap-4">
                            <h2
                                class="text-3xl font-black text-base-content dark:text-[#F8FAFC] tracking-tight font-['Plus_Jakarta_Sans']">
                                INV-{{ str_pad($activeBooking->id, 4, '0', STR_PAD_LEFT) }}</h2>
                            {!! $getStatusBadge($activeBooking->current_status) !!}
                        </div>
                        <div class="text-sm font-medium text-base-content/60 dark:text-[#94A3B8] mt-1">
                            {{ $activeBooking->user->name }} •
                            {{ $activeBooking->product_reference_id ? __('Custom Product') : __('3D Print Service') }}</div>
                    </div>
                </div>
                <x-button icon="o-x-mark" @click="$wire.manageDrawerOpen = false"
                    class="btn-circle btn-ghost text-base-content/70 dark:text-[#94A3B8] hover:bg-base-200 dark:hover:bg-white/10 dark:hover:text-white" />
            </div>

            <div
                class="bg-base-100 dark:bg-[#031026] px-8 border-b border-base-300 dark:border-white/10 sticky top-[100px] z-30 flex gap-8">
                <button wire:click="$set('drawerTab', 'pricing')"
                    class="py-4 text-sm font-bold border-b-[3px] transition-all outline-none {{ $drawerTab === 'pricing' ? 'border-primary text-primary dark:border-[#22D3EE] dark:text-[#22D3EE]' : 'border-transparent text-base-content/50 dark:text-[#94A3B8] hover:text-base-content dark:hover:text-white' }}">
                    <x-icon name="o-currency-dollar" class="w-5 h-5 inline-block mr-1 pb-0.5" />
                    {{ __('Pricing & Materials') }}
                </button>
                <button wire:click="$set('drawerTab', 'timeline')"
                    class="py-4 text-sm font-bold border-b-[3px] transition-all outline-none {{ $drawerTab === 'timeline' ? 'border-primary text-primary dark:border-[#22D3EE] dark:text-[#22D3EE]' : 'border-transparent text-base-content/50 dark:text-[#94A3B8] hover:text-base-content dark:hover:text-white' }}">
                    <x-icon name="o-clock" class="w-5 h-5 inline-block mr-1 pb-0.5" /> {{ __('Production Timeline') }}
                </button>
            </div>

            <div class="p-8">
                {{-- TAB PRICING --}}
                @if ($drawerTab === 'pricing')
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-7xl mx-auto animate-[fade-in_0.2s_ease-out]">
                        <div class="space-y-6">
                            <div
                                class="bg-base-100 dark:bg-[#062E5C]/40 dark:backdrop-blur-xl border border-base-200 dark:border-white/10 rounded-2xl p-6 shadow-sm">
                                <h3 class="font-bold text-lg mb-6 text-base-content dark:text-white">
                                    {{ __('Calculation & Pricing') }}</h3>
                                <x-form wire:submit="saveCalculation">
                                    @if (!$activeBooking->product_reference_id)
                                        <div class="grid grid-cols-2 gap-5 mb-5">
                                            <x-input label="{{ __('Weight (gr)') }}" wire:model="slicer_weight_grams" type="number"
                                                class="rounded-lg bg-base-200 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-white" />
                                            <x-input label="{{ __('Time (min)') }}" wire:model="slicer_print_time_minutes"
                                                type="number"
                                                class="rounded-lg bg-base-200 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-white" />
                                        </div>
                                    @endif
                                    <x-input label="{{ __('Agreed / Final Price (Rp)') }}" wire:model="final_price"
                                        type="number" prefix="Rp" required
                                        class="rounded-lg bg-base-200 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-primary dark:text-[#22D3EE] text-lg font-bold" />
                                    <div class="mt-6"><x-button label="{{ __('Set Price') }}" type="submit"
                                            class="btn-primary rounded-lg dark:bg-[#00A8B5] dark:hover:bg-[#00909B] text-white border-none w-full"
                                            spinner="saveCalculation" /></div>
                                </x-form>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div
                                class="bg-base-100 dark:bg-[#062E5C]/40 dark:backdrop-blur-xl border border-base-200 dark:border-white/10 rounded-2xl p-6 shadow-sm">
                                <h3 class="font-bold text-lg mb-6 text-base-content dark:text-white">
                                    {{ __('Consume Material') }}</h3>
                                <x-form wire:submit="deductMaterial">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="col-span-2"><x-select label="{{ __('Material Used') }}"
                                                wire:model="selectedMaterialId" :options="$availableMaterials"
                                                option-label="display_name" option-value="id" searchable required
                                                class="rounded-lg bg-base-200 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-white" />
                                        </div>
                                        <div class="col-span-1"><x-input label="{{ __('Qty') }}" wire:model="deductQuantity"
                                                type="number" required
                                                class="rounded-lg bg-base-200 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-white" />
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-end"><x-button label="{{ __('Deduct Stock') }}" type="submit"
                                            class="btn-error rounded-lg dark:bg-[#00426D] dark:hover:bg-[#0D5A9E] text-white border-none px-6"
                                            icon="o-minus-circle" spinner="deductMaterial" /></div>
                                </x-form>

                                @if ($activeBooking->materialMovements->count() > 0)
                                    <div class="mt-8 pt-6 border-t border-base-200 dark:border-white/10">
                                        <div
                                            class="text-xs font-bold text-base-content/50 dark:text-[#94A3B8] mb-4 uppercase tracking-widest">
                                            {{ __('Logged Usage') }}</div>
                                        <div class="space-y-3">
                                            @foreach ($activeBooking->materialMovements as $mov)
                                                <div
                                                    class="flex justify-between items-center p-3 rounded-lg bg-base-200 dark:bg-white/5 border border-base-300 dark:border-white/5 text-sm">
                                                    <div class="text-base-content dark:text-[#F8FAFC] font-semibold">
                                                        {{ $mov->material->name }}</div>
                                                    <div
                                                        class="font-mono text-warning dark:text-[#FFC72C] font-black bg-warning/10 dark:bg-[#FFC72C]/10 px-3 py-1 rounded-md">
                                                        -{{ $mov->quantity }}{{ $mov->material->unit }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- TAB TIMELINE --}}
                @if ($drawerTab === 'timeline')
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-7xl mx-auto animate-[fade-in_0.2s_ease-out]">
                        <div class="lg:col-span-5">
                            <div
                                class="bg-base-100 dark:bg-[#062E5C]/40 dark:backdrop-blur-xl border border-base-200 dark:border-white/10 rounded-2xl p-6 shadow-sm sticky top-[200px]">
                                <h3 class="font-bold text-lg mb-6 text-base-content dark:text-white">{{ __('Post Update') }}
                                </h3>
                                <x-form wire:submit="addProgress" class="flex flex-col gap-5">
                                    <x-select label="{{ __('Status') }}" wire:model.live="progressStatus"
                                        :options="[['id' => 'slicing', 'name' => 'Slicing'], ['id' => 'printing', 'name' => 'Printing'], ['id' => 'revising', 'name' => 'Revising'], ['id' => 'finishing', 'name' => 'Finishing'], ['id' => 'completed', 'name' => 'Completed']]"
                                        required
                                        class="rounded-lg bg-base-200 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-white" />
                                    <div
                                        class="form-control w-full bg-base-200 dark:bg-[#031026]/50 p-4 rounded-lg border border-base-300 dark:border-white/10">
                                        <div class="flex justify-between mb-2"><span
                                                class="text-xs uppercase text-base-content/50 dark:text-[#94A3B8]">{{ __('Completion') }}</span>
                                            <span
                                                class="font-bold text-primary dark:text-[#22D3EE]">{{ $progressPercentage }}%</span>
                                        </div>
                                        <input type="range" min="0" max="100" wire:model.live="progressPercentage"
                                            class="range range-sm range-primary dark:range-info" step="5" />
                                    </div>
                                    <x-textarea label="{{ __('Notes') }}" wire:model="progressNotes" rows="3" required
                                        class="rounded-lg bg-base-200 dark:bg-[#031026]/50 border-base-300 dark:border-white/10 text-base-content dark:text-white" />
                                    <x-file wire:model="progressFiles" label="{{ __('Attach (Opt)') }}" multiple
                                        class="text-base-content/60 dark:text-[#94A3B8]" />
                                    <x-button label="{{ __('Submit') }}" type="submit"
                                        class="btn-primary rounded-lg dark:bg-[#22D3EE] dark:hover:bg-[#67E8F9] dark:text-[#031026] border-none w-full font-bold mt-2"
                                        spinner="addProgress" />
                                </x-form>
                            </div>
                        </div>

                        <div class="lg:col-span-7 space-y-6">
                            <div
                                class="bg-base-100 dark:bg-[#062E5C]/20 border border-base-200 dark:border-white/5 p-6 rounded-2xl shadow-sm">
                                <div class="relative pl-6 border-l-2 border-primary/20 dark:border-[#22D3EE]/20 space-y-8">
                                    @foreach($activeBooking->progressUpdates->sortByDesc('created_at') as $prog)
                                        <div class="relative">
                                            <div
                                                class="absolute -left-[33px] top-1.5 w-4 h-4 {{ $prog->status_label === 'revising' ? 'bg-error dark:bg-red-500' : 'bg-primary dark:bg-[#22D3EE]' }} rounded-full shadow-[0_0_15px_rgba(34,211,238,0.5)]">
                                            </div>
                                            <div
                                                class="bg-base-200/50 dark:bg-[#062E5C]/60 dark:backdrop-blur-md p-5 rounded-xl border border-base-300 dark:border-white/10 hover:border-primary/30 dark:hover:border-[#22D3EE]/30 transition-colors">
                                                <div class="flex justify-between items-center mb-3">
                                                    <span
                                                        class="badge rounded-md badge-primary dark:bg-[#00426D] text-white border-none text-[10px] font-bold uppercase px-3">{{ $prog->status_label }}
                                                        - {{ $prog->percentage }}%</span>
                                                    <span
                                                        class="text-xs text-base-content/50 dark:text-[#94A3B8] font-mono">{{ $prog->created_at->format('d M H:i') }}</span>
                                                </div>
                                                <p class="text-sm text-base-content dark:text-[#F8FAFC] leading-relaxed">
                                                    {{ $prog->notes }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </x-drawer>

    <x-omni-lightbox />
</div>