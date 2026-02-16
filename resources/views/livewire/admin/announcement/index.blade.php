<div>
    {{-- HEADER --}}
    <x-header title="Announcements" subtitle="Manage news & updates" separator progress-indicator>
        <x-slot:middle class="!justify-end gap-2">
            <x-input icon="o-magnifying-glass" placeholder="Search..." wire:model.live.debounce.250ms="search" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="New Announcement" icon="o-plus" class="btn-primary" wire:click="create" />
        </x-slot:actions>
    </x-header>

    {{-- TABLE --}}
    <x-card class="bg-base-100 shadow-sm">
        <x-table :headers="[
            ['key' => 'title', 'label' => 'Title / Content'],
            ['key' => 'recipients', 'label' => 'Target Audience'],
            ['key' => 'is_published', 'label' => 'Status'],
            ['key' => 'created_at', 'label' => 'Date'],
            ['key' => 'actions', 'label' => '', 'sortable' => false],
        ]" :rows="$announcements" with-pagination>

            {{-- TITLE & CONTENT --}}
            @scope('cell_title', $item)
                <div class="flex flex-col">
                    <span class="font-bold text-base-content">{{ $item->title }}</span>
                    <span class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($item->content, 60) }}</span>
                </div>
            @endscope

            {{-- RECIPIENTS LOGIC --}}
            @scope('cell_recipients', $item)
                @if ($item->is_global)
                    {{-- Tampilan Global --}}
                    <span class="badge badge-info badge-sm gap-1 text-white font-bold">
                        <x-icon name="o-globe-alt" class="w-3 h-3" /> All Users
                    </span>
                @else
                    {{-- Tampilan Spesifik --}}
                    @if ($item->recipients->count() > 0)
                        <div class="flex items-center gap-2">
                            <div class="avatar-group -space-x-4 rtl:space-x-reverse">
                                @foreach ($item->recipients->take(3) as $user)
                                    <div class="avatar border-none">
                                        <div class="w-8">
                                            <img
                                                src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" />
                                        </div>
                                    </div>
                                @endforeach
                                @if ($item->recipients->count() > 3)
                                    <div class="avatar placeholder border-none">
                                        <div class="w-8 bg-neutral text-neutral-content">
                                            <span class="text-xs">+{{ $item->recipients->count() - 3 }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">({{ $item->recipients->count() }})</span>
                        </div>
                    @else
                        <span class="badge badge-ghost badge-sm text-xs italic">No recipients</span>
                    @endif
                @endif
            @endscope

            {{-- STATUS --}}
            @scope('cell_is_published', $item)
                @if ($item->is_published)
                    <span class="badge badge-success badge-sm gap-1">
                        <x-icon name="o-check" class="w-3 h-3" /> Published
                    </span>
                @else
                    <span class="badge badge-warning badge-sm gap-1">
                        <x-icon name="o-pencil" class="w-3 h-3" /> Draft
                    </span>
                @endif
            @endscope

            {{-- DATE --}}
            @scope('cell_created_at', $item)
                <div class="flex flex-col text-xs">
                    <span class="font-bold">{{ $item->created_at->format('d M Y') }}</span>
                    <span class="text-gray-400">{{ $item->created_at->format('H:i') }}</span>
                    <span class="text-[10px] text-gray-400">by {{ $item->creator->name ?? 'System' }}</span>
                </div>
            @endscope

            {{-- ACTIONS --}}
            @scope('cell_actions', $item)
                <div class="flex justify-end gap-1">
                    <x-button icon="o-pencil" wire:click="edit({{ $item->id }})"
                        class="btn-xs btn-square btn-ghost text-blue-600" />
                    <x-button icon="o-trash" wire:click="confirmDelete({{ $item->id }})"
                        class="btn-xs btn-square btn-ghost text-error" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- MODAL CREATE / EDIT --}}
    <x-modal wire:model="modalOpen" :title="$editingId ? 'Edit Announcement' : 'New Announcement'" separator>
        <x-form wire:submit="save">

            <div class="space-y-5">
                {{-- Judul --}}
                <x-input label="Title" wire:model="title" placeholder="e.g. System Maintenance" />

                {{-- Opsi Global --}}
                <div class="bg-base-200 p-3 rounded-lg border border-base-300 flex justify-between items-center">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold">Send to All Users?</span>
                        <span class="text-xs text-gray-500">If enabled, everyone will receive this notification.</span>
                    </div>
                    <x-toggle wire:model.live="is_global" class="toggle-success" />
                </div>

                {{-- Target Audience (Muncul hanya jika TIDAK Global) --}}
                @if (!$is_global)
                    <div x-transition>
                        <x-choices label="Specific Audience" wire:model="recipient_ids" :options="$usersList"
                            option-label="name" option-value="id" icon="o-users" searchable
                            placeholder="Select users..." hint="Select one or more users." />
                    </div>
                @endif

                {{-- Status Publish --}}
                <div class="flex items-center justify-between bg-base-200 p-3 rounded-lg border border-base-300">
                    <span class="text-sm font-bold">Publish Immediately?</span>
                    <x-toggle wire:model="is_published" class="toggle-primary" />
                </div>

                {{-- Konten --}}
                <x-textarea label="Content" wire:model="content" placeholder="Write the announcement details here..."
                    rows="5" />
            </div>

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.modalOpen = false" />
                <x-button label="Save" class="btn-primary" type="submit" spinner="save" icon="o-check" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    {{-- MODAL DELETE --}}
    <x-modal-confirm wire:model="deleteModalOpen" method="delete" title="Delete Announcement?"
        text="This action cannot be undone." confirm-text="Delete" />
</div>
