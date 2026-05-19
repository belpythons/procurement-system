@extends('layouts.app')

@section('title', 'Riwayat Permit')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Riwayat Permit</h2>
            <p class="text-sm text-gray-500">Daftar permit Anda yang telah selesai atau ditolak.</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
        <div class="p-0">
            <x-permit-table :permits="$permits" actionRoutePrefix="pekerja" />
        </div>
    </div>

</div>
@endsection
