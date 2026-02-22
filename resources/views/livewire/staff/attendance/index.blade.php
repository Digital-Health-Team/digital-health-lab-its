<div class="max-w-5xl mx-auto space-y-8 pb-10">

    {{-- LOGIC: GATHER ONLY TASK IMAGES FOR LIGHTBOX --}}
    @php
        $taskImages = collect();

        // HANYA Ambil Foto Bukti (Attachments) dari setiap Work Log (Tugas)
        foreach ($attendance->reports as $report) {
            foreach ($report->attachments as $att) {
                if (\Illuminate\Support\Str::startsWith($att->file_type, 'image')) {
                    $taskImages->push(asset('storage/' . $att->file_path));
                }
            }
        }

        $taskImagesArray = $taskImages->values()->toArray();
    @endphp

    {{-- HEADER NAVIGATION --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <a href="javascript:history.back()"
                class="btn btn-ghost btn-sm pl-0 gap-2 text-gray-500 hover:bg-transparent">
                <x-icon name="o-arrow-left" class="w-4 h-4" /> Back
            </a>
            <h1 class="text-3xl font-black mt-2 dark:text-gray-100">Attendance Report</h1>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mt-1">
                <x-icon name="o-calendar" class="w-4 h-4" />
                {{ $attendance->created_at->translatedFormat('l, d F Y') }}
            </div>
        </div>

        {{-- STATUS BADGE & DURATION --}}
        <div class="flex items-center gap-4">
            <div class="text-right hidden md:block">
                <div class="text-[10px] uppercase font-bold tracking-widest opacity-50">Total Duration</div>
                <div class="font-mono text-xl font-bold dark:text-gray-200">
                    @if ($attendance->check_out)
                        {{ $attendance->check_in->diff($attendance->check_out)->format('%hh %im') }}
                    @else
                        <span class="loading loading-dots loading-xs"></span>
                    @endif
                </div>
            </div>
            <div
                class="badge p-4 font-bold {{ $attendance->check_out ? 'badge-success text-white' : 'badge-warning text-black' }}">
                {{ $attendance->check_out ? 'COMPLETED' : 'ON GOING' }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT COLUMN: TIME & LOCATION --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- 1. TIMELINE CARD --}}
            <div class="card bg-base-100 dark:bg-gray-900 shadow-sm border border-base-200 dark:border-gray-700">
                <div class="card-body p-0">
                    <div class="grid grid-cols-2 divide-x divide-base-200 dark:divide-gray-700">
                        {{-- Check In Section --}}
                        <div class="p-6 relative group">
                            <div class="absolute top-0 left-0 w-1 h-full bg-primary"></div>
                            <div class="flex items-center gap-2 mb-2 text-primary">
                                <x-icon name="o-arrow-right-end-on-rectangle" class="w-5 h-5" />
                                <span class="font-bold text-sm tracking-wider">CHECK IN</span>
                            </div>
                            <div class="text-3xl font-black font-mono mb-1 dark:text-gray-100">
                                {{ $attendance->check_in->format('H:i') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $attendance->check_in_latitude ? 'Location Recorded' : 'No Location Data' }}
                            </div>
                        </div>

                        {{-- Check Out Section --}}
                        <div class="p-6 relative">
                            <div
                                class="flex items-center gap-2 mb-2 {{ $attendance->check_out ? 'text-error' : 'text-gray-400' }}">
                                <x-icon name="o-arrow-right-start-on-rectangle" class="w-5 h-5" />
                                <span class="font-bold text-sm tracking-wider">CHECK OUT</span>
                            </div>
                            <div class="text-3xl font-black font-mono mb-1 dark:text-gray-100">
                                {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '--:--' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $attendance->check_out_latitude ? 'Location Recorded' : 'Waiting for checkout...' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. MAP VISUALIZATION --}}
            @if ($attendance->check_in_latitude || $attendance->check_out_latitude)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Map In --}}
                    @if ($attendance->check_in_latitude)
                        <div
                            class="rounded-xl overflow-hidden border border-base-200 dark:border-gray-700 h-64 relative shadow-sm group">
                            <div
                                class="absolute top-3 left-3 z-10 bg-white/90 dark:bg-black/80 backdrop-blur px-3 py-1 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1">
                                <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div> Start Location
                            </div>
                            <iframe width="100%" height="100%" frameborder="0" style="border:0"
                                class="filter grayscale hover:grayscale-0 transition duration-500"
                                src="https://maps.google.com/maps?q={{ $attendance->check_in_latitude }},{{ $attendance->check_in_longitude }}&hl=es;z=14&output=embed">
                            </iframe>
                            <a href="https://maps.google.com/maps?q={{ $attendance->check_in_latitude }},{{ $attendance->check_in_longitude }}"
                                target="_blank"
                                class="absolute bottom-3 right-3 btn btn-xs btn-circle btn-ghost bg-white/80 dark:bg-black/50 hover:bg-white dark:hover:bg-black shadow-md">
                                <x-icon name="o-arrow-top-right-on-square" class="w-4 h-4" />
                            </a>
                        </div>
                    @endif

                    {{-- Map Out --}}
                    @if ($attendance->check_out_latitude)
                        <div
                            class="rounded-xl overflow-hidden border border-base-200 dark:border-gray-700 h-64 relative shadow-sm group">
                            <div
                                class="absolute top-3 left-3 z-10 bg-white/90 dark:bg-black/80 backdrop-blur px-3 py-1 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1">
                                <div class="w-2 h-2 rounded-full bg-error"></div> End Location
                            </div>
                            <iframe width="100%" height="100%" frameborder="0" style="border:0"
                                class="filter grayscale hover:grayscale-0 transition duration-500"
                                src="https://maps.google.com/maps?q={{ $attendance->check_out_latitude }},{{ $attendance->check_out_longitude }}&hl=es;z=14&output=embed">
                            </iframe>
                            <a href="https://maps.google.com/maps?q={{ $attendance->check_out_latitude }},{{ $attendance->check_out_longitude }}"
                                target="_blank"
                                class="absolute bottom-3 right-3 btn btn-xs btn-circle btn-ghost bg-white/80 dark:bg-black/50 hover:bg-white dark:hover:bg-black shadow-md">
                                <x-icon name="o-arrow-top-right-on-square" class="w-4 h-4" />
                            </a>
                        </div>
                    @elseif($attendance->check_in && !$attendance->check_out)
                        <div
                            class="rounded-xl border border-dashed border-base-300 dark:border-gray-700 h-64 flex flex-col items-center justify-center text-gray-400 bg-base-50 dark:bg-gray-800/50">
                            <x-icon name="o-map" class="w-10 h-10 opacity-20 mb-2" />
                            <span class="text-xs">Location will appear after checkout</span>
                        </div>
                    @endif
                </div>
            @endif

            {{-- 3. WORK LOG (TASK REPORT) --}}
            <div class="card bg-base-100 dark:bg-gray-900 shadow-sm border border-base-200 dark:border-gray-700">
                <div class="card-body">
                    <h3 class="card-title text-base flex items-center gap-2 mb-4">
                        <div class="p-2 bg-base-200 dark:bg-gray-800 rounded-lg"><x-icon
                                name="o-clipboard-document-check" class="w-5 h-5" /></div>
                        Work Log & Achievements
                    </h3>

                    @if ($attendance->reports->count() > 0)
                        <div class="space-y-4">
                            @foreach ($attendance->reports as $report)
                                <div
                                    class="relative pl-6 pb-2 border-l-2 border-base-200 dark:border-gray-700 last:border-l-0 last:pb-0">
                                    <div
                                        class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 border-base-100 dark:border-gray-900 {{ $report->status_at_report == 'completed' ? 'bg-success' : 'bg-info' }}">
                                    </div>

                                    <div
                                        class="bg-base-50 dark:bg-gray-800/50 p-4 rounded-xl border border-base-200 dark:border-gray-700 hover:border-primary/30 transition group">
                                        {{-- Task Header --}}
                                        <div class="flex justify-between items-start mb-2 gap-2">
                                            <div class="flex-1">
                                                <div
                                                    class="text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-0.5">
                                                    {{ $report->jobdesk->project->name['id'] ?? 'Unknown Project' }}
                                                </div>

                                                {{-- [UPDATE] Link ke Detail Jobdesk (Halaman Revision) --}}
                                                <a href="{{ route('user.jobdesks.revision', $report->jobdesk_id) }}"
                                                    class="inline-flex items-center gap-1 font-bold text-sm md:text-base dark:text-gray-200 hover:text-primary dark:hover:text-primary transition">
                                                    <span>{{ $report->jobdesk->title['id'] ?? ($report->jobdesk->title['en'] ?? '-') }}</span>
                                                    <x-icon name="o-arrow-top-right-on-square"
                                                        class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity" />
                                                </a>
                                            </div>

                                            <span
                                                class="badge badge-sm font-bold uppercase shrink-0 mt-1 {{ $report->status_at_report == 'completed' ? 'badge-success text-white' : 'badge-ghost' }}">
                                                {{ str_replace('_', ' ', $report->status_at_report) }}
                                            </span>
                                        </div>

                                        {{-- Notes --}}
                                        @foreach ($report->details as $detail)
                                            <div
                                                class="text-sm text-gray-600 dark:text-gray-400 mb-3 bg-white dark:bg-gray-800 p-3 rounded-lg border border-base-100 dark:border-gray-700/50">
                                                {{ $detail->content }}
                                            </div>
                                        @endforeach

                                        {{-- Attachments --}}
                                        @if ($report->attachments->count() > 0)
                                            <div
                                                class="flex flex-wrap gap-2 pt-2 border-t border-base-200 dark:border-gray-700/50">
                                                @foreach ($report->attachments as $att)
                                                    @php
                                                        $url = asset('storage/' . $att->file_path);
                                                        $isImage = \Illuminate\Support\Str::startsWith(
                                                            $att->file_type,
                                                            'image',
                                                        );
                                                        $globalIndex = $isImage
                                                            ? array_search($url, $taskImagesArray)
                                                            : false;
                                                    @endphp

                                                    {{-- IMAGE ATTACHMENT WITH LIGHTBOX --}}
                                                    <div @click.prevent="$dispatch('open-lightbox', { url: '{{ $url }}', index: {{ $globalIndex !== false ? $globalIndex : 0 }} })"
                                                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white dark:bg-gray-800 border border-base-200 dark:border-gray-700 text-xs hover:border-primary hover:text-primary transition cursor-pointer group/att">
                                                        <div
                                                            class="w-8 h-8 rounded bg-base-200 dark:bg-gray-700 overflow-hidden flex-shrink-0">
                                                            <img src="{{ $url }}"
                                                                class="w-full h-full object-cover group-hover/att:scale-110 transition">
                                                        </div>
                                                        <div class="flex flex-col">
                                                            <span
                                                                class="font-bold truncate max-w-[100px]">{{ $att->file_name }}</span>
                                                            <span
                                                                class="text-[10px] text-gray-400 group-hover/att:text-primary">Click
                                                                to view</span>
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
                        <div
                            class="text-center py-10 opacity-50 border-2 border-dashed border-base-200 dark:border-gray-700 rounded-xl">
                            <x-icon name="o-clipboard" class="w-10 h-10 mx-auto mb-2 opacity-20" />
                            <p>No detailed reports submitted for this session.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: SELFIE PROOF --}}
        <div class="space-y-6">
            <div
                class="card bg-base-100 dark:bg-gray-900 shadow-sm border border-base-200 dark:border-gray-700 sticky top-6">
                <div class="card-body p-5">
                    <h3 class="card-title text-base mb-4 flex items-center gap-2">
                        <x-icon name="o-face-smile" class="w-5 h-5 text-primary" /> Visual Verification
                    </h3>

                    <div class="space-y-6">
                        {{-- Selfie In --}}
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-xs font-bold uppercase opacity-60">Start Shift</span>
                                <span
                                    class="text-xs font-mono opacity-40">{{ $attendance->check_in->format('H:i') }}</span>
                            </div>
                            <div class="aspect-video rounded-xl bg-black overflow-hidden relative shadow-md group">
                                @if ($attendance->selfie_in)
                                    {{-- OPEN SELFIE IN LIGHTBOX --}}
                                    <img src="{{ asset('storage/' . $attendance->selfie_in) }}"
                                        @click.prevent="$dispatch('open-lightbox', { url: '{{ asset('storage/' . $attendance->selfie_in) }}', index: 0 })"
                                        class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition duration-500 cursor-zoom-in">
                                    <div
                                        class="absolute bottom-2 left-2 bg-black/50 text-white text-[10px] px-2 py-0.5 rounded backdrop-blur-md pointer-events-none">
                                        Verified
                                    </div>
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-500">
                                        <x-icon name="o-user" class="w-10 h-10 opacity-20" />
                                        <span class="text-xs mt-2">No Image</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Connector Line --}}
                        <div class="flex justify-center -my-2">
                            <div
                                class="h-8 w-0.5 bg-gradient-to-b from-transparent via-base-300 dark:via-gray-600 to-transparent">
                            </div>
                        </div>

                        {{-- Selfie Out --}}
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-xs font-bold uppercase opacity-60">End Shift</span>
                                <span class="text-xs font-mono opacity-40">
                                    {{ $attendance->check_out ? $attendance->check_out->format('H:i') : 'Active' }}
                                </span>
                            </div>
                            <div class="aspect-video rounded-xl bg-black overflow-hidden relative shadow-md group">
                                @if ($attendance->selfie_out)
                                    {{-- OPEN SELFIE OUT IN LIGHTBOX --}}
                                    <img src="{{ asset('storage/' . $attendance->selfie_out) }}"
                                        @click.prevent="$dispatch('open-lightbox', { url: '{{ asset('storage/' . $attendance->selfie_out) }}', index: 0 })"
                                        class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition duration-500 cursor-zoom-in">
                                    <div
                                        class="absolute bottom-2 left-2 bg-black/50 text-white text-[10px] px-2 py-0.5 rounded backdrop-blur-md pointer-events-none">
                                        Verified
                                    </div>
                                @else
                                    <div
                                        class="w-full h-full flex flex-col items-center justify-center text-gray-500 bg-base-200 dark:bg-gray-800">
                                        @if (!$attendance->check_out)
                                            <span
                                                class="loading loading-ring loading-lg text-primary opacity-50"></span>
                                            <span class="text-xs mt-2 font-bold text-primary animate-pulse">Shift
                                                Active</span>
                                        @else
                                            <x-icon name="o-no-symbol" class="w-8 h-8 opacity-20" />
                                            <span class="text-xs mt-2">Missing</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Panggil Reusable Component Lightbox HANYA untuk attachment tugas --}}
    <x-lightbox :images="$taskImagesArray" />
</div>
