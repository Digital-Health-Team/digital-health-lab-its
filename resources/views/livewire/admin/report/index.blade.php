@php
    use App\Enums\ReportStatus;
    use App\Enums\ReportType;
    use App\Models\Inventory;
    use App\Models\RawMaterial;
    use App\Models\Tool;

    $statusBadge = fn (ReportStatus $status) => match ($status) {
        ReportStatus::Open => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-500/20',
        ReportStatus::InReview => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-indigo-200 dark:border-indigo-500/20',
        ReportStatus::Resolved => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20',
        ReportStatus::Rejected => 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-500/20',
    };
@endphp

<div class="space-y-6 pb-10">

    {{-- HEADER --}}
    <div class="border-b border-slate-200 dark:border-slate-800 pb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Issue Reports') }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    @if($canResolve)
                        {{ __('All reported stock damage and equipment faults, for warehouse triage.') }}
                    @else
                        {{ __('Report damaged stock or faulty equipment to the warehouse team and track the outcome.') }}
                    @endif
                </p>
            </div>
            <button wire:click="create"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold
                       bg-indigo-600 dark:bg-indigo-500 text-white hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors cursor-pointer">
                <x-icon name="o-flag" class="w-4 h-4" />
                {{ __('New Report') }}
            </button>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-wrap items-center gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search description…') }}"
            class="w-64 px-3 py-2 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700
                   text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50" />

        <select wire:model.live="filterStatus"
            class="px-3 py-2 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
            <option value="">{{ __('All Statuses') }}</option>
            @foreach(ReportStatus::cases() as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterType"
            class="px-3 py-2 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
            <option value="">{{ __('All Types') }}</option>
            @foreach(ReportType::cases() as $type)
                <option value="{{ $type->value }}">{{ $type->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- LEFT: Report list --}}
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-[#0A3D7A]/40 text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                                <th class="py-3 px-6">{{ __('Report') }}</th>
                                @if($canResolve)
                                    <th class="py-3 px-6">{{ __('Reporter') }}</th>
                                @endif
                                <th class="py-3 px-6">{{ __('Item') }}</th>
                                <th class="py-3 px-6">{{ __('Status') }}</th>
                                <th class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-[#0A3D7A]/30 text-slate-700 dark:text-slate-300">
                            @forelse($reports as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#062E5C]/60 transition-colors {{ $activeReport?->id === $item->id ? 'bg-indigo-50/60 dark:bg-indigo-500/5' : '' }}">
                                    <td class="py-3 px-6">
                                        <div class="font-semibold text-slate-800 dark:text-[#F8FAFC] text-xs">{{ $item->type->label() }}</div>
                                        <div class="text-[11px] text-slate-400 dark:text-slate-500 truncate max-w-[220px]">{{ str($item->description)->limit(60) }}</div>
                                        <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $item->created_at->diffForHumans() }}</div>
                                    </td>
                                    @if($canResolve)
                                        <td class="py-3 px-6 text-xs">{{ $item->reporter->name ?? '-' }}</td>
                                    @endif
                                    <td class="py-3 px-6 text-xs">
                                        @if($item->reportable)
                                            <span class="font-semibold">{{ $item->reportable->name ?? '-' }}</span>
                                            <span class="block text-[10px] text-slate-400">{{ class_basename($item->reportable_type) }}</span>
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge($item->status) }}">
                                            {{ $item->status->label() }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="flex items-center justify-end gap-2">
                                            <button wire:click="viewReport({{ $item->id }})"
                                                class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                                title="{{ __('View') }}">
                                                <x-icon name="o-eye" class="w-4 h-4" />
                                            </button>
                                            @can('update', $item)
                                                <button wire:click="edit({{ $item->id }})"
                                                    class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                                    title="{{ __('Edit') }}">
                                                    <x-icon name="o-pencil-square" class="w-4 h-4" />
                                                </button>
                                            @endcan
                                            @can('delete', $item)
                                                <button wire:click="confirmDelete({{ $item->id }})"
                                                    class="p-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-rose-100 hover:text-rose-600 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-rose-500/20 dark:hover:text-rose-400 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                                    title="{{ __('Delete') }}">
                                                    <x-icon name="o-trash" class="w-4 h-4" />
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $canResolve ? 5 : 4 }}" class="text-center py-14">
                                        <x-icon name="o-clipboard-document-check" class="w-10 h-10 mx-auto mb-2 text-slate-300 dark:text-slate-600" />
                                        <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('No issue reports yet.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-3 border-t border-slate-200 dark:border-[#0A3D7A]/40">
                    {{ $reports->links() }}
                </div>
            </div>
        </div>

        {{-- RIGHT: Detail panel --}}
        <div class="lg:col-span-2">
            @if($activeReport)
                <div class="bg-white dark:bg-[#062E5C]/40 border border-slate-200 dark:border-[#0A3D7A]/40 rounded-2xl shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-[#0A3D7A]/40">
                        <div>
                            <h3 class="font-bold text-sm text-slate-800 dark:text-[#F8FAFC]">{{ $activeReport->type->label() }} <span class="text-slate-400 font-mono">#{{ $activeReport->id }}</span></h3>
                            <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                                {{ __('By :name, :date', ['name' => $activeReport->reporter->name ?? '-', 'date' => $activeReport->created_at->format('d M Y H:i')]) }}
                            </div>
                        </div>
                        <button wire:click="clearReport" class="p-1.5 rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer">
                            <x-icon name="o-x-mark" class="w-4 h-4" />
                        </button>
                    </div>

                    <div class="p-5 space-y-4 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge($activeReport->status) }}">
                                {{ $activeReport->status->label() }}
                            </span>
                            @if($activeReport->reportable)
                                <span class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ class_basename($activeReport->reportable_type) }}: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $activeReport->reportable->name ?? '-' }}</span>
                                </span>
                            @endif
                        </div>

                        <div>
                            <div class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-widest mb-1">{{ __('Description') }}</div>
                            <p class="text-slate-700 dark:text-slate-300 whitespace-pre-line">{{ $activeReport->description }}</p>
                        </div>

                        @if($activeReport->attachments->isNotEmpty())
                            <div>
                                <div class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-widest mb-2">{{ __('Photos') }}</div>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach($activeReport->attachments as $photo)
                                        <a href="{{ $photo->file_url }}" target="_blank" class="block rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 aspect-square">
                                            <img src="{{ $photo->file_url }}" alt="{{ $photo->file_name }}" class="w-full h-full object-cover" />
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($activeReport->resolution_note || $activeReport->resolver)
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50">
                                <div class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-widest mb-1">{{ __('Resolution') }}</div>
                                <p class="text-xs text-slate-600 dark:text-slate-300">{{ $activeReport->resolution_note ?: '-' }}</p>
                                @if($activeReport->resolver)
                                    <div class="text-[10px] text-slate-400 mt-1.5">
                                        {{ __('By :name on :date', ['name' => $activeReport->resolver->name, 'date' => $activeReport->resolved_at?->format('d M Y H:i') ?? '-']) }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Warehouse resolution controls --}}
                        @can('resolve', $activeReport)
                            <div class="pt-3 border-t border-slate-200 dark:border-slate-700/50 space-y-3">
                                <div class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-widest">{{ __('Process Report') }}</div>
                                <select wire:model="newStatus"
                                    class="w-full px-3 py-2 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                                    @foreach(ReportStatus::cases() as $status)
                                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                    @endforeach
                                </select>
                                <textarea wire:model="resolutionNote" rows="3" placeholder="{{ __('Resolution note (optional)…') }}"
                                    class="w-full px-3 py-2 rounded-lg text-sm bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50"></textarea>
                                <button wire:click="updateStatus" wire:loading.attr="disabled"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition-colors cursor-pointer">
                                    <x-icon name="o-check" class="w-4 h-4" />
                                    {{ __('Save Status') }}
                                </button>
                            </div>
                        @endcan
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-[#062E5C]/40 border border-dashed border-slate-300 dark:border-[#0A3D7A]/60 rounded-2xl p-10 text-center">
                    <x-icon name="o-cursor-arrow-rays" class="w-10 h-10 mx-auto mb-3 text-slate-300 dark:text-slate-600" />
                    <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('Select a report to see the full details.') }}</p>
                </div>
            @endif
        </div>

    </div>

    {{-- FORM MODAL --}}
    <x-modal wire:model="formModal" title="{{ $editingId ? __('Edit Report') : __('New Issue Report') }}">
        <x-form wire:submit="save" class="space-y-4" no-separator>
            <x-select label="{{ __('Issue Type') }}" wire:model="reportType"
                :options="collect(ReportType::cases())->map(fn ($t) => ['id' => $t->value, 'name' => $t->label()])"
                option-label="name" option-value="id"
                placeholder="{{ __('Select type…') }}" required />

            <x-select label="{{ __('Related Item (optional)') }}" wire:model.live="reportableType"
                :options="[
                    ['id' => '', 'name' => __('None — general report')],
                    ['id' => \App\Models\RawMaterial::class, 'name' => __('Raw Material')],
                    ['id' => \App\Models\Tool::class, 'name' => __('Tool / Equipment')],
                    ['id' => \App\Models\Inventory::class, 'name' => __('Inventory Item')],
                ]"
                option-label="name" option-value="id" />

            @if($reportableType)
                <x-select label="{{ __('Which item?') }}" wire:model="reportableId"
                    :options="$reportableOptions"
                    option-label="name" option-value="id"
                    placeholder="{{ __('Select item…') }}" required />
            @endif

            <x-textarea label="{{ __('Description') }}" wire:model="description" rows="4"
                hint="{{ __('Describe the damage, fault, or shortage in detail.') }}" required />

            <x-file wire:model="photos" label="{{ __('Photos') }}" multiple accept="image/*"
                hint="{{ __('Optional evidence photos. Max 20MB each.') }}" />

            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.formModal = false" />
                <x-button label="{{ $editingId ? __('Save Changes') : __('Submit Report') }}" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- DELETE MODAL --}}
    <x-modal wire:model="deleteModal" title="{{ __('Delete Report') }}">
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('This will permanently remove the report and its photos.') }}</p>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteModal = false" />
            <x-button label="{{ __('Delete') }}" class="btn-error" wire:click="deleteRecord" spinner="deleteRecord" />
        </x-slot:actions>
    </x-modal>

</div>
