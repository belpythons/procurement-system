@extends('layouts.app')

@section('title', 'Buat Permit Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex items-center gap-4 border-b pb-4">
        <a href="{{ route('pekerja.dashboard') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
            <i class="ph ph-arrow-left text-2xl"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Form Pengajuan Permit</h2>
            <p class="text-sm text-gray-500">Isi detail pekerjaan dengan lengkap. Supervisor dan Safety Officer akan di-assign otomatis.</p>
        </div>
    </div>

    <!-- Form Section -->
    <form action="{{ route('pekerja.permit.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Jenis Pekerjaan -->
            <div class="col-span-1 md:col-span-2">
                <label for="jenis_pekerjaan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Pekerjaan <span class="text-red-500">*</span></label>
                <select id="jenis_pekerjaan" name="jenis_pekerjaan" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="" disabled selected>Pilih jenis pekerjaan...</option>
                    <option value="Hot Work">Hot Work (Pekerjaan Panas)</option>
                    <option value="Cold Work">Cold Work (Pekerjaan Dingin)</option>
                    <option value="Confined Space">Confined Space (Ruang Terbatas)</option>
                    <option value="Listrik & Instrument">Listrik & Instrument</option>
                    <option value="Penggalian">Penggalian</option>
                    <option value="Kendaraan & Alat Berat">Kendaraan & Alat Berat</option>
                </select>
                @error('jenis_pekerjaan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Judul Pekerjaan -->
            <div class="col-span-1 md:col-span-2">
                <label for="nama_pekerjaan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul Pekerjaan <span class="text-red-500">*</span></label>
                <input type="text" id="nama_pekerjaan" name="nama_pekerjaan" required placeholder="Contoh: Pengelasan pipa jalur A" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                @error('nama_pekerjaan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Gedung & Lokasi -->
            <div>
                <label for="gedung" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gedung / Area</label>
                <input type="text" id="gedung" name="gedung" placeholder="Contoh: Control Building" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            <div>
                <label for="lokasi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Detail Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" placeholder="Contoh: Lantai 2 Ruang Server" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>

            <!-- Tanggal & Waktu -->
            <div class="col-span-1 md:col-span-2 grid grid-cols-3 gap-4">
                <div>
                    <label for="tanggal_kerja" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal_kerja" name="tanggal_kerja" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
                <div>
                    <label for="jam_mulai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Mulai</label>
                    <input type="time" id="jam_mulai" name="jam_mulai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
                <div>
                    <label for="jam_selesai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jam Selesai</label>
                    <input type="time" id="jam_selesai" name="jam_selesai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
            </div>

            <!-- Tingkat Risiko -->
            <div class="col-span-1 md:col-span-2">
                <label class="block mb-3 text-sm font-medium text-gray-900 dark:text-white">Estimasi Tingkat Risiko (Self-Assessment)</label>
                <ul class="grid w-full gap-4 md:grid-cols-3">
                    <li>
                        <input type="radio" id="risiko_rendah" name="tingkat_risiko" value="Risiko Rendah" class="hidden peer" required>
                        <label for="risiko_rendah" class="inline-flex items-center justify-between w-full p-4 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-green-500 peer-checked:bg-green-50 hover:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors">                           
                            <div class="block">
                                <div class="w-full text-sm font-semibold">Risiko Rendah</div>
                            </div>
                            <i class="ph ph-shield-check text-xl"></i>
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="risiko_sedang" name="tingkat_risiko" value="Risiko Sedang" class="hidden peer">
                        <label for="risiko_sedang" class="inline-flex items-center justify-between w-full p-4 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors">                           
                            <div class="block">
                                <div class="w-full text-sm font-semibold">Risiko Sedang</div>
                            </div>
                            <i class="ph ph-warning-circle text-xl"></i>
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="risiko_tinggi" name="tingkat_risiko" value="Risiko Tinggi" class="hidden peer">
                        <label for="risiko_tinggi" class="inline-flex items-center justify-between w-full p-4 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-red-500 peer-checked:bg-red-50 hover:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors">                           
                            <div class="block">
                                <div class="w-full text-sm font-semibold">Risiko Tinggi</div>
                            </div>
                            <i class="ph ph-warning text-xl"></i>
                        </label>
                    </li>
                </ul>
            </div>

            <!-- APD Checkboxes -->
            <div class="col-span-1 md:col-span-2 border-t pt-4 mt-2">
                <label class="block mb-3 text-sm font-medium text-gray-900 dark:text-white">Alat Pelindung Diri (APD) yang akan digunakan</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @php
                        $apds = [
                            'Helm Safety', 'Sepatu Safety', 'Kacamata Safety', 
                            'Sarung Tangan', 'Masker Debu', 'Masker Gas', 
                            'Earplug / Earmuff', 'Safety Harness', 'Baju Pelindung',
                            'Face Shield', 'Rompi Reflektor', 'APAR'
                        ];
                    @endphp
                    @foreach($apds as $apd)
                    <div class="flex items-center">
                        <input id="apd_{{ $loop->index }}" type="checkbox" name="apd[]" value="{{ $apd }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="apd_{{ $loop->index }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $apd }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="col-span-1 md:col-span-2 border-t pt-4 mt-2">
                <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi Rinci Pekerjaan</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Jelaskan secara detail metode kerja, alat berat yang digunakan, dan potensi bahaya..."></textarea>
            </div>

        </div>

        <!-- Submit Action -->
        <div class="mt-8 flex justify-end gap-3 border-t pt-5">
            <a href="{{ route('pekerja.dashboard') }}" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Batal</a>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none flex items-center gap-2">
                <i class="ph ph-paper-plane-right"></i>
                Ajukan Permit
            </button>
        </div>

    </form>
</div>
@endsection