{{-- X-DATA ALPINEJS UNTUK AUTO-SAVE LOCAL STORAGE & MODAL --}}
<div class="h-auto min-h-[calc(100vh-6rem)] flex flex-col pb-10" x-data="{
    storageKey: 'roadmap_project_{{ $project->slug }}',
    draftModalOpen: false, // State untuk modal konfirmasi
    init() {
        // 1. Saat load, cek apakah ada draft tersimpan di Local Storage
        let saved = localStorage.getItem(this.storageKey);
        if (saved) {
            let parsed = JSON.parse(saved);
            // Sinkronisasi data ke state Livewire $staffTasks
            if (Object.keys(parsed).length > 0) {
                this.$wire.set('staffTasks', parsed);
            }
        }

        // 2. Watcher: Setiap kali $staffTasks berubah di Livewire, simpan ke Local Storage
        this.$watch('$wire.staffTasks', value => {
            localStorage.setItem(this.storageKey, JSON.stringify(value));
        });
    },
    clearDraft() {
        // Eksekusi penghapusan
        this.$wire.set('staffTasks', []);
        localStorage.removeItem(this.storageKey);
        this.draftModalOpen = false; // Tutup modal setelah dihapus
    }
}" {{-- Event Listener saat sukses disimpan ke DB --}}
    @clear-roadmap-storage.window="
        localStorage.removeItem(storageKey);
        setTimeout(() => { window.location.href = $event.detail.url; }, 500);
    ">

    @php
        $pName = is_array($project->name) ? $project->name['id'] ?? $project->name['en'] : $project->name;
    @endphp

    {{-- STICKY HEADER --}}
    <div
        class="sticky top-0 z-50 bg-base-100/95 dark:bg-gray-900/95 backdrop-blur-xl py-4 px-4 md:px-8 border-b border-base-200 dark:border-gray-800 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm">
        <div>
            <a href="{{ route('admin.projects.show', $project->slug) }}"
                class="btn btn-ghost btn-sm pl-0 gap-2 text-gray-500 hover:bg-transparent mb-1">
                <x-icon name="o-arrow-left" class="w-4 h-4" /> Back to Project
            </a>
            <h1 class="text-2xl md:text-3xl font-black dark:text-white flex items-center gap-3">
                Roadmap Builder
            </h1>
            <p class="text-sm text-gray-500 mt-1">Assign tasks to multiple staff for <b
                    class="text-primary">{{ $pName }}</b>.</p>
        </div>

        <div class="flex gap-2 w-full md:w-auto items-center">
            {{-- Tombol Trigger Modal Hapus Draft --}}
            <button type="button" @click="draftModalOpen = true"
                class="btn btn-ghost btn-sm text-error hidden md:flex hover:bg-error/20">
                Clear Draft
            </button>

            <x-button label="Cancel" link="{{ route('admin.projects.show', $project->slug) }}"
                class="flex-1 md:flex-none btn-outline" />
            <x-button label="Save & Publish" icon="o-paper-airplane"
                class="btn-primary flex-1 md:flex-none shadow-lg shadow-primary/30" wire:click="saveRoadmap"
                spinner="saveRoadmap" />
        </div>
    </div>

    {{-- KONTEN UTAMA DENGAN PADDING --}}
    <div class="px-4 md:px-8 flex-1 flex flex-col min-h-0">

        {{-- ERROR VALIDATION MESSAGES --}}
        @if ($errors->any())
            <div class="alert alert-error text-sm text-white shadow-sm rounded-xl mb-6">
                <x-icon name="o-exclamation-triangle" class="w-5 h-5 shrink-0" />
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- STAFF SELECTOR (SEARCH BAR) --}}
        <div
            class="bg-base-200/50 dark:bg-gray-800/50 border border-base-300 dark:border-gray-700 p-4 rounded-xl mb-6 flex flex-col sm:flex-row gap-4 items-end shadow-sm max-w-3xl">
            <div class="flex-1 w-full">
                <x-choices label="Search & Add Staff to Roadmap" wire:model="selectedStaffId" :options="$staffList"
                    option-label="name" option-value="id" single searchable search-function="searchStaff"
                    placeholder="Type name to search staff..." icon="o-magnifying-glass" class="dark:bg-gray-900" />
            </div>
            <x-button label="Add Staff Column" icon="o-plus" class="btn-primary w-full sm:w-auto mt-2 sm:mt-0"
                wire:click="addStaffSection" spinner="addStaffSection" />
        </div>

        {{-- KANBAN BOARD CONTAINER (SCROLL MENYAMPING) --}}
        <div class="flex-1 overflow-x-auto overflow-y-hidden custom-scrollbar pb-6">
            <div class="flex items-start gap-6 h-full min-w-max">

                @forelse($staffTasks as $staffId => $tasks)
                    @php
                        // Cari data detail staff (gunakan fallback jika staff tidak ditemukan di list live)
                        $staff =
                            collect($staffList)->firstWhere('id', $staffId) ??
                            \App\Models\User::find($staffId)->toArray();
                    @endphp

                    {{-- STAFF COLUMN (LEBAR TETAP AGAR TIDAK MENGGENCET) --}}
                    <div class="w-[350px] shrink-0 bg-base-200/40 dark:bg-gray-800/50 border border-base-300 dark:border-gray-700 rounded-2xl flex flex-col max-h-[70vh] shadow-sm"
                        wire:key="staff-col-{{ $staffId }}">

                        {{-- Column Header --}}
                        <div
                            class="p-4 bg-base-100 dark:bg-gray-800 border-b border-base-200 dark:border-gray-700 rounded-t-2xl flex justify-between items-center shrink-0">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <x-avatar :image="!empty($staff['profile_photo'])
                                    ? asset('storage/' . $staff['profile_photo'])
                                    : null"
                                    class="w-10 h-10 border border-base-300 dark:border-gray-600 shadow-sm shrink-0" />
                                <div class="flex flex-col min-w-0">
                                    <h2 class="font-bold text-sm dark:text-gray-100 truncate w-full"
                                        title="{{ $staff['name'] ?? 'Unknown' }}">{{ $staff['name'] ?? 'Unknown' }}</h2>
                                    <div class="text-[10px] text-gray-500">{{ count($tasks) }} Tasks</div>
                                </div>
                            </div>

                            {{-- Delete Column --}}
                            <button type="button" wire:click="removeStaffSection({{ $staffId }})"
                                class="btn btn-xs btn-circle btn-ghost text-error shrink-0" tooltip="Remove Staff">
                                <x-icon name="o-trash" class="w-4 h-4" />
                            </button>
                        </div>

                        {{-- Column Body (Daftar Tugas BISA DI-SCROLL KE BAWAH) --}}
                        <div class="p-3 space-y-3 flex-1 overflow-y-auto custom-scrollbar">
                            @foreach ($tasks as $index => $task)
                                {{-- TASK CARD --}}
                                <div class="bg-base-100 dark:bg-gray-900 border border-base-200 dark:border-gray-700 p-4 rounded-xl shadow-sm relative group hover:border-primary/50 transition-colors"
                                    wire:key="staff-{{ $staffId }}-task-{{ $index }}">

                                    {{-- Task Number & Remove Button --}}
                                    <div class="flex justify-between items-center mb-3">
                                        <div
                                            class="badge badge-primary badge-sm text-[10px] font-bold text-white shadow-sm">
                                            Task #{{ $index + 1 }}</div>

                                        <button type="button"
                                            wire:click="removeTaskForStaff({{ $staffId }}, {{ $index }})"
                                            class="text-error opacity-40 hover:opacity-100 transition"
                                            tooltip="Delete Task">
                                            <x-icon name="o-x-mark" class="w-4 h-4" />
                                        </button>
                                    </div>

                                    {{-- Task Inputs --}}
                                    <div class="space-y-3">
                                        <x-input label="Task Title"
                                            wire:model="staffTasks.{{ $staffId }}.{{ $index }}.title.id"
                                            placeholder="Ex: Update Homepage UI..."
                                            class="font-bold border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus:bg-white input-sm text-sm"
                                            required />

                                        <x-datetime label="Deadline"
                                            wire:model="staffTasks.{{ $staffId }}.{{ $index }}.deadline_task"
                                            type="datetime-local" icon="o-calendar"
                                            class="input-sm border-gray-300 dark:border-gray-700" />

                                        {{-- Optional Details Accordion --}}
                                        <div x-data="{ open: false }">
                                            <button type="button" @click="open = !open"
                                                class="text-[10px] uppercase font-bold text-gray-500 hover:text-primary transition flex items-center justify-center gap-1 mt-2 w-full bg-base-200/50 dark:bg-gray-800 border border-dashed border-base-300 py-1.5 rounded-lg">
                                                <x-icon name="o-chevron-down" class="w-3 h-3 transition-transform"
                                                    x-bind:class="open ? 'rotate-180' : ''" />
                                                <span
                                                    x-text="open ? 'Hide Descriptions' : 'Add Description (Optional)'"></span>
                                            </button>

                                            <div x-show="open" x-collapse>
                                                <div class="pt-3 space-y-3 mt-1">
                                                    <x-input label="Title (English)"
                                                        wire:model="staffTasks.{{ $staffId }}.{{ $index }}.title.en"
                                                        placeholder="English translation..."
                                                        class="input-sm text-xs bg-base-50 dark:bg-gray-800 border-gray-300 dark:border-gray-700" />
                                                    <x-textarea label="Instructions"
                                                        wire:model="staffTasks.{{ $staffId }}.{{ $index }}.description.id"
                                                        rows="2" placeholder="Write detailed instructions..."
                                                        class="text-xs bg-base-50 dark:bg-gray-800 border-gray-300 dark:border-gray-700" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Footer Column: Add Task Button --}}
                        <div
                            class="p-3 bg-base-200/50 dark:bg-gray-800/80 border-t border-base-200 dark:border-gray-700 rounded-b-2xl shrink-0">
                            <button type="button" wire:click="addTaskForStaff({{ $staffId }})"
                                class="w-full py-2.5 flex items-center justify-center gap-2 bg-base-100 dark:bg-gray-900 border border-base-300 dark:border-gray-600 shadow-sm rounded-lg text-gray-600 dark:text-gray-300 font-bold text-sm hover:border-primary hover:text-primary transition group">
                                <x-icon name="o-plus" class="w-4 h-4 group-hover:scale-110 transition" />
                                Add Task
                            </button>
                        </div>
                    </div>
                @empty
                    <div
                        class="flex flex-col items-center justify-center w-full py-20 opacity-50 border-2 border-dashed border-base-300 dark:border-gray-700 rounded-3xl bg-base-200/30">
                        <div class="p-4 bg-base-100 rounded-full mb-4 shadow-sm">
                            <x-icon name="o-rectangle-group" class="w-12 h-12 text-gray-400" />
                        </div>
                        <h3 class="font-black text-xl text-gray-600 dark:text-gray-300">Empty Roadmap</h3>
                        <p class="text-sm mt-2 max-w-md text-center">Start building your project roadmap by searching
                            and selecting a staff member from the input above.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI CLEAR DRAFT (Di-trigger dari Alpine.js) --}}
    <dialog class="modal" :class="{ 'modal-open': draftModalOpen }">
        <div class="modal-box dark:bg-gray-900">
            <h3 class="font-black text-xl text-error flex items-center gap-2 mb-2">
                <x-icon name="o-exclamation-triangle" class="w-7 h-7" />
                Clear All Drafts?
            </h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">
                Are you sure you want to clear all unsaved tasks in the roadmap? This action cannot be undone and your
                current progress will be lost.
            </p>
            <div class="modal-action mt-6">
                <button type="button" class="btn btn-ghost" @click="draftModalOpen = false">Cancel</button>
                <button type="button" class="btn btn-error text-white shadow-lg" @click="clearDraft()">Yes, Clear
                    Drafts</button>
            </div>
        </div>
        {{-- Backdrop untuk menutup modal saat diklik di luar kotak --}}
        <div class="modal-backdrop bg-black/50 backdrop-blur-sm" @click="draftModalOpen = false"></div>
    </dialog>

</div>
