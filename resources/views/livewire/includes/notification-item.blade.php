@php
    $data = $notification->data;
    $isUnread = is_null($notification->read_at);
    $url = $data['url'] ?? null;
    $icon = $data['icon'] ?? 'o-bell';
    $title = $data['title'] ?? __('Notification');
    $message = $data['message'] ?? '';
    $time = $notification->created_at?->diffForHumans() ?? '';
@endphp

<button
    wire:click="markAsRead('{{ $notification->id }}'{{ $url ? ", '$url'" : '' }})"
    class="w-full text-left px-4 py-3 hover:bg-base-200 transition-colors border-b border-base-200 last:border-0 flex items-start gap-3
        {{ $isUnread ? 'bg-primary/5' : '' }}"
>
    {{-- Icon --}}
    <div class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
        {{ $isUnread ? 'bg-primary/15 text-primary' : 'bg-base-200 text-base-content/50' }}">
        <x-icon :name="$icon" class="w-4 h-4" />
    </div>

    {{-- Content --}}
    <div class="flex-1 min-w-0">
        <p class="text-xs font-semibold leading-snug {{ $isUnread ? 'text-base-content' : 'text-base-content/70' }}">
            {{ $title }}
        </p>
        <p class="text-xs mt-0.5 leading-snug {{ $isUnread ? 'text-base-content/80' : 'text-base-content/50' }} line-clamp-2">
            {{ $message }}
        </p>
        <p class="text-[10px] mt-1 text-base-content/40">{{ $time }}</p>
    </div>

    {{-- Unread dot --}}
    @if ($isUnread)
        <div class="mt-1.5 flex-shrink-0 w-2 h-2 rounded-full bg-primary"></div>
    @endif
</button>
