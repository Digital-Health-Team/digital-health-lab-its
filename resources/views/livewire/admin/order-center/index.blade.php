<div>
    <x-header title="{{ __('Order Center') }}" subtitle="{{ __('Manage lab print requests, custom product negotiations, and production timelines') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Create Manual Order') }}" icon="o-plus" class="btn-primary" wire:click="createOrder" />
        </x-slot:actions>
    </x-header>

    {{-- FILTER --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-input placeholder="{{ __('Search ID, name, or email...') }}" wire:model.live.debounce="search" icon="o-magnifying-glass" />
        <x-select wire:model.live="filterStatus" :options="[['id'=>'pending','name'=>'Pending'], ['id'=>'negotiating','name'=>'Negotiating'], ['id'=>'in_progress','name'=>'In Progress'], ['id'=>'completed','name'=>'Completed']]" placeholder="{{ __('All Status') }}" />
    </div>

    {{-- MAIN TABLE --}}
    <x-card class="p-0 overflow-hidden border border-base-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr class="bg-base-100">
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
                        <tr wire:key="book-{{ $booking->id }}">
                            <td class="font-mono font-bold text-primary">INV-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="font-semibold">{{ $booking->user->name ?? 'User' }}</div>
                                <div class="text-[10px] text-gray-500">{{ $booking->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                @if($booking->product_reference_id)
                                    <div class="badge badge-secondary badge-outline text-[10px] font-bold"><x-icon name="o-star" class="w-3 h-3 mr-1"/> {{ __('Custom Product') }}</div>
                                @else
                                    <div class="badge badge-primary badge-outline text-[10px] font-bold"><x-icon name="o-cube" class="w-3 h-3 mr-1"/> {{ $booking->service->name ?? 'Service' }}</div>
                                @endif
                            </td>
                            <td class="font-mono">
                                @if($booking->agreed_price)
                                    <span class="text-success font-bold">Rp {{ number_format($booking->agreed_price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-warning font-semibold text-xs italic">{{ __('Needs Calc / Nego') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="badge {{ match($booking->current_status) { 'pending' => 'badge-warning', 'completed' => 'badge-success', 'in_progress' => 'badge-info', 'negotiating' => 'badge-secondary', default => 'badge-ghost' } }} text-white text-[10px] font-bold uppercase tracking-wider">
                                    {{ str_replace('_', ' ', $booking->current_status) }}
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <x-button label="{{ __('Process') }}" icon="o-cog-8-tooth" wire:click="manageOrder({{ $booking->id }})" class="btn-sm btn-primary" />
                                    <x-dropdown icon="o-ellipsis-vertical" class="btn-sm btn-ghost btn-circle">
                                        <x-menu-item title="{{ __('Edit Data') }}" icon="o-pencil-square" wire:click="editOrder({{ $booking->id }})" />
                                        <x-menu-item title="{{ __('Delete') }}" icon="o-trash" wire:click="confirmDelete({{ $booking->id }})" class="text-error hover:bg-error/10" />
                                    </x-dropdown>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-10">{{ __('No orders found.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $bookings->links() }}</div>
    </x-card>

    {{-- MODAL: CORE CRUD --}}
    <x-modal wire:model="crudModalOpen" title="{{ $editingId ? __('Edit Core Order Data') : __('Create Manual Order') }}" separator>
        <x-form wire:submit="saveCoreOrder">
            @if(!$editingId)
                <x-choices label="{{ __('Select Customer') }}" wire:model="crud_user_id" :options="$availableUsers" option-label="name" option-value="id" single searchable required />
            @else
                <div class="mb-4 p-3 bg-base-200 rounded-lg text-sm">
                    <span class="font-bold">{{ __('Customer:') }}</span>
                    {{ \App\Models\ServiceBooking::find($editingId)?->user->name ?? '-' }}
                </div>
            @endif

            <x-select label="{{ __('Service Type') }}" wire:model="crud_service_id" :options="$availableServices" option-label="name" option-value="id" required />
            <x-select label="{{ __('Status') }}" wire:model="crud_status" :options="[['id'=>'pending','name'=>'Pending'], ['id'=>'negotiating','name'=>'Negotiating'], ['id'=>'in_progress','name'=>'In Progress'], ['id'=>'completed','name'=>'Completed'], ['id'=>'cancelled','name'=>'Cancelled']]" required />

            @if($editingId)
                <x-input label="{{ __('Agreed Price Force Override (Rp)') }}" wire:model="crud_final_price" type="number" prefix="Rp" hint="{{ __('Leave blank or 0 if pending.') }}" />
            @endif

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.crudModalOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save') }}" type="submit" class="btn-primary" spinner="saveCoreOrder" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- MODAL: DELETE --}}
    <x-modal wire:model="deleteModalOpen" title="{{ __('Confirm Deletion') }}" separator>
        <div class="py-4 text-base-content/80">
            {{ __('Are you sure you want to delete this order? Associated materials will NOT be automatically refunded.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModalOpen = false" class="btn-ghost" />
            <x-button label="{{ __('Yes, Delete') }}" class="btn-error" wire:click="deleteRecord" spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>

    {{-- DRAWER: OPERATIONAL DASHBOARD (FLOW A & B) --}}
    <x-drawer wire:model="manageDrawerOpen" right class="w-11/12 lg:w-4/5 bg-base-200" without-close-button>

        {{-- LOGIKA KATEGORISASI OMNI-VIEWER --}}
        @php
            $categorizeFile = function($mime, $ext, $url, $name) {
                $ext = strtolower($ext ?? '');
                $type = 'other'; $icon = 'o-document-text';
                if (str_starts_with($mime ?? '', 'image/')) { $type = 'image'; $icon = 'o-photo'; }
                elseif (str_starts_with($mime ?? '', 'video/')) { $type = 'video'; $icon = 'o-video-camera'; }
                elseif ($mime === 'application/pdf' || $ext === 'pdf') { $type = 'pdf'; $icon = 'o-document-chart-bar'; }
                elseif (in_array($ext, ['glb', 'gltf', 'obj', 'stl'])) { $type = '3d'; $icon = 'o-cube'; }
                return ['type' => $type, 'url' => $url, 'name' => $name, 'icon' => $icon];
            };
        @endphp

        @if($activeBooking)
            {{-- HEADER DRAWER --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-base-100 p-6 border-b border-base-300 shadow-sm sticky top-0 z-40 gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        <x-icon name="{{ $activeBooking->product_reference_id ? 'o-star' : 'o-cube' }}" class="w-6 h-6" />
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-2xl font-black text-primary">INV-{{ str_pad($activeBooking->id, 4, '0', STR_PAD_LEFT) }}</h2>
                            <div class="badge {{ match($activeBooking->current_status) { 'pending' => 'badge-warning', 'completed' => 'badge-success', 'in_progress' => 'badge-info', 'negotiating' => 'badge-secondary', default => 'badge-ghost' } }} text-white font-bold uppercase text-[10px] tracking-widest">
                                {{ str_replace('_', ' ', $activeBooking->current_status) }}
                            </div>
                        </div>
                        <div class="text-sm font-medium text-gray-500 mt-1">
                            {{ $activeBooking->product_reference_id ? __('Custom Product Request') : __('3D Print Service Request') }}
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    @php
                        $phone = $activeBooking->user->profile?->phone ?? '';
                        $waLink = $phone ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $phone) : '#';
                    @endphp
                    @if($phone)
                        <a href="{{ $waLink }}" target="_blank" class="btn btn-success text-white shadow-sm">
                            <x-icon name="o-chat-bubble-oval-left" class="w-4 h-4" /> {{ __('WhatsApp') }}
                        </a>
                    @endif
                    <x-button icon="o-x-mark" @click="$wire.manageDrawerOpen = false" class="btn-circle btn-ghost" />
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KOLOM KIRI: PRICING & MATERIAL --}}
                <div class="space-y-6 lg:col-span-1">

                    {{-- BRIEFING & CUSTOMER INFO --}}
                    <x-card class="shadow-sm border border-base-300 bg-base-100 p-0 overflow-hidden">
                        <div class="p-4 bg-base-200/50 border-b border-base-300">
                            <div class="font-bold text-sm mb-1">{{ __('Customer Details') }}</div>
                            <div class="text-sm">{{ $activeBooking->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $activeBooking->user->email }}</div>
                        </div>
                        <div class="p-4">
                            <div class="font-bold text-sm mb-2">{{ __('Request Briefing') }}</div>
                            <p class="text-sm text-gray-600 italic whitespace-pre-wrap">{{ $activeBooking->brief_description ?? __('No description provided.') }}</p>
                        </div>
                    </x-card>

                    {{-- DYNAMIC PRICING CALCULATION --}}
                    <x-card title="{{ $activeBooking->product_reference_id ? __('Price Negotiation') : __('Slicer Calculation') }}" class="shadow-sm border border-base-300 bg-base-100">
                        <x-form wire:submit="saveCalculation">
                            @if(!$activeBooking->product_reference_id)
                                <div class="bg-info/10 p-3 rounded-lg border border-info/20 mb-4 text-xs">
                                    <span class="font-bold text-info">{{ __('Auto Calc Hint:') }}</span>
                                    {{ __('Base price for') }} {{ $activeBooking->service->name }} {{ __('is Rp') }} {{ number_format($activeBooking->service->base_price ?? 0, 0, ',', '.') }}/gr.
                                </div>
                                <x-input label="{{ __('Weight from Slicer') }}" wire:model="slicer_weight_grams" type="number" suffix="gr" />
                                <x-input label="{{ __('Est. Print Time') }}" wire:model="slicer_print_time_minutes" type="number" suffix="min" class="mt-3" />
                                <hr class="my-4 border-base-200">
                            @endif

                            <x-input label="{{ __('Agreed / Final Price (Rp)') }}" wire:model="final_price" type="number" prefix="Rp" required hint="{{ __('This will update the user invoice total.') }}" />

                            <x-slot:actions>
                                <x-button label="{{ __('Set Price & Bill User') }}" type="submit" class="btn-primary btn-sm w-full" spinner="saveCalculation" />
                            </x-slot:actions>
                        </x-form>
                    </x-card>

                    {{-- MATERIAL DEDUCTION --}}
                    <x-card title="{{ __('Consume Material') }}" class="shadow-sm border border-base-300 bg-base-100">
                        <x-form wire:submit="deductMaterial">
                            <x-select label="{{ __('Material Used') }}" wire:model="selectedMaterialId" :options="$availableMaterials" option-label="name" option-value="id" placeholder="{{ __('Select raw material...') }}" required />
                            <x-input label="{{ __('Quantity') }}" wire:model="deductQuantity" type="number" required />
                            <x-slot:actions><x-button label="{{ __('Deduct Stock') }}" type="submit" class="btn-error text-white btn-sm w-full" spinner="deductMaterial" /></x-slot:actions>
                        </x-form>

                        @if($activeBooking->materialMovements->count() > 0)
                            <div class="mt-4 pt-4 border-t border-base-200">
                                <div class="text-xs font-bold text-gray-500 mb-2 uppercase">{{ __('Deduction History') }}</div>
                                @foreach($activeBooking->materialMovements as $mov)
                                    <div class="flex justify-between items-center text-sm py-1 border-b border-base-200 last:border-0">
                                        <span class="truncate pr-2">{{ $mov->material->name }}</span>
                                        <span class="font-mono text-error font-bold">-{{ $mov->quantity }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </x-card>
                </div>

                {{-- KOLOM KANAN: TIMELINE PROGRESS --}}
                <div class="lg:col-span-2 space-y-6">
                    <x-card title="{{ __('Production Timeline') }}" subtitle="{{ __('Update progress to notify the customer') }}" class="shadow-sm border border-base-300 bg-base-100 h-full flex flex-col">

                        {{-- 1. VISUAL PROGRESS BAR & STEPS --}}
                        @php
                            $lastProg = $activeBooking->progressUpdates->sortByDesc('created_at')->first();
                            $currentPct = $lastProg->percentage ?? 0;
                            $currentPhase = $activeBooking->current_status;
                        @endphp

                        <div class="mb-8 bg-base-200/30 p-5 rounded-xl border border-base-200">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">{{ __('Overall Progress') }}</span>
                                <span class="text-2xl font-black text-primary">{{ $currentPct }}%</span>
                            </div>
                            <progress class="progress progress-primary w-full h-3" value="{{ $currentPct }}" max="100"></progress>

                            <ul class="steps steps-horizontal w-full mt-6 text-xs font-bold text-gray-400">
                                <li class="step {{ in_array($currentPhase, ['slicing', 'printing', 'revising', 'finishing', 'completed']) ? 'step-primary text-primary' : '' }}">{{ __('Slicing') }}</li>
                                <li class="step {{ in_array($currentPhase, ['printing', 'revising', 'finishing', 'completed']) ? 'step-primary text-primary' : '' }}">{{ __('Printing') }}</li>

                                <li class="step {{ $currentPhase === 'revising' ? 'step-error text-error' : (in_array($currentPhase, ['finishing', 'completed']) ? 'step-primary text-primary' : '') }}" data-content="{{ $currentPhase === 'revising' ? '!' : '✓' }}">
                                    {{ __('Revising') }}
                                </li>

                                <li class="step {{ in_array($currentPhase, ['finishing', 'completed']) ? 'step-primary text-primary' : '' }}">{{ __('Finishing') }}</li>
                                <li class="step {{ $currentPhase === 'completed' ? 'step-primary text-primary' : '' }}">{{ __('Completed') }}</li>
                            </ul>
                        </div>

                        {{-- 2. FORM ADD PROGRESS --}}
                        <div class="bg-base-200 p-4 rounded-xl border border-base-300 mb-6">
                            <x-form wire:submit="addProgress" class="flex flex-col gap-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-select label="{{ __('Phase / Status') }}" wire:model.live="progressStatus" :options="[['id'=>'slicing','name'=>'Slicing'], ['id'=>'printing','name'=>'Printing'], ['id'=>'revising','name'=>'Revising / Troubleshooting'], ['id'=>'finishing','name'=>'Finishing'], ['id'=>'completed','name'=>'Completed']]" required />

                                    <div class="form-control w-full">
                                        <div class="label pt-0"><span class="label-text font-semibold">{{ __('Set Completion (%)') }}</span> <span class="font-bold text-primary" x-text="$wire.progressPercentage + '%'"></span></div>
                                        <input type="range" min="0" max="100" wire:model.live="progressPercentage" class="range range-sm range-primary" step="5" />
                                    </div>
                                </div>

                                <x-textarea label="{{ __('Progress Notes') }}" wire:model="progressNotes" rows="2" placeholder="{{ __('e.g. Print failed at 40%, restarting. Or: Slicing done, starting print...') }}" required />
                                <x-file wire:model="progressFiles" label="{{ __('Attach Photo (Opt)') }}" multiple hint="{{ __('Support Images, PDF, 3D (STL/OBJ/GLB), Video') }}" />

                                <div class="flex justify-end"><x-button label="{{ __('Post Update') }}" type="submit" class="btn-primary" icon="o-paper-airplane" spinner="addProgress" /></div>
                            </x-form>
                        </div>

                        {{-- 3. FEED TIMELINE DENGAN OMNI LIGHTBOX --}}
                        <div class="relative pl-4 border-l-2 border-primary/30 space-y-6 pb-4">
                            @forelse($activeBooking->progressUpdates->sortByDesc('created_at') as $prog)
                                <div class="relative">
                                    <div class="absolute -left-[25px] top-1 w-4 h-4 {{ $prog->status_label === 'revising' ? 'bg-error' : 'bg-primary' }} rounded-full border-4 border-base-100"></div>
                                    <div class="bg-base-100 p-4 rounded-xl border border-base-200 shadow-sm transition hover:shadow-md">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex items-center gap-2">
                                                <span class="badge {{ $prog->status_label === 'revising' ? 'badge-error' : 'badge-primary' }} font-bold uppercase text-[10px]">{{ $prog->status_label }}</span>
                                                <span class="text-xs font-bold text-gray-400">{{ $prog->percentage }}%</span>
                                            </div>
                                            <span class="text-xs text-gray-500 font-mono">{{ $prog->created_at->format('d M, H:i') }}</span>
                                        </div>
                                        <p class="text-sm font-medium text-base-content/80">{{ $prog->notes }}</p>

                                        {{-- RENDER ATTACHMENTS MENGGUNAKAN OMNI LIGHTBOX --}}
                                        @if($prog->attachments->count() > 0)
                                            @php
                                                $galleryItems = [];
                                                foreach($prog->attachments as $att) {
                                                    $ext = pathinfo($att->file_url, PATHINFO_EXTENSION);
                                                    $galleryItems[] = $categorizeFile($att->file_type, $ext, asset('storage/'.$att->file_url), basename($att->file_url));
                                                }
                                            @endphp

                                            <div x-data="{ gallery: @js($galleryItems) }" class="flex gap-2 mt-3 overflow-x-auto pb-1">
                                                @foreach($prog->attachments as $index => $att)
                                                    @php $cat = $galleryItems[$index]; @endphp

                                                    <div @click="$dispatch('open-lightbox', { index: {{ $index }}, items: gallery })"
                                                         class="shrink-0 cursor-pointer relative group w-16 h-16 rounded-lg border border-base-300 overflow-hidden bg-base-200 flex items-center justify-center">

                                                        @if($cat['type'] === 'image')
                                                            <img src="{{ $cat['url'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                        @else
                                                            <x-icon name="{{ $cat['icon'] }}" class="w-8 h-8 text-gray-400 group-hover:text-primary transition-colors" />
                                                        @endif

                                                        <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-sm text-gray-400 italic text-center py-6">{{ __('No progress updates recorded yet.') }}</div>
                            @endforelse
                        </div>
                    </x-card>
                </div>
            </div>
        @endif
    </x-drawer>

    {{-- KOMPONEN GLOBAL LIGHTBOX (Hanya dipanggil 1x di paling bawah) --}}
    <x-omni-lightbox />
</div>
