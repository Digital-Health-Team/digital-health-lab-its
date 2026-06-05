<div class="space-y-6 pb-10">

    {{-- HEADER --}}
    <div class="border-b border-slate-200 dark:border-slate-800 pb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Platform Overview') }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Full-system visibility — all roles, all activity.') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.order-center') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold
                           bg-indigo-600 dark:bg-indigo-500 text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                    <x-icon name="o-shopping-cart" class="w-4 h-4" />
                    {{ __('Order Center') }}
                </a>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">

        {{-- Total Users --}}
        <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-4 right-4 p-2 rounded-xl bg-indigo-50 dark:bg-indigo-500/10">
                <x-icon name="o-users" class="w-5 h-5 text-indigo-500 dark:text-indigo-400" />
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-[#F8FAFC] mt-1">{{ number_format($stats['total_users']) }}</div>
            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">{{ __('Total Users') }}</div>
        </div>

        {{-- Pending Orders --}}
        <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-4 right-4 p-2 rounded-xl bg-amber-50 dark:bg-amber-500/10">
                <x-icon name="o-bell-alert" class="w-5 h-5 text-amber-500 dark:text-amber-400" />
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-[#F8FAFC] mt-1">{{ $stats['pending_orders'] }}</div>
            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">{{ __('Pending Orders') }}</div>
            @if($stats['pending_orders'] > 0)
                <div class="mt-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400">
                    {{ __('Action Required') }}
                </div>
            @endif
        </div>

        {{-- Active Orders --}}
        <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-4 right-4 p-2 rounded-xl bg-cyan-50 dark:bg-cyan-500/10">
                <x-icon name="o-cog-8-tooth" class="w-5 h-5 text-cyan-500 dark:text-cyan-400" />
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-[#F8FAFC] mt-1">{{ $stats['active_orders'] }}</div>
            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">{{ __('In Production') }}</div>
        </div>

        {{-- Pending Projects --}}
        <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-4 right-4 p-2 rounded-xl bg-violet-50 dark:bg-violet-500/10">
                <x-icon name="o-academic-cap" class="w-5 h-5 text-violet-500 dark:text-violet-400" />
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-[#F8FAFC] mt-1">{{ $stats['pending_projects'] }}</div>
            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">{{ __('Pending Moderation') }}</div>
        </div>

        {{-- Low Stock --}}
        <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-4 right-4 p-2 rounded-xl bg-rose-50 dark:bg-rose-500/10">
                <x-icon name="o-exclamation-triangle" class="w-5 h-5 text-rose-500 dark:text-rose-400" />
            </div>
            <div class="text-3xl font-black {{ $stats['low_stock'] > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-[#F8FAFC]' }} mt-1">{{ $stats['low_stock'] }}</div>
            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">{{ __('Low Stock') }}</div>
        </div>

        {{-- Revenue --}}
        <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-4 right-4 p-2 rounded-xl bg-emerald-50 dark:bg-emerald-500/10">
                <x-icon name="o-banknotes" class="w-5 h-5 text-emerald-500 dark:text-emerald-400" />
            </div>
            <div class="text-lg font-black text-slate-900 dark:text-[#F8FAFC] mt-1 truncate">
                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
            </div>
            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">{{ __('Total Revenue') }}</div>
        </div>

    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Active Orders Pipeline --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-[#0A3D7A]/40">
                    <div>
                        <h2 class="font-bold text-slate-800 dark:text-[#F8FAFC]">{{ __('Orders Pipeline') }}</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Pending negotiations and active production') }}</p>
                    </div>
                    <a href="{{ route('admin.order-center') }}"
                        class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                        {{ __('View All') }} →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-[#0A3D7A]/40 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                                <th class="py-3 px-6">{{ __('Order') }}</th>
                                <th class="py-3 px-6">{{ __('Customer') }}</th>
                                <th class="py-3 px-6">{{ __('Service') }}</th>
                                <th class="py-3 px-6">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-[#0A3D7A]/30 text-slate-700 dark:text-slate-300">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#062E5C]/60 transition-colors">
                                    <td class="py-3 px-6 font-mono font-bold text-indigo-600 dark:text-cyan-400 text-xs">
                                        INV-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="font-semibold text-slate-800 dark:text-[#F8FAFC]">{{ $order->transaction->user->name ?? '-' }}</div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500">{{ $order->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="py-3 px-6 text-xs text-slate-600 dark:text-slate-400">{{ $order->service->name ?? '-' }}</td>
                                    <td class="py-3 px-6">
                                        @if($order->current_status === 'negotiating')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20 animate-pulse">
                                                {{ __('Negotiating') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/20">
                                                {{ $order->current_status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12">
                                        <x-icon name="o-inbox" class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-600" />
                                        <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('No active orders.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT: Sidebar Alerts --}}
        <div class="space-y-4">

            {{-- Low Stock Alert --}}
            <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-[#0A3D7A]/40">
                    <h3 class="font-bold text-sm text-slate-800 dark:text-[#F8FAFC]">{{ __('Low Stock Alert') }}</h3>
                    <a href="{{ route('admin.raw-materials') }}" class="text-[10px] font-semibold text-indigo-600 dark:text-cyan-400 hover:underline">{{ __('Manage') }}</a>
                </div>
                <div class="p-4 space-y-2.5">
                    @forelse($lowStockItems as $item)
                        <div class="flex justify-between items-center p-2.5 rounded-lg bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/20">
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold text-rose-600 dark:text-rose-400 truncate">
                                    {{ $item->brand?->name }} {{ $item->materialCategory?->name }}
                                </div>
                                <div class="text-[10px] text-slate-400 dark:text-slate-500">{{ $item->color?->name }}</div>
                            </div>
                            <div class="text-right ml-3 shrink-0">
                                <div class="font-mono font-black text-sm text-rose-600 dark:text-rose-400">{{ $item->current_stock }}</div>
                                <div class="text-[10px] text-slate-400">{{ $item->unit }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-sm text-slate-400 dark:text-slate-500">
                            <x-icon name="o-check-badge" class="w-8 h-8 mx-auto mb-2 text-emerald-400 opacity-60" />
                            {{ __('All stocks healthy.') }}
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Pending Projects --}}
            <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-[#0A3D7A]/40">
                    <h3 class="font-bold text-sm text-slate-800 dark:text-[#F8FAFC]">{{ __('Pending Moderation') }}</h3>
                    <a href="{{ route('admin.open-source-projects') }}" class="text-[10px] font-semibold text-indigo-600 dark:text-cyan-400 hover:underline">{{ __('Review') }}</a>
                </div>
                <div class="p-4 space-y-2.5">
                    @forelse($pendingProjects as $project)
                        <div class="flex justify-between items-center p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50">
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold text-slate-700 dark:text-[#F8FAFC] truncate">{{ $project->title }}</div>
                                <div class="text-[10px] text-slate-400 dark:text-slate-500 truncate">{{ $project->user->name ?? '-' }}</div>
                            </div>
                            <x-icon name="o-clock" class="w-4 h-4 shrink-0 ml-2 text-amber-400" />
                        </div>
                    @empty
                        <div class="text-center py-4 text-sm text-slate-400 dark:text-slate-500">
                            {{ __('No pending projects.') }}
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
