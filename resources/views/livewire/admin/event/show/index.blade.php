<div>
    <div class="mb-4">
        <x-button icon="o-arrow-left" label="{{ __('Back to Events') }}" link="{{ route('admin.events') }}" class="btn-ghost btn-sm" />
    </div>

    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl font-black text-primary">{{ $event->name }} ({{ $event->year }})</h1>
            <p class="text-gray-500 font-medium italic">"{{ $event->theme_title }}"</p>
        </div>
    </div>

    <x-card class="p-0 overflow-hidden shadow-sm border border-base-200">
        {{-- TABS --}}
        <div class="flex border-b border-base-200 px-2 bg-base-100">
            <button wire:click="$set('activeTab', 'teams')" class="px-6 py-4 text-sm font-semibold border-b-2 {{ $activeTab === 'teams' ? 'border-primary text-primary' : 'border-transparent text-gray-500' }}">
                <x-icon name="o-user-group" class="w-4 h-4 inline mr-1" /> {{ __('Teams') }} ({{ $event->teams->count() }})
            </button>
            <button wire:click="$set('activeTab', 'projects')" class="px-6 py-4 text-sm font-semibold border-b-2 {{ $activeTab === 'projects' ? 'border-primary text-primary' : 'border-transparent text-gray-500' }}">
                <x-icon name="o-academic-cap" class="w-4 h-4 inline mr-1" /> {{ __('Projects / Karya') }} ({{ $projects->count() }})
            </button>
        </div>

        <div class="p-6">
            @if($activeTab === 'teams')
                <div class="flex justify-between mb-4">
                    <h3 class="font-bold text-lg">{{ __('Registered Teams') }}</h3>
                    <x-button label="{{ __('Add Team') }}" icon="o-plus" class="btn-sm btn-primary" wire:click="createTeam" />
                </div>
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>{{ __('Team Name') }}</th>
                            <th>{{ __('Course') }}</th>
                            <th>{{ __('Members') }}</th>
                            <th class="text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->teams as $team)
                            <tr>
                                <td class="font-bold">{{ $team->name }}</td>
                                <td>{{ $team->course_name }}</td>
                                <td><span class="badge">{{ $team->members->count() }}</span></td>
                                <td class="text-right">
                                    <x-button label="{{ __('Manage Team') }}" icon="o-arrow-right" link="{{ route('admin.teams.show', $team->id) }}" class="btn-sm btn-outline btn-primary" />
                                    <x-button icon="o-pencil-square" wire:click="editTeam({{ $team->id }})" class="btn-sm btn-ghost text-blue-500" />
                                    <x-button icon="o-trash" wire:click="confirmDeleteTeam({{ $team->id }})" class="btn-sm btn-ghost text-red-500" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if($activeTab === 'projects')
                <h3 class="font-bold text-lg mb-4">{{ __('Submitted Projects') }}</h3>
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>{{ __('Project Title') }}</th>
                            <th>{{ __('Team') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td class="font-bold text-primary">{{ $project->title }}</td>
                                <td class="font-semibold">{{ $project->team->name }}</td>
                                <td><span class="badge badge-outline">{{ $project->category }}</span></td>
                                <td>
                                    <span class="badge {{ match($project->status) { 'approved' => 'badge-success', 'rejected' => 'badge-error', default => 'badge-warning' } }} text-white">
                                        {{ strtoupper($project->status) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    {{-- Mengarah langsung ke detail Team --}}
                                    <x-button label="{{ __('Review in Team') }}" icon="o-eye" link="{{ route('admin.teams.show', $project->team_id) }}" class="btn-sm btn-primary" />
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-gray-500">{{ __('No projects submitted yet.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </x-card>

    {{-- Modals for Team CRUD --}}
    <x-modal wire:model="teamModalOpen" title="{{ $editingTeamId ? __('Edit Team') : __('Add Team') }}">
        <x-form wire:submit="saveTeam">
            <x-input label="{{ __('Team Name') }}" wire:model="teamName" required />
            <x-input label="{{ __('Course Name') }}" wire:model="teamCourseName" required />
            <x-slot:actions><x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="saveTeam" /></x-slot:actions>
        </x-form>
    </x-modal>

    <x-modal wire:model="deleteTeamModalOpen" title="{{ __('Delete Team') }}">
        {{ __('Cannot delete team with submitted projects.') }}
        <x-slot:actions><x-button label="{{ __('Delete') }}" class="btn-error" wire:click="deleteTeam" /></x-slot:actions>
    </x-modal>
</div>
