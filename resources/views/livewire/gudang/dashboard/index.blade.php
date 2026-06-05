<div class="space-y-6 pb-10">

    {{-- HEADER --}}
    <div class="border-b border-slate-200 dark:border-slate-800 pb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Warehouse Dashboard') }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Stock levels, material movements, and reimbursements.') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.raw-materials') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold
                           bg-indigo-600 dark:bg-indigo-500 text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                    <x-icon name="o-cube" class="w-4 h-4" />
                    {{ __('Raw Materials') }}
                </a>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Low Stock --}}
        <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-4 right-4 p-2 rounded-xl bg-rose-50 dark:bg-rose-500/10">
                <x-icon name="o-exclamation-triangle" class="w-5 h-5 text-rose-500 dark:text-rose-400" />
            </div>
            <div class="text-3xl font-black {{ $stats['low_stock'] > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-[#F8FAFC]' }} mt-1">{{ $stats['low_stock'] }}</div>
            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">{{ __('Low Stock Items') }}</div>
            @if($stats['low_stock'] > 0)
                <div class="mt-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400">
                    {{ __('Restock Needed') }}
                </div>
            @endif
        </div>

        {{-- Total Materials --}}
        <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-4 right-4 p-2 rounded-xl bg-indigo-50 dark:bg-indigo-500/10">
                <x-icon name="o-cube-transparent" class="w-5 h-5 text-indigo-500 dark:text-indigo-400" />
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-[#F8FAFC] mt-1">{{ $stats['total_materials'] }}</div>
            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">{{ __('Material Types') }}</div>
        </div>

        {{-- Pending Reimbursements --}}
        <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-4 right-4 p-2 rounded-xl bg-amber-50 dark:bg-amber-500/10">
                <x-icon name="o-banknotes" class="w-5 h-5 text-amber-500 dark:text-amber-400" />
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-[#F8FAFC] mt-1">{{ $stats['pending_reimbursements'] }}</div>
            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">{{ __('Pending Reimburse') }}</div>
        </div>

        {{-- Total Inventories --}}
        <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-4 right-4 p-2 rounded-xl bg-emerald-50 dark:bg-emerald-500/10">
                <x-icon name="o-archive-box" class="w-5 h-5 text-emerald-500 dark:text-emerald-400" />
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-[#F8FAFC] mt-1">{{ $stats['total_inventories'] }}</div>
            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wide">{{ __('Inventory Assets') }}</div>
        </div>

    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- LEFT: Recent Movements --}}
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-[#0A3D7A]/40">
                    <div>
                        <h2 class="font-bold text-slate-800 dark:text-[#F8FAFC]">{{ __('Recent Movements') }}</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Latest stock in/out transactions') }}</p>
                    </div>
                    <a href="{{ route('admin.raw-materials') }}"
                        class="text-xs font-semibold text-indigo-600 dark:text-cyan-400 hover:underline">
                        {{ __('View All') }} →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-[#0A3D7A]/40 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                                <th class="py-3 px-6">{{ __('Material') }}</th>
                                <th class="py-3 px-6 text-center">{{ __('Type') }}</th>
                                <th class="py-3 px-6 text-right">{{ __('Qty') }}</th>
                                <th class="py-3 px-6">{{ __('By') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-[#0A3D7A]/30 text-slate-700 dark:text-slate-300">
                            @forelse($recentMovements as $movement)
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#062E5C]/60 transition-colors">
                                    <td class="py-3 px-6">
                                        <div class="font-semibold text-slate-800 dark:text-[#F8FAFC] text-xs">
                                            {{ $movement->material?->brand?->name }} {{ $movement->material?->materialCategory?->name }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500">{{ $movement->created_at->format('d M, H:i') }}</div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        @if($movement->type === 'in')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                                {{ __('IN') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20">
                                                {{ __('OUT') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-right font-mono font-bold text-sm {{ $movement->type === 'in' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $movement->type === 'in' ? '+' : '-' }}{{ number_format($movement->quantity) }}
                                    </td>
                                    <td class="py-3 px-6 text-xs text-slate-500 dark:text-slate-400">
                                        {{ $movement->creator?->name ?? 'System' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12">
                                        <x-icon name="o-inbox" class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-600" />
                                        <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('No movements recorded yet.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT: Low Stock Alert --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-[#0A3D7A]/40">
                    <div>
                        <h3 class="font-bold text-sm text-slate-800 dark:text-[#F8FAFC]">{{ __('Low Stock Alert') }}</h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Materials at or below 100 units') }}</p>
                    </div>
                    <a href="{{ route('admin.raw-materials') }}" class="text-[10px] font-semibold text-indigo-600 dark:text-cyan-400 hover:underline">{{ __('Restock') }}</a>
                </div>
                <div class="p-4 space-y-2.5 max-h-[480px] overflow-y-auto">
                    @forelse($lowStockItems as $item)
                        <div class="flex justify-between items-center p-3 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-100 dark:border-rose-500/20">
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold text-rose-700 dark:text-rose-400 truncate">
                                    {{ $item->brand?->name }} {{ $item->materialCategory?->name }}
                                </div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $item->color?->name }} · {{ $item->lab?->name ?? 'Lab' }}</div>
                            </div>
                            <div class="text-right ml-3 shrink-0">
                                <div class="font-mono font-black text-base text-rose-600 dark:text-rose-400">{{ $item->current_stock }}</div>
                                <div class="text-[10px] text-slate-400">{{ $item->unit }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-sm text-slate-400 dark:text-slate-500">
                            <x-icon name="o-check-badge" class="w-10 h-10 mx-auto mb-2 text-emerald-400 opacity-70" />
                            {{ __('All stocks are healthy!') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
