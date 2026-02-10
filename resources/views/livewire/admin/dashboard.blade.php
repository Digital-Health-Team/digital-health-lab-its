<div class="space-y-8">
    {{-- HEADER --}}
    <x-header title="{{ __('Dashboard Admin') }}" separator progress-indicator>
        <x-slot:middle class="!justify-start">
            <div class="text-gray-500">
                {{ __('Hello Admin') }}, <span class="font-bold text-gray-800">{{ auth()->user()->name }}</span>.
                {{ __('Here is the summary of activities today.') }}
            </div>
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="{{ __('New Project') }}" icon="o-plus" link="#" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    {{-- STATS CARDS --}}
    {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat title="{{ __('Total Staff') }}" value="{{ $stats['total_users'] }}" icon="o-users"
            class="bg-base-100 shadow-sm border-l-4 border-primary" description="{{ __('Registered accounts') }}" />

        <x-stat title="{{ __('Active Projects') }}" value="{{ $stats['active_projects'] }}" icon="o-briefcase"
            class="bg-base-100 shadow-sm border-l-4 border-info" description="{{ __('Running now') }}" />

        <x-stat title="{{ __('Attendance Today') }}" value="{{ $stats['attendance_today'] }}" icon="o-clock"
            class="bg-base-100 shadow-sm border-l-4 border-success" description="{{ __('Staff Checked-in') }}" />

        <x-stat title="{{ __('Pending Revisions') }}" value="{{ $stats['pending_revisions'] }}"
            icon="o-exclamation-triangle" class="bg-base-100 shadow-sm border-l-4 border-warning"
            description="{{ __('Needs attention') }}" />
    </div> --}}

    {{-- CONTENT GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- LEFT COLUMN --}}
        {{-- <div class="lg:col-span-2 space-y-8">
            <x-card title="{{ __('Urgent Project Deadlines') }}" separator>
                @forelse($urgentProjects as $project)
                @empty
                    <div class="text-center text-gray-500 py-4">{{ __('No urgent projects.') }}</div>
                @endforelse

                <x-slot:actions>
                    <x-button label="{{ __('View All') }}" link="#" class="btn-ghost btn-sm" />
                </x-slot:actions>
            </x-card>
        </div> --}}

        {{-- RIGHT COLUMN --}}
        {{-- <div class="space-y-8">
            <x-card title="{{ __('Recent Users') }}" separator>
            </x-card>

            <x-card title="{{ __('Quick Menu') }}" class="bg-base-200/50">
                <div class="grid grid-cols-2 gap-2">
                    <x-button label="{{ __('Approval') }}" icon="o-check-badge"
                        class="btn-sm bg-white shadow-sm border-0" link="#" />
                    <x-button label="{{ __('Reports') }}" icon="o-chart-bar" class="btn-sm bg-white shadow-sm border-0"
                        link="#" />
                </div>
            </x-card>
        </div> --}}
    </div>
</div>
