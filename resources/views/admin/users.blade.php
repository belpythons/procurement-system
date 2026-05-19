@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Manajemen User</h2>
            <p class="text-sm text-gray-500">Kelola data pengguna dan hak akses sistem.</p>
        </div>
        <button data-modal-target="modal-tambah" data-modal-toggle="modal-tambah" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 transition-colors">
            <i class="ph ph-plus-circle text-lg mr-2"></i> Tambah User
        </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">Departemen & Sub</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $u->name }}</div>
                            <div class="text-xs text-gray-500">{{ $u->email }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $u->username }}</td>
                        <td class="px-6 py-4">
                            <div class="text-gray-900">{{ $u->department->nama_departemen ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $u->sub_department ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded capitalize">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($u->status === 'Aktif')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Aktif</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button data-modal-target="modal-edit-{{ $u->id }}" data-modal-toggle="modal-edit-{{ $u->id }}" class="text-blue-600 hover:text-blue-900 mr-2"><i class="ph ph-pencil-simple text-lg"></i></button>
                            <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"><i class="ph ph-trash text-lg"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div id="modal-edit-{{ $u->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                            <div class="relative bg-white rounded-lg shadow">
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                    <h3 class="text-lg font-semibold text-gray-900">Edit User</h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="modal-edit-{{ $u->id }}">
                                        <i class="ph ph-x text-lg"></i>
                                    </button>
                                </div>
                                <form action="{{ route('admin.users.update', $u->id) }}" method="POST" class="p-4 md:p-5">
                                    @csrf
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <div class="col-span-2 sm:col-span-1">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                                            <input type="text" name="name" value="{{ $u->name }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                                            <input type="text" name="username" value="{{ $u->username }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                            <input type="email" name="email" value="{{ $u->email }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Password (Kosongkan jika tidak diubah)</label>
                                            <input type="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Departemen</label>
                                            <select name="department_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                <option value="">Pilih Departemen</option>
                                                @foreach($departments as $d)
                                                    <option value="{{ $d->id }}" {{ $u->department_id == $d->id ? 'selected' : '' }}>{{ $d->nama_departemen }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Sub Departemen</label>
                                            <input type="text" name="sub_department" value="{{ $u->sub_department }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                                            <select name="role" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                <option value="pekerja" {{ $u->role == 'pekerja' ? 'selected' : '' }}>Pekerja</option>
                                                <option value="supervisor" {{ $u->role == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                                                <option value="safety officer" {{ $u->role == 'safety officer' ? 'selected' : '' }}>Safety Officer</option>
                                                <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2 sm:col-span-1">
                                            <label class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                            <select name="status" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                <option value="Aktif" {{ $u->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="Nonaktif" {{ $u->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-4 border-t border-gray-200">
            {{ $users->links('pagination::tailwind') }}
        </div>
        @endif
    </div>
</div>

<!-- Tambah Modal -->
<div id="modal-tambah" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">Tambah User Baru</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-toggle="modal-tambah">
                    <i class="ph ph-x text-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap *</label>
                        <input type="text" name="name" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Username *</label>
                        <input type="text" name="username" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Email *</label>
                        <input type="email" name="email" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Password *</label>
                        <input type="password" name="password" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Departemen</label>
                        <select name="department_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Pilih Departemen...</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_departemen }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Sub Departemen</label>
                        <input type="text" name="sub_department" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Role *</label>
                        <select name="role" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="pekerja">Pekerja</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="safety officer">Safety Officer</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Status *</label>
                        <select name="status" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Tambah User
                </button>
            </form>
        </div>
    </div>
</div>
@endsection