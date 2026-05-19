<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\Permit;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ══════════════════════════════════════════════════
        // 1. BUAT USER SUPERVISOR & SAFETY OFFICER DULU
        //    (tanpa department_id karena dept belum ada)
        // ══════════════════════════════════════════════════

        $supervisor = User::create([
            'name'     => 'Puspitasari Alfaris',
            'username' => 'puspitasari',
            'email'    => 'pitaa@outlook.com',
            'role'     => User::ROLE_SUPERVISOR,
            'status'   => 'Aktif',
            'password' => Hash::make('supervisor123'),
        ]);

        $supervisor2 = User::create([
            'name'     => 'Hendra Wijaya',
            'username' => 'hendra.w',
            'email'    => 'hendra.w@outlook.com',
            'role'     => User::ROLE_SUPERVISOR,
            'status'   => 'Aktif',
            'password' => Hash::make('supervisor123'),
        ]);

        $safety = User::create([
            'name'     => 'Projek Bertiga',
            'username' => 'projekbertiga',
            'email'    => 'projekbertiga@outlook.com',
            'role'     => User::ROLE_SAFETY_OFFICER,
            'status'   => 'Aktif',
            'password' => Hash::make('magang26'),
        ]);

        $safety2 = User::create([
            'name'     => 'Rina Safitri',
            'username' => 'rina.safety',
            'email'    => 'rina.safety@outlook.com',
            'role'     => User::ROLE_SAFETY_OFFICER,
            'status'   => 'Aktif',
            'password' => Hash::make('safety123'),
        ]);

        // ══════════════════════════════════════════════════
        // 2. BUAT DEPARTEMEN (dengan supervisor & safety)
        // ══════════════════════════════════════════════════

        $deptMaintenance = Department::create([
            'nama_departemen'   => 'Maintenance Dept',
            'supervisor_id'     => $supervisor->id,
            'safety_officer_id' => $safety->id,
        ]);

        $deptLogistics = Department::create([
            'nama_departemen'   => 'Logistics Dept',
            'supervisor_id'     => $supervisor->id,
            'safety_officer_id' => $safety->id,
        ]);

        $deptOperation = Department::create([
            'nama_departemen'   => 'Operation Dept',
            'supervisor_id'     => $supervisor2->id,
            'safety_officer_id' => $safety2->id,
        ]);

        $deptProduction = Department::create([
            'nama_departemen'   => 'Production Dept',
            'supervisor_id'     => $supervisor2->id,
            'safety_officer_id' => $safety2->id,
        ]);

        // ══════════════════════════════════════════════════
        // 3. UPDATE DEPARTMENT_ID UNTUK SUPERVISOR & SAFETY
        // ══════════════════════════════════════════════════

        $supervisor->update(['department_id' => $deptMaintenance->id]);
        $supervisor2->update(['department_id' => $deptOperation->id]);
        $safety->update(['department_id' => $deptMaintenance->id]);
        $safety2->update(['department_id' => $deptOperation->id]);

        // ══════════════════════════════════════════════════
        // 4. BUAT ADMIN (tidak perlu department)
        // ══════════════════════════════════════════════════

        User::create([
            'name'     => 'Ayu Wulandari',
            'username' => 'ayuwulandari',
            'email'    => 'ayuu17@outlook.com',
            'role'     => User::ROLE_ADMIN,
            'status'   => 'Aktif',
            'password' => Hash::make('adminadmin'),
        ]);

        // ══════════════════════════════════════════════════
        // 5. BUAT PEKERJA (di berbagai departemen)
        // ══════════════════════════════════════════════════

        $pekerjaData = [
            // Maintenance Dept
            [
                'name'          => 'Mira Agustiansyah',
                'username'      => 'mira.a',
                'email'         => 'miraa@outlook.com',
                'department_id' => $deptMaintenance->id,
                'sub_department'=> 'Mechanic',
            ],
            [
                'name'          => 'Fikri Ramadhan',
                'username'      => 'fikri.r',
                'email'         => 'fikri.r@outlook.com',
                'department_id' => $deptMaintenance->id,
                'sub_department'=> 'MPC',
            ],
            [
                'name'          => 'Bima Saputra',
                'username'      => 'bima.s',
                'email'         => 'bima.s@outlook.com',
                'department_id' => $deptMaintenance->id,
                'sub_department'=> 'Electrical & Instrument',
            ],
            // Logistics Dept
            [
                'name'          => 'Dinda Cahya',
                'username'      => 'dinda.c',
                'email'         => 'dinda.c@outlook.com',
                'department_id' => $deptLogistics->id,
                'sub_department'=> 'Warehouse',
            ],
            // Operation Dept
            [
                'name'          => 'Yoga Prasetya',
                'username'      => 'yoga.p',
                'email'         => 'yoga.p@outlook.com',
                'department_id' => $deptOperation->id,
                'sub_department'=> 'Utility',
            ],
            [
                'name'          => 'Riko Permana',
                'username'      => 'riko.p',
                'email'         => 'riko.p@outlook.com',
                'department_id' => $deptOperation->id,
                'sub_department'=> 'Control Room',
            ],
            // Production Dept
            [
                'name'          => 'Lestari Wulandari',
                'username'      => 'lestari.w',
                'email'         => 'lestari.w@outlook.com',
                'department_id' => $deptProduction->id,
                'sub_department'=> 'Line 1',
            ],
            [
                'name'          => 'Dani Kurniawan',
                'username'      => 'dani.k',
                'email'         => 'dani.k@outlook.com',
                'department_id' => $deptProduction->id,
                'sub_department'=> 'Line 2',
            ],
        ];

        $pekerjaModels = [];
        foreach ($pekerjaData as $data) {
            $pekerjaModels[] = User::create(array_merge($data, [
                'role'     => User::ROLE_PEKERJA,
                'status'   => 'Aktif',
                'password' => Hash::make('pekerja123'),
            ]));
        }

        // ══════════════════════════════════════════════════
        // 6. BUAT PERMITS (terhubung otomatis ke supervisor
        //    & safety officer berdasarkan departemen pekerja)
        // ══════════════════════════════════════════════════

        foreach ($pekerjaModels as $pekerja) {
            $dept = $pekerja->department;

            // 1 permit Pending per pekerja
            Permit::factory()->create([
                'user_id'           => $pekerja->id,
                'department_id'     => $dept->id,
                'supervisor_id'     => $dept->supervisor_id,
                'safety_officer_id' => $dept->safety_officer_id,
            ]);

            // 1 permit Selesai per pekerja (riwayat)
            Permit::factory()->selesai()->create([
                'user_id'           => $pekerja->id,
                'department_id'     => $dept->id,
                'supervisor_id'     => $dept->supervisor_id,
                'safety_officer_id' => $dept->safety_officer_id,
            ]);
        }

        // Beberapa permit tambahan dengan status berbeda-beda
        // Mira — Ditolak
        $mira = $pekerjaModels[0];
        Permit::factory()->ditolak()->create([
            'user_id'           => $mira->id,
            'department_id'     => $mira->department->id,
            'supervisor_id'     => $mira->department->supervisor_id,
            'safety_officer_id' => $mira->department->safety_officer_id,
        ]);

        // Dinda — Disetujui
        $dinda = $pekerjaModels[3];
        Permit::factory()->disetujui()->create([
            'user_id'           => $dinda->id,
            'department_id'     => $dinda->department->id,
            'supervisor_id'     => $dinda->department->supervisor_id,
            'safety_officer_id' => $dinda->department->safety_officer_id,
        ]);

        // Yoga — Selesai (extra)
        $yoga = $pekerjaModels[4];
        Permit::factory()->selesai()->create([
            'user_id'           => $yoga->id,
            'department_id'     => $yoga->department->id,
            'supervisor_id'     => $yoga->department->supervisor_id,
            'safety_officer_id' => $yoga->department->safety_officer_id,
        ]);
    }
}