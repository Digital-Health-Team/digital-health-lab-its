<div>
    <x-modal wire:model="modalOpen" class="backdrop-blur-sm" box-class="w-11/12 lg:w-3/5 max-w-5xl">

        @if($team)
            <div class="mb-6">
                <h2 class="text-2xl font-black text-primary">{{ __('Manage Team Members') }}</h2>
                <p class="text-gray-500 font-medium">{{ __('Team') }}: <span class="text-base-content">{{ $team->name }}</span> | {{ __('Course') }}: {{ $team->course_name }}</p>
            </div>

            {{-- FORM TAMBAH ANGGOTA --}}
            <x-card class="bg-base-200/50 border border-base-300 shadow-none mb-6">
                <form wire:submit.prevent="addMember" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full">
                        <x-choices
                            label="{{ __('Select Student / User') }}"
                            wire:model="selectedUserId"
                            :options="$availableUsers"
                            option-label="name"
                            option-value="id"
                            single
                            searchable
                            placeholder="{{ __('Search user...') }}"
                        />
                    </div>

                    <div class="flex-1 w-full">
                        <x-input label="{{ __('Role in Team') }}" wire:model="roleInTeam" placeholder="e.g., Ketua, 3D Modeler, Programmer" required />
                    </div>

                    <div class="w-full md:w-auto">
                        <x-button type="submit" label="{{ __('Add Member') }}" icon="o-plus" class="btn-primary w-full" spinner="addMember" />
                    </div>
                </form>
            </x-card>

            {{-- TABEL ANGGOTA SAAT INI --}}
            <div class="overflow-x-auto border border-base-200 rounded-xl">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th class="w-12 text-center text-xs uppercase text-gray-500">#</th>
                            <th class="text-xs uppercase text-gray-500">{{ __('Name') }}</th>
                            <th class="text-xs uppercase text-gray-500">{{ __('Department / Univ') }}</th>
                            <th class="text-xs uppercase text-gray-500">{{ __('Team Role') }}</th>
                            <th class="text-right text-xs uppercase text-gray-500">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($team->members as $member)
                            <tr wire:key="member-{{ $member->id }}">
                                <td class="text-center text-gray-400 text-sm">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="font-bold">{{ $member->profile?->full_name ?? $member->email }}</div>
                                    <div class="text-[10px] font-mono text-gray-500">{{ $member->profile?->nim ?? $member->profile?->nik ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="text-sm font-medium">{{ $member->profile?->university ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $member->profile?->department ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-primary badge-outline font-semibold uppercase tracking-wider text-[10px]">
                                        {{ $member->pivot->role_in_team }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <x-button icon="o-trash" wire:click="removeMember({{ $member->id }})" class="btn-sm btn-circle btn-ghost text-red-500 hover:bg-red-50" tooltip="{{ __('Remove') }}" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-400">
                                    <x-icon name="o-users" class="w-10 h-10 mb-2 opacity-30 mx-auto" />
                                    {{ __('No members in this team yet.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <x-slot:actions>
            <x-button label="{{ __('Done') }}" @click="$wire.modalOpen = false" class="btn-neutral" />
        </x-slot:actions>
    </x-modal>
</div>
