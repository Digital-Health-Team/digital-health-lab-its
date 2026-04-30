<div>
    <x-header title="{{ __('Raw Materials') }}" subtitle="{{ __('Manage lab material inventory and monitor usage logs') }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('Add Material') }}" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    <div class="mb-6 max-w-md">
        <x-input placeholder="{{ __('Search material name...') }}" wire:model.live.debounce.500ms="search" icon="o-magnifying-glass" />
    </div>

    <x-card class="p-0 overflow-hidden shadow-sm border border-base-200">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr class="bg-base-200/50">
                        <th class="w-12 text-center text-xs uppercase text-gray-500">#</th>
                        <th class="text-xs uppercase text-gray-500">{{ __('Material Name') }}</th>
                        <th class="text-xs uppercase text-gray-500">{{ __('Category') }}</th>
                        <th class="text-xs uppercase text-gray-500">{{ __('Current Stock') }}</th>
                        <th class="text-right text-xs uppercase text-gray-500">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                        <tr wire:key="mat-{{ $material->id }}" class="hover:bg-base-200/50 transition-colors">
                            <td class="text-center text-gray-400 font-medium">{{ $loop->iteration + ($materials->firstItem() - 1) }}</td>
                            <td class="font-bold text-primary">{{ $material->name }}</td>
                            <td>
                                <span class="badge badge-outline badge-sm uppercase text-[10px] font-bold tracking-wider">{{ $material->category }}</span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $material->current_stock > 100 ? 'bg-success' : 'bg-error animate-pulse' }}"></div>
                                    <span class="font-mono font-bold text-base-content">{{ $material->current_stock }}</span>
                                    <span class="text-xs text-gray-500 font-medium">{{ $material->unit }}</span>
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end items-center gap-1">
                                    {{-- Tombol utama untuk membuka History & Restock --}}
                                    <x-button label="{{ __('Manage History') }}" icon="o-clipboard-document-list" wire:click="viewHistory({{ $material->id }})" class="btn-sm btn-outline btn-primary" />

                                    <div class="w-px h-4 bg-base-300 mx-1"></div>
                                    <x-button icon="o-pencil-square" wire:click="edit({{ $material->id }})" class="btn-sm btn-circle btn-ghost text-blue-500 hover:bg-blue-50" tooltip="{{ __('Edit Data') }}" />
                                    <x-button icon="o-trash" wire:click="confirmDelete({{ $material->id }})" class="btn-sm btn-circle btn-ghost text-error hover:bg-error/10" tooltip="{{ __('Delete') }}" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-400">
                                <x-icon name="o-cube" class="w-12 h-12 mb-3 opacity-30 mx-auto" />
                                {{ __('No raw materials found in inventory.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($materials->hasPages())
            <div class="p-4 border-t border-base-200 bg-base-50/50">{{ $materials->links() }}</div>
        @endif
    </x-card>

    {{-- DRAWER CRUD MASTER DATA --}}
    <x-drawer wire:model="drawerOpen" title="{{ $editingId ? __('Edit Material Master') : __('Add New Material') }}" class="w-11/12 md:w-1/2 lg:w-1/3" right separator>
        <x-form wire:submit="save">
            <x-input label="{{ __('Material Name') }}" wire:model="name" placeholder="e.g., PLA+ White 1.75mm" required />
            <div class="grid grid-cols-2 gap-4">
                <x-select label="{{ __('Category') }}" wire:model="category" :options="$categories" option-label="name" option-value="id" placeholder="{{ __('Select...') }}" required />
                <x-select label="{{ __('Measurement Unit') }}" wire:model="unit" :options="$units" option-label="name" option-value="id" placeholder="{{ __('Select...') }}" required />
            </div>

            @if(!$editingId)
                <x-input label="{{ __('Initial Stock') }}" wire:model="current_stock" type="number" hint="{{ __('Set the starting stock balance.') }}" required />
            @else
                <div class="bg-base-200/50 p-4 rounded-xl border border-base-200 mt-2 text-sm text-gray-500">
                    <x-icon name="o-lock-closed" class="w-4 h-4 inline mr-1" />
                    {{ __('Stock editing is locked for auditing purposes. Please use the "Manage & History" button to add inbound stock.') }}
                </div>
            @endif

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.drawerOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save Master Data') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-drawer>

    {{-- MODAL: KONFIRMASI HAPUS --}}
    <x-modal wire:model="deleteModalOpen" title="{{ __('Confirm Deletion') }}" separator>
        <div class="py-4 text-base-content/80">
            {{ __('Are you sure you want to permanently delete this material? This action cannot be undone and will fail if the material has movement history.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModalOpen = false" class="btn-ghost" />
            <x-button label="{{ __('Yes, Delete') }}" class="btn-error text-white" wire:click="deleteRecord" spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>

    {{-- DRAWER HISTORY & RESTOCK (GABUNGAN) --}}
    <x-drawer wire:model="historyDrawerOpen" right class="w-11/12 lg:w-3/4 bg-base-200" without-close-button>
        @if($activeMaterial)
            <div class="flex justify-between items-center bg-base-100 p-6 border-b border-base-300 shadow-sm sticky top-0 z-50">
                <div>
                    <h2 class="text-2xl font-black text-primary">{{ $activeMaterial->name }}</h2>
                    <div class="text-sm font-medium text-gray-500 mt-1 uppercase tracking-widest">{{ $activeMaterial->category }}</div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right hidden sm:block">
                        <div class="text-[10px] uppercase font-bold text-gray-400">{{ __('Current Balance') }}</div>
                        <div class="font-mono text-2xl font-black {{ $activeMaterial->current_stock > 100 ? 'text-success' : 'text-error' }}">
                            {{ $activeMaterial->current_stock }} <span class="text-sm font-medium">{{ $activeMaterial->unit }}</span>
                        </div>
                    </div>
                    <div class="w-px h-10 bg-base-300 hidden sm:block"></div>
                    <x-button icon="o-x-mark" @click="$wire.historyDrawerOpen = false" class="btn-circle btn-ghost" />
                </div>
            </div>

            <div class="p-6">
                {{-- VISUALISASI DATA (STAT CARDS) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <x-card class="bg-base-100 shadow-sm border-l-4 border-success">
                        <div class="text-xs font-bold text-gray-400 uppercase">{{ __('Total Restock (IN)') }}</div>
                        <div class="text-2xl font-mono font-black text-success mt-1">+{{ $totalIn }} <span class="text-sm font-medium">{{ $activeMaterial->unit }}</span></div>
                    </x-card>
                    <x-card class="bg-base-100 shadow-sm border-l-4 border-error">
                        <div class="text-xs font-bold text-gray-400 uppercase">{{ __('Total Usage (OUT)') }}</div>
                        <div class="text-2xl font-mono font-black text-error mt-1">-{{ $totalOut }} <span class="text-sm font-medium">{{ $activeMaterial->unit }}</span></div>
                    </x-card>

                    @php
                        $totalActivity = $totalIn + $totalOut;
                        $usageRatio = $totalActivity > 0 ? round(($totalOut / $totalActivity) * 100) : 0;
                    @endphp
                    <x-card class="bg-base-100 shadow-sm border border-base-200 flex flex-col justify-center">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-xs font-bold text-gray-400 uppercase">{{ __('Usage Ratio') }}</span>
                            <span class="text-lg font-black text-primary">{{ $usageRatio }}%</span>
                        </div>
                        <progress class="progress progress-error w-full h-3" value="{{ $usageRatio }}" max="100"></progress>
                    </x-card>
                </div>

                {{-- KONTEN UTAMA BAWAH --}}
                <x-card class="shadow-sm border border-base-300 bg-base-100 p-0 overflow-hidden">

                    {{-- HEADER TABEL & TOMBOL TOGGLE RESTOCK --}}
                    <div class="p-4 border-b border-base-200 flex justify-between items-center bg-base-100">
                        <h3 class="font-bold text-lg text-primary">{{ __('Movement Log') }}</h3>
                        <x-button label="{{ __('Add Stock (IN)') }}" icon="o-plus" class="btn-sm btn-success text-white" wire:click="$toggle('showRestockForm')" />
                    </div>

                    {{-- FORM RESTOCK (INLINE) --}}
                    @if($showRestockForm)
                        <div class="bg-success/5 border-b border-success/20 p-5 animate-[fade-in_0.3s_ease-out]">
                            <div class="mb-3 flex items-center gap-2 text-success font-bold text-sm">
                                <x-icon name="o-arrow-down-tray" class="w-4 h-4" /> {{ __('Restock Material') }}
                            </div>
                            <x-form wire:submit="processRestock">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                    <div class="md:col-span-3">
                                        <x-input label="{{ __('Qty Received') }}" wire:model="restockQty" type="number" suffix="{{ $activeMaterial->unit }}" required />
                                    </div>
                                    <div class="md:col-span-9">
                                        <x-input label="{{ __('Source / Notes') }}" wire:model="restockNotes" placeholder="{{ __('e.g., Supplier Tokopedia, Batch #992') }}" required />
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 mt-2">
                                    <x-button label="{{ __('Cancel') }}" wire:click="$set('showRestockForm', false)" class="btn-ghost btn-sm" />
                                    <x-button label="{{ __('Confirm Restock') }}" class="btn-success text-white btn-sm shadow-md" type="submit" spinner="processRestock" />
                                </div>
                            </x-form>
                        </div>
                    @endif

                    {{-- TABEL RIWAYAT / LOG PENGGUNAAN --}}
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full text-sm">
                            <thead class="bg-base-200/50 text-gray-500">
                                <tr>
                                    <th>{{ __('Date / Time') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Qty') }}</th>
                                    <th>{{ __('Reference & Notes') }}</th>
                                    <th>{{ __('Logged By') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeMaterial->movements as $log)
                                    <tr class="hover:bg-base-200/30">
                                        <td class="font-mono text-xs">{{ $log->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            @if($log->type === 'in')
                                                <span class="badge badge-success text-white font-bold text-[10px] uppercase tracking-wider"><x-icon name="o-arrow-down-left" class="w-3 h-3 mr-1"/> IN</span>
                                            @else
                                                <span class="badge badge-error text-white font-bold text-[10px] uppercase tracking-wider"><x-icon name="o-arrow-up-right" class="w-3 h-3 mr-1"/> OUT</span>
                                            @endif
                                        </td>
                                        <td class="font-mono font-bold {{ $log->type === 'in' ? 'text-success' : 'text-error' }}">
                                            {{ $log->type === 'in' ? '+' : '-' }}{{ $log->quantity }}
                                        </td>
                                        <td>
                                            <div class="font-semibold text-base-content max-w-xs truncate" title="{{ $log->notes }}">
                                                {{ $log->notes ?: '-' }}
                                            </div>
                                            @if($log->service_booking_id)
                                                <a href="{{ route('admin.order-center') }}" class="text-[10px] text-primary hover:underline block mt-0.5">
                                                    {{ __('View Order') }} #{{ str_pad($log->service_booking_id, 4, '0', STR_PAD_LEFT) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td class="text-xs text-gray-500">
                                            {{ $log->creator->name ?? 'System' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-8 text-gray-400">{{ __('No movement history recorded yet.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>
        @endif
    </x-drawer>
</div>
