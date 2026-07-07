{{-- Label row + "+ New" quick-create + "Manage" link for a lookup entity.
     Expects: $entity (category|brand|color|lab), $label, $placeholder,
     optional $required, $hint, $withHex (adds a hex picker to the quick-add row) --}}
<div x-data="{ adding: false, newName: '', newHex: '#6366F1' }">
    <div class="flex items-center justify-between mb-1.5">
        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
            {{ $label }}
            @if($required ?? false)
                <span class="text-rose-500">*</span>
            @endif
            @if($hint ?? false)
                <span class="text-xs font-normal text-slate-400 ml-1">{{ $hint }}</span>
            @endif
        </label>
        <div class="flex items-center gap-3">
            <button type="button" @click="adding = !adding"
                class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline cursor-pointer">
                <span x-show="!adding">+ {{ __('New') }}</span>
                <span x-show="adding" x-cloak class="text-slate-400">{{ __('Cancel') }}</span>
            </button>
            <button type="button" wire:click="openManage('{{ $entity }}')"
                class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline cursor-pointer">
                {{ __('Manage') }}
            </button>
        </div>
    </div>
    <div x-show="adding" x-cloak x-transition class="mb-2 flex gap-2 items-center">
        @if($withHex ?? false)
            <input type="color" x-model="newHex"
                title="{{ __('Pick a swatch color') }}"
                class="shrink-0 w-9 h-9 p-0.5 rounded-lg border border-indigo-300 dark:border-indigo-600 bg-white dark:bg-slate-900 cursor-pointer" />
        @endif
        <input type="text" x-model="newName"
            placeholder="{{ $placeholder }}"
            @keydown.enter.prevent="$wire.quickCreate('{{ $entity }}', newName, {{ ($withHex ?? false) ? 'newHex' : 'null' }}).then(() => { adding = false; newName = ''; })"
            class="flex-1 px-2.5 py-1.5 text-sm rounded-lg border border-indigo-300 dark:border-indigo-600
                   bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200
                   focus:outline-none focus:ring-2 focus:ring-indigo-500/30" />
        <button type="button"
            @click="$wire.quickCreate('{{ $entity }}', newName, {{ ($withHex ?? false) ? 'newHex' : 'null' }}).then(() => { adding = false; newName = ''; })"
            class="px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white cursor-pointer transition-colors">
            {{ __('Add') }}
        </button>
        <button type="button" @click="adding = false; newName = ''"
            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer transition-colors">
            <x-icon name="o-x-mark" class="w-4 h-4" />
        </button>
    </div>
</div>
