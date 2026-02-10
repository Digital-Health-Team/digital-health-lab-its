<x-dropdown no-x-anchor right>
    <x-slot:trigger>
        {{-- Tampilkan Bendera Aktif di Tombol Navbar --}}
        @php
            $currentFlag = app()->getLocale() == 'id' ? '🇮🇩' : '🇺🇸';
        @endphp
        <x-button :label="$currentFlag" class="btn-ghost btn-circle text-lg" tooltip="{{ __('Change Language') }}" />
    </x-slot:trigger>

    {{-- MENU ITEMS --}}
    {{-- Tambahkan class bg-base-300 jika locale cocok --}}

    <x-menu-item title="English 🇺🇸" wire:click="changeLocale('en')"
        class="{{ app()->getLocale() == 'en' ? 'bg-base-300 font-bold' : '' }}" />

    <x-menu-item title="Indonesia 🇮🇩" wire:click="changeLocale('id')"
        class="{{ app()->getLocale() == 'id' ? 'bg-base-300 font-bold' : '' }}" />
</x-dropdown>
