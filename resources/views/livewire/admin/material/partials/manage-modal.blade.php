{{-- Generic manage modal for lookup entities (category / brand / color / lab).
     Driven by $manageModal / $manageEntity on the component; lists $manageRecords. --}}
@if($manageModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div wire:click="closeManage" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

    <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl flex flex-col max-h-[80vh]">
        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                {{ __('Manage :entity', ['entity' => __($manageLabel)]) }}
            </h3>
            <button type="button" wire:click="closeManage"
                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                <x-icon name="o-x-mark" class="w-4 h-4" />
            </button>
        </div>

        {{-- Body --}}
        <div class="overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($manageRecords as $record)
                @php
                    $usageCount = collect($record->getAttributes())
                        ->filter(fn ($v, $k) => str_ends_with($k, '_count'))
                        ->sum();
                @endphp
                <div class="flex items-center gap-3 px-5 py-3" wire:key="manage-{{ $manageEntity }}-{{ $record->id }}">
                    @if($manageEditId === $record->id)
                        @if($manageEntity === 'color')
                            <input type="color" wire:model="manageEditHex"
                                title="{{ __('Swatch color') }}"
                                class="shrink-0 w-8 h-8 p-0.5 rounded-lg border border-indigo-300 dark:border-indigo-600 bg-white dark:bg-slate-900 cursor-pointer" />
                        @endif
                        <input type="text" wire:model="manageEditName"
                            wire:keydown.enter="saveRename"
                            class="flex-1 px-2.5 py-1.5 text-sm rounded-lg border border-indigo-300 dark:border-indigo-600
                                   bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500/30" />
                        <button type="button" wire:click="saveRename"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white cursor-pointer transition-colors">
                            {{ __('Save') }}
                        </button>
                        <button type="button" wire:click="cancelRename"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer transition-colors">
                            <x-icon name="o-x-mark" class="w-4 h-4" />
                        </button>
                    @else
                        <div class="flex-1 min-w-0 flex items-center gap-2">
                            @if($manageEntity === 'color' && $record->hex)
                                <span class="shrink-0 w-3.5 h-3.5 rounded-full border border-slate-200 dark:border-slate-700"
                                    style="background-color: {{ $record->hex }}"></span>
                            @endif
                            <span class="text-sm text-slate-700 dark:text-slate-200 truncate">{{ $record->name }}</span>
                            @if($usageCount > 0)
                                <span class="shrink-0 text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                                    {{ __(':count in use', ['count' => $usageCount]) }}
                                </span>
                            @endif
                        </div>
                        <button type="button" wire:click="startRename({{ $record->id }})"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 cursor-pointer transition-colors">
                            <x-icon name="o-pencil-square" class="w-4 h-4" />
                        </button>
                        <button type="button" wire:click="deleteEntity({{ $record->id }})"
                            wire:confirm="{{ __('Delete \':name\'?', ['name' => $record->name]) }}"
                            @disabled($usageCount > 0)
                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 cursor-pointer transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                            <x-icon name="o-trash" class="w-4 h-4" />
                        </button>
                    @endif
                </div>
            @empty
                <p class="px-5 py-6 text-sm text-slate-400 dark:text-slate-500 italic text-center">
                    {{ __('Nothing here yet.') }}
                </p>
            @endforelse
        </div>

        @error('manageEditName')
            <p class="px-5 py-2 text-xs text-rose-500 border-t border-slate-100 dark:border-slate-800">{{ $message }}</p>
        @enderror
        @error('manageEditHex')
            <p class="px-5 py-2 text-xs text-rose-500 border-t border-slate-100 dark:border-slate-800">{{ $message }}</p>
        @enderror
    </div>
</div>
@endif
