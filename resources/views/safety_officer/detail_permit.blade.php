@extends('layouts.app')

@section('title', 'Detail Permit')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 pb-10">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <a href="javascript:history.back()" class="text-gray-500 hover:text-blue-600 transition-colors">
                <i class="ph ph-arrow-left text-2xl"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Permit <span class="text-blue-600">#{{ $permit->nomor_permit }}</span></h2>
            </div>
        </div>
        
        @php
            $statusColor = match($permit->status) {
                'Pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                'Disetujui' => 'bg-blue-100 text-blue-800 border-blue-300',
                'Selesai' => 'bg-green-100 text-green-800 border-green-300',
                'Ditolak' => 'bg-red-100 text-red-800 border-red-300',
                default => 'bg-gray-100 text-gray-800 border-gray-300'
            };
        @endphp
        <div class="px-4 py-2 rounded-lg border {{ $statusColor }} flex items-center gap-2 font-semibold text-sm">
            Status Terkini: {{ $permit->status }}
        </div>
    </div>

    <!-- Safety Officer Action Form (Only if Pending or Disetujui AND no evaluation yet) -->
    @if(in_array($permit->status, ['Pending', 'Disetujui']) && is_null($permit->evaluasi_risiko))
    <div class="bg-orange-50 border-2 border-orange-300 rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="ph-fill ph-shield-check text-orange-600 text-2xl"></i>
            <h3 class="text-lg font-bold text-orange-900">Form Evaluasi K3 (Safety Officer)</h3>
        </div>
        
        <form method="POST" action="{{ route('safety_officer.permit.evaluasi', $permit->id) }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div class="col-span-1">
                    <label for="tingkat_risiko" class="block mb-2 text-sm font-medium text-gray-900">Penilaian Final Tingkat Risiko <span class="text-red-500">*</span></label>
                    <select id="tingkat_risiko" name="tingkat_risiko" required class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5">
                        <option value="" disabled selected>Pilih Tingkat Risiko...</option>
                        <option value="Risiko Rendah">Risiko Rendah</option>
                        <option value="Risiko Sedang">Risiko Sedang</option>
                        <option value="Risiko Tinggi">Risiko Tinggi</option>
                    </select>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label for="catatan_safety" class="block mb-2 text-sm font-medium text-gray-900">Catatan Evaluasi / Rekomendasi Tambahan <span class="text-red-500">*</span></label>
                    <textarea id="catatan_safety" name="catatan_safety" required rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500" placeholder="Tuliskan rekomendasi APD tambahan, prosedur LOTO, atau bahaya spesifik..."></textarea>
                    @error('catatan_safety') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <button type="submit" class="text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center gap-2">
                <i class="ph-bold ph-floppy-disk"></i> Simpan Evaluasi K3
            </button>
        </form>
    </div>
    @endif

    <!-- Grid Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri: Info Utama -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Detail Pekerjaan -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800">Informasi Pekerjaan</h3>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
                        <div>
                            <p class="text-sm text-gray-500">Judul Pekerjaan</p>
                            <p class="font-medium text-gray-900">{{ $permit->nama_pekerjaan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jenis Pekerjaan</p>
                            <p class="font-medium text-gray-900">{{ $permit->jenis_pekerjaan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Pelaksanaan</p>
                            <p class="font-medium text-gray-900">
                                <i class="ph ph-calendar-blank text-gray-400 mr-1"></i>
                                {{ $permit->tanggal_kerja?->format('d M Y') ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Waktu (Jam)</p>
                            <p class="font-medium text-gray-900">
                                <i class="ph ph-clock text-gray-400 mr-1"></i>
                                {{ $permit->jam_mulai ?? '-' }} s/d {{ $permit->jam_selesai ?? '-' }}
                            </p>
                        </div>
                        <div class="sm:col-span-2 border-t pt-3">
                            <p class="text-sm text-gray-500">Detail Lokasi</p>
                            <p class="font-medium text-gray-900">
                                {{ $permit->gedung ? $permit->gedung . ' - ' : '' }} {{ $permit->lokasi ?? '-' }}
                            </p>
                        </div>
                        <div class="sm:col-span-2 border-t pt-3">
                            <p class="text-sm text-gray-500">Deskripsi Rinci</p>
                            <p class="text-sm text-gray-800 mt-1 whitespace-pre-line">{{ $permit->deskripsi ?? 'Tidak ada deskripsi spesifik.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- APD & Risiko -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- APD -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 flex items-center gap-2">
                        <i class="ph ph-hard-hat text-blue-600 text-lg"></i>
                        <h3 class="font-semibold text-gray-800">Persyaratan APD</h3>
                    </div>
                    <div class="p-5">
                        @if(is_array($permit->apd) && count($permit->apd) > 0)
                            <ul class="space-y-2">
                                @foreach($permit->apd as $apd_item)
                                <li class="flex items-center gap-2 text-sm text-gray-700">
                                    <i class="ph-fill ph-check-circle text-green-500"></i>
                                    {{ $apd_item }}
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 italic">Tidak ada APD spesifik yang didaftarkan.</p>
                        @endif
                    </div>
                </div>

                <!-- Risiko -->
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-gray-50 px-5 py-3 border-b border-gray-200 flex items-center gap-2">
                        <i class="ph ph-warning-circle text-orange-500 text-lg"></i>
                        <h3 class="font-semibold text-gray-800">Tingkat Risiko</h3>
                    </div>
                    <div class="p-5">
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 mb-1">Self-Assessment Pekerja:</p>
                            <span class="bg-gray-100 text-gray-800 text-sm font-medium px-3 py-1 rounded border border-gray-200">
                                {{ $permit->tingkat_risiko ?? 'Belum dinilai' }}
                            </span>
                        </div>
                        <div class="border-t pt-3">
                            <p class="text-xs text-gray-500 mb-1">Evaluasi Safety Officer Anda:</p>
                            @if($permit->evaluasi_risiko)
                                <p class="text-sm text-gray-800 font-medium">{{ $permit->evaluasi_risiko }}</p>
                            @else
                                <p class="text-xs text-gray-400 italic text-red-500">Anda belum memberikan evaluasi.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Tim & Catatan -->
        <div class="space-y-6">
            
            <!-- Audit Trail / Personel -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800">Personel Terlibat</h3>
                </div>
                <div class="p-5 space-y-4">
                    
                    <!-- Pemohon -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="ph ph-user text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Pemohon (Pekerja)</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $permit->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $permit->department->nama_departemen ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="border-l-2 border-gray-200 ml-5 h-4 my-1"></div>

                    <!-- Supervisor -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                            <i class="ph ph-user-gear text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Supervisor (Penyetuju)</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $permit->supervisor->name ?? '-' }}</p>
                            @if($permit->catatan_supervisor)
                                <div class="mt-2 p-2 bg-gray-50 border border-gray-100 rounded text-xs text-gray-700">
                                    <span class="font-medium text-gray-900">Catatan:</span><br>
                                    {{ $permit->catatan_supervisor }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="border-l-2 border-gray-200 ml-5 h-4 my-1"></div>

                    <!-- Safety Officer -->
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="ph ph-shield-plus text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Safety Officer (Evaluator)</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $permit->safetyOfficer->name ?? '-' }}</p>
                            @if($permit->catatan_safety)
                                <div class="mt-2 p-2 bg-gray-50 border border-gray-100 rounded text-xs text-gray-700">
                                    <span class="font-medium text-gray-900">Catatan Anda:</span><br>
                                    {{ $permit->catatan_safety }}
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection
