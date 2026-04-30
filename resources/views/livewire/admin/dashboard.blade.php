<div class="space-y-6 pb-10">
    {{-- HEADER --}}
    <x-header title="{{ __('Dashboard') }}" separator progress-indicator>
        <x-slot:middle class="!justify-start">
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="{{ __('Order Center') }}" icon="o-shopping-cart" link="{{ route('admin.order-center') }}" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    {{-- STATS CARDS (Top Priority Metrics) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat title="{{ __('New Orders (Pending)') }}" value="{{ $stats['pending_orders'] }}" icon="o-bell-alert"
            class="bg-base-100 shadow-sm border-l-4 border-warning hover:scale-[1.02] transition-transform" color="text-warning" description="{{ __('Needs price calculation') }}" />

        <x-stat title="{{ __('In Production') }}" value="{{ $stats['processing_orders'] }}" icon="o-cog-8-tooth"
            class="bg-base-100 shadow-sm border-l-4 border-info hover:scale-[1.02] transition-transform" color="text-info" description="{{ __('Currently printing/slicing') }}" />

        <x-stat title="{{ __('Project Moderation') }}" value="{{ $stats['pending_projects'] }}" icon="o-academic-cap"
            class="bg-base-100 shadow-sm border-l-4 border-primary hover:scale-[1.02] transition-transform" color="text-primary" description="{{ __('Awaiting approval') }}" />

        <x-stat title="{{ __('Low Stock Alert') }}" value="{{ $stats['low_stock_materials'] }}" icon="o-exclamation-triangle"
            class="bg-base-100 shadow-sm border-l-4 border-error hover:scale-[1.02] transition-transform" color="text-error" description="{{ __('Materials ≤ 100 units') }}" />
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KOLOM KIRI (Lebar 2/3): DAFTAR ORDER AKTIF --}}
        <div class="lg:col-span-2 space-y-6">
            <x-card title="{{ __('Active Orders Pipeline') }}" subtitle="{{ __('Incoming and currently processing print requests.') }}" class="shadow-sm border border-base-200 p-0 overflow-hidden">
                <x-slot:menu>
                    <x-button label="{{ __('View All') }}" link="{{ route('admin.order-center') }}" class="btn-ghost btn-sm text-primary" />
                </x-slot:menu>

                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full text-sm">
                        <thead class="bg-base-200/50">
                            <tr>
                                <th>{{ __('INV Number') }}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Service') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-right"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeOrders as $order)
                                <tr>
                                    <td class="font-mono font-bold text-primary">{{ $order->invoice_number ?? 'INV-'.$order->id }}</td>
                                    <td>
                                        <div class="font-semibold">{{ $order->transaction->user->name ?? 'Unknown' }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $order->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td><span class="badge badge-outline badge-sm">{{ $order->service->name }}</span></td>
                                    <td>
                                        @if($order->current_status === 'pending')
                                            <span class="badge badge-warning text-white text-[10px] uppercase font-bold tracking-wider animate-pulse">{{ __('Action Required') }}</span>
                                        @else
                                            <span class="badge badge-info text-white text-[10px] uppercase font-bold tracking-wider">{{ $order->current_status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <x-button icon="o-arrow-right" link="{{ route('admin.order-center') }}" class="btn-xs btn-circle btn-ghost text-primary" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-10 text-gray-400">
                                        <x-icon name="o-inbox" class="w-10 h-10 mb-2 opacity-30 mx-auto" />
                                        {{ __('No active orders at the moment. Good job!') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        {{-- KOLOM KANAN (Lebar 1/3): ALERTS & TASKS --}}
        <div class="space-y-6">

            {{-- 1. MATERIAL STOCK ALERT --}}
            <x-card title="{{ __('Low Stock Materials') }}" class="shadow-sm border border-base-200">
                <x-slot:menu>
                    <x-button icon="o-cube" link="{{ route('admin.raw-materials') }}" class="btn-ghost btn-xs btn-circle" />
                </x-slot:menu>

                <div class="space-y-3">
                    @forelse($lowStockItems as $item)
                        <div class="flex justify-between items-center p-3 bg-error/10 border border-error/20 rounded-lg">
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-sm text-error truncate">{{ $item->name }}</div>
                                <div class="text-[10px] text-gray-500 uppercase">{{ $item->category }}</div>
                            </div>
                            <div class="text-right ml-3">
                                <div class="font-mono font-bold text-error">{{ $item->current_stock }}</div>
                                <div class="text-[10px] text-gray-500">{{ $item->unit }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-400 text-sm">
                            <x-icon name="o-check-badge" class="w-8 h-8 mb-2 opacity-50 mx-auto text-success" />
                            {{ __('All material stocks are healthy.') }}
                        </div>
                    @endforelse
                </div>
            </x-card>

            {{-- 2. PENDING PROJECTS MODERATION --}}
            <x-card title="{{ __('Pending Moderation') }}" class="shadow-sm border border-base-200">
                <x-slot:menu>
                    <x-button icon="o-code-bracket-square" link="{{ route('admin.open-source-projects') }}" class="btn-ghost btn-xs btn-circle" />
                </x-slot:menu>

                <div class="space-y-3">
                    @forelse($pendingProjects as $project)
                        <div class="group flex justify-between items-center p-3 bg-base-200 hover:bg-base-300 transition-colors rounded-lg border border-base-300">
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-sm text-base-content truncate">{{ $project->title }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ __('By') }}: {{ $project->user->profile?->full_name ?? $project->user->name }}</div>
                            </div>
                            <x-button icon="o-arrow-right" link="{{ route('admin.open-source-projects') }}" class="btn-xs btn-circle btn-ghost opacity-0 group-hover:opacity-100 transition-opacity" />
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-400 text-sm">
                            {{ __('No pending projects to moderate.') }}
                        </div>
                    @endforelse
                </div>
            </x-card>

        </div>
    </div>
</div>
