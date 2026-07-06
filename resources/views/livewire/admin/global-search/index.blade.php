<div class="space-y-6 animate-[fade-in_0.4s_ease-out]">

    {{-- ============================================ --}}
    {{-- HEADER + SEARCH INPUT                        --}}
    {{-- ============================================ --}}
    <div class="rounded-2xl p-6 shadow-lg border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026]">
        <div class="mb-5">
            <h1 class="text-2xl sm:text-3xl font-bold text-base-content dark:text-[#F8FAFC] tracking-tight">
                {{ __('Global Search') }}
            </h1>
            <p class="text-sm text-base-content/60 dark:text-[#94A3B8] mt-1">
                {{ __('Search across orders, materials, inventory, users, projects, teams, open-source projects, publications, products, services, trainings, and events.') }}
            </p>
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <x-icon name="o-magnifying-glass" class="w-5 h-5 text-base-content/40 dark:text-[#94A3B8]" />
            </div>
            <input type="text" wire:model.live.debounce.350ms="search" autofocus
                placeholder="{{ __('Type at least 2 characters to search...') }}"
                class="w-full pl-11 pr-4 py-3 rounded-xl text-base
                       bg-base-200/50 dark:bg-[#062E5C]/60 border border-base-300 dark:border-[#0A3D7A]/50
                       text-base-content dark:text-[#F8FAFC] placeholder-base-content/40 dark:placeholder-[#94A3B8]/60
                       focus:ring-2 focus:ring-primary dark:focus:ring-[#22D3EE] focus:border-primary dark:focus:border-[#22D3EE] transition-shadow" />
            @if($search)
                <button wire:click="$set('search', '')"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-base-content/40 dark:text-[#94A3B8] hover:text-base-content dark:hover:text-[#F8FAFC] transition-colors cursor-pointer">
                    <x-icon name="o-x-mark" class="w-5 h-5" />
                </button>
            @endif
        </div>

        @if($search && strlen(trim($search)) >= 2)
            <div class="mt-3 flex items-center gap-2 text-xs text-base-content/50 dark:text-[#94A3B8]">
                <x-icon name="o-sparkles" class="w-3.5 h-3.5 text-primary dark:text-[#22D3EE]" />
                <span>
                    {{ $totalCount > 0
                        ? __(':count result(s) found for ":term"', ['count' => $totalCount, 'term' => trim($search)])
                        : __('No results found for ":term"', ['term' => trim($search)]) }}
                </span>
            </div>
        @endif
    </div>

    {{-- ============================================ --}}
    {{-- IDLE STATE                                   --}}
    {{-- ============================================ --}}
    @if(!$search || strlen(trim($search)) < 2)
        <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg py-20 text-center">
            <x-icon name="o-magnifying-glass" class="w-14 h-14 mx-auto mb-4 text-base-content/15 dark:text-[#0A3D7A]/50" />
            <p class="text-base font-semibold text-base-content/40 dark:text-[#94A3B8]">{{ __('Start typing to search.') }}</p>
            <p class="text-sm text-base-content/30 dark:text-[#94A3B8]/50 mt-1">{{ __('Orders, materials, inventory, users, projects, teams, open-source projects, publications, products, services, trainings, and events.') }}</p>
        </div>

    {{-- ============================================ --}}
    {{-- NO RESULTS STATE                             --}}
    {{-- ============================================ --}}
    @elseif($totalCount === 0)
        <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg py-20 text-center">
            <x-icon name="o-face-frown" class="w-14 h-14 mx-auto mb-4 text-base-content/15 dark:text-[#0A3D7A]/50" />
            <p class="text-base font-semibold text-base-content/40 dark:text-[#94A3B8]">{{ __('No results found.') }}</p>
            <p class="text-sm text-base-content/30 dark:text-[#94A3B8]/50 mt-1">{{ __('Try a different keyword or check your spelling.') }}</p>
        </div>

    {{-- ============================================ --}}
    {{-- RESULTS                                      --}}
    {{-- ============================================ --}}
    @else
        <div class="space-y-6">

            {{-- ======== ORDERS ======== --}}
            @if($results['orders']->isNotEmpty())
                <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/30 dark:bg-[#062E5C]/30">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="o-shopping-bag" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                            <h2 class="text-sm font-bold text-base-content dark:text-[#F8FAFC] uppercase tracking-wider">{{ __('Orders') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                {{ $results['orders']->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.order-center') }}"
                            class="text-xs font-semibold text-primary dark:text-[#22D3EE] hover:underline">{{ __('View All') }} →</a>
                    </div>
                    <ul class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30">
                        @foreach($results['orders'] as $order)
                            <li class="flex items-center gap-4 px-6 py-4 hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">

                                {{-- Icon --}}
                                <div class="w-9 h-9 rounded-lg bg-primary/10 dark:bg-[#22D3EE]/10 flex items-center justify-center shrink-0 border border-primary/20 dark:border-[#22D3EE]/20">
                                    <x-icon name="o-shopping-bag" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                                </div>

                                {{-- Body --}}
                                <div class="flex-1 min-w-0">
                                    {{-- Row 1: ID · service name --}}
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="font-mono text-xs font-bold text-primary dark:text-[#22D3EE] shrink-0">
                                            INV-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                        @if($order->service)
                                            <span class="text-base-content/30 dark:text-[#94A3B8]/40 shrink-0">·</span>
                                            <span class="text-sm font-semibold text-base-content dark:text-[#F8FAFC] truncate">{{ $order->service->name }}</span>
                                        @endif
                                    </div>
                                    {{-- Row 2: customer · date · brief description --}}
                                    <div class="flex items-center gap-1.5 mt-0.5 text-xs text-base-content/50 dark:text-[#94A3B8]">
                                        <span class="truncate max-w-[160px]">{{ $order->user?->profile?->full_name ?? $order->user?->name ?? '—' }}</span>
                                        <span class="shrink-0">·</span>
                                        <span class="shrink-0">{{ $order->created_at->diffForHumans() }}</span>
                                        @if($order->brief_description)
                                            <span class="shrink-0">·</span>
                                            <span class="truncate italic opacity-70">{{ Str::limit($order->brief_description, 40) }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Price --}}
                                <div class="shrink-0 text-right hidden sm:block">
                                    @if($order->agreed_price)
                                        <span class="text-xs font-mono font-bold text-success dark:text-[#22C55E]">
                                            Rp {{ number_format($order->agreed_price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-xs italic text-warning dark:text-[#F59E0B]">{{ __('Needs Nego') }}</span>
                                    @endif
                                </div>

                                {{-- Status badge --}}
                                <span class="text-[10px] font-bold px-2 py-1 rounded-md uppercase shrink-0
                                    {{ match($order->current_status) {
                                        'completed'              => 'bg-success/10 dark:bg-[#22C55E]/10 text-success dark:text-[#22C55E] border border-success/20 dark:border-[#22C55E]/20',
                                        'cancelled'              => 'bg-error/10 dark:bg-[#EF4444]/10 text-error dark:text-[#EF4444] border border-error/20 dark:border-[#EF4444]/20',
                                        'in_progress','printing' => 'bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20',
                                        'finishing'              => 'bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400 border border-violet-200 dark:border-violet-500/20',
                                        'negotiating'            => 'bg-warning/10 dark:bg-[#F59E0B]/10 text-warning dark:text-[#F59E0B] border border-warning/20 dark:border-[#F59E0B]/20',
                                        default                  => 'bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] border border-base-300 dark:border-[#0A3D7A]/40',
                                    } }}">
                                    {{ str_replace('_', ' ', $order->current_status) }}
                                </span>

                                {{-- Deep link to specific order --}}
                                <a href="{{ route('admin.order-center.show', $order) }}"
                                    class="shrink-0 p-1.5 rounded-lg bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] hover:text-primary dark:hover:text-[#22D3EE] hover:bg-primary/10 dark:hover:bg-[#0A3D7A]/40 border border-base-300 dark:border-[#0A3D7A]/40 transition-colors">
                                    <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                                </a>

                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ======== RAW MATERIALS ======== --}}
            @if($results['materials']->isNotEmpty())
                <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/30 dark:bg-[#062E5C]/30">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="o-cube" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                            <h2 class="text-sm font-bold text-base-content dark:text-[#F8FAFC] uppercase tracking-wider">{{ __('Raw Materials') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                {{ $results['materials']->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.raw-materials') }}"
                            class="text-xs font-semibold text-primary dark:text-[#22D3EE] hover:underline">{{ __('View All') }} →</a>
                    </div>
                    <ul class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30">
                        @foreach($results['materials'] as $mat)
                            <li class="flex items-center gap-4 px-6 py-3.5 hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">
                                <div class="w-9 h-9 rounded-lg bg-primary/10 dark:bg-[#22D3EE]/10 flex items-center justify-center shrink-0 border border-primary/20 dark:border-[#22D3EE]/20">
                                    <x-icon name="o-cube" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-base-content dark:text-[#F8FAFC]">
                                        {{ $mat->name }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs text-base-content/50 dark:text-[#94A3B8]">{{ $mat->brand->name }}</span>
                                        <span class="text-base-content/30 dark:text-[#94A3B8]/40">·</span>
                                        <span class="text-xs font-mono text-base-content/60 dark:text-[#94A3B8]">
                                            {{ $mat->unit }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('admin.raw-materials') }}"
                                    class="shrink-0 p-1.5 rounded-lg bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] hover:text-primary dark:hover:text-[#22D3EE] hover:bg-primary/10 dark:hover:bg-[#0A3D7A]/40 border border-base-300 dark:border-[#0A3D7A]/40 transition-colors">
                                    <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ======== INVENTORY ======== --}}
            @if($results['inventories']->isNotEmpty())
                <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/30 dark:bg-[#062E5C]/30">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="o-archive-box" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                            <h2 class="text-sm font-bold text-base-content dark:text-[#F8FAFC] uppercase tracking-wider">{{ __('Inventory') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                {{ $results['inventories']->count() }}
                            </span>
                        </div>
                    </div>
                    <ul class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30">
                        @foreach($results['inventories'] as $inv)
                            <li class="flex items-center gap-4 px-6 py-3.5 hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">
                                <div class="w-9 h-9 rounded-lg bg-primary/10 dark:bg-[#22D3EE]/10 flex items-center justify-center shrink-0 border border-primary/20 dark:border-[#22D3EE]/20">
                                    <x-icon name="o-archive-box" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-base-content dark:text-[#F8FAFC] truncate">{{ $inv->name }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs text-base-content/50 dark:text-[#94A3B8]">{{ $inv->lab->name ?? '—' }}</span>
                                        @if($inv->brand)
                                            <span class="text-base-content/30 dark:text-[#94A3B8]/40">·</span>
                                            <span class="text-xs text-base-content/50 dark:text-[#94A3B8]">{{ $inv->brand->name }}</span>
                                        @endif
                                        <span class="text-base-content/30 dark:text-[#94A3B8]/40">·</span>
                                        <span class="text-xs font-mono text-base-content/60 dark:text-[#94A3B8]">
                                            {{ $inv->available_quantity }}/{{ $inv->total_quantity }} {{ __('available') }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ======== USERS ======== --}}
            @if($results['users']->isNotEmpty())
                <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/30 dark:bg-[#062E5C]/30">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="o-users" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                            <h2 class="text-sm font-bold text-base-content dark:text-[#F8FAFC] uppercase tracking-wider">{{ __('Users') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                {{ $results['users']->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.users') }}"
                            class="text-xs font-semibold text-primary dark:text-[#22D3EE] hover:underline">{{ __('View All') }} →</a>
                    </div>
                    <ul class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30">
                        @foreach($results['users'] as $user)
                            <li class="flex items-center gap-4 px-6 py-3.5 hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">
                                <div class="w-9 h-9 rounded-full bg-primary/10 dark:bg-[#00426D] flex items-center justify-center shrink-0 border border-primary/20 dark:border-[#22D3EE]/30">
                                    <span class="text-sm font-black text-primary dark:text-[#22D3EE]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-base-content dark:text-[#F8FAFC] truncate">{{ $user->name }}</div>
                                    <div class="text-xs text-base-content/50 dark:text-[#94A3B8] truncate">{{ $user->email }}</div>
                                </div>
                                @if($user->role)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md uppercase bg-base-200 dark:bg-[#062E5C]/60 text-base-content/60 dark:text-[#94A3B8] border border-base-300 dark:border-[#0A3D7A]/40 shrink-0">
                                        {{ str_replace('_', ' ', $user->role->display_name ?? $user->role->name) }}
                                    </span>
                                @endif
                                <a href="{{ route('admin.users') }}"
                                    class="shrink-0 p-1.5 rounded-lg bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] hover:text-primary dark:hover:text-[#22D3EE] hover:bg-primary/10 dark:hover:bg-[#0A3D7A]/40 border border-base-300 dark:border-[#0A3D7A]/40 transition-colors">
                                    <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ======== PROJECTS ======== --}}
            @if($results['projects']->isNotEmpty())
                <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/30 dark:bg-[#062E5C]/30">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="o-academic-cap" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                            <h2 class="text-sm font-bold text-base-content dark:text-[#F8FAFC] uppercase tracking-wider">{{ __('Projects') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                {{ $results['projects']->count() }}
                            </span>
                        </div>
                    </div>
                    <ul class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30">
                        @foreach($results['projects'] as $proj)
                            <li class="flex items-center gap-4 px-6 py-3.5 hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">
                                <div class="w-9 h-9 rounded-lg bg-primary/10 dark:bg-[#22D3EE]/10 flex items-center justify-center shrink-0 border border-primary/20 dark:border-[#22D3EE]/20">
                                    <x-icon name="o-academic-cap" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-base-content dark:text-[#F8FAFC] truncate">{{ $proj->title }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs text-base-content/50 dark:text-[#94A3B8] capitalize">{{ str_replace('_', ' ', $proj->category) }}</span>
                                        @if($proj->team)
                                            <span class="text-base-content/30 dark:text-[#94A3B8]/40">·</span>
                                            <span class="text-xs text-base-content/50 dark:text-[#94A3B8]">{{ $proj->team->name }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md uppercase shrink-0
                                    {{ match($proj->status) {
                                        'approved' => 'bg-success/10 dark:bg-[#22C55E]/10 text-success dark:text-[#22C55E] border border-success/20 dark:border-[#22C55E]/20',
                                        'rejected' => 'bg-error/10 dark:bg-[#EF4444]/10 text-error dark:text-[#EF4444] border border-error/20 dark:border-[#EF4444]/20',
                                        default => 'bg-warning/10 dark:bg-[#F59E0B]/10 text-warning dark:text-[#F59E0B] border border-warning/20 dark:border-[#F59E0B]/20',
                                    } }}">
                                    {{ $proj->status }}
                                </span>
                                @if($proj->team)
                                    <a href="{{ route('admin.teams.show', $proj->team_id) }}"
                                        class="shrink-0 p-1.5 rounded-lg bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] hover:text-primary dark:hover:text-[#22D3EE] hover:bg-primary/10 dark:hover:bg-[#0A3D7A]/40 border border-base-300 dark:border-[#0A3D7A]/40 transition-colors">
                                        <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ======== TEAMS ======== --}}
            @if($results['teams']->isNotEmpty())
                <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/30 dark:bg-[#062E5C]/30">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="o-user-group" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                            <h2 class="text-sm font-bold text-base-content dark:text-[#F8FAFC] uppercase tracking-wider">{{ __('Teams') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                {{ $results['teams']->count() }}
                            </span>
                        </div>
                    </div>
                    <ul class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30">
                        @foreach($results['teams'] as $team)
                            <li class="flex items-center gap-4 px-6 py-3.5 hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">
                                <div class="w-9 h-9 rounded-lg bg-primary/10 dark:bg-[#22D3EE]/10 flex items-center justify-center shrink-0 border border-primary/20 dark:border-[#22D3EE]/20">
                                    <x-icon name="o-user-group" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-base-content dark:text-[#F8FAFC] truncate">{{ $team->name }}</div>
                                    @if($team->event)
                                        <div class="text-xs text-base-content/50 dark:text-[#94A3B8]">{{ $team->event->name }} ({{ $team->event->year }})</div>
                                    @endif
                                </div>
                                <a href="{{ route('admin.teams.show', $team) }}"
                                    class="shrink-0 p-1.5 rounded-lg bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] hover:text-primary dark:hover:text-[#22D3EE] hover:bg-primary/10 dark:hover:bg-[#0A3D7A]/40 border border-base-300 dark:border-[#0A3D7A]/40 transition-colors">
                                    <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ======== OPEN SOURCE PROJECTS ======== --}}
            @if($results['open_source_projects']->isNotEmpty())
                <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/30 dark:bg-[#062E5C]/30">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="o-code-bracket-square" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                            <h2 class="text-sm font-bold text-base-content dark:text-[#F8FAFC] uppercase tracking-wider">{{ __('Open-Source Projects') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                {{ $results['open_source_projects']->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.open-source-projects') }}"
                            class="text-xs font-semibold text-primary dark:text-[#22D3EE] hover:underline">{{ __('View All') }} →</a>
                    </div>
                    <ul class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30">
                        @foreach($results['open_source_projects'] as $osp)
                            <li class="flex items-center gap-4 px-6 py-3.5 hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">
                                <div class="w-9 h-9 rounded-lg bg-primary/10 dark:bg-[#22D3EE]/10 flex items-center justify-center shrink-0 border border-primary/20 dark:border-[#22D3EE]/20">
                                    <x-icon name="o-code-bracket-square" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-base-content dark:text-[#F8FAFC] truncate">{{ $osp->title }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs text-base-content/50 dark:text-[#94A3B8]">{{ $osp->user->name ?? '—' }}</span>
                                        @if($osp->category)
                                            <span class="text-base-content/30 dark:text-[#94A3B8]/40">·</span>
                                            <span class="text-xs text-base-content/50 dark:text-[#94A3B8] capitalize">{{ str_replace('_', ' ', $osp->category) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md uppercase shrink-0
                                    {{ $osp->status === 'published' || $osp->status === 'approved'
                                        ? 'bg-success/10 dark:bg-[#22C55E]/10 text-success dark:text-[#22C55E] border border-success/20 dark:border-[#22C55E]/20'
                                        : 'bg-warning/10 dark:bg-[#F59E0B]/10 text-warning dark:text-[#F59E0B] border border-warning/20 dark:border-[#F59E0B]/20' }}">
                                    {{ $osp->status }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ======== PUBLICATIONS ======== --}}
            @if($results['publications']->isNotEmpty())
                <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/30 dark:bg-[#062E5C]/30">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="o-book-open" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                            <h2 class="text-sm font-bold text-base-content dark:text-[#F8FAFC] uppercase tracking-wider">{{ __('Publications') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                {{ $results['publications']->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.publications') }}"
                            class="text-xs font-semibold text-primary dark:text-[#22D3EE] hover:underline">{{ __('View All') }} →</a>
                    </div>
                    <ul class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30">
                        @foreach($results['publications'] as $pub)
                            <li class="flex items-center gap-4 px-6 py-3.5 hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">
                                <div class="w-9 h-9 rounded-lg bg-primary/10 dark:bg-[#22D3EE]/10 flex items-center justify-center shrink-0 border border-primary/20 dark:border-[#22D3EE]/20">
                                    <x-icon name="o-book-open" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-base-content dark:text-[#F8FAFC] truncate">{{ $pub->title }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs text-base-content/50 dark:text-[#94A3B8] truncate">{{ $pub->author }}</span>
                                        @if($pub->journal)
                                            <span class="text-base-content/30 dark:text-[#94A3B8]/40">·</span>
                                            <span class="text-xs text-base-content/50 dark:text-[#94A3B8] truncate">{{ $pub->journal }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md uppercase bg-base-200 dark:bg-[#062E5C]/60 text-base-content/60 dark:text-[#94A3B8] border border-base-300 dark:border-[#0A3D7A]/40 shrink-0">
                                    {{ $pub->category }}
                                </span>
                                <a href="{{ route('admin.publications') }}"
                                    class="shrink-0 p-1.5 rounded-lg bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] hover:text-primary dark:hover:text-[#22D3EE] hover:bg-primary/10 dark:hover:bg-[#0A3D7A]/40 border border-base-300 dark:border-[#0A3D7A]/40 transition-colors">
                                    <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ======== PRODUCTS ======== --}}
            @if($results['products']->isNotEmpty())
                <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/30 dark:bg-[#062E5C]/30">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="o-swatch" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                            <h2 class="text-sm font-bold text-base-content dark:text-[#F8FAFC] uppercase tracking-wider">{{ __('Products') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                {{ $results['products']->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.products') }}"
                            class="text-xs font-semibold text-primary dark:text-[#22D3EE] hover:underline">{{ __('View All') }} →</a>
                    </div>
                    <ul class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30">
                        @foreach($results['products'] as $product)
                            <li class="flex items-center gap-4 px-6 py-3.5 hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">
                                <div class="w-9 h-9 rounded-lg bg-primary/10 dark:bg-[#22D3EE]/10 flex items-center justify-center shrink-0 border border-primary/20 dark:border-[#22D3EE]/20">
                                    <x-icon name="o-swatch" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-base-content dark:text-[#F8FAFC] truncate">{{ $product->name }}</div>
                                    <div class="text-xs text-base-content/50 dark:text-[#94A3B8] mt-0.5">
                                        @if($product->price_min === $product->price_max)
                                            Rp {{ number_format($product->price_min, 0, ',', '.') }}
                                        @else
                                            Rp {{ number_format($product->price_min, 0, ',', '.') }} – {{ number_format($product->price_max, 0, ',', '.') }}
                                        @endif
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md uppercase shrink-0
                                    {{ $product->is_active
                                        ? 'bg-success/10 dark:bg-[#22C55E]/10 text-success dark:text-[#22C55E] border border-success/20 dark:border-[#22C55E]/20'
                                        : 'bg-base-200 dark:bg-[#062E5C]/60 text-base-content/40 dark:text-[#94A3B8]/60 border border-base-300 dark:border-[#0A3D7A]/40' }}">
                                    {{ $product->is_active ? __('Active') : __('Inactive') }}
                                </span>
                                <a href="{{ route('admin.products') }}"
                                    class="shrink-0 p-1.5 rounded-lg bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] hover:text-primary dark:hover:text-[#22D3EE] hover:bg-primary/10 dark:hover:bg-[#0A3D7A]/40 border border-base-300 dark:border-[#0A3D7A]/40 transition-colors">
                                    <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ======== SERVICES ======== --}}
            @if($results['services']->isNotEmpty())
                <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/30 dark:bg-[#062E5C]/30">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="o-briefcase" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                            <h2 class="text-sm font-bold text-base-content dark:text-[#F8FAFC] uppercase tracking-wider">{{ __('Services') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                {{ $results['services']->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.services') }}"
                            class="text-xs font-semibold text-primary dark:text-[#22D3EE] hover:underline">{{ __('View All') }} →</a>
                    </div>
                    <ul class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30">
                        @foreach($results['services'] as $svc)
                            <li class="flex items-center gap-4 px-6 py-3.5 hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">
                                <div class="w-9 h-9 rounded-lg bg-primary/10 dark:bg-[#22D3EE]/10 flex items-center justify-center shrink-0 border border-primary/20 dark:border-[#22D3EE]/20">
                                    <x-icon name="o-briefcase" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-base-content dark:text-[#F8FAFC] truncate">{{ $svc->name }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs text-base-content/50 dark:text-[#94A3B8] capitalize">{{ str_replace('_', ' ', $svc->service_type) }}</span>
                                        <span class="text-base-content/30 dark:text-[#94A3B8]/40">·</span>
                                        <span class="text-xs font-mono text-base-content/60 dark:text-[#94A3B8]">Rp {{ number_format($svc->base_price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('admin.services') }}"
                                    class="shrink-0 p-1.5 rounded-lg bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] hover:text-primary dark:hover:text-[#22D3EE] hover:bg-primary/10 dark:hover:bg-[#0A3D7A]/40 border border-base-300 dark:border-[#0A3D7A]/40 transition-colors">
                                    <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ======== TRAININGS ======== --}}
            @if($results['trainings']->isNotEmpty())
                <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/30 dark:bg-[#062E5C]/30">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="o-presentation-chart-bar" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                            <h2 class="text-sm font-bold text-base-content dark:text-[#F8FAFC] uppercase tracking-wider">{{ __('Trainings') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                {{ $results['trainings']->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.trainings') }}"
                            class="text-xs font-semibold text-primary dark:text-[#22D3EE] hover:underline">{{ __('View All') }} →</a>
                    </div>
                    <ul class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30">
                        @foreach($results['trainings'] as $training)
                            <li class="flex items-center gap-4 px-6 py-3.5 hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">
                                <div class="w-9 h-9 rounded-lg bg-primary/10 dark:bg-[#22D3EE]/10 flex items-center justify-center shrink-0 border border-primary/20 dark:border-[#22D3EE]/20">
                                    <x-icon name="o-presentation-chart-bar" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-base-content dark:text-[#F8FAFC] truncate">{{ $training->title }}</div>
                                    <div class="text-xs text-base-content/50 dark:text-[#94A3B8] mt-0.5 truncate">{{ $training->instructor_name }}</div>
                                </div>
                                @if($training->level)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md uppercase bg-base-200 dark:bg-[#062E5C]/60 text-base-content/60 dark:text-[#94A3B8] border border-base-300 dark:border-[#0A3D7A]/40 shrink-0">
                                        {{ $training->level }}
                                    </span>
                                @endif
                                <a href="{{ route('admin.trainings.show', $training) }}"
                                    class="shrink-0 p-1.5 rounded-lg bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] hover:text-primary dark:hover:text-[#22D3EE] hover:bg-primary/10 dark:hover:bg-[#0A3D7A]/40 border border-base-300 dark:border-[#0A3D7A]/40 transition-colors">
                                    <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ======== EVENTS ======== --}}
            @if($results['events']->isNotEmpty())
                <div class="rounded-2xl border border-base-200 dark:border-[#0A3D7A]/40 bg-base-100 dark:bg-[#031026] shadow-lg overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200 dark:border-[#0A3D7A]/30 bg-base-200/30 dark:bg-[#062E5C]/30">
                        <div class="flex items-center gap-2.5">
                            <x-icon name="o-calendar-days" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                            <h2 class="text-sm font-bold text-base-content dark:text-[#F8FAFC] uppercase tracking-wider">{{ __('Events') }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary/10 dark:bg-[#22D3EE]/10 text-primary dark:text-[#22D3EE] border border-primary/20 dark:border-[#22D3EE]/20">
                                {{ $results['events']->count() }}
                            </span>
                        </div>
                        <a href="{{ route('admin.events') }}"
                            class="text-xs font-semibold text-primary dark:text-[#22D3EE] hover:underline">{{ __('View All') }} →</a>
                    </div>
                    <ul class="divide-y divide-base-200 dark:divide-[#0A3D7A]/30">
                        @foreach($results['events'] as $event)
                            <li class="flex items-center gap-4 px-6 py-3.5 hover:bg-base-200/40 dark:hover:bg-[#0A3D7A]/20 transition-colors">
                                <div class="w-9 h-9 rounded-lg bg-primary/10 dark:bg-[#22D3EE]/10 flex items-center justify-center shrink-0 border border-primary/20 dark:border-[#22D3EE]/20">
                                    <x-icon name="o-calendar-days" class="w-4 h-4 text-primary dark:text-[#22D3EE]" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-base-content dark:text-[#F8FAFC] truncate">{{ $event->name }} ({{ $event->year }})</div>
                                    @if($event->theme_title)
                                        <div class="text-xs text-base-content/50 dark:text-[#94A3B8] mt-0.5 truncate">{{ $event->theme_title }}</div>
                                    @endif
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md uppercase shrink-0
                                    {{ $event->is_active
                                        ? 'bg-success/10 dark:bg-[#22C55E]/10 text-success dark:text-[#22C55E] border border-success/20 dark:border-[#22C55E]/20'
                                        : 'bg-base-200 dark:bg-[#062E5C]/60 text-base-content/40 dark:text-[#94A3B8]/60 border border-base-300 dark:border-[#0A3D7A]/40' }}">
                                    {{ $event->is_active ? __('Active') : __('Inactive') }}
                                </span>
                                <a href="{{ route('admin.events.show', $event) }}"
                                    class="shrink-0 p-1.5 rounded-lg bg-base-200 dark:bg-[#062E5C]/60 text-base-content/50 dark:text-[#94A3B8] hover:text-primary dark:hover:text-[#22D3EE] hover:bg-primary/10 dark:hover:bg-[#0A3D7A]/40 border border-base-300 dark:border-[#0A3D7A]/40 transition-colors">
                                    <x-icon name="o-arrow-top-right-on-square" class="w-3.5 h-3.5" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>
    @endif

</div>
