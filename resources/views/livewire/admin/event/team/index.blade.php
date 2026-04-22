<div>
    <div class="mb-4">
        <x-button icon="o-arrow-left" label="{{ __('Back to Event') }}" link="{{ route('admin.events.show', $team->event_id) }}" class="btn-ghost btn-sm" />
    </div>

    <div class="mb-6">
        <h1 class="text-3xl font-black text-primary">{{ $team->name }}</h1>
        <p class="text-gray-500 font-medium">{{ __('Course') }}: {{ $team->course_name }} | {{ __('Event') }}: {{ $team->event->name }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- BAGIAN 1: ANGGOTA TIM --}}
        <x-card title="{{ __('Team Members') }}" class="shadow-sm border border-base-200">
            <form wire:submit.prevent="addMember" class="flex gap-2 mb-4 items-end">
                <div class="flex-1">
                    <x-choices wire:model="selectedUserId" :options="$availableUsers" option-label="name" option-value="id" placeholder="{{ __('Select Student') }}" single searchable />
                </div>
                <div class="flex-1">
                    <x-input wire:model="roleInTeam" placeholder="{{ __('Role (e.g. Ketua)') }}" required />
                </div>
                <x-button type="submit" icon="o-plus" class="btn-primary" spinner="addMember" />
            </form>

            <table class="table table-zebra w-full text-sm">
                <thead>
                    <tr><th>{{ __('Name') }}</th><th>{{ __('Role') }}</th><th class="text-right"></th></tr>
                </thead>
                <tbody>
                    @forelse($team->members as $member)
                        <tr>
                            <td class="font-bold">{{ $member->profile?->full_name ?? $member->email }}</td>
                            <td><span class="badge badge-outline">{{ $member->pivot->role_in_team }}</span></td>
                            <td class="text-right"><x-button icon="o-trash" wire:click="removeMember({{ $member->id }})" class="btn-xs btn-circle btn-ghost text-red-500" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-gray-400">{{ __('No members.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>

        {{-- BAGIAN 2: KARYA PROJECT --}}
        <x-card class="shadow-sm border border-base-200">
            <x-slot:title>
                <div class="flex justify-between items-center">
                    <span>{{ __('Project / Karya') }}</span>
                    <x-button label="{{ __('Add Project') }}" icon="o-plus" class="btn-sm btn-primary" wire:click="createProject" />
                </div>
            </x-slot:title>

            @forelse($team->projects as $project)
                <div class="bg-base-200/50 p-4 rounded-xl border border-base-300 mb-4 relative group">
                    <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <x-button icon="o-pencil-square" wire:click="editProject({{ $project->id }})" class="btn-xs btn-circle btn-ghost text-blue-500" />
                    </div>

                    <div class="font-black text-lg text-primary">{{ $project->title }}</div>
                    <div class="text-sm font-medium text-gray-500 mb-3">{{ __('Category') }}: {{ $project->category }}</div>

                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-base-300">
                        <div>
                            <span class="badge {{ match($project->status) { 'approved' => 'badge-success', 'rejected' => 'badge-error', default => 'badge-warning' } }} text-white">
                                {{ strtoupper($project->status) }}
                            </span>
                        </div>

                        {{-- Tombol Verifikasi Admin --}}
                        <div class="flex gap-2">
                            @if($project->status !== 'approved')
                                <x-button label="{{ __('Approve') }}" icon="o-check" wire:click="updateProjectStatus({{ $project->id }}, 'approved')" class="btn-sm btn-success text-white" />
                            @endif
                            @if($project->status !== 'rejected')
                                <x-button label="{{ __('Reject') }}" icon="o-x-mark" wire:click="updateProjectStatus({{ $project->id }}, 'rejected')" class="btn-sm btn-error text-white btn-outline" />
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-400 border-2 border-dashed border-base-300 rounded-xl">
                    <x-icon name="o-academic-cap" class="w-10 h-10 mb-2 opacity-50" />
                    <p>{{ __('This team has not submitted a project yet.') }}</p>
                </div>
            @endforelse
        </x-card>
    </div>

    {{-- Modal Form Project --}}
    <x-modal wire:model="projectModalOpen" title="{{ $editingProjectId ? __('Edit Project') : __('Submit Project') }}">
        <x-form wire:submit="saveProject">
            <x-input label="{{ __('Project Title') }}" wire:model="projTitle" required />
            <x-select label="{{ __('Category') }}" wire:model="projCategory" :options="[['id'=>'3d_model','name'=>'3D Model'], ['id'=>'iot_system','name'=>'IoT System'], ['id'=>'app','name'=>'Application']]" placeholder="{{ __('Select Category') }}" required />
            <x-slot:actions><x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="saveProject" /></x-slot:actions>
        </x-form>
    </x-modal>
</div>
