<div>
    {{-- 1. DESKTOP VIEW (DROPDOWN) --}}
    <div class="hidden sm:block">
        <x-dropdown right no-x-anchor class="w-[350px] min-w-[350px] max-w-[400px] overflow-hidden rounded-xl shadow-lg">
            <x-slot:trigger>
                <div class="relative btn btn-circle btn-ghost btn-sm">
                    <x-icon name="o-bell" class="w-5 h-5" />
                    @if ($unreadCount > 0)
                        <span
                            class="absolute top-0 right-0 badge badge-error badge-xs text-white indicator-item animate-pulse">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </div>
            </x-slot:trigger>

            <div
                class="px-4 py-3 border-b border-base-200 flex justify-between items-center bg-base-100 sticky top-0 z-10">
                <h3 class="font-bold text-sm">Notifications</h3>
                @if ($unreadCount > 0)
                    <button wire:click.stop="markAllAsRead"
                        class="text-[10px] text-primary hover:underline font-bold">Mark all as read</button>
                @endif
            </div>

            <div class="max-h-[400px] overflow-y-auto custom-scrollbar bg-base-100">
                @forelse ($notifications as $notification)
                    @include('livewire.includes.notification-item', [
                        'notification' => $notification,
                        'view' => 'desktop',
                    ])
                @empty
                    <div class="py-10 text-center flex flex-col items-center opacity-50">
                        <x-icon name="o-bell-slash" class="w-8 h-8 mb-2 opacity-40" />
                        <p class="text-xs">No notifications yet.</p>
                    </div>
                @endforelse
            </div>
        </x-dropdown>
    </div>

    {{-- 2. MOBILE VIEW (BOTTOM MODAL) --}}
    <div class="block sm:hidden">
        <button onclick="mobile_notif_modal.showModal()" class="relative btn btn-circle btn-ghost btn-sm">
            <x-icon name="o-bell" class="w-5 h-5" />
            @if ($unreadCount > 0)
                <span class="absolute top-0 right-0 badge badge-error badge-xs text-white indicator-item animate-pulse">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            @endif
        </button>

        <dialog id="mobile_notif_modal" class="modal modal-bottom" wire:ignore.self>
            <div class="modal-box p-0 max-h-[85vh] flex flex-col rounded-t-2xl">
                <div
                    class="px-5 py-4 border-b border-base-200 flex justify-between items-center bg-base-100 sticky top-0 z-10">
                    <h3 class="font-bold text-base">Notifications</h3>
                    <div class="flex items-center gap-4">
                        @if ($unreadCount > 0)
                            <button wire:click.stop="markAllAsRead" class="text-xs text-primary font-bold">Mark all
                                read</button>
                        @endif
                        <form method="dialog"><button
                                class="btn btn-circle btn-ghost btn-sm text-base-content/50"><x-icon name="o-x-mark"
                                    class="w-5 h-5" /></button></form>
                    </div>
                </div>

                <div class="overflow-y-auto bg-base-100 pb-safe">
                    @forelse ($notifications as $notification)
                        @include('livewire.includes.notification-item', [
                            'notification' => $notification,
                            'view' => 'mobile',
                        ])
                    @empty
                        <div class="py-16 text-center flex flex-col items-center opacity-50">
                            <x-icon name="o-bell-slash" class="w-10 h-10 mb-3 opacity-40" />
                            <p class="text-sm">No notifications yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <form method="dialog" class="modal-backdrop"><button>close</button></form>
        </dialog>
    </div>

    {{-- 3. WELCOME MODAL (AUTO OPEN ON NEW LOGIN) --}}
    @if ($showWelcomeModal)
        <dialog id="welcome_notif_modal" class="modal modal-bottom sm:modal-middle" wire:ignore.self>
            <div class="modal-box p-0 max-h-[85vh] flex flex-col sm:max-w-lg rounded-t-2xl sm:rounded-2xl">
                <div
                    class="px-5 py-4 border-b border-base-200 flex justify-between items-start bg-base-100 sticky top-0 z-10">
                    <div>
                        <h3 class="font-black text-lg text-primary flex items-center gap-2">Welcome Back! 👋</h3>
                        <p class="text-xs opacity-70 mt-1">You have <b class="text-error">{{ $unreadCount }} pending
                                updates</b>.</p>
                    </div>
                    <form method="dialog"><button class="btn btn-circle btn-ghost btn-sm text-base-content/50"
                            wire:click="$set('showWelcomeModal', false)"><x-icon name="o-x-mark"
                                class="w-5 h-5" /></button></form>
                </div>
                <div class="overflow-y-auto bg-base-100">
                    @foreach ($notifications as $notification)
                        @include('livewire.includes.notification-item', [
                            'notification' => $notification,
                            'view' => 'welcome',
                        ])
                    @endforeach
                </div>
                <div
                    class="p-4 border-t border-base-200 bg-base-100 flex justify-between items-center sticky bottom-0 z-10 pb-safe">
                    <button wire:click="markAllAsRead" class="text-xs text-primary font-bold hover:underline">Mark all
                        as read</button>
                    <form method="dialog"><button class="btn btn-primary btn-sm"
                            wire:click="$set('showWelcomeModal', false)">Got it!</button></form>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop"><button
                    wire:click="$set('showWelcomeModal', false)">close</button></form>
        </dialog>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    document.getElementById('welcome_notif_modal')?.showModal();
                }, 300);
            });
        </script>
    @endif

    {{-- 4. PUSHER & AUDIO JAVASCRIPT --}}
    <audio id="notifSound" src="{{ asset('assets/sounds/notification.mp3') }}" preload="auto"></audio>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }

            if (typeof window.Echo !== 'undefined') {
                const userId = {{ auth()->id() }};
                window.Echo.private(`App.Models.User.${userId}`)
                    .notification((notification) => {
                        // A. Audio (Browser butuh user interaksi (klik/tap web) 1x dulu agar ini jalan)
                        const sound = document.getElementById('notifSound');
                        if (sound) {
                            sound.currentTime = 0;
                            sound.play().catch(e => console.warn(
                                "Klik layar 1x agar suara notifikasi bisa diputar."));
                        }

                        // B. OS Notification
                        if ('Notification' in window && Notification.permission === 'granted') {
                            const nav = new Notification(notification.title || 'Pemberitahuan Baru', {
                                body: notification.message || 'Anda mendapatkan pembaruan tugas.',
                                icon: '/assets/images/logo_idig_htech.png',
                            });
                            nav.onclick = function(event) {
                                event.preventDefault();
                                window.focus();
                                if (notification.url) window.location.href = notification.url;
                                nav.close();
                            };
                        }
                    });
            }
        });
    </script>
</div>
