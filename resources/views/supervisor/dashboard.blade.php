@extends('layouts.app')

@section('title', 'Supervisor Dashboard')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Supervisor Dashboard</h2>
            <p class="text-sm text-gray-500">Overview persetujuan Permit-to-Work untuk departemen Anda.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-100 rounded-lg text-gray-600"><i class="ph ph-files text-2xl"></i></div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-500">Total Dept Permit</p>
                    <h5 class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</h5>
                </div>
            </div>
        </div>
        
        <!-- Notifikasi Menunggu Persetujuan -->
        <div class="p-6 bg-white border {{ $stats['pending'] > 0 ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200' }} rounded-lg shadow-sm relative overflow-hidden">
            @if($stats['pending'] > 0)
                <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-200 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>
                <div class="absolute top-3 right-3 flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                </div>
            @endif
            <div class="flex items-center gap-4 relative z-10">
                <div class="p-3 {{ $stats['pending'] > 0 ? 'bg-yellow-200 text-yellow-700' : 'bg-yellow-100 text-yellow-600' }} rounded-lg">
                    <i class="ph ph-clock text-2xl"></i>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-500">Perlu Persetujuan</p>
                    <h5 class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</h5>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-100 rounded-lg text-blue-600"><i class="ph ph-check-circle text-2xl"></i></div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-500">Telah Disetujui</p>
                    <h5 class="text-2xl font-bold text-gray-900">{{ $stats['disetujui'] }}</h5>
                </div>
            </div>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-red-100 rounded-lg text-red-600"><i class="ph ph-x-circle text-2xl"></i></div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-500">Ditolak</p>
                    <h5 class="text-2xl font-bold text-gray-900">{{ $stats['ditolak'] }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Permits Table -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-5 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
            <h3 class="text-lg font-semibold text-gray-900">Permit Menunggu Review</h3>
            <a href="{{ route('supervisor.monitoring') }}" class="text-sm font-medium text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="p-0">
            <x-permit-table :permits="$permits" actionRoutePrefix="supervisor" />
        </div>
    </div>

</div>
@endsection