@props([
    'label',
    'model',
    'type' => 'text',
    'languages' => ['id', 'en'], // Default bahasa
])

<div x-data="{ lang: 'id' }" class="form-control w-full mb-4">
    {{-- Header: Label & Tab Switcher --}}
    <div class="flex justify-between items-end mb-1">
        <label class="label">
            <span class="label-text font-bold">{{ $label }}</span>
        </label>

        <div class="join">
            @foreach ($languages as $code)
                <button type="button" @click="lang = '{{ $code }}'"
                    :class="lang === '{{ $code }}' ? 'btn-primary text-white' : 'btn-ghost text-gray-500'"
                    class="btn btn-xs join-item uppercase transition-all">
                    {{ $code }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Input Fields Loop --}}
    @foreach ($languages as $code)
        <div x-show="lang === '{{ $code }}'" x-transition.opacity>
            @if ($type === 'textarea')
                <textarea wire:model="{{ $model }}.{{ $code }}"
                    class="textarea textarea-bordered w-full h-24 focus:outline-primary"
                    placeholder="Input in {{ strtoupper($code) }}..."></textarea>
            @else
                <input type="{{ $type }}" wire:model="{{ $model }}.{{ $code }}"
                    class="input input-bordered w-full focus:outline-primary"
                    placeholder="Input in {{ strtoupper($code) }}..." />
            @endif

            {{-- Validation Error per Bahasa --}}
            @error($model . '.' . $code)
                <div class="text-error text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>
    @endforeach
</div>
