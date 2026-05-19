@extends('layouts.app')

@section('title', 'Dashboard Pekerja')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Overview Pekerjaan</h2>
            <p class="text-sm text-gray-500">Ringkasan status Permit to Work yang Anda ajukan.</p>
        </div>
        <a href="{{ route('pekerja.permit.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 transition-colors">
            <i class="ph ph-plus-circle text-lg mr-2"></i>
            Buat Permit Baru
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gray-100 rounded-lg dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                    <i class="ph ph-files text-2xl"></i>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengajuan</p>
                    <h5 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</h5>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-100 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="p-3 bg-yellow-100 rounded-lg text-yellow-600">
                    <i class="ph ph-clock text-2xl"></i>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">Menunggu Review</p>
                    <h5 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending'] }}</h5>
                </div>
            </div>
        </div>

        <!-- Disetujui -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-blue-100 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="p-3 bg-blue-100 rounded-lg text-blue-600">
                    <i class="ph ph-check-circle text-2xl"></i>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">Permit Disetujui</p>
                    <h5 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['disetujui'] }}</h5>
                </div>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-red-100 rounded-bl-full -mr-8 -mt-8 opacity-50"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="p-3 bg-red-100 rounded-lg text-red-600">
                    <i class="ph ph-x-circle text-2xl"></i>
                </div>
                <div>
                    <p class="mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">Permit Ditolak</p>
                    <h5 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['ditolak'] }}</h5>
                </div>
            </div>
        </div>

    </div>

    <!-- Active Permits Table -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 rounded-t-lg">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Permit Aktif Terkini</h3>
            <a href="{{ route('pekerja.riwayat') }}" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">Lihat Semua</a>
        </div>
        <div class="p-0">
            <x-permit-table :permits="$permits" actionRoutePrefix="pekerja" />
        </div>
    </div>

</div>
@endsection