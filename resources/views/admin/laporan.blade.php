@extends('layouts.app')

@section('title', 'Laporan & Audit Permit')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Laporan Pusat & Audit</h2>
            <p class="text-sm text-gray-500">Filter, Ekspor, atau Impor data riwayat Permit to Work.</p>
        </div>
        
        <!-- Import/Export Actions -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- Export -->
            <a href="{{ route('admin.laporan.export.excel', request()->query()) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-green-700 bg-green-100 border border-green-200 rounded-lg hover:bg-green-200 transition-colors">
                <i class="ph ph-file-xls text-lg mr-2"></i> Export Excel
            </a>
            <a href="{{ route('admin.laporan.export.pdf', request()->query()) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-red-700 bg-red-100 border border-red-200 rounded-lg hover:bg-red-200 transition-colors">
                <i class="ph ph-file-pdf text-lg mr-2"></i> Export PDF
            </a>
            
            <div class="w-px h-6 bg-gray-300 mx-1 hidden sm:block"></div>
            
            <!-- Import (Admin Only) -->
            <button data-modal-target="modal-import" data-modal-toggle="modal-import" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 border border-blue-200 rounded-lg hover:bg-blue-200 transition-colors">
                <i class="ph ph-upload-simple text-lg mr-2"></i> Import Excel
            </button>
        </div>
    </div>

    <!-- Alert untuk Pesan Error Import -->
    @if(session('import_errors'))
        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
            <span class="font-bold flex items-center gap-2"><i class="ph ph-warning-circle text-lg"></i> Peringatan Import:</span>
            <ul class="mt-1.5 list-disc list-inside">
                @foreach(session('import_errors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Filter Form -->
    <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-sm">
        <form action="{{ route('admin.laporan') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="w-full sm:w-1/4">
                <label for="start_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div class="w-full sm:w-1/4">
                <label for="end_date" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div class="w-full sm:w-1/4">
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="w-full sm:w-1/4 flex gap-2">
                <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 flex justify-center items-center gap-2">
                    <i class="ph ph-funnel"></i> Filter
                </button>
                @if(request()->has('start_date') || request()->has('end_date') || request()->has('status'))
                <a href="{{ route('admin.laporan') }}" class="w-full text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 flex justify-center items-center border border-gray-300">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-0">
            <x-permit-table :permits="$permits" actionRoutePrefix="admin" />
        </div>
    </div>

</div>

<!-- Modal Import Excel -->
<div id="modal-import" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            
            <!-- Header Modal -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">
                    Import Data Permit
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="modal-import">
                    <i class="ph ph-x text-lg"></i>
                </button>
            </div>
            
            <!-- Body Modal -->
            <form action="{{ route('admin.laporan.import') }}" method="POST" enctype="multipart/form-data" class="p-4 md:p-5">
                @csrf
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-4">Unggah file Excel (<code>.xlsx</code>) yang berisi riwayat data Permit to Work. Pastikan Anda telah mengisi data sesuai dengan format template yang disediakan.</p>
                    
                    <a href="{{ route('admin.laporan.template') }}" class="inline-flex items-center justify-center w-full px-4 py-2 mb-4 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="ph ph-download-simple text-lg mr-2"></i> Unduh Template Kosong
                    </a>

                    <label class="block mb-2 text-sm font-medium text-gray-900" for="file_excel">Pilih File Excel</label>
                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="file_excel" name="file" type="file" accept=".xlsx" required>
                    <p class="mt-1 text-xs text-gray-500">Maksimal ukuran file: 5MB.</p>
                </div>
                
                <button type="submit" class="w-full text-white inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <i class="ph ph-upload-simple mr-2"></i> Mulai Proses Import
                </button>
            </form>
        </div>
    </div>
</div>
@endsection