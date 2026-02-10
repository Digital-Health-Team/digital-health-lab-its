@props([
    'title' => 'Konfirmasi',
    'text' => 'Apakah Anda yakin?',
    'icon' => 'o-exclamation-triangle',
    'confirmText' => 'Ya, Hapus',
    'cancelText' => 'Batal',
    'method' => 'delete', // Method di Livewire yang akan dipanggil
    'loading' => true, // Tampilkan spinner saat proses
])

{{-- Wrapper Modal (Backdrop Blur) --}}
<x-modal {{ $attributes }} class="backdrop-blur-sm">

    <div class="mb-5 text-center">
        {{-- Icon Peringatan Besar --}}
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
            <x-icon name="{{ $icon }}" class="w-8 h-8 text-red-600" />
        </div>

        {{-- Judul --}}
        <h3 class="text-lg font-bold text-gray-900">
            {{ $title }}
        </h3>

        {{-- Deskripsi --}}
        <div class="mt-2">
            <p class="text-sm text-gray-500">
                {{ $text }}
            </p>
        </div>
    </div>

    {{-- Tombol Aksi (Grid Layout) --}}
    <div class="grid grid-cols-2 gap-3 mt-6">
        {{-- Tombol Batal --}}
        <x-button label="{{ $cancelText }}" class="btn-ghost border border-gray-300 bg-white"
            @click="$wire.{{ $attributes->wire('model')->value() }} = false" />

        {{-- Tombol Konfirmasi (Merah) --}}
        <x-button label="{{ $confirmText }}" class="btn-error text-white" wire:click="{{ $method }}" spinner />
    </div>
</x-modal>
