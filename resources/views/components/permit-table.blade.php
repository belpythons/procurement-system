@props(['permits', 'actionRoutePrefix' => null])

<div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b">
            <tr>
                <th scope="col" class="px-6 py-4">No. Permit</th>
                <th scope="col" class="px-6 py-4">Pekerja</th>
                <th scope="col" class="px-6 py-4">Pekerjaan</th>
                <th scope="col" class="px-6 py-4">Tanggal & Lokasi</th>
                <th scope="col" class="px-6 py-4 text-center">Tingkat Risiko</th>
                <th scope="col" class="px-6 py-4 text-center">Status</th>
                <th scope="col" class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permits as $permit)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    
                    <!-- No Permit -->
                    <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $permit->nomor_permit }}
                    </td>
                    
                    <!-- Pekerja & Dept -->
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $permit->user->name ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $permit->department->nama_departemen ?? '-' }}</div>
                    </td>

                    <!-- Pekerjaan -->
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $permit->jenis_pekerjaan }}</div>
                        <div class="text-xs text-gray-500 truncate max-w-[200px]" title="{{ $permit->nama_pekerjaan }}">
                            {{ $permit->nama_pekerjaan }}
                        </div>
                    </td>

                    <!-- Tanggal & Lokasi -->
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $permit->tanggal_kerja?->format('d M Y') ?? '-' }}</div>
                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                            <i class="ph ph-map-pin"></i> {{ $permit->lokasi ?? '-' }}
                        </div>
                    </td>

                    <!-- Tingkat Risiko -->
                    <td class="px-6 py-4 text-center">
                        @php
                            $risikoClass = match(strtolower($permit->tingkat_risiko)) {
                                'risiko tinggi' => 'bg-red-100 text-red-800 border-red-200',
                                'risiko sedang' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'risiko rendah' => 'bg-green-100 text-green-800 border-green-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200'
                            };
                        @endphp
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border {{ $risikoClass }}">
                            {{ $permit->tingkat_risiko ?? 'Belum Dinilai' }}
                        </span>
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4 text-center">
                        @php
                            $statusColor = match($permit->status) {
                                'Pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                'Disetujui' => 'bg-blue-100 text-blue-800 border-blue-300',
                                'Selesai' => 'bg-green-100 text-green-800 border-green-300',
                                'Ditolak' => 'bg-red-100 text-red-800 border-red-300',
                                default => 'bg-gray-100 text-gray-800 border-gray-300'
                            };
                            
                            $statusIcon = match($permit->status) {
                                'Pending' => 'ph-clock',
                                'Disetujui' => 'ph-check-circle',
                                'Selesai' => 'ph-flag-checkered',
                                'Ditolak' => 'ph-x-circle',
                                default => 'ph-info'
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium border {{ $statusColor }}">
                            <i class="ph {{ $statusIcon }}"></i>
                            {{ $permit->status }}
                        </span>
                    </td>

                    <!-- Aksi -->
                    <td class="px-6 py-4 text-center">
                        @php
                            // Tentukan route dinamis berdasarkan role yang login jika tidak di-pass manual
                            if (!$actionRoutePrefix) {
                                $role = auth()->user()->role;
                                $actionRoutePrefix = match($role) {
                                    'admin' => 'admin',
                                    'pekerja' => 'pekerja',
                                    'supervisor' => 'supervisor',
                                    'safety officer' => 'safety_officer',
                                    default => 'pekerja'
                                };
                            }
                        @endphp

                        <a href="{{ route($actionRoutePrefix . '.permit.detail', $permit->id) }}" 
                           class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 shadow-sm transition-all">
                            <i class="ph ph-eye text-sm"></i> Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 bg-gray-50">
                        <div class="flex flex-col items-center justify-center">
                            <i class="ph ph-folder-open text-4xl text-gray-400 mb-2"></i>
                            <p class="text-sm">Tidak ada data permit yang ditemukan.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Pagination -->
    @if($permits->hasPages())
        <div class="p-4 border-t border-gray-200">
            {{ $permits->links('pagination::tailwind') }}
        </div>
    @endif
</div>
