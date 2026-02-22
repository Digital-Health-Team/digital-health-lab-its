<div class="h-auto min-h-[calc(100vh-6rem)] flex flex-col space-y-6 pb-10">

    {{-- LOGIC MASTER DATA & FIX TRANSLATION --}}
    @php
        // 1. Logic Attachments (Gallery)
        $threadAttachments = $jobdesk->revisionThreads->flatMap(fn($thread) => $thread->attachments);
        $reportAttachments = $jobdesk->reports->flatMap(fn($report) => $report->attachments);

        $allAttachments = $threadAttachments
            ->merge($reportAttachments)
            ->filter(fn($att) => \Illuminate\Support\Str::startsWith($att->file_type, 'image'))
            ->map(fn($att) => asset('storage/' . $att->file_path))
            ->values()
            ->toArray();

        // 2. Handle Project Name
        $projectName = $jobdesk->project->name;
        if (is_array($projectName)) {
            $projectName = $projectName['id'] ?? ($projectName['en'] ?? '-');
        }

        // 3. Handle Jobdesk Title
        $taskName = $jobdesk->title ?? $jobdesk->name;
        if (is_array($taskName)) {
            $taskName = $taskName['id'] ?? ($taskName['en'] ?? '-');
        }
    @endphp

    {{-- HEADER (RESPONSIVE) --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-1 gap-4 shrink-0">
        <div>
            <div class="flex items-center gap-2 text-xs md:text-sm text-gray-500 mb-1">
                <a href="javascript:history.back()" class="hover:underline flex items-center gap-1">
                    <x-icon name="o-arrow-left" class="w-3 h-3" /> Back
                </a>
                <span class="opacity-50">/</span>
                <span
                    class="truncate max-w-[150px] md:max-w-xs uppercase tracking-wider font-bold">{{ $projectName }}</span>
            </div>
            <h1 class="text-xl md:text-2xl font-bold flex flex-wrap items-center gap-2 md:gap-3">
                <span class="truncate max-w-[200px] md:max-w-md">{{ $taskName }}</span>
                <div
                    class="badge {{ match ($jobdesk->status) {'revision' => 'badge-error','approved' => 'badge-success','review' => 'badge-warning',default => 'badge-ghost'} }} uppercase font-bold text-[10px]">
                    {{ str_replace('_', ' ', $jobdesk->status) }}
                </div>
            </h1>
        </div>

        {{-- Creator Info --}}
        <div
            class="w-full md:w-auto flex items-center gap-3 text-sm bg-gray-50 dark:bg-gray-800 md:bg-transparent md:dark:bg-transparent p-3 md:p-0 rounded-lg border border-gray-100 dark:border-gray-700 md:border-none">
            <div class="text-right">
                <div class="text-gray-500 dark:text-gray-400 text-xs">Assigned By</div>
                <div class="font-bold dark:text-gray-200">{{ $jobdesk->creator->name }}</div>
            </div>
            <x-avatar :image="$jobdesk->creator->profile_photo ? asset('storage/' . $jobdesk->creator->profile_photo) : null" class="!w-9 !h-9" />
        </div>
    </div>

    {{-- LAYOUT GRID UTAMA (Discussion & Form) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 min-h-[500px]">

        {{-- KOLOM KIRI: THREAD HISTORY --}}
        <div class="lg:col-span-2 flex flex-col">
            <div
                class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col shadow-sm h-full overflow-hidden">
                <div
                    class="p-4 border-b border-gray-100 dark:border-gray-700/50 flex items-center justify-between font-bold text-gray-800 dark:text-gray-200 shrink-0">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-gray-100 dark:bg-gray-800 rounded-md">
                            <x-icon name="o-chat-bubble-left-right" class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                        </div>
                        Discussion & Revision
                    </div>
                    <span class="text-xs font-normal opacity-60">{{ $jobdesk->revisionThreads->count() }} replies</span>
                </div>

                <div
                    class="p-4 md:p-6 bg-gray-50/50 dark:bg-black/20 flex-1 overflow-y-auto max-h-[600px] custom-scrollbar">
                    @if ($jobdesk->revisionThreads->count() > 0)
                        <div class="relative space-y-6 md:space-y-8">
                            <div class="absolute left-5 md:left-6 top-2 bottom-2 w-0.5 bg-gray-200 dark:bg-gray-700">
                            </div>

                            @foreach ($jobdesk->revisionThreads as $thread)
                                <div class="relative pl-12 md:pl-14 group">
                                    <div
                                        class="absolute left-1 md:left-2 top-0 bg-white dark:bg-gray-800 p-1 rounded-full border border-gray-200 dark:border-gray-700 z-10">
                                        <x-avatar :image="$thread->user->profile_photo
                                            ? asset('storage/' . $thread->user->profile_photo)
                                            : null" class="!w-8 !h-8 md:!w-9 md:!h-9" />
                                    </div>

                                    <div
                                        class="p-3 md:p-5 rounded-2xl border shadow-sm w-full {{ $thread->is_staff_reply ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-100 dark:border-blue-800 ml-auto mr-0 max-w-[95%] md:max-w-[90%]' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 max-w-[98%] md:max-w-[95%]' }}">
                                        <div
                                            class="flex flex-wrap justify-between items-center mb-2 md:mb-3 pb-2 border-b border-gray-100 dark:border-gray-700/50 gap-2">
                                            <div class="flex flex-col">
                                                <span
                                                    class="font-bold text-sm text-gray-800 dark:text-gray-200">{{ $thread->user->name }}</span>
                                                <span
                                                    class="text-[10px] text-gray-400 dark:text-gray-500">{{ $thread->created_at->format('d M Y, H:i') }}</span>
                                            </div>
                                            <span
                                                class="badge {{ $thread->is_staff_reply ? 'badge-primary' : 'badge-warning' }} badge-xs text-white border-none">
                                                {{ $thread->is_staff_reply ? 'ME' : 'INSTRUCTION' }}
                                            </span>
                                        </div>

                                        <div
                                            class="prose prose-sm max-w-none dark:prose-invert text-sm leading-relaxed text-gray-700 dark:text-gray-300 break-words">
                                            {!! nl2br(e($thread->content)) !!}
                                        </div>

                                        @if ($thread->attachments->count() > 0)
                                            <div
                                                class="mt-3 md:mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/50 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 md:gap-3">
                                                @foreach ($thread->attachments as $att)
                                                    @php
                                                        $url = asset('storage/' . $att->file_path);
                                                        $globalIndex = array_search($url, $allAttachments);
                                                        $isImage = \Illuminate\Support\Str::startsWith(
                                                            $att->file_type,
                                                            'image',
                                                        );
                                                    @endphp

                                                    {{-- [UPDATE DISPATCH EVENT UNTUK REVISION THREAD] --}}
                                                    <div @click.prevent="$dispatch('open-lightbox', { url: '{{ $url }}', index: {{ $globalIndex !== false ? $globalIndex : 0 }} })"
                                                        class="group relative aspect-square bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 cursor-zoom-in hover:ring-2 hover:ring-primary transition">
                                                        <img src="{{ $url }}"
                                                            class="object-cover w-full h-full transform group-hover:scale-105 transition duration-500">
                                                        <div
                                                            class="absolute inset-x-0 bottom-0 bg-black/60 text-white text-[10px] p-1 truncate text-center backdrop-blur-md opacity-0 group-hover:opacity-100 transition">
                                                            {{ $att->file_name }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full opacity-30 dark:opacity-20 py-10">
                            <x-icon name="o-chat-bubble-left-right"
                                class="w-16 h-16 md:w-20 md:h-20 mb-4 dark:text-gray-500" />
                            <div class="text-lg font-semibold dark:text-gray-500">No discussion yet</div>
                            <div class="text-xs">Start the conversation below.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: FORM INPUT (STICKY) --}}
        <div class="flex flex-col">
            <div
                class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col shadow-sm sticky top-6">
                <div
                    class="p-3 md:p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 font-bold text-sm shrink-0">
                    Submit Reply / Fix
                </div>

                <div class="p-4 md:p-5">
                    @if (!$canSubmit)
                        <div class="flex flex-col items-center justify-center text-center space-y-4 opacity-70 py-10">
                            <div class="bg-base-200 p-4 rounded-full">
                                <x-icon name="o-lock-closed" class="w-8 h-8 text-gray-500" />
                            </div>
                            <div>
                                <h3 class="font-bold">Access Locked</h3>
                                <p class="text-xs max-w-[200px] mx-auto mt-1">
                                    Anda harus melakukan <b>Check In</b> di Dashboard terlebih dahulu untuk mengirim
                                    update.
                                </p>
                            </div>
                            <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-primary">Go to Dashboard</a>
                        </div>
                    @else
                        <x-form wire:submit="sendReply" class="flex flex-col gap-4 md:gap-5">
                            <div
                                class="bg-primary/5 border border-primary/20 dark:bg-primary/10 dark:border-primary/30 p-3 rounded-lg flex items-center justify-between">
                                <div class="text-xs">
                                    <div class="font-bold text-primary">Is the issue resolved?</div>
                                    <div class="opacity-60 text-[10px] dark:text-gray-400">Status will change to
                                        <b>REVIEW</b>.
                                    </div>
                                </div>
                                <input type="checkbox" wire:model="markAsFixed"
                                    class="toggle toggle-primary toggle-sm" />
                            </div>

                            <x-textarea label="Reply Message" wire:model="replyContent"
                                placeholder="I have fixed the issue..." rows="5"
                                class="flex-1 font-mono text-sm bg-white dark:bg-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 focus:border-primary min-h-[120px]" />

                            <div
                                class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                                <x-file label="Attachments" wire:model="replyFiles"
                                    accept="image/*,application/pdf,application/zip" multiple hint="Max 10MB" />
                            </div>

                            <x-button label="Send Reply" class="btn-primary w-full shadow-md text-white mb-2"
                                type="submit" spinner="sendReply" icon="o-paper-airplane" />
                        </x-form>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- SECTION FULL WIDTH: WORK LOGS / ACHIEVEMENTS --}}
    <div class="w-full mt-4">
        <div
            class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col shadow-sm shrink-0">
            <div
                class="p-4 border-b border-gray-100 dark:border-gray-700/50 flex items-center gap-2 font-bold text-gray-800 dark:text-gray-200">
                <div class="p-1.5 bg-gray-100 dark:bg-gray-800 rounded-md">
                    <x-icon name="o-clipboard-document-check" class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                </div>
                Work Log & Achievements
            </div>

            <div class="p-4 md:p-8 bg-gray-50/30 dark:bg-gray-800/20">
                @if ($jobdesk->reports->count() > 0)
                    <div class="relative border-l-2 border-blue-200 dark:border-blue-900/50 ml-4 md:ml-8 space-y-10">
                        @foreach ($jobdesk->reports->sortByDesc('created_at') as $report)
                            <div class="relative pl-8 md:pl-10">
                                {{-- Timeline Dot --}}
                                <div
                                    class="absolute -left-[11px] top-1 w-5 h-5 rounded-full bg-blue-500 border-4 border-white dark:border-gray-900 shadow-sm">
                                </div>

                                {{-- Card Log --}}
                                <div
                                    class="bg-white dark:bg-gray-800 p-5 md:p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition">

                                    {{-- Header Card Log --}}
                                    <div
                                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                                        <div>
                                            <div
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-1">
                                                <x-icon name="o-calendar" class="w-3 h-3" />
                                                {{ $report->created_at->format('d F Y - H:i') }}
                                            </div>
                                            <div class="font-bold text-base text-gray-800 dark:text-gray-200 mt-1">
                                                {{ $taskName }}
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 flex-wrap">
                                            {{-- [BARU] Tombol Link ke Attendance / Shift Detail --}}
                                            <a href="{{ route('user.attendance.show', $report->attendance_id) }}"
                                                class="btn btn-xs bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600 hover:bg-primary hover:text-white hover:border-primary transition group shadow-sm text-[10px]">
                                                <x-icon name="o-clock"
                                                    class="w-3 h-3 text-gray-400 group-hover:text-white transition" />
                                                Shift Detail
                                            </a>

                                            <span class="badge badge-ghost badge-sm uppercase font-bold text-[10px]">
                                                {{ str_replace('_', ' ', $report->status_at_report) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div
                                        class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed bg-gray-50 dark:bg-black/20 p-4 rounded-xl border border-gray-100 dark:border-gray-700/50">
                                        "{{ current($report->details)->content ?? 'Tidak ada catatan yang diberikan pada sesi ini.' }}"
                                    </div>

                                    {{-- Log Attachments (GAYA THUMBNAIL) --}}
                                    @if ($report->attachments->count() > 0)
                                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700/50">
                                            <div class="text-[10px] font-bold opacity-50 uppercase mb-3">Proofs /
                                                Attachments</div>

                                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-2 md:gap-3">
                                                @foreach ($report->attachments as $att)
                                                    @php
                                                        $url = asset('storage/' . $att->file_path);
                                                        $globalIndex = array_search($url, $allAttachments);
                                                        $isImage = \Illuminate\Support\Str::startsWith(
                                                            $att->file_type,
                                                            'image',
                                                        );
                                                    @endphp

                                                    {{-- Tampilan Thumbnail Gambar --}}
                                                    <div @click.prevent="$dispatch('open-lightbox', { url: '{{ $url }}', index: {{ $globalIndex !== false ? $globalIndex : 0 }} })"
                                                        class="group relative aspect-square bg-gray-100 dark:bg-gray-900 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 cursor-zoom-in hover:ring-2 hover:ring-primary transition">
                                                        <img src="{{ $url }}"
                                                            class="object-cover w-full h-full transform group-hover:scale-105 transition duration-500">
                                                        <div
                                                            class="absolute inset-x-0 bottom-0 bg-black/60 text-white text-[10px] p-1 truncate text-center backdrop-blur-md opacity-0 group-hover:opacity-100 transition">
                                                            {{ $att->file_name }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="text-center py-10 opacity-50 flex flex-col items-center border border-dashed border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-black/10">
                        <x-icon name="o-document-minus" class="w-12 h-12 mb-3 opacity-30" />
                        <span class="text-sm font-semibold">Belum Ada Catatan Pekerjaan</span>
                        <span class="text-xs mt-1">Log pengerjaan akan muncul di sini setelah Anda melakukan
                            checkout.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Panggil Reusable Component Lightbox di bagian paling bawah --}}
    <x-lightbox :images="$allAttachments" />

</div>
