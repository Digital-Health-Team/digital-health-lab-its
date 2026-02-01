<div class="space-y-8">
    
    {{-- CARD 1: FORM --}}
    <x-card title="Input Logbook Harian" subtitle="Jangan lupa untuk memasukkan data logbook anda hari ini" separator progress-indicator="save">
        <x-form wire:submit="create">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Date --}}
                <x-datetime label="Tanggal" wire:model="date" icon="o-calendar" />
                
                {{-- File Proof --}}
                <x-file label="Bukti Kegiatan (Gambar)" wire:model="proof" accept="image/png, image/jpeg" />
                
                {{-- Activity --}}
                <div class="md:col-span-2">
                    <x-textarea label="Aktifitas" wire:model="activity" placeholder="Deskripsikan kegiatan anda hari ini..." rows="3" hint="Minimal 10 karakter" />
                </div>
            </div>
            
            <x-slot:actions>
                <x-button label="Simpan" class="btn-primary" type="submit" spinner="create" icon="o-plus" />
            </x-slot:actions>
        </x-form>
    </x-card>

    {{-- CARD 2: RECAP STATS --}}
    <div>
        <div class="text-xl font-bold mb-4">Rekap Logbook per {{ $monthName }}</div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Pending --}}
            <div class="bg-primary text-primary-content p-6 rounded-lg relative overflow-hidden">
                <div class="absolute right-0 top-0 p-4 opacity-20">
                     <x-icon name="o-clock" class="w-16 h-16" />
                </div>
                <div class="relative z-10">
                    <div class="font-bold text-lg">Menunggu Validasi</div>
                    <div class="text-4xl font-extrabold mt-2">{{ $stats['pending'] }}</div>
                     <div class="text-xs mt-1 opacity-80">Logbook</div>
                </div>
            </div>

            {{-- Validated --}}
            <div class="bg-success text-success-content p-6 rounded-lg relative overflow-hidden">
                 <div class="absolute right-0 top-0 p-4 opacity-20">
                     <x-icon name="o-check-circle" class="w-16 h-16" />
                </div>
                <div class="relative z-10">
                    <div class="font-bold text-lg">Disetujui</div>
                    <div class="text-4xl font-extrabold mt-2">{{ $stats['validated'] }}</div>
                     <div class="text-xs mt-1 opacity-80">Logbook</div>
                </div>
            </div>

            {{-- Rejected --}}
            <div class="bg-error text-error-content p-6 rounded-lg relative overflow-hidden">
                 <div class="absolute right-0 top-0 p-4 opacity-20">
                     <x-icon name="o-x-circle" class="w-16 h-16" />
                </div>
                <div class="relative z-10">
                    <div class="font-bold text-lg">Ditolak</div>
                    <div class="text-4xl font-extrabold mt-2">{{ $stats['rejected'] }}</div>
                     <div class="text-xs mt-1 opacity-80">Logbook</div>
                </div>
            </div>
        </div>
    </div>

    {{-- CARD 3: TABLE --}}
    <x-card>
        {{-- Custom Header for Filters --}}
        <div class="mb-6">
            <h3 class="text-xl font-bold">Data Logbook</h3>
            <p class="text-sm text-gray-500 mb-4">Berikut adalah data logbook anda</p>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                {{-- Search --}}
                <div class="md:col-span-4">
                    <div class="text-sm font-bold mb-1">Cari</div>
                    <x-input icon="o-magnifying-glass" wire:model.live.debounce="search" placeholder="Cari aktivitas..." class="w-full" />
                </div>
                {{-- Date Start --}}
                <div class="md:col-span-2">
                    <div class="text-sm font-bold mb-1">Dari Tanggal</div>
                    <x-datetime wire:model.live="startDate" type="date" class="w-full" />
                </div>
                {{-- Date End --}}
                <div class="md:col-span-2">
                    <div class="text-sm font-bold mb-1">Sampai Tanggal</div>
                    <x-datetime wire:model.live="endDate" type="date" class="w-full" />
                </div>
                {{-- Status --}}
                <div class="md:col-span-2">
                    <div class="text-sm font-bold mb-1">Status</div>
                    <x-select wire:model.live="filterStatus" :options="[['id' => 'all', 'name' => 'Semua'], ['id' => 'pending', 'name' => 'Menunggu'], ['id' => 'validated', 'name' => 'Disetujui'], ['id' => 'rejected', 'name' => 'Ditolak']]" class="w-full" />
                </div>
                
                {{-- Reset --}}
                <div class="md:col-span-2 flex justify-end">
                     <x-button label="Reset" icon="o-arrow-path" wire:click="resetFilters" class="btn-error" />
                </div>
            </div>
        </div>

        @if($logbooks->count() > 0)
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <!-- head -->
                    <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Aktivitas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logbooks as $logbook)
                        <tr>
                            <td>{{ $logbook->date->translatedFormat('d F Y') }}</td>
                            <td>{{ Str::limit($logbook->activity, 50) }}</td>
                            <td>
                                @if($logbook->status == 'pending')
                                    <div class="badge badge-primary">Menunggu</div>
                                @elseif($logbook->status == 'validated')
                                    <div class="badge badge-success">Disetujui</div>
                                @else
                                    <div class="badge badge-error">Ditolak</div>
                                @endif
                            </td>
                            <td>
                                <x-button icon="o-trash" class="btn-sm btn-error" wire:click="confirmDelete({{ $logbook->id }})" tooltip="Delete" />
                                <x-button icon="o-pencil" class="btn-sm btn-warning" wire:click="edit({{ $logbook->id }})" tooltip="Edit" />
                                <x-button icon="o-eye" class="btn-sm btn-primary" wire:click="showLogbook({{ $logbook->id }})" tooltip="Detail" />
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="mt-4">
                {{ $logbooks->links() }}
            </div>
        @else
             <div class="text-center py-10">
                 <x-icon name="o-inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                 <div class="text-gray-500">Belum ada data logbook bulan ini.</div>
             </div>
        @endif
    </x-card>

    {{-- DELETE CONFIRMATION MODAL --}}
    <x-modal wire:model="deleteModal" title="Hapus Logbook?" subtitle="Apakah anda yakin ingin menghapus data ini?">
        <div>Data yang dihapus tidak dapat dikembalikan.</div>
        <x-slot:actions>
            <x-button label="Batal" @click="$wire.deleteModal = false" />
            <x-button label="Hapus" class="btn-error" wire:click="delete" />
        </x-slot:actions>
    </x-modal>

    {{-- DETAIL DRAWER (RIGHT SIDED) --}}
    <x-drawer wire:model="detailDrawer" title="Detail Logbook" separator with-close-button class="w-11/12 lg:w-1/3" right>
        @if($selectedLogbook)
            <div class="space-y-4">
                <div>
                    <div class="font-bold text-sm text-gray-500">Tanggal</div>
                    <div>{{ $selectedLogbook->date->translatedFormat('l, d F Y') }}</div>
                </div>

                <div>
                    <div class="font-bold text-sm text-gray-500">Status</div>
                    @if($selectedLogbook->status == 'pending')
                        <div class="badge badge-primary">Menunggu Validasi</div>
                    @elseif($selectedLogbook->status == 'validated')
                        <div class="badge badge-success">Disetujui</div>
                    @else
                        <div class="badge badge-error">Ditolak</div>
                    @endif
                </div>

                <div>
                    <div class="font-bold text-sm text-gray-500 mb-4">Aktivitas</div>
                    <div class="whitespace-pre-wrap bg-base-200 p-4 rounded-lg">{{ $selectedLogbook->activity }}</div>
                </div>

                @if($selectedLogbook->proof_file_path)
                    <div>
                        <div class="font-bold text-sm text-gray-500 mb-2">Bukti Kegiatan</div>
                        <img src="{{ asset('storage/'.$selectedLogbook->proof_file_path) }}" class="rounded-lg shadow w-full" alt="Bukti Kegiatan">
                    </div>
                @endif
                
                @if($selectedLogbook->feedback)
                    <div>
                        <div class="font-bold text-sm text-gray-500 mb-4">Catatan Dosen/Pembimbing</div>
                        <div class="whitespace-pre-wrap bg-base-200 p-4 rounded-lg">"{{ $selectedLogbook->feedback }}"</div>
                    </div>
                @endif
            </div>
        @endif
    </x-drawer>

    {{-- EDIT DRAWER --}}
    <x-drawer wire:model="editDrawer" title="Edit Logbook" separator with-close-button class="w-11/12 lg:w-1/3" right>
        <x-form wire:submit="update">
            <div class="grid grid-cols-1 gap-4">
                {{-- Date --}}
                <x-datetime label="Tanggal" wire:model="editDate" icon="o-calendar" />
                
                {{-- Activity --}}
                <x-textarea label="Aktifitas" wire:model="editActivity" placeholder="Deskripsikan kegiatan anda hari ini..." rows="5" hint="Minimal 10 karakter" />
                
                {{-- File Proof --}}
                <div>
                    <x-file label="Bukti Baru (Opsional)" wire:model="editProof" accept="image/png, image/jpeg" />
                    <div class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah bukti.</div>
                </div>
            </div>
            
            <x-slot:actions>
                <x-button label="Batal" @click="$wire.editDrawer = false" />
                <x-button label="Update" class="btn-warning" type="submit" spinner="update" icon="o-pencil" />
            </x-slot:actions>
        </x-form>
    </x-drawer>
</div>
