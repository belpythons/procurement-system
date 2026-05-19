@extends('layouts.app')

@section('title', 'Monitoring Permit')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Monitoring Permit Aktif</h2>
            <p class="text-sm text-gray-500">Pantau seluruh permit yang berstatus Pending atau Disetujui di pabrik.</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-0">
            <x-permit-table :permits="$permits" actionRoutePrefix="admin" />
        </div>
    </div>

</div>
@endsection