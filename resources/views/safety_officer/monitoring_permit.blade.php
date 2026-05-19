@extends('layouts.app')

@section('title', 'Evaluasi Permit Dept')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Evaluasi Permit Aktif</h2>
            <p class="text-sm text-gray-500">Pantau dan evaluasi permit yang sedang berjalan di bawah pengawasan Anda.</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="p-0">
            <x-permit-table :permits="$permits" actionRoutePrefix="safety_officer" />
        </div>
    </div>

</div>
@endsection
