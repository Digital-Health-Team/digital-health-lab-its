<div class="space-y-6">

    {{-- HEADER --}}
    <div
        class="flex flex-col md:flex-row justify-between items-center gap-4 bg-base-100 p-6 rounded-2xl shadow-sm border border-base-200">
        <div>
            <h1 class="text-2xl font-black">Hi, {{ auth()->user()->name }} 👋</h1>
            <p class="text-sm opacity-60">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
        </div>
        <div>
            @if (!$todayAttendance)
                <x-button label="CHECK IN" icon="o-camera" class="btn-primary btn-lg shadow-lg"
                    @click="$wire.checkInModal = true" />
            @elseif(!$todayAttendance->check_out)
                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block">
                        <div class="text-xs font-bold uppercase opacity-50">Clocked In</div>
                        <div class="font-mono text-xl font-bold">{{ $todayAttendance->check_in->format('H:i') }}</div>
                    </div>
                    <x-button label="CHECK OUT" icon="o-arrow-right-on-rectangle"
                        class="btn-error text-white btn-lg shadow-lg" @click="$wire.checkOutModal = true" />
                </div>
            @else
                <div class="badge badge-success p-4 font-bold text-white gap-2">
                    <x-icon name="o-check-circle" /> Shift Completed
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT COLUMN: TABS --}}
        <div class="lg:col-span-2 space-y-6">
            <x-tabs wire:model="selectedTab">

                {{-- TAB TASKS --}}
                <x-tab name="tasks" label="My Tasks" icon="o-clipboard-document-list">

                    {{-- Filters --}}
                    <div class="flex flex-col sm:flex-row gap-3 mb-4 mt-2">
                        <select wire:model.live="sortDeadline"
                            class="select select-sm select-bordered w-full sm:w-auto">
                            <option value="asc">📅 Deadline: Nearest First</option>
                            <option value="desc">📅 Deadline: Furthest First</option>
                        </select>
                        <select wire:model.live="filterProjectId"
                            class="select select-sm select-bordered w-full sm:w-auto">
                            <option value="">📂 All Projects</option>
                            @foreach ($this->myProjects as $proj)
                                {{-- SAFE ACCESS NAME --}}
                                @php $pName = is_array($proj->name) ? ($proj->name['id'] ?? $proj->name['en']) : $proj->name; @endphp
                                <option value="{{ $proj->id }}">{{ $pName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3">
                        @forelse($this->activeTasks as $task)
                            {{-- SAFE ACCESS TITLE & PROJECT NAME --}}
                            @php
                                $tTitle = is_array($task->title)
                                    ? $task->title['id'] ?? $task->title['en']
                                    : $task->title;
                                $pName = is_array($task->project->name)
                                    ? $task->project->name['id'] ?? $task->project->name['en']
                                    : $task->project->name;
                            @endphp

                            <a href="{{ route('user.jobdesks.revision', $task->id) }}"
                                class="block flex flex-col md:flex-row justify-between items-start md:items-center p-4 rounded-xl border border-base-300 bg-base-100 hover:bg-base-200 transition relative overflow-hidden group shadow-sm">

                                <div
                                    class="absolute left-0 top-0 bottom-0 w-1
                                    {{ $task->status == 'revision' ? 'bg-error' : ($task->status == 'on_progress' ? 'bg-info' : 'bg-gray-300') }}">
                                </div>

                                <div class="pl-3 space-y-1 w-full">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-widest opacity-50 bg-base-200 px-2 py-0.5 rounded">
                                                {{ $pName }}
                                            </span>
                                            @if ($task->status == 'revision')
                                                <span
                                                    class="badge badge-error badge-xs text-white animate-pulse">REVISION</span>
                                            @endif
                                        </div>
                                        @php $diff = now()->diffInDays(\Carbon\Carbon::parse($task->deadline_task), false); @endphp
                                        <div
                                            class="text-xs font-bold {{ $diff < 0 ? 'text-error' : ($diff < 2 ? 'text-warning' : 'text-success') }}">
                                            {{ $diff < 0 ? 'Overdue' : ($diff == 0 ? 'Due Today' : $diff . ' days left') }}
                                        </div>
                                    </div>

                                    <h3 class="font-bold text-base group-hover:text-primary transition">
                                        {{ $tTitle }}
                                    </h3>

                                    <div class="flex items-center gap-4 text-xs opacity-70">
                                        <span class="flex items-center gap-1">
                                            <x-icon name="o-calendar" class="w-3 h-3" />
                                            {{ \Carbon\Carbon::parse($task->deadline_task)->format('d M H:i') }}
                                        </span>
                                    </div>
                                </div>
                                <x-icon name="o-chevron-right"
                                    class="opacity-30 group-hover:opacity-100 hidden md:block" />
                            </a>
                        @empty
                            <div
                                class="text-center py-10 opacity-50 bg-base-100 rounded-xl border border-dashed border-base-300">
                                <p>No tasks match your filter.</p>
                            </div>
                        @endforelse
                    </div>
                </x-tab>

                {{-- TAB PROJECTS --}}
                <x-tab name="projects" label="My Projects" icon="o-briefcase">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                        @forelse($this->myProjects as $proj)
                            @php
                                $pName = is_array($proj->name) ? $proj->name['id'] ?? $proj->name['en'] : $proj->name;
                                $pDesc = is_array($proj->description)
                                    ? $proj->description['id'] ?? $proj->description['en']
                                    : $proj->description;
                            @endphp
                            <div class="card bg-base-100 shadow-sm border border-base-200 hover:shadow-md transition">
                                <div class="card-body p-5">
                                    <div class="flex justify-between items-start">
                                        <h2 class="card-title text-base">{{ $pName }}</h2>
                                        <div class="badge badge-ghost">{{ $proj->status }}</div>
                                    </div>
                                    <p class="text-xs opacity-60 line-clamp-2">{{ $pDesc }}</p>
                                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-base-200">
                                        <div class="text-xs">
                                            <div class="font-bold">{{ $proj->pending_tasks }} Pending</div>
                                            <div class="opacity-50">of {{ $proj->total_tasks }} Tasks</div>
                                        </div>
                                        <x-button label="View Details"
                                            link="{{ route('user.projects.show', $proj->id) }}"
                                            class="btn-sm btn-outline" />
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-10 opacity-50">You are not assigned to any project
                                yet.</div>
                        @endforelse
                    </div>
                </x-tab>
            </x-tabs>
        </div>

        {{-- RIGHT COLUMN: ANNOUNCEMENTS --}}
        <div class="space-y-6">
            <x-card title="Announcements" separator>
                @foreach ($announcements as $info)
                    <div class="mb-4 last:mb-0 border-b border-base-200 pb-2 last:border-0">
                        <div class="font-bold text-sm">{{ $info->title }}</div>
                        <p class="text-xs opacity-70 line-clamp-2 mt-1">{{ $info->content }}</p>
                        <div class="text-[10px] opacity-40 mt-1">{{ $info->created_at->diffForHumans() }}</div>
                    </div>
                @endforeach
            </x-card>
        </div>
    </div>

    {{-- MODALS CHECK IN/OUT --}}
    <x-modal wire:model="checkInModal" title="Daily Check In" separator persistent>
        <div x-data="cameraHandler()" x-init="startCamera()" class="space-y-4">
            <div class="relative bg-black rounded-xl overflow-hidden aspect-video">
                <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
                <canvas x-ref="canvas" class="hidden"></canvas>
                <div x-show="photo" class="absolute inset-0 bg-black"><img :src="photo"
                        class="w-full h-full object-cover" /></div>
            </div>
            <div class="flex justify-center gap-4">
                <button x-show="!photo" @click="takePhoto()" type="button"
                    class="btn btn-circle btn-primary btn-lg"><x-icon name="o-camera" class="w-8 h-8" /></button>
                <button x-show="photo" @click="resetPhoto()" type="button"
                    class="btn btn-circle btn-ghost text-error"><x-icon name="o-arrow-path" class="w-6 h-6" /></button>
            </div>
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.checkInModal = false" />
                <x-button label="CONFIRM" class="btn-primary" @click="$wire.set('photoIn', photo); $wire.doCheckIn()"
                    x-show="photo" spinner />
            </x-slot:actions>
        </div>
    </x-modal>

    <x-modal wire:model="checkOutModal" title="End of Day Report" separator persistent>
        <div x-data="cameraHandler()" x-init="startCamera()" class="space-y-6">
            <div class="space-y-2">
                <label class="font-bold text-sm">Selfie Bukti</label>
                <div class="relative bg-black rounded-lg overflow-hidden h-32 w-full">
                    <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
                    <canvas x-ref="canvas" class="hidden"></canvas>
                    <div x-show="photo" class="absolute inset-0 bg-black"><img :src="photo"
                            class="w-full h-full object-cover" /></div>
                    <button x-show="!photo" @click="takePhoto(); $wire.set('photoOut', photo)" type="button"
                        class="absolute bottom-2 right-2 btn btn-xs btn-primary">Capture</button>
                    <button x-show="photo" @click="resetPhoto()" type="button"
                        class="absolute bottom-2 right-2 btn btn-xs btn-error text-white">Retake</button>
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-bold text-sm">Pilih Tugas Selesai</label>
                <div class="max-h-40 overflow-y-auto border border-base-300 rounded-lg p-2 bg-base-50">
                    @forelse($this->activeTasks as $task)
                        @php $tTitle = is_array($task->title) ? ($task->title['id'] ?? $task->title['en']) : $task->title; @endphp
                        <div
                            class="flex items-center justify-between bg-white p-2 rounded mb-1 border border-base-200">
                            <x-checkbox wire:model="selectedJobdesks" value="{{ $task->id }}"
                                label="{{ Str::limit($tTitle, 25) }}" right />
                            @if (in_array($task->id, $selectedJobdesks))
                                <x-checkbox wire:model="finishedJobdesks" value="{{ $task->id }}"
                                    label="100% Done" class="checkbox-success checkbox-xs" />
                            @endif
                        </div>
                    @empty
                        <div class="text-xs opacity-50 p-2">Tidak ada tugas aktif.</div>
                    @endforelse
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-bold text-sm">Deskripsi Pengerjaan</label>
                <x-textarea wire:model="note" placeholder="Jelaskan apa saja yang selesai..." rows="2" />
                <x-file wire:model="attachments" label="Upload File Hasil" multiple />
            </div>
            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.checkOutModal = false" />
                <x-button label="SUBMIT REPORT" class="btn-primary" @click="$wire.doCheckOut()" x-show="photo"
                    spinner />
            </x-slot:actions>
        </div>
    </x-modal>

    <script>
        function cameraHandler() {
            return {
                photo: null,
                stream: null,
                async startCamera() {
                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: true
                        });
                        this.$refs.video.srcObject = this.stream;
                    } catch (err) {
                        console.error(err);
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
