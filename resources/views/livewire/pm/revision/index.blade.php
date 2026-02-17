<div class="h-[calc(100vh-6rem)] flex flex-col" x-data="{
    galleryOpen: false,
    currentImage: '',
    openGallery(url) { this.currentImage = url;
        this.galleryOpen = true; },
    closeGallery() { this.galleryOpen = false; }
}">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 px-1 gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs md:text-sm text-gray-500 mb-1">
                <a href="{{ route('pm.dashboard') }}" class="hover:underline flex items-center gap-1">
                    <x-icon name="o-arrow-left" class="w-3 h-3" /> Dashboard
                </a>
                <span class="opacity-50">/</span>
                @php
                    $pName = $jobdesk->project->name;
                    if (is_array($pName)) {
                        $pName = $pName['id'] ?? ($pName['en'] ?? '-');
                    }

                    $tTitle = $jobdesk->title;
                    if (is_array($tTitle)) {
                        $tTitle = $tTitle['id'] ?? ($tTitle['en'] ?? '-');
                    }
                @endphp
                <span class="truncate max-w-[150px]">{{ $pName }}</span>
            </div>
            <h1 class="text-xl md:text-2xl font-bold flex items-center gap-2">
                <span class="truncate max-w-[300px]">{{ $tTitle }}</span>
                <div
                    class="badge {{ match ($jobdesk->status) {'revision' => 'badge-error','approved' => 'badge-success',default => 'badge-warning'} }}">
                    {{ strtoupper($jobdesk->status) }}
                </div>
            </h1>
        </div>

        <div class="flex gap-2">
            <x-button label="Approve Task" icon="o-check" class="btn-success text-white btn-sm"
                wire:confirm="Are you sure this task is complete?" wire:click="markAsApproved" />
        </div>
    </div>

    <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-6 overflow-hidden min-h-0">

        {{-- LEFT: CHAT HISTORY --}}
        <div
            class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col overflow-hidden shadow-sm h-full">
            <div
                class="p-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 font-bold text-sm">
                Discussion History
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-6 bg-gray-50/50 dark:bg-black/20 flex flex-col-reverse">
                {{-- Note: flex-col-reverse to stick to bottom usually, but here we iterate normal --}}
                @forelse($jobdesk->revisionThreads->sortBy('created_at') as $thread)
                    <div class="flex gap-3 {{ !$thread->is_staff_reply ? 'flex-row-reverse' : '' }}">
                        <x-avatar :image="$thread->user->profile_photo
                            ? asset('storage/' . $thread->user->profile_photo)
                            : null" class="w-8 h-8 flex-shrink-0" />

                        <div class="max-w-[85%]">
                            <div
                                class="flex items-center gap-2 mb-1 {{ !$thread->is_staff_reply ? 'justify-end' : '' }}">
                                <span class="text-xs font-bold">{{ $thread->user->name }}</span>
                                <span class="text-[10px] opacity-50">{{ $thread->created_at->format('d M H:i') }}</span>
                            </div>

                            <div
                                class="p-3 rounded-2xl text-sm shadow-sm border border-gray-100 dark:border-gray-700
                                {{ !$thread->is_staff_reply
                                    ? 'bg-primary/10 text-gray-800 dark:text-gray-200 rounded-tr-none'
                                    : 'bg-white dark:bg-gray-800 rounded-tl-none' }}">
                                {!! nl2br(e($thread->content)) !!}
                            </div>

                            @if ($thread->attachments->count() > 0)
                                <div
                                    class="flex flex-wrap gap-2 mt-2 {{ !$thread->is_staff_reply ? 'justify-end' : '' }}">
                                    @foreach ($thread->attachments as $att)
                                        <div @click="openGallery('{{ asset('storage/' . $att->file_path) }}')"
                                            class="w-16 h-16 rounded-lg bg-gray-200 overflow-hidden cursor-pointer hover:ring-2 hover:ring-primary">
                                            <img src="{{ asset('storage/' . $att->file_path) }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 opacity-30">No discussion yet.</div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT: INPUT FORM --}}
        <div
            class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl flex flex-col shadow-sm h-full overflow-hidden">
            <div class="p-3 border-b bg-gray-50 dark:bg-gray-800 font-bold text-sm">
                Give Instruction / Revision
            </div>
            <div class="flex-1 overflow-y-auto p-4">
                <x-form wire:submit="sendInstruction" class="flex flex-col h-full gap-4">
                    <x-textarea label="Instruction" wire:model="content" placeholder="Type your instruction here..."
                        rows="6" class="flex-1" />

                    <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg border border-dashed border-gray-300">
                        <x-file label="Attachments" wire:model="attachments" accept="image/*" multiple
                            hint="Max 10MB" />
                    </div>

                    <x-button label="Send Instruction" class="btn-warning w-full text-white" type="submit"
                        spinner="sendInstruction" icon="o-paper-airplane" />
                </x-form>
            </div>
        </div>
    </div>

    {{-- LIGHTBOX --}}
    <div x-show="galleryOpen" class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4"
        x-transition.opacity style="display: none;">
        <button @click="closeGallery()" class="absolute top-4 right-4 text-white p-2"><x-icon name="o-x-mark"
                class="w-8 h-8" /></button>
        <img :src="currentImage" class="max-w-full max-h-full rounded shadow-2xl">
    </div>
</div>
