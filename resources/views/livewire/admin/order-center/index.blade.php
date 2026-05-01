<div>
    <x-header title="{{ __('Order Center') }}"
        subtitle="{{ __('Manage lab print requests, custom product negotiations, and production timelines') }}"
        separator>
        <x-slot:actions>
            <x-button label="{{ __('Create Manual Order') }}" icon="o-plus" class="btn-primary" wire:click="createOrder" />
        </x-slot:actions>
    </x-header>

    {{-- HELPER UNTUK WARNA STATUS (Terpusat agar konsisten di Tabel & Drawer) --}}
    @php
        $getStatusBadge = function ($status) {
            $class = match ($status) {
                'pending' => 'badge-warning text-white border-transparent',
                'negotiating' => 'bg-purple-500 text-white border-transparent',
                'in_progress', 'slicing' => 'badge-info text-white border-transparent',
                'printing' => 'bg-blue-500 text-white border-transparent',
                'finishing' => 'bg-teal-500 text-white border-transparent',
                'completed' => 'badge-success text-white border-transparent',
                'revising', 'cancelled' => 'badge-error text-white border-transparent',
                default => 'badge-neutral text-white border-transparent',
            };
            return "<div class='badge {$class} font-bold uppercase text-[10px] tracking-widest px-3 py-2'>" .
                str_replace('_', ' ', $status) .
                '</div>';
        };
    @endphp

    {{-- FILTER --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-input placeholder="{{ __('Search INV, name, or email...') }}" wire:model.live.debounce="search"
            icon="o-magnifying-glass" />
        <x-select wire:model.live="filterStatus" :options="[
            ['id' => 'pending', 'name' => 'Pending'],
            ['id' => 'negotiating', 'name' => 'Negotiating'],
            ['id' => 'in_progress', 'name' => 'In Progress'],
            ['id' => 'completed', 'name' => 'Completed'],
        ]" placeholder="{{ __('All Status') }}" />
    </div>

    {{-- MAIN TABLE --}}
    <x-card class="p-0 overflow-hidden border border-base-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr class="bg-base-200/50 text-gray-500">
                        <th>{{ __('INV Number') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Order Type') }}</th>
                        <th>{{ __('Agreed Price') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr wire:key="book-{{ $booking->id }}" class="hover:bg-base-200/50 transition-colors">
                            <td class="font-mono font-bold text-primary cursor-pointer hover:underline"
                                wire:click="manageOrder({{ $booking->id }})">
                                INV-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="font-semibold text-base-content">{{ $booking->user->name ?? 'User' }}</div>
                                <div class="text-[10px] text-gray-500">{{ $booking->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                @if ($booking->product_reference_id)
                                    <div class="badge badge-secondary badge-outline text-[10px] font-bold"><x-icon
                                            name="o-star" class="w-3 h-3 mr-1" /> {{ __('Custom Product') }}</div>
                                @else
                                    <div class="badge badge-primary badge-outline text-[10px] font-bold"><x-icon
                                            name="o-cube" class="w-3 h-3 mr-1" />
                                        {{ $booking->service->name ?? 'Service' }}</div>
                                @endif
                            </td>
                            <td class="font-mono">
                                @if ($booking->agreed_price)
                                    <span class="text-success font-bold">Rp
                                        {{ number_format($booking->agreed_price, 0, ',', '.') }}</span>
                                @else
                                    <span
                                        class="text-warning font-semibold text-xs italic">{{ __('Needs Calc / Nego') }}</span>
                                @endif
                            </td>
                            <td>
                                {!! $getStatusBadge($booking->current_status) !!}
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <x-button label="{{ __('Process') }}" icon="o-cog-8-tooth"
                                        wire:click="manageOrder({{ $booking->id }})" class="btn-sm btn-primary" />
                                    <x-dropdown icon="o-ellipsis-vertical" class="btn-sm btn-ghost btn-circle">
                                        <x-menu-item title="{{ __('Edit Data') }}" icon="o-pencil-square"
                                            wire:click="editOrder({{ $booking->id }})" />
                                        <x-menu-item title="{{ __('Delete') }}" icon="o-trash"
                                            wire:click="confirmDelete({{ $booking->id }})"
                                            class="text-error hover:bg-error/10" />
                                    </x-dropdown>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-400">{{ __('No orders found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $bookings->links() }}</div>
    </x-card>

    {{-- DRAWER: FORM CREATE / EDIT (Telah diubah dari Modal & Diperlebar) --}}
    <x-drawer wire:model="crudDrawerOpen"
        title="{{ $editingId ? __('Edit Core Order Data') : __('Create Manual Order') }}"
        class="w-11/12 md:w-1/2 lg:w-1/3" right separator>
        <x-form wire:submit="saveCoreOrder">

            {{-- BAGIAN CUSTOMER --}}
            @if (!$editingId)
                {{-- Toggle Pemilihan Customer saat Create --}}
                <div class="bg-base-200/50 p-4 rounded-xl border border-base-200 mb-2">
                    <x-toggle label="{{ __('Create New Customer') }}" wire:model.live="isNewUser" right
                        class="toggle-primary text-sm font-bold" />

                    <div class="mt-4">
                        @if ($isNewUser)
                            {{-- Form Customer Baru --}}
                            <div class="space-y-4 animate-[fade-in_0.3s_ease-out]">
                                <x-input label="{{ __('Full Name') }}" wire:model="newUserName"
                                    placeholder="e.g. Budi Santoso" icon="o-user" required />
                                <x-input label="{{ __('Email Address') }}" wire:model="newUserEmail"
                                    placeholder="e.g. budi@gmail.com" type="email" icon="o-envelope" required />
                                <x-input label="{{ __('WhatsApp Number') }}" wire:model="newUserPhone"
                                    placeholder="e.g. 08123456789" icon="o-device-phone-mobile" required
                                    hint="{{ __('Used for progress notification') }}" />
                            </div>
                        @else
                            {{-- Pilih Customer Existing --}}
                            <div class="animate-[fade-in_0.3s_ease-out]">
                                <x-choices label="{{ __('Select Customer') }}" wire:model="crud_user_id"
                                    :options="$availableUsers" option-label="name" option-value="id" single searchable
                                    required />
                            </div>
                        @endif
                    </div>
                </div>
            @else
                {{-- Info Customer saat Edit --}}
                <div class="mb-4 p-4 bg-base-200/50 rounded-xl border border-base-200 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-base-300 flex items-center justify-center"><x-icon
                            name="o-user" class="w-5 h-5 text-gray-500" /></div>
                    <div>
                        <div class="text-xs text-gray-500 font-bold uppercase">{{ __('Customer') }}</div>
                        <div class="font-semibold">
                            {{ \App\Models\ServiceBooking::find($editingId)?->user->name ?? '-' }}</div>
                    </div>
                </div>
            @endif

            {{-- BAGIAN ORDER --}}
            <div class="space-y-4">
                <x-select label="{{ __('Service Type') }}" wire:model="crud_service_id" :options="$availableServices"
                    option-label="name" option-value="id" placeholder="{{ __('Select Service') }}" required />
                <div class="form-control w-full">
                    <label class="label pt-0 pb-1">
                        <span class="label-text font-semibold">{{ __('Status') }} <span
                                class="text-error">*</span></span>
                    </label>
                    <select wire:model="crud_status" class="select select-bordered w-full font-medium" required>
                        <option value="" disabled>{{ __('Select Status...') }}</option>

                        <optgroup label="–– {{ __('PRE-PRODUCTION / DEAL') }} ––">
                            <option value="pending">{{ __('Pending (Awaiting Review)') }}</option>
                            <option value="negotiating">{{ __('Negotiating (Chat/Price)') }}</option>
                        </optgroup>

                        <optgroup label="–– {{ __('PRODUCTION PHASES') }} ––">
                            <option value="in_progress">{{ __('In Progress (General)') }}</option>
                            <option value="slicing">{{ __('Slicing') }}</option>
                            <option value="printing">{{ __('Printing') }}</option>
                            <option value="revising">{{ __('Revising / Troubleshooting') }}</option>
                            <option value="finishing">{{ __('Finishing') }}</option>
                        </optgroup>

                        <optgroup label="–– {{ __('FINALIZATION') }} ––">
                            <option value="completed">{{ __('Completed') }}</option>
                            <option value="cancelled">{{ __('Cancelled') }}</option>
                        </optgroup>
                    </select>
                </div>

                @if ($editingId)
                    <x-input label="{{ __('Agreed Price Force Override (Rp)') }}" wire:model="crud_final_price"
                        type="number" prefix="Rp" hint="{{ __('Leave blank or 0 if pending.') }}" />
                @endif
            </div>

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.crudDrawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save Order') }}" type="submit" class="btn-primary"
                    spinner="saveCoreOrder" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- MODAL: DELETE --}}
    <x-modal wire:model="deleteModalOpen" title="{{ __('Confirm Deletion') }}" separator>
        <div class="py-4 text-base-content/80">
            {{ __('Are you sure you want to delete this order? Associated materials will NOT be automatically refunded.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModalOpen = false" class="btn-ghost" />
            <x-button label="{{ __('Yes, Delete') }}" class="btn-error text-white" wire:click="deleteRecord"
                spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>

    {{-- DRAWER: OPERATIONAL DASHBOARD (TABS) --}}
    <x-drawer wire:model="manageDrawerOpen" right class="w-11/12 lg:w-4/5 bg-base-200" without-close-button>

        {{-- LOGIKA KATEGORISASI OMNI-VIEWER --}}
        @php
            $categorizeFile = function ($mime, $ext, $url, $name) {
                $ext = strtolower($ext ?? '');
                $type = 'other';
                $icon = 'o-document-text';
                if (str_starts_with($mime ?? '', 'image/')) {
                    $type = 'image';
                    $icon = 'o-photo';
                } elseif (str_starts_with($mime ?? '', 'video/')) {
                    $type = 'video';
                    $icon = 'o-video-camera';
                } elseif ($mime === 'application/pdf' || $ext === 'pdf') {
                    $type = 'pdf';
                    $icon = 'o-document-chart-bar';
                } elseif (in_array($ext, ['glb', 'gltf', 'obj', 'stl'])) {
                    $type = '3d';
                    $icon = 'o-cube';
                }
                return ['type' => $type, 'url' => $url, 'name' => $name, 'icon' => $icon];
            };
        @endphp

        @if ($activeBooking)
            {{-- 1. HEADER DRAWER STATIS --}}
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center bg-base-100 px-8 py-5 border-b border-base-300 shadow-sm sticky top-0 z-40 gap-4">
                <div class="flex items-center gap-5">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary to-info flex items-center justify-center text-white shadow-md">
                        <x-icon name="{{ $activeBooking->product_reference_id ? 'o-star' : 'o-cube' }}"
                            class="w-7 h-7" />
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-3xl font-black text-primary tracking-tight">
                                INV-{{ str_pad($activeBooking->id, 4, '0', STR_PAD_LEFT) }}</h2>
                            {!! $getStatusBadge($activeBooking->current_status) !!}
                        </div>
                        <div class="text-sm font-medium text-gray-500 mt-0.5">
                            {{ $activeBooking->product_reference_id ? __('Custom Product Request') : __('3D Print Service Request') }}
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    @php
                        $phone = $activeBooking->user->profile?->phone ?? '';
                        $waLink = $phone ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $phone) : '#';
                    @endphp
                    @if ($phone)
                        <div class="btn btn-ghost text-gray-600">
                            <x-icon name="o-phone" class="w-4 h-4" /> {{ $phone }}
                        </div>
                        <a href="{{ $waLink }}" target="_blank"
                            class="btn btn-success text-white shadow-sm border-none">
                            <x-icon name="o-chat-bubble-oval-left" class="w-4 h-4" /> {{ __('WhatsApp') }}
                        </a>
                    @endif
                    <x-button icon="o-x-mark" @click="$wire.manageDrawerOpen = false"
                        class="btn-circle btn-ghost bg-base-200 hover:bg-error hover:text-white" />
                </div>
            </div>

            {{-- 2. TABS NAVIGASI --}}
            <div class="bg-base-100 px-8 border-b border-base-300 sticky top-[92px] z-30 flex gap-8 overflow-x-auto">
                <button wire:click="$set('drawerTab', 'pricing')"
                    class="py-4 text-sm font-bold border-b-[3px] transition-all whitespace-nowrap outline-none {{ $drawerTab === 'pricing' ? 'border-primary text-primary' : 'border-transparent text-gray-400 hover:text-base-content hover:border-base-300' }}">
                    <x-icon name="o-currency-dollar" class="w-5 h-5 inline-block mr-1 pb-0.5" />
                    {{ __('Pricing & Materials') }}
                </button>
                <button wire:click="$set('drawerTab', 'timeline')"
                    class="py-4 text-sm font-bold border-b-[3px] transition-all whitespace-nowrap outline-none {{ $drawerTab === 'timeline' ? 'border-primary text-primary' : 'border-transparent text-gray-400 hover:text-base-content hover:border-base-300' }}">
                    <x-icon name="o-clock" class="w-5 h-5 inline-block mr-1 pb-0.5" />
                    {{ __('Production Timeline') }}
                </button>
            </div>

            {{-- 3. KONTEN TABS --}}
            <div class="p-8">

                {{-- KONTEN: TAB PRICING & MATERIALS --}}
                @if ($drawerTab === 'pricing')
                    <div
                        class="grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-7xl mx-auto animate-[fade-in_0.2s_ease-out]">

                        {{-- Sisi Kiri: Info Klien --}}
                        <div class="lg:col-span-5 space-y-6">
                            <x-card class="shadow-sm border border-base-300 bg-base-100 p-0 overflow-hidden">
                                <div class="p-5 bg-base-200/40 border-b border-base-300 flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-base-300 flex items-center justify-center">
                                        <x-icon name="o-user" class="w-5 h-5 text-gray-500" />
                                    </div>
                                    <div>
                                        <div class="font-bold text-base-content">{{ $activeBooking->user->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $activeBooking->user->email }}</div>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="font-bold text-sm mb-3 text-primary uppercase tracking-wider">
                                        {{ __('Request Briefing') }}</div>
                                    <p
                                        class="text-sm text-base-content/80 leading-relaxed bg-base-200/50 p-4 rounded-xl border border-base-200">
                                        {{ $activeBooking->brief_description ?? __('No description provided.') }}</p>
                                </div>
                            </x-card>
                        </div>

                        {{-- Sisi Kanan: Form Harga & Potong Bahan --}}
                        <div class="lg:col-span-7 space-y-6">
                            {{-- Pricing Calculation --}}
                            <x-card
                                title="{{ $activeBooking->product_reference_id ? __('Price Negotiation') : __('Slicer Calculation') }}"
                                class="shadow-sm border border-base-300 bg-base-100">
                                <x-form wire:submit="saveCalculation">
                                    @if (!$activeBooking->product_reference_id)
                                        <div
                                            class="bg-info/10 p-4 rounded-xl border border-info/20 mb-5 text-sm flex items-start gap-3">
                                            <x-icon name="o-information-circle"
                                                class="w-5 h-5 text-info shrink-0 mt-0.5" />
                                            <div>
                                                <span
                                                    class="font-bold text-info block mb-1">{{ __('Auto Calc Hint') }}</span>
                                                <span class="text-info/80">{{ __('Base price for') }}
                                                    <strong>{{ $activeBooking->service->name }}</strong>
                                                    {{ __('is') }} <strong>Rp
                                                        {{ number_format($activeBooking->service->base_price ?? 0, 0, ',', '.') }}/gr</strong>.</span>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-5 mb-5">
                                            <x-input label="{{ __('Weight (Slicer)') }}"
                                                wire:model="slicer_weight_grams" type="number" suffix="gr" />
                                            <x-input label="{{ __('Print Time') }}"
                                                wire:model="slicer_print_time_minutes" type="number"
                                                suffix="min" />
                                        </div>
                                    @endif

                                    <x-input label="{{ __('Agreed / Final Price (Rp)') }}" wire:model="final_price"
                                        type="number" prefix="Rp" required
                                        hint="{{ __('This will directly update the invoice amount for the user.') }}" />

                                    <x-slot:actions>
                                        <x-button label="{{ __('Save & Set Price') }}" type="submit"
                                            class="btn-primary w-full md:w-auto" spinner="saveCalculation" />
                                    </x-slot:actions>
                                </x-form>
                            </x-card>

                            {{-- Material Deduction --}}
                            <x-card title="{{ __('Consume Material') }}"
                                class="shadow-sm border border-base-300 bg-base-100">
                                <div
                                    class="bg-warning/10 p-4 rounded-xl border border-warning/20 mb-5 text-sm flex items-start gap-3">
                                    <x-icon name="o-exclamation-triangle"
                                        class="w-5 h-5 text-warning shrink-0 mt-0.5" />
                                    <div>
                                        <span
                                            class="text-warning/90">{{ __('Deducting here automatically updates the master inventory and logs an "OUT" movement linked to this order.') }}</span>
                                    </div>
                                </div>

                                <x-form wire:submit="deductMaterial">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="md:col-span-2">
                                            <x-select label="{{ __('Material Used') }}"
                                                wire:model="selectedMaterialId" :options="$availableMaterials"
                                                option-label="display_name" option-value="id"
                                                placeholder="{{ __('Select material to consume...') }}" searchable
                                                required />
                                        </div>
                                        <div class="md:col-span-1">
                                            <x-input label="{{ __('Quantity') }}" wire:model="deductQuantity"
                                                type="number" required />
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-end">
                                        <x-button label="{{ __('Deduct Stock') }}" type="submit"
                                            class="btn-error text-white w-full md:w-auto shadow-sm"
                                            icon="o-arrow-up-right" spinner="deductMaterial" />
                                    </div>
                                </x-form>

                                @if ($activeBooking->materialMovements->count() > 0)
                                    <div class="mt-8 pt-6 border-t border-base-200">
                                        <div class="text-xs font-bold text-gray-400 mb-4 uppercase tracking-widest">
                                            {{ __('Logged Usage for this Order') }}</div>
                                        <div class="space-y-3 max-h-56 overflow-y-auto pr-2 custom-scrollbar">
                                            @foreach ($activeBooking->materialMovements as $mov)
                                                <div
                                                    class="flex justify-between items-center p-3.5 rounded-xl bg-base-200/50 border border-base-200 text-sm">
                                                    <div class="flex flex-col min-w-0 pr-3 flex-1">
                                                        <span class="font-bold text-base-content truncate"
                                                            title="{{ $mov->material->name }}">{{ $mov->material->name }}</span>
                                                        <span
                                                            class="text-[10px] text-gray-500 font-mono mt-0.5">{{ $mov->created_at->format('d M Y, H:i') }}</span>
                                                    </div>
                                                    <div
                                                        class="font-mono text-error font-black bg-error/10 px-3 py-1.5 rounded-lg shrink-0 border border-error/20">
                                                        -{{ $mov->quantity }}<span
                                                            class="text-[10px] ml-1">{{ $mov->material->unit }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </x-card>
                        </div>
                    </div>
                @endif

                {{-- KONTEN: TAB PRODUCTION TIMELINE --}}
                @if ($drawerTab === 'timeline')
                    <div
                        class="grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-7xl mx-auto animate-[fade-in_0.2s_ease-out]">

                        {{-- Kolom Kiri Timeline: Input Progress --}}
                        <div class="lg:col-span-5 space-y-6">
                            <div class="bg-base-100 p-6 rounded-2xl border border-base-300 shadow-sm">
                                <h3 class="font-bold text-lg mb-6 flex items-center gap-2 text-primary">
                                    <x-icon name="o-paper-airplane" class="w-5 h-5" /> {{ __('Post New Update') }}
                                </h3>

                                <x-form wire:submit="addProgress" class="flex flex-col gap-5">
                                    <x-select label="{{ __('Phase / Status') }}" wire:model.live="progressStatus"
                                        :options="[
                                            ['id' => 'slicing', 'name' => 'Slicing'],
                                            ['id' => 'printing', 'name' => 'Printing'],
                                            ['id' => 'revising', 'name' => 'Revising / Troubleshooting'],
                                            ['id' => 'finishing', 'name' => 'Finishing'],
                                            ['id' => 'completed', 'name' => 'Completed'],
                                        ]" required />

                                    <div
                                        class="form-control w-full bg-base-200/50 p-4 rounded-xl border border-base-200">
                                        <div class="flex justify-between items-center mb-2">
                                            <span
                                                class="font-bold text-xs uppercase text-gray-500 tracking-wider">{{ __('Set Completion') }}</span>
                                            <span class="font-black text-primary text-lg"
                                                x-text="$wire.progressPercentage + '%'"></span>
                                        </div>
                                        <input type="range" min="0" max="100"
                                            wire:model.live="progressPercentage" class="range range-sm range-primary"
                                            step="5" />
                                    </div>

                                    <x-textarea label="{{ __('Progress Notes') }}" wire:model="progressNotes"
                                        rows="3"
                                        placeholder="{{ __('e.g. Slicing done, starting print...') }}" required />

                                    <div class="bg-base-200/30 p-3 rounded-xl border border-dashed border-base-300">
                                        <x-file wire:model="progressFiles"
                                            label="{{ __('Attach Files (Optional)') }}" multiple
                                            hint="{{ __('Images, PDF, STL/OBJ/GLB, Video') }}" />
                                    </div>

                                    <div class="flex justify-end pt-2">
                                        <x-button label="{{ __('Submit Update') }}" type="submit"
                                            class="btn-primary w-full shadow-md" spinner="addProgress" />
                                    </div>
                                </x-form>
                            </div>
                        </div>

                        {{-- Kolom Kanan Timeline: Visual Progress & Feed --}}
                        <div class="lg:col-span-7 space-y-6">

                            {{-- Progress Bar Indicator --}}
                            @php
                                $lastProg = $activeBooking->progressUpdates->sortByDesc('created_at')->first();
                                $currentPct = $lastProg->percentage ?? 0;
                                $currentPhase = $activeBooking->current_status;
                            @endphp

                            <div class="bg-base-100 p-8 rounded-2xl border border-base-300 shadow-sm">
                                <div class="flex justify-between items-end mb-4">
                                    <span
                                        class="text-sm font-bold text-gray-500 uppercase tracking-widest">{{ __('Overall Completion') }}</span>
                                    <span class="text-4xl font-black text-primary">{{ $currentPct }}%</span>
                                </div>
                                <progress class="progress progress-primary w-full h-3 bg-base-200"
                                    value="{{ $currentPct }}" max="100"></progress>

                                <ul class="steps steps-horizontal w-full mt-8 text-[11px] font-bold text-gray-400">
                                    <li
                                        class="step {{ in_array($currentPhase, ['slicing', 'printing', 'revising', 'finishing', 'completed']) ? 'step-primary text-primary' : '' }}">
                                        {{ __('Slicing') }}</li>
                                    <li
                                        class="step {{ in_array($currentPhase, ['printing', 'revising', 'finishing', 'completed']) ? 'step-primary text-primary' : '' }}">
                                        {{ __('Printing') }}</li>

                                    <li class="step {{ $currentPhase === 'revising' ? 'step-error text-error' : (in_array($currentPhase, ['finishing', 'completed']) ? 'step-primary text-primary' : '') }}"
                                        data-content="{{ $currentPhase === 'revising' ? '!' : '✓' }}">
                                        {{ __('Revising') }}
                                    </li>

                                    <li
                                        class="step {{ in_array($currentPhase, ['finishing', 'completed']) ? 'step-primary text-primary' : '' }}">
                                        {{ __('Finishing') }}</li>
                                    <li
                                        class="step {{ $currentPhase === 'completed' ? 'step-primary text-primary' : '' }}">
                                        {{ __('Completed') }}</li>
                                </ul>
                            </div>

                            {{-- Feed Timeline --}}
                            <div class="bg-base-100 p-8 rounded-2xl border border-base-300 shadow-sm">
                                <h3 class="font-bold text-lg mb-8">{{ __('History Log') }}</h3>

                                <div class="relative pl-5 border-l-2 border-primary/20 space-y-8 pb-2 ml-2">
                                    @forelse($activeBooking->progressUpdates->sortByDesc('created_at') as $prog)
                                        <div class="relative">
                                            {{-- Indikator Titik Timeline --}}
                                            <div
                                                class="absolute -left-[30px] top-1.5 w-5 h-5 {{ $prog->status_label === 'revising' ? 'bg-error' : 'bg-primary' }} rounded-full border-4 border-base-100 shadow-sm">
                                            </div>

                                            <div
                                                class="bg-base-200/30 p-5 rounded-2xl border border-base-200 hover:shadow-md transition-shadow hover:border-primary/30">
                                                <div
                                                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-3">
                                                    <div class="flex items-center gap-3">
                                                        <span
                                                            class="badge {{ $prog->status_label === 'revising' ? 'badge-error' : 'badge-primary' }} text-white font-bold uppercase text-[10px] tracking-wider px-3">{{ $prog->status_label }}</span>
                                                        <span
                                                            class="text-sm font-black text-primary">{{ $prog->percentage }}%</span>
                                                    </div>
                                                    <span
                                                        class="text-xs text-gray-500 font-mono bg-base-100 px-2 py-1 rounded-md border border-base-300">{{ $prog->created_at->format('d M Y, H:i') }}</span>
                                                </div>
                                                <p class="text-sm font-medium text-base-content/80 leading-relaxed">
                                                    {{ $prog->notes }}</p>

                                                {{-- RENDER ATTACHMENTS MENGGUNAKAN OMNI LIGHTBOX --}}
                                                @if ($prog->attachments->count() > 0)
                                                    @php
                                                        $galleryItems = [];
                                                        foreach ($prog->attachments as $att) {
                                                            $ext = pathinfo($att->file_url, PATHINFO_EXTENSION);
                                                            $galleryItems[] = $categorizeFile(
                                                                $att->file_type,
                                                                $ext,
                                                                asset('storage/' . $att->file_url),
                                                                basename($att->file_url),
                                                            );
                                                        }
                                                    @endphp

                                                    <div x-data="{ gallery: @js($galleryItems) }"
                                                        class="flex gap-3 mt-5 overflow-x-auto pb-2 custom-scrollbar">
                                                        @foreach ($prog->attachments as $index => $att)
                                                            @php $cat = $galleryItems[$index]; @endphp

                                                            <div @click="$dispatch('open-lightbox', { index: {{ $index }}, items: gallery })"
                                                                class="shrink-0 cursor-pointer relative group w-20 h-20 rounded-xl border border-base-300 overflow-hidden bg-white flex items-center justify-center shadow-sm">

                                                                @if ($cat['type'] === 'image')
                                                                    <img src="{{ $cat['url'] }}"
                                                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                                @else
                                                                    <div
                                                                        class="flex flex-col items-center gap-1 group-hover:scale-110 transition-transform duration-300">
                                                                        <x-icon name="{{ $cat['icon'] }}"
                                                                            class="w-8 h-8 text-primary/70" />
                                                                        <span
                                                                            class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">{{ $cat['type'] }}</span>
                                                                    </div>
                                                                @endif

                                                                <div
                                                                    class="absolute inset-0 bg-primary/10 opacity-0 group-hover:opacity-100 transition-opacity">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div
                                            class="text-sm text-gray-400 italic text-center py-10 bg-base-200/30 rounded-2xl border-2 border-dashed border-base-300">
                                            <x-icon name="o-clock" class="w-10 h-10 mx-auto mb-3 opacity-30" />
                                            {{ __('No progress updates recorded yet.') }}
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </x-drawer>

    {{-- KOMPONEN GLOBAL LIGHTBOX --}}
    <x-omni-lightbox />
</div>
