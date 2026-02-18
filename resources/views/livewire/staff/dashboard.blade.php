<div class="space-y-6">
    {{-- ======================================================================== --}}
    {{-- 1. HEADER SECTION (Greeting & Main Action) --}}
    {{-- ======================================================================== --}}
    <div class="flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-3xl font-black dark:text-white">Dashboard</h1>
                <x-button icon="o-question-mark-circle" class="btn-circle btn-ghost btn-xs text-info" tooltip="Baca SOP"
                    @click="$wire.sopModal = true" />
            </div>

            <div class="mt-1">
                <p class="text-gray-500 dark:text-gray-400">
                    Hi, <span class="font-bold text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</span>.
                </p>

                {{-- [UPDATE] Role & Department Badges --}}
                <div class="flex flex-wrap gap-2 mt-2 items-center">
                    {{-- Role Badge --}}
                    <span class="badge badge-neutral text-xs font-bold uppercase tracking-wide">
                        {{ auth()->user()->role }}
                    </span>

                    {{-- Department Badges --}}
                    @if (!empty(auth()->user()->departments) && is_array(auth()->user()->departments))
                        <div class="h-4 w-px bg-gray-300 dark:bg-gray-600 mx-1"></div> {{-- Separator --}}

                        @foreach (auth()->user()->departments as $dept)
                            <span
                                class="badge badge-outline badge-xs text-[10px] font-semibold text-gray-600 dark:text-gray-400 border-gray-400">
                                {{ $dept }}
                            </span>
                        @endforeach
                    @endif
                </div>

                <p class="text-xs text-gray-400 mt-2">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>

        {{-- ACTION BUTTONS (Start/End Shift) --}}
        <div>
            @if ($currentSession)
                <div
                    class="flex items-center gap-4 bg-white dark:bg-gray-800 p-2 pr-2 pl-4 rounded-full shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="text-right hidden sm:block">
                        <div class="text-[10px] font-bold uppercase opacity-50 tracking-wider">Clocked In</div>
                        <div class="font-mono text-xl font-bold text-primary leading-none">
                            {{ $currentSession->check_in->format('H:i') }}
                        </div>
                    </div>
                    <x-button label="END SHIFT" icon="o-arrow-right-on-rectangle"
                        class="btn-error text-white rounded-full shadow-lg" wire:click="openCheckoutModal" />
                </div>
            @else
                <x-button label="START NEW SHIFT" icon="o-camera" class="btn-primary rounded-full shadow-xl btn-lg"
                    @click="$wire.checkInModal = true" />
            @endif
        </div>
    </div>

    {{-- ======================================================================== --}}
    {{-- 2. QUICK STATS ROW --}}
    {{-- ======================================================================== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Card 1: Shift Status --}}
        <div class="stat bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
            <div class="stat-figure text-primary bg-primary/10 p-3 rounded-full">
                <x-icon name="o-clock" class="w-6 h-6" />
            </div>
            <div class="stat-title text-xs font-bold uppercase opacity-60">Work Today</div>
            <div class="stat-value text-2xl font-black dark:text-gray-100">{{ $stats['hours_today'] }}</div>
            <div class="stat-desc text-xs">Accumulated time</div>
        </div>

        {{-- Card 2: Pending Tasks --}}
        <div class="stat bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
            <div class="stat-figure text-warning bg-warning/10 p-3 rounded-full">
                <x-icon name="o-clipboard-document-list" class="w-6 h-6" />
            </div>
            <div class="stat-title text-xs font-bold uppercase opacity-60">Pending Tasks</div>
            <div class="stat-value text-2xl font-black dark:text-gray-100">{{ $stats['pending_tasks'] }}</div>
            <div class="stat-desc text-xs">Need attention</div>
        </div>

        {{-- Card 3: Active Projects --}}
        <div class="stat bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
            <div class="stat-figure text-info bg-info/10 p-3 rounded-full">
                <x-icon name="o-briefcase" class="w-6 h-6" />
            </div>
            <div class="stat-title text-xs font-bold uppercase opacity-60">Projects</div>
            <div class="stat-value text-2xl font-black dark:text-gray-100">{{ $stats['active_projects'] }}</div>
            <div class="stat-desc text-xs">Active assignments</div>
        </div>

        {{-- Card 4: Date (Simple) --}}
        <div class="stat bg-primary text-primary-content shadow-sm rounded-2xl border border-primary">
            <div class="stat-actions">
                <div class="text-xs opacity-80 uppercase font-bold">Today is</div>
            </div>
            <div class="stat-value text-3xl">{{ \Carbon\Carbon::now()->format('d') }}</div>
            <div class="stat-desc opacity-90 font-bold uppercase">{{ \Carbon\Carbon::now()->format('F Y') }}</div>
        </div>
    </div>

    {{-- ======================================================================== --}}
    {{-- 3. MAIN CONTENT (Tabs & Lists) --}}
    {{-- ======================================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT COLUMN: TABS --}}
        <div class="lg:col-span-2 space-y-6">
            <x-tabs wire:model="selectedTab" class="custom-tabs">

                {{-- TAB TASKS --}}
                <x-tab name="tasks" label="My Tasks" icon="o-clipboard-document-list">

                    {{-- Filter Toolbar --}}
                    <div
                        class="flex flex-col sm:flex-row gap-2 mb-4 mt-2 p-1 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                        <select wire:model.live="sortDeadline"
                            class="select select-sm select-ghost w-full sm:w-auto focus:bg-white dark:focus:bg-gray-800">
                            <option value="asc">📅 Deadline: Nearest</option>
                            <option value="desc">📅 Deadline: Furthest</option>
                        </select>
                        <div class="h-8 w-px bg-gray-300 dark:bg-gray-700 hidden sm:block"></div>
                        <select wire:model.live="filterProjectId"
                            class="select select-sm select-ghost w-full sm:w-auto focus:bg-white dark:focus:bg-gray-800">
                            <option value="">📂 All Projects</option>
                            @foreach ($myProjects as $proj)
                                @php $pName = is_array($proj->name) ? ($proj->name['id'] ?? $proj->name['en']) : $proj->name; @endphp
                                <option value="{{ $proj->id }}">{{ $pName }}</option>
                            @endforeach
                        </select>
                        <div class="h-8 w-px bg-gray-300 dark:bg-gray-700 hidden sm:block"></div>
                        <select wire:model.live="filterStatus"
                            class="select select-sm select-ghost w-full sm:w-auto focus:bg-white dark:focus:bg-gray-800">
                            <option value="">⚡ Active Tasks</option>
                            <option value="pending">⚪ Pending</option>
                            <option value="on_progress">🔵 On Progress</option>
                            <option value="revision">🔴 Revision</option>
                            <option value="review">🟡 Under Review</option>
                            <option value="approved">🟢 Completed</option>
                        </select>
                    </div>

                    {{-- Task List --}}
                    <div class="space-y-3">
                        @forelse($activeTasks as $task)
                            @php
                                $tTitle = is_array($task->title)
                                    ? $task->title['id'] ?? $task->title['en']
                                    : $task->title;
                                $pName = is_array($task->project->name)
                                    ? $task->project->name['id'] ?? $task->project->name['en']
                                    : $task->project->name;
                                $deadline = \Carbon\Carbon::parse($task->deadline_task);
                                $isOverdue = $deadline->isPast();
                                $isToday = $deadline->isToday();
                                $dateStr = $deadline->format('d M');
                                $diffStr = $deadline->diffForHumans(null, true) . ($isOverdue ? ' ago' : ' left');
                            @endphp

                            <a href="{{ route('user.jobdesks.revision', $task->id) }}"
                                class="block flex flex-col md:flex-row justify-between items-start md:items-center p-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-primary/50 hover:shadow-md transition group relative overflow-hidden">

                                {{-- Status Indicator --}}
                                <div
                                    class="absolute left-0 top-0 bottom-0 w-1.5
                                    {{ match ($task->status) {
                                        'revision' => 'bg-error',
                                        'on_progress' => 'bg-info',
                                        'review' => 'bg-warning',
                                        'approved' => 'bg-success',
                                        default => 'bg-gray-300',
                                    } }}">
                                </div>

                                <div class="pl-3 space-y-1 w-full">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-widest text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 px-2 py-0.5 rounded">
                                                {{ $pName }}
                                            </span>
                                            <span
                                                class="badge badge-xs font-bold uppercase border-none
                                                {{ match ($task->status) {
                                                    'revision' => 'badge-error text-white animate-pulse',
                                                    'on_progress' => 'badge-info text-white',
                                                    'review' => 'badge-warning text-black',
                                                    'approved' => 'badge-success text-white',
                                                    default => 'badge-ghost opacity-60',
                                                } }}">
                                                {{ str_replace('_', ' ', $task->status) }}
                                            </span>
                                        </div>

                                        <div
                                            class="text-xs font-bold {{ $isOverdue ? 'text-error' : ($isToday ? 'text-warning' : 'text-success') }}">
                                            @if ($isOverdue)
                                                Overdue {{ $diffStr }}
                                            @elseif($isToday)
                                                Due Today ({{ $diffStr }})
                                            @else
                                                {{ $diffStr }}
                                            @endif
                                        </div>
                                    </div>

                                    <h3
                                        class="font-bold text-base text-gray-800 dark:text-gray-100 group-hover:text-primary transition">
                                        {{ $tTitle }}</h3>

                                    <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1"
                                            title="{{ $deadline->format('d M Y H:i') }}">
                                            <x-icon name="o-calendar" class="w-3 h-3" />
                                            {{ $dateStr }}
                                            <span class="opacity-50">at {{ $deadline->format('H:i') }}</span>
                                        </span>
                                    </div>
                                </div>
                                <x-icon name="o-chevron-right"
                                    class="opacity-30 group-hover:opacity-100 hidden md:block dark:text-gray-400" />
                            </a>
                        @empty
                            <div
                                class="text-center py-12 opacity-50 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                                <x-icon name="o-check-circle" class="w-10 h-10 mx-auto mb-2 opacity-20" />
                                <p class="text-sm">No tasks match your filter.</p>
                            </div>
                        @endforelse
                    </div>
                </x-tab>

                {{-- TAB PROJECTS --}}
                <x-tab name="projects" label="Projects" icon="o-briefcase">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        @forelse($myProjects as $proj)
                            @php
                                $pName = is_array($proj->name) ? $proj->name['id'] ?? $proj->name['en'] : $proj->name;
                                $pDesc = is_array($proj->description)
                                    ? $proj->description['id'] ?? $proj->description['en']
                                    : $proj->description;
                            @endphp
                            <div
                                class="card bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition">
                                <div class="card-body p-5">
                                    <div class="flex justify-between items-start">
                                        <h2 class="card-title text-base dark:text-gray-200">{{ $pName }}
                                        </h2>
                                        <div class="badge badge-ghost text-xs">{{ $proj->status }}</div>
                                    </div>
                                    <p class="text-xs opacity-60 line-clamp-2 dark:text-gray-400">{{ $pDesc }}
                                    </p>
                                    <div
                                        class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                        <div class="text-xs dark:text-gray-400">
                                            <div class="font-bold">{{ $proj->pending_tasks }} Pending</div>
                                            <div class="opacity-50">of {{ $proj->total_tasks }} Tasks</div>
                                        </div>
                                        <x-button label="View Details"
                                            link="{{ route('user.projects.show', $proj->id) }}"
                                            class="btn-sm btn-outline dark:text-gray-300" />
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-10 opacity-50 dark:text-gray-400">You are not
                                assigned to any project yet.</div>
                        @endforelse
                    </div>
                </x-tab>

                {{-- TAB HISTORY (UPDATED LINK) --}}
                <x-tab name="history" label="History" icon="o-clock">
                    <div
                        class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mt-2">
                        <table class="table table-zebra">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400">
                                <tr>
                                    <th>Date</th>
                                    <th>Shift Time</th>
                                    <th>Duration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="dark:text-gray-300">
                                @forelse($attendanceHistory as $history)
                                    <tr>
                                        <td class="font-bold">
                                            {{ $history->created_at->format('d M') }}
                                            @if ($history->created_at->isToday())
                                                <span class="badge badge-xs badge-info">Today</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $history->check_in->format('H:i') }}</span>
                                                <x-icon name="o-arrow-right" class="w-3 h-3 opacity-30" />
                                                <span
                                                    class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $history->check_out ? $history->check_out->format('H:i') : '...' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-xs opacity-60 font-mono">
                                            @if ($history->check_out)
                                                {{ $history->check_in->diff($history->check_out)->format('%hh %im') }}
                                            @else
                                                <span class="text-primary animate-pulse font-bold">Active</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- BUTTON UPDATED TO LINK --}}
                                            <x-button icon="o-eye" class="btn-xs btn-ghost"
                                                link="{{ route('user.attendance.show', $history->id) }}"
                                                tooltip="View Full Details" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-8 opacity-50">No history found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-tab>
            </x-tabs>
        </div>

        {{-- RIGHT COLUMN: ANNOUNCEMENTS --}}
        <div class="space-y-6">
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700 font-bold flex items-center gap-2">
                    <x-icon name="o-megaphone" class="w-5 h-5 text-primary" /> Announcements
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($announcements as $info)
                        <div wire:click="showAnnouncement({{ $info->id }})"
                            class="p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition group">
                            <div
                                class="font-bold text-sm dark:text-gray-200 group-hover:text-primary transition flex justify-between">
                                {{ $info->title }}
                                <x-icon name="o-chevron-right"
                                    class="w-3 h-3 opacity-0 group-hover:opacity-100 transition" />
                            </div>
                            <p class="text-xs opacity-70 line-clamp-2 mt-1 dark:text-gray-400">{{ $info->content }}
                            </p>
                            <div class="text-[10px] opacity-40 mt-2 flex items-center gap-1">
                                <x-icon name="o-clock" class="w-3 h-3" />
                                {{ $info->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                    @if (count($announcements) == 0)
                        <div class="text-xs text-center opacity-50 py-8">No announcements.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================================================== --}}
    {{-- MODALS SECTION --}}
    {{-- ======================================================================== --}}

    {{-- 1. MODAL SOP --}}
    <x-modal wire:model="sopModal" title="SOP Penggunaan Website" separator>
        <div class="prose prose-sm dark:prose-invert max-w-none dark:text-gray-300">
            <ol class="list-decimal pl-4 space-y-2">
                <li><strong>Memulai Bekerja (Check In):</strong> Wajib klik <span class="text-primary font-bold">START
                        NEW SHIFT</span>, izinkan akses lokasi (GPS), dan ambil foto selfie.</li>
                <li><strong>Bekerja:</strong> Pantau tugas di tab <strong>My Tasks</strong>. Selalu update status tugas
                    jika ada progress.</li>
                <li><strong>Selesai Bekerja (Check Out):</strong> Klik <span class="text-error font-bold">END
                        SHIFT</span>. Ikuti langkah wizard: Selfie -> Pilih Tugas Selesai -> Upload Bukti.</li>
            </ol>
        </div>
        <x-slot:actions>
            <x-button label="Saya Mengerti" class="btn-primary" @click="$wire.sopModal = false" />
        </x-slot:actions>
    </x-modal>

    {{-- 2. MODAL CHECK IN (Camera + GPS) --}}
    <x-modal wire:model="checkInModal" title="Daily Check In" separator persistent>
        <div x-data="cameraHandler()" x-init="startCamera()" class="space-y-4">
            <div class="relative bg-black rounded-xl overflow-hidden aspect-video shadow-lg">
                <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
                <canvas x-ref="canvas" class="hidden"></canvas>
                <div x-show="photo" class="absolute inset-0 bg-black" style="display: none;">
                    <img :src="photo" class="w-full h-full object-cover" />
                </div>
                {{-- GPS Status --}}
                <div
                    class="absolute top-2 left-2 px-2 py-1 bg-black/50 text-white text-[10px] rounded backdrop-blur-md flex items-center gap-1">
                    <x-icon name="o-map-pin" class="w-3 h-3 text-success" /> Location Tracking On
                </div>
            </div>

            <div class="flex justify-center -mt-8 relative z-10">
                <button x-show="!photo" @click="takePhoto()" type="button"
                    class="btn btn-circle btn-primary btn-lg border-4 border-base-100 dark:border-gray-800 shadow-xl"><x-icon
                        name="o-camera" class="w-8 h-8" /></button>
                <button x-show="photo" @click="resetPhoto()" type="button" style="display: none;"
                    class="btn btn-circle btn-error text-white btn-lg border-4 border-base-100 dark:border-gray-800 shadow-xl"><x-icon
                        name="o-arrow-path" class="w-8 h-8" /></button>
            </div>

            <div class="flex justify-between items-center pt-4">
                <x-button label="Cancel" @click="$wire.checkInModal = false" class="btn-ghost" />
                <div x-show="photo" style="display: none;">
                    <x-button label="CONFIRM START" class="btn-primary"
                        @click="$wire.set('photoIn', photo); $wire.doCheckIn()" spinner />
                </div>
            </div>
        </div>
    </x-modal>

    {{-- 3. MODAL CHECK OUT (WIZARD + GPS) --}}
    <x-modal wire:model="checkOutModal" title="End Shift Report" separator persistent class="backdrop-blur-sm">
        <div x-data="cameraHandler()" x-init="startCamera()" class="space-y-4 min-h-[450px] flex flex-col">

            {{-- Stepper --}}
            <ul class="steps w-full mb-4">
                <li class="step {{ $checkoutStep >= 1 ? 'step-primary' : '' }}">Selfie</li>
                <li class="step {{ $checkoutStep >= 2 ? 'step-primary' : '' }}">Tasks</li>
                <li class="step {{ $checkoutStep >= 3 ? 'step-primary' : '' }}">Proof</li>
            </ul>

            {{-- Step 1: Selfie --}}
            @if ($checkoutStep === 1)
                <div class="flex-1 flex flex-col justify-center">
                    <div
                        class="relative bg-black rounded-2xl overflow-hidden w-full h-64 md:h-80 shadow-lg border border-base-300 dark:border-gray-700">
                        <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
                        <canvas x-ref="canvas" class="hidden"></canvas>
                        <div x-show="photo" class="absolute inset-0 bg-black" style="display: none;"><img
                                :src="photo" class="w-full h-full object-cover" /></div>
                        <div class="absolute top-4 left-0 right-0 text-center pointer-events-none">
                            <span class="bg-black/50 text-white px-3 py-1 rounded-full text-xs backdrop-blur-md">Ambil
                                foto selfie bukti pulang</span>
                        </div>
                        <div class="absolute bottom-6 left-0 right-0 flex justify-center gap-4">
                            <button x-show="!photo" @click="takePhoto(); $wire.set('photoOut', photo)" type="button"
                                class="btn btn-circle btn-primary btn-lg border-4 border-white/20 shadow-xl scale-110"><x-icon
                                    name="o-camera" class="w-8 h-8" /></button>
                            <button x-show="photo" @click="resetPhoto()" type="button" style="display: none;"
                                class="btn btn-circle btn-error text-white btn-lg border-4 border-white/20 shadow-xl"><x-icon
                                    name="o-arrow-path" class="w-8 h-8" /></button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Step 2: Select Tasks --}}
            @if ($checkoutStep === 2)
                <div class="flex-1 flex flex-col">
                    <h3 class="font-bold text-lg mb-2 dark:text-gray-200">Apa yang dikerjakan hari ini?</h3>
                    <div class="flex-1 overflow-y-auto max-h-[350px] space-y-2 pr-1">
                        @forelse($activeTasks as $task)
                            @php $tTitle = is_array($task->title) ? ($task->title['id'] ?? $task->title['en']) : $task->title; @endphp
                            <div
                                class="p-3 rounded-xl border transition-all duration-200
                                @if (in_array($task->id, $selectedJobdesks)) border-primary bg-primary/5 dark:bg-primary/10 dark:border-primary/50 @else border-gray-200 bg-white dark:bg-gray-800 dark:border-gray-700 @endif">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <input type="checkbox" wire:model.live="selectedJobdesks"
                                            value="{{ $task->id }}"
                                            class="checkbox checkbox-primary checkbox-sm" />
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-bold truncate dark:text-gray-200">{{ Str::limit($tTitle, 35) }}</span>
                                            <span
                                                class="text-[10px] opacity-60 uppercase dark:text-gray-400">{{ $task->status }}</span>
                                        </div>
                                    </div>
                                    @if (in_array($task->id, $selectedJobdesks))
                                        <div
                                            class="flex items-center gap-2 bg-success/10 px-2 py-1 rounded-lg border border-success/20">
                                            <span class="text-[10px] font-bold text-success">DONE?</span>
                                            <input type="checkbox" wire:model="finishedJobdesks"
                                                value="{{ $task->id }}"
                                                class="toggle toggle-success toggle-xs" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 opacity-50 dark:text-gray-400">Tidak ada tugas aktif.</div>
                        @endforelse
                    </div>
                </div>
            @endif

            {{-- Step 3: Proof & Notes --}}
            @if ($checkoutStep === 3)
                <div class="flex-1 space-y-4">
                    <div>
                        <label class="font-bold text-sm dark:text-gray-300">Catatan Pengerjaan</label>
                        <x-textarea wire:model="note" placeholder="Jelaskan progress hari ini..." rows="3"
                            class="text-sm mt-1" />
                    </div>
                    <div>
                        <label class="font-bold text-sm dark:text-gray-300 mb-1 block">Upload Bukti (Hanya
                            Gambar)</label>
                        <div
                            class="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl border border-dashed border-gray-300 dark:border-gray-600 text-center">
                            <x-file wire:model="attachments" accept="image/png, image/jpeg, image/webp" multiple>
                                <div class="flex flex-col items-center gap-2 cursor-pointer">
                                    <x-icon name="o-photo" class="w-8 h-8 opacity-50" />
                                    <span class="text-xs opacity-60">Klik untuk upload foto bukti (Max 10MB)</span>
                                </div>
                            </x-file>
                        </div>
                        @if ($attachments)
                            <div class="mt-2 text-xs text-success flex items-center gap-1">
                                <x-icon name="o-check" class="w-3 h-3" /> {{ count($attachments) }} file
                                dipilih.
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Footer Nav --}}
            <div class="flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-700 mt-auto">
                @if ($checkoutStep === 1)
                    <x-button label="Cancel" @click="$wire.checkOutModal = false" class="btn-ghost text-error" />
                @else
                    <x-button label="Back" wire:click="prevCheckOutStep" class="btn-ghost" />
                @endif

                @if ($checkoutStep < 3)
                    <div x-show="photo || {{ $checkoutStep }} > 1"><x-button label="Next Step"
                            wire:click="nextCheckOutStep" class="btn-primary" /></div>
                    @if ($checkoutStep === 1)
                        <div x-show="!photo" class="text-xs text-error opacity-60">*Ambil foto dulu</div>
                    @endif
                @else
                    <x-button label="SUBMIT REPORT" class="btn-success text-white shadow-lg"
                        @click="$wire.doCheckOut()" spinner />
                @endif
            </div>
        </div>
    </x-modal>

    {{-- 4. MODAL DETAIL ANNOUNCEMENT --}}
    <x-modal wire:model="announcementModal" title="Announcement" separator>
        @if ($selectedAnnouncement)
            <div class="space-y-4">
                <div class="flex justify-between items-start">
                    <h2 class="text-xl font-bold dark:text-gray-100">{{ $selectedAnnouncement->title }}</h2>
                    <span
                        class="text-xs opacity-50 dark:text-gray-500">{{ $selectedAnnouncement->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div
                    class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed">
                    {!! nl2br(e($selectedAnnouncement->content)) !!}
                </div>
            </div>
        @endif
        <x-slot:actions>
            <x-button label="Close" @click="$wire.announcementModal = false" />
        </x-slot:actions>
    </x-modal>

    {{-- SCRIPT: CAMERA & GPS --}}
    <script>
        function cameraHandler() {
            return {
                photo: null,
                stream: null,
                lat: null,
                lng: null,
                async startCamera() {
                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: true
                        });
                        this.$refs.video.srcObject = this.stream;
                    } catch (err) {
                        console.error('Cam Error', err);
                    }

                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(pos => {
                            this.lat = pos.coords.latitude;
                            this.lng = pos.coords.longitude;
                            @this.set('latitude', this.lat);
                            @this.set('longitude', this.lng);
                        }, err => console.error('GPS Error', err), {
                            enableHighAccuracy: true
                        });
                    }
                },
                takePhoto() {
                    const canvas = this.$refs.canvas;
                    const video = this.$refs.video;
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);
                    this.photo = canvas.toDataURL('image/webp');
                },
                resetPhoto() {
                    this.photo = null;
                }
            }
        }
    </script>
</div>
