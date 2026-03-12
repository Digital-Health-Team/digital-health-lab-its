<div class="space-y-8 pb-10">
    {{-- HEADER --}}
    <x-header title="Admin Dashboard" separator progress-indicator>
        <x-slot:middle class="!justify-start">
            <div class="text-gray-500">
                Hello, <span class="font-bold text-base-content">{{ auth()->user()->name }}</span>.
                Here's what's happening today.
            </div>
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Add Staff" icon="o-user-plus" link="{{ route('admin.users') }}" class="btn-outline btn-sm" />
        </x-slot:actions>
    </x-header>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat title="Total Staff" value="{{ $stats['total_staff'] }}" icon="o-users"
            class="bg-base-100 shadow-sm border-l-4 border-primary" color="text-primary" />
    </div>
</div>
