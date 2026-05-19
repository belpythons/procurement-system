@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h2>
            <p class="text-sm text-gray-500">Overview sistem manajemen Permit-to-Work perusahaan.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-100 rounded-lg text-gray-600"><i class="ph ph-files text-2xl"></i></div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-500">Total Permit</p>
                    <h5 class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</h5>
                </div>
            </div>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-yellow-100 rounded-lg text-yellow-600"><i class="ph ph-clock text-2xl"></i></div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-500">Pending</p>
                    <h5 class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</h5>
                </div>
            </div>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-100 rounded-lg text-blue-600"><i class="ph ph-check-circle text-2xl"></i></div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-500">Disetujui</p>
                    <h5 class="text-2xl font-bold text-gray-900">{{ $stats['disetujui'] }}</h5>
                </div>
            </div>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-100 rounded-lg text-green-600"><i class="ph ph-flag-checkered text-2xl"></i></div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-500">Selesai</p>
                    <h5 class="text-2xl font-bold text-gray-900">{{ $stats['selesai'] }}</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Permits Table -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-5 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
            <h3 class="text-lg font-semibold text-gray-900">10 Permit Terbaru</h3>
            <a href="{{ route('admin.monitoring') }}" class="text-sm font-medium text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="p-0">
            <x-permit-table :permits="$permits" actionRoutePrefix="admin" />
        </div>
    </div>

</div>
@endsection
