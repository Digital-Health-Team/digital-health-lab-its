<div class="space-y-6 pb-10">
    {{-- HEADER --}}
    <x-header title="{{ $team->name }}" subtitle="{{ __('Course') }}: {{ $team->course_name }} &nbsp;•&nbsp; {{ __('Event') }}: {{ $team->event->name }}" separator>
        <x-slot:actions>
            <x-button icon="o-arrow-left" label="{{ __('Back to Event') }}" link="{{ route('admin.events.show', $team->event_id) }}" class="btn-ghost" />
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- BAGIAN 1: ANGGOTA TIM --}}
        <x-card title="{{ __('Team Members') }}" class="shadow-sm border border-base-200 bg-base-100 h-full flex flex-col">

            {{-- Form Tambah Anggota --}}
            <form wire:submit.prevent="addMember" class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-6 p-4 bg-base-200/30 rounded-xl border border-base-200">
                <div class="md:col-span-6">
                    <x-choices wire:model="selectedUserId" :options="$availableUsers" option-label="name" option-value="id" placeholder="{{ __('Select Student...') }}" single searchable />
                </div>
                <div class="md:col-span-4">
                    <x-input wire:model="roleInTeam" placeholder="{{ __('Role (e.g. Ketua)') }}" required />
                </div>
                <div class="md:col-span-2 flex items-end">
                    <x-button type="submit" icon="o-plus" class="btn-primary w-full" spinner="addMember" tooltip="{{ __('Add') }}" />
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead class="bg-base-200/50 text-gray-500">
                        <tr>
                            <th class="w-10 text-center">#</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th class="text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($team->members as $member)
                            <tr class="hover:bg-base-200/50 transition-colors">
                                <td class="text-center font-medium text-gray-400">{{ $loop->iteration }}</td>
                                <td class="font-bold text-base-content">{{ $member->profile?->full_name ?? $member->email }}</td>
                                <td><span class="badge badge-outline badge-sm">{{ $member->pivot->role_in_team }}</span></td>
                                <td class="text-right">
                                    <x-button icon="o-trash" wire:click="removeMember({{ $member->id }})" class="btn-xs btn-circle btn-ghost text-error hover:bg-error/10" tooltip="{{ __('Remove') }}" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-400">
                                    <x-icon name="o-users" class="w-10 h-10 mb-2 opacity-30 mx-auto" />
                                    {{ __('No members in this team yet.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        {{-- BAGIAN 2: KARYA PROJECT --}}
        <x-card title="{{ __('Project / Karya') }}" class="shadow-sm border border-base-200 bg-base-100 h-full flex flex-col">
            <x-slot:menu>
                <x-button label="{{ __('Add Project') }}" icon="o-plus" class="btn-sm btn-primary" wire:click="createProject" />
            </x-slot:menu>

            <div class="space-y-4">
                @forelse($team->projects as $project)
                    <div class="bg-base-100 p-5 rounded-xl border border-base-300 hover:border-primary/50 hover:shadow-md transition-all duration-300 relative group">

                        {{-- Quick Actions Hover --}}
                        <div class="absolute top-3 right-3 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <x-button icon="o-pencil-square" wire:click="editProject({{ $project->id }})" class="btn-xs btn-circle btn-ghost text-info bg-base-200" tooltip="{{ __('Edit') }}" />
                        </div>

                        <div class="font-black text-lg text-primary pr-8">{{ $project->title }}</div>
                        <div class="text-sm font-medium text-gray-500 mb-4">{{ __('Category') }}: <span class="badge badge-neutral badge-sm ml-1">{{ str_replace('_', ' ', $project->category) }}</span></div>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-4 pt-4 border-t border-base-200">
                            <div>
                                <span class="badge {{ match($project->status) { 'approved' => 'badge-success', 'rejected' => 'badge-error', default => 'badge-warning' } }} text-white font-bold uppercase tracking-wider text-[10px]">
                                    {{ $project->status }}
                                </span>
                            </div>

                            {{-- Tombol Verifikasi Admin (Diperbaiki) --}}
                            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                                @if($project->status !== 'approved')
                                    <x-button label="{{ __('Approve') }}" icon="o-check" wire:click="updateProjectStatus({{ $project->id }}, 'approved')" class="btn-sm btn-success text-white flex-1 sm:flex-none" />
                                @endif
                                @if($project->status !== 'rejected')
                                    {{-- Hapus text-white agar teksnya mengikuti warna border-error --}}
                                    <x-button label="{{ __('Reject') }}" icon="o-x-mark" wire:click="updateProjectStatus({{ $project->id }}, 'rejected')" class="btn-sm btn-outline btn-error flex-1 sm:flex-none hover:text-white" />
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400 bg-base-200/30 rounded-xl border-2 border-dashed border-base-300">
                        <x-icon name="o-academic-cap" class="w-12 h-12 mb-3 opacity-30 mx-auto" />
                        <p class="font-medium">{{ __('This team has not submitted a project yet.') }}</p>
                    </div>
                @endforelse
            </div>
        </x-card>
    </div>

    {{-- Modal Form Project --}}
    <x-modal wire:model="projectModalOpen" title="{{ $editingProjectId ? __('Edit Project') : __('Submit Project') }}" separator>
        <x-form wire:submit="saveProject">
            <x-input label="{{ __('Project Title') }}" wire:model="projTitle" required />
            <x-select label="{{ __('Category') }}" wire:model="projCategory" :options="[['id'=>'3d_model','name'=>'3D Model'], ['id'=>'iot_system','name'=>'IoT System'], ['id'=>'app','name'=>'Application']]" placeholder="{{ __('Select Category') }}" required />
            <x-slot:actions>
                <x-button label="{{ __('Cancel') }}" @click="$wire.projectModalOpen = false" class="btn-ghost" />
                <x-button label="{{ __('Save Project') }}" class="btn-primary" type="submit" spinner="saveProject" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
