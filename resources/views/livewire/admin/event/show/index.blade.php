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

        {{-- TABS (Modern Pill Style) --}}
        <div class="flex gap-2 p-3 bg-base-200/50 border-b border-base-200 overflow-x-auto">
            <button
                wire:click="$set('activeTab', 'teams')"
                class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all cursor-pointer whitespace-nowrap {{ $activeTab === 'teams' ? 'bg-primary text-white shadow-md' : 'text-gray-500 hover:bg-base-200 hover:text-base-content' }}">
                <x-icon name="o-user-group" class="w-4 h-4" />
                {{ __('Teams') }}
                <span class="badge badge-sm border-none {{ $activeTab === 'teams' ? 'bg-white/20 text-white' : 'badge-neutral' }}">{{ $event->teams->count() }}</span>
            </button>

            <button
                wire:click="$set('activeTab', 'projects')"
                class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold transition-all cursor-pointer whitespace-nowrap {{ $activeTab === 'projects' ? 'bg-primary text-white shadow-md' : 'text-gray-500 hover:bg-base-200 hover:text-base-content' }}">
                <x-icon name="o-academic-cap" class="w-4 h-4" />
                {{ __('Projects / Karya') }}
                <span class="badge badge-sm border-none {{ $activeTab === 'projects' ? 'bg-white/20 text-white' : 'badge-neutral' }}">{{ $projects->count() }}</span>
            </button>
        </div>

        <div class="p-0 sm:p-6">

            {{-- TAB: TEAMS --}}
            @if($activeTab === 'teams')
                <div class="flex flex-col sm:flex-row justify-between items-center mb-4 px-4 sm:px-0 gap-4">
                    <h3 class="font-bold text-lg text-base-content">{{ __('Registered Teams') }}</h3>
                    <x-button label="{{ __('Add Team') }}" icon="o-plus" class="btn-sm btn-primary w-full sm:w-auto" wire:click="createTeam" />
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full text-sm">
                        <thead class="bg-base-200/50 text-gray-500">
                            <tr>
                                <th class="w-12 text-center">#</th>
                                <th>{{ __('Team Name') }}</th>
                                <th>{{ __('Course') }}</th>
                                <th>{{ __('Members') }}</th>
                                <th class="text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($event->teams as $team)
                                <tr class="hover:bg-base-200/50 transition-colors">
                                    <td class="text-center font-medium text-gray-400">{{ $loop->iteration }}</td>
                                    <td class="font-bold text-base-content">{{ $team->name }}</td>
                                    <td>{{ $team->course_name }}</td>
                                    <td>
                                        <div class="flex items-center gap-1 text-gray-500 font-medium">
                                            <x-icon name="o-users" class="w-4 h-4" />
                                            {{ $team->members->count() }}
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <x-button label="{{ __('Manage') }}" icon="o-arrow-right" link="{{ route('admin.teams.show', $team->id) }}" class="btn-sm btn-outline btn-primary" />
                                            <div class="w-px h-6 bg-base-300 mx-1"></div>
                                            <x-button icon="o-pencil-square" wire:click="editTeam({{ $team->id }})" class="btn-sm btn-circle btn-ghost text-blue-500 hover:bg-blue-50" tooltip="{{ __('Edit') }}" />
                                            <x-button icon="o-trash" wire:click="confirmDeleteTeam({{ $team->id }})" class="btn-sm btn-circle btn-ghost text-red-500 hover:bg-red-50" tooltip="{{ __('Delete') }}" />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-gray-400">
                                        <x-icon name="o-user-group" class="w-12 h-12 mb-3 opacity-30 mx-auto" />
                                        {{ __('No teams registered yet.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- TAB: PROJECTS --}}
            @if($activeTab === 'projects')
                <div class="px-4 sm:px-0 mb-4">
                    <h3 class="font-bold text-lg text-base-content">{{ __('Submitted Projects') }}</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full text-sm">
                        <thead class="bg-base-200/50 text-gray-500">
                            <tr>
                                <th class="w-12 text-center">#</th>
                                <th>{{ __('Project Title') }}</th>
                                <th>{{ __('Team') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                                <tr class="hover:bg-base-200/50 transition-colors">
                                    <td class="text-center font-medium text-gray-400">{{ $loop->iteration }}</td>
                                    <td class="font-bold text-primary">{{ $project->title }}</td>
                                    <td class="font-semibold">{{ $project->team->name }}</td>
                                    <td><span class="badge badge-outline text-xs">{{ $project->category }}</span></td>
                                    <td>
                                        <span class="badge {{ match($project->status) { 'approved' => 'badge-success', 'rejected' => 'badge-error', default => 'badge-warning' } }} text-white text-[10px] font-bold uppercase tracking-wider">
                                            {{ $project->status }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <x-button label="{{ __('Review') }}" icon="o-eye" link="{{ route('admin.teams.show', $project->team_id) }}" class="btn-sm btn-primary" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12 text-gray-400">
                                        <x-icon name="o-academic-cap" class="w-12 h-12 mb-3 opacity-30 mx-auto" />
                                        {{ __('No projects submitted yet.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </x-card>

    {{-- Modals for Team CRUD --}}
    <x-modal wire:model="teamModalOpen" title="{{ $editingTeamId ? __('Edit Team') : __('Add Team') }}" separator>
        <x-form wire:submit="saveTeam">
            <x-input label="{{ __('Team Name') }}" wire:model="teamName" required />
            <x-input label="{{ __('Course Name') }}" wire:model="teamCourseName" required />
            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.teamModalOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="saveTeam" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-modal wire:model="deleteTeamModalOpen" title="{{ __('Delete Team') }}" separator>
        <div class="py-4 text-base-content/80">
            {{ __('Are you sure you want to delete this team? Note: Cannot delete team with submitted projects.') }}
        </div>
        <x-slot:actions>
            <x-button label="{{ __('Cancel') }}" @click="$wire.deleteTeamModalOpen = false" class="btn-ghost" />
            <x-button label="{{ __('Yes, Delete') }}" class="btn-error" wire:click="deleteTeam" spinner="deleteTeam" />
        </x-slot:actions>
    </x-modal>
</div>
