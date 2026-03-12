<div class="space-y-8 pb-20">

    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-black mb-2">Global Search</h1>
        <div class="flex items-center gap-2 text-gray-500">
            <x-icon name="o-magnifying-glass" class="w-5 h-5" />
            <span>Results for: <span class="font-bold text-base-content">"{{ $search }}"</span></span>
        </div>
    </div>

    {{-- Main Search Input (In-Page) --}}
    <div class="bg-base-100 p-4 rounded-xl border border-base-200 shadow-sm">
        <x-input icon="o-magnifying-glass" placeholder="Type to search users, projects, tasks, logs..."
            wire:model.live.debounce.300ms="search" class="w-full" autofocus />
    </div>

    @if ($search == '')
        <div class="text-center py-20 opacity-50">
            <x-icon name="o-magnifying-glass" class="w-16 h-16 mx-auto mb-4 opacity-20" />
            <p>Start typing to search across the system.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-8">

            {{-- ========================================================= --}}
            {{-- 1. USERS SECTION                                          --}}
            {{-- ========================================================= --}}
            <div class="space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h2 class="font-bold flex items-center gap-2">
                        <x-icon name="o-users" class="w-5 h-5 text-primary" /> Users ({{ $users->count() }})
                    </h2>
                    {{-- Filter Role --}}
                    <select wire:model.live="filterUserRole" class="select select-xs select-bordered">
                        <option value="">All Roles</option>
                        <option value="staff">Staff</option>
                        <option value="pm">PM</option>
                        <option value="super_admin">Admin</option>
                    </select>
                </div>

                @forelse($users as $user)
                    <div
                        class="flex items-center justify-between p-3 bg-base-100 rounded-lg border border-base-200 hover:border-primary transition group">
                        <div class="flex items-center gap-3">
                            <x-avatar :image="$user->profile_photo ? asset('storage/' . $user->profile_photo) : null" class="w-10 h-10" />
                            <div>
                                <div class="font-bold text-sm">{{ $user->name }}</div>
                                <div class="text-xs opacity-60 uppercase">{{ $user->role }}</div>
                            </div>
                        </div>
                        {{-- BUTTON DETAIL --}}
                        <x-button icon="o-eye" class="btn-xs btn-square btn-ghost text-primary"
                            link="{{ route('admin.staff.show', $user->id) }}" tooltip="View Profile" />
                    </div>
                @empty
                    <div class="text-xs opacity-50 italic py-2">No users found.</div>
                @endforelse
            </div>

        </div>
    @endif
</div>
