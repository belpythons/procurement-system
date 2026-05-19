@extends('layouts.app')

@section('title', 'Laporan Permit Dept')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Laporan Permit Departemen</h2>
            <p class="text-sm text-gray-500">Filter dan ekspor data permit di bawah pengawasan Anda.</p>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('supervisor.laporan.export.excel', request()->query()) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-green-700 bg-green-100 border border-green-200 rounded-lg hover:bg-green-200 transition-colors">
                <i class="ph ph-file-xls text-lg mr-2"></i> Export Excel
            </a>
            <a href="{{ route('supervisor.laporan.export.pdf', request()->query()) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-red-700 bg-red-100 border border-red-200 rounded-lg hover:bg-red-200 transition-colors">
                <i class="ph ph-file-pdf text-lg mr-2"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 border border-gray-200 rounded-lg shadow-sm">
        <form action="{{ route('supervisor.laporan') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
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
                <a href="{{ route('supervisor.laporan') }}" class="w-full text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 flex justify-center items-center border border-gray-300">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-0">
            <x-permit-table :permits="$permits" actionRoutePrefix="supervisor" />
        </div>
    </div>

</div>
@endsection
