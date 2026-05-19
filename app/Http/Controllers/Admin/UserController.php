<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    // ─── MANAJEMEN USER ───

    public function index()
    {
        $users = User::with('department')->latest()->paginate(10);
        $departments = Department::all();
        return view('admin.users', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|unique:users',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:5',
            'role'          => 'required|in:admin,pekerja,supervisor,safety officer',
            'department_id' => 'nullable|exists:departments,id',
            'sub_department' => 'nullable|string|max:255',
            'status'        => 'required|in:Aktif,Nonaktif',
        ]);

        User::create([
            'name'           => $request->name,
            'username'       => $request->username,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'department_id'  => $request->department_id,
            'sub_department' => $request->sub_department,
            'role'           => $request->role,
            'status'         => $request->status,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|unique:users,username,' . $id,
            'email'         => 'required|email|unique:users,email,' . $id,
            'role'          => 'required|in:admin,pekerja,supervisor,safety officer',
            'department_id' => 'nullable|exists:departments,id',
            'sub_department' => 'nullable|string|max:255',
            'status'        => 'required|in:Aktif,Nonaktif',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name'           => $request->name,
            'username'       => $request->username,
            'email'          => $request->email,
            'role'           => $request->role,
            'department_id'  => $request->department_id,
            'sub_department' => $request->sub_department,
            'status'         => $request->status,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user->foto && File::exists(public_path('uploads/' . $user->foto))) {
            File::delete(public_path('uploads/' . $user->foto));
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    // ─── PROFIL ADMIN ───

    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'foto'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($user->foto && File::exists(public_path('uploads/' . $user->foto))) {
                File::delete(public_path('uploads/' . $user->foto));
            }

            $file = $request->file('foto');
            $nama_file = time() . '_' . $file->getClientOriginalName();

            if (!File::isDirectory(public_path('uploads'))) {
                File::makeDirectory(public_path('uploads'), 0777, true, true);
            }

            $file->move(public_path('uploads'), $nama_file);
            $user->foto = $nama_file;
        }

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}