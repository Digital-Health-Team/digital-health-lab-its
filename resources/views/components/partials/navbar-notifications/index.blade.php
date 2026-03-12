@php
    $isUnread = is_null($notification->read_at);
    $title = $notification->data['title'] ?? 'New Notification';
    $message = $notification->data['message'] ?? '';
    $url = $notification->data['url'] ?? null;
    $icon = $notification->data['icon'] ?? 'o-bell';
    $color = $notification->data['color'] ?? 'text-primary';
@endphp

<div wire:click="markAsRead('{{ $notification->id }}', '{{ $url }}')"
    wire:key="{{ $view }}-notif-{{ $notification->id }}"
    class="flex gap-4 p-4 border-b border-base-200/50 hover:bg-base-200 transition cursor-pointer {{ $isUnread ? 'bg-primary/5' : 'opacity-75' }}">

    <div class="shrink-0 mt-0.5">
        <div class="w-9 h-9 sm:w-8 sm:h-8 rounded-full flex items-center justify-center bg-base-200">
            <x-icon name="{{ $icon }}" class="w-5 h-5 sm:w-4 sm:h-4 {{ $color }}" />
        </div>
    </div>

    <div class="flex-1 min-w-0">
        <p class="text-sm font-bold truncate {{ $isUnread ? 'text-base-content' : 'text-base-content/70' }}">
            {{ $title }}</p>
        <p
            class="text-xs sm:text-[11px] leading-snug mt-1 sm:mt-0.5 line-clamp-2 {{ $isUnread ? 'text-base-content/80' : 'text-base-content/50' }}">
            {{ $message }}</p>
        <p class="text-[10px] sm:text-[9px] font-mono opacity-40 mt-2 sm:mt-1.5">
            {{ $notification->created_at->diffForHumans() }}</p>
    </div>

    @if ($isUnread)
        <div class="shrink-0 flex items-center">
            <div class="w-2.5 h-2.5 sm:w-2 sm:h-2 rounded-full bg-primary"></div>
        </div>
    @endif
</div>
