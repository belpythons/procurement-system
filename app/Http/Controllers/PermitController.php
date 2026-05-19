<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permit;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class PermitController extends Controller
{
    // ═══════════════════════════════════════════════════════════
    // SHARED: Monitoring (permit aktif — Pending & Disetujui)
    // ═══════════════════════════════════════════════════════════

    /**
     * Monitoring permit — Admin: semua, Supervisor/Safety: miliknya.
     */
    public function monitoring(Request $request)
    {
        $user = Auth::user();

        $query = Permit::with('user', 'department', 'supervisor', 'safetyOfficer')
            ->whereIn('status', [Permit::STATUS_PENDING, Permit::STATUS_DISETUJUI]);

        // Filter berdasarkan role
        if ($user->isSupervisor()) {
            $query->where('supervisor_id', $user->id);
        } elseif ($user->isSafetyOfficer()) {
            $query->where('safety_officer_id', $user->id);
        }
        // Admin melihat semua

        $permits = $query->latest()->paginate(10);

        // Tentukan view berdasarkan role
        $view = match (true) {
            $user->isAdmin()         => 'admin.monitoring_permit',
            $user->isSupervisor()    => 'supervisor.monitoring_permit',
            $user->isSafetyOfficer() => 'safety_officer.monitoring_permit',
            default                  => 'pekerja.dashboard',
        };

        return view($view, compact('permits'));
    }

    // ═══════════════════════════════════════════════════════════
    // SHARED: Riwayat (permit final — Selesai & Ditolak)
    // ═══════════════════════════════════════════════════════════

    /**
     * Riwayat permit — permit yang sudah selesai/ditolak.
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();

        $query = Permit::with('user', 'department', 'supervisor', 'safetyOfficer')
            ->whereIn('status', [Permit::STATUS_SELESAI, Permit::STATUS_DITOLAK]);

        if ($user->isPekerja()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isSupervisor()) {
            $query->where('supervisor_id', $user->id);
        } elseif ($user->isSafetyOfficer()) {
            $query->where('safety_officer_id', $user->id);
        }

        $permits = $query->latest()->paginate(10);

        $view = match (true) {
            $user->isAdmin()         => 'admin.riwayat_permit',
            $user->isPekerja()       => 'pekerja.riwayat_permit',
            $user->isSupervisor()    => 'supervisor.riwayat_permit',
            $user->isSafetyOfficer() => 'safety_officer.riwayat_permit',
            default                  => 'pekerja.riwayat_permit',
        };

        return view($view, compact('permits'));
    }

    // ═══════════════════════════════════════════════════════════
    // SHARED: Laporan (semua permit — untuk filter & export)
    // ═══════════════════════════════════════════════════════════

    /**
     * Halaman laporan — menampilkan semua permit dengan filter.
     */
    public function laporan(Request $request)
    {
        $user = Auth::user();

        $query = Permit::with('user', 'department', 'supervisor', 'safetyOfficer');

        // Filter berdasarkan role
        if ($user->isPekerja()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isSupervisor()) {
            $query->where('supervisor_id', $user->id);
        } elseif ($user->isSafetyOfficer()) {
            $query->where('safety_officer_id', $user->id);
        }

        // Filter tanggal (opsional)
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_kerja', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_kerja', '<=', $request->end_date);
        }

        // Filter status (opsional)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $permits = $query->latest()->paginate(10);

        $view = match (true) {
            $user->isAdmin()         => 'admin.laporan',
            $user->isPekerja()       => 'pekerja.laporan_permit',
            $user->isSupervisor()    => 'supervisor.laporan_permit',
            $user->isSafetyOfficer() => 'safety_officer.laporan_permit',
            default                  => 'pekerja.laporan_permit',
        };

        return view($view, compact('permits'));
    }

    // ═══════════════════════════════════════════════════════════
    // SHARED: Detail Permit
    // ═══════════════════════════════════════════════════════════

    /**
     * Detail permit — view terpisah per role.
     */
    public function detail(Permit $permit)
    {
        $user = Auth::user();

        // Eager load relasi
        $permit->load('user', 'department', 'supervisor', 'safetyOfficer');

        // Otorisasi: pastikan user berhak melihat permit ini
        $this->authorizePermitAccess($user, $permit);

        $view = match (true) {
            $user->isAdmin()         => 'admin.detail_permit',
            $user->isPekerja()       => 'pekerja.detail_permit',
            $user->isSupervisor()    => 'supervisor.detail_permit',
            $user->isSafetyOfficer() => 'safety_officer.detail_permit',
            default                  => abort(403),
        };

        return view($view, compact('permit'));
    }

    // ═══════════════════════════════════════════════════════════
    // PEKERJA: Buat Permit (dengan Auto-Assign)
    // ═══════════════════════════════════════════════════════════

    /**
     * Form buat permit — hanya tampilkan form, tanpa dropdown atasan.
     */
    public function create()
    {
        return view('pekerja.buat_permit');
    }

    /**
     * Simpan permit baru.
     *
     * AUTO-ASSIGN LOGIC:
     * - Ambil department_id dari user yang sedang login
     * - Dari tabel departments, tarik supervisor_id & safety_officer_id
     * - Isi otomatis tanpa input manual dari pekerja
     * - Nomor permit di-generate otomatis (PRM-001, PRM-002, ...)
     * - Status default: "Pending"
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_pekerjaan' => 'required|string',
            'nama_pekerjaan'  => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'tanggal_kerja'   => 'required|date',
            'jam_mulai'       => 'nullable|string',
            'jam_selesai'     => 'nullable|string',
            'gedung'          => 'nullable|string|max:255',
            'area'            => 'nullable|string|max:255',
            'lokasi'          => 'nullable|string|max:255',
            'tingkat_risiko'  => 'nullable|string',
            'apd'             => 'nullable|array',
            'apd.*'           => 'string',
        ]);

        $user = Auth::user();
        $department = $user->department;

        // Validasi: pekerja HARUS punya departemen
        if (!$department) {
            return back()->with('error', 'Anda belum terdaftar di departemen manapun. Hubungi Admin.');
        }

        // Validasi: departemen HARUS punya supervisor & safety officer
        if (!$department->supervisor_id || !$department->safety_officer_id) {
            return back()->with('error', 'Departemen Anda belum memiliki Supervisor/Safety Officer. Hubungi Admin.');
        }

        // ── AUTO-ASSIGN ──
        Permit::create([
            'nomor_permit'      => Permit::generateNomorPermit(),
            'user_id'           => $user->id,
            'department_id'     => $department->id,
            'supervisor_id'     => $department->supervisor_id,       // ← Auto dari department
            'safety_officer_id' => $department->safety_officer_id,   // ← Auto dari department
            'jenis_pekerjaan'   => $request->jenis_pekerjaan,
            'nama_pekerjaan'    => $request->nama_pekerjaan,
            'deskripsi'         => $request->deskripsi,
            'tanggal_kerja'     => $request->tanggal_kerja,
            'jam_mulai'         => $request->jam_mulai,
            'jam_selesai'       => $request->jam_selesai,
            'gedung'            => $request->gedung,
            'area'              => $request->area,
            'lokasi'            => $request->lokasi,
            'tingkat_risiko'    => $request->tingkat_risiko,
            'apd'               => $request->apd ?? [],
            'status'            => Permit::STATUS_PENDING,           // ← Selalu Pending
        ]);

        return redirect()->route('pekerja.dashboard')
            ->with('success', 'Permit berhasil diajukan dan sedang menunggu persetujuan.');
    }

    /**
     * Form edit permit — hanya bisa diedit jika masih Pending.
     */
    public function edit(Permit $permit)
    {
        $user = Auth::user();

        // Hanya pemilik permit & status Pending
        if ($permit->user_id !== $user->id || $permit->status !== Permit::STATUS_PENDING) {
            return back()->with('error', 'Permit tidak dapat diedit.');
        }

        return view('pekerja.edit_permit', compact('permit'));
    }

    /**
     * Update permit — hanya bisa jika masih Pending.
     */
    public function update(Request $request, Permit $permit)
    {
        $user = Auth::user();

        if ($permit->user_id !== $user->id || $permit->status !== Permit::STATUS_PENDING) {
            return back()->with('error', 'Permit tidak dapat diedit.');
        }

        $request->validate([
            'jenis_pekerjaan' => 'required|string',
            'nama_pekerjaan'  => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'tanggal_kerja'   => 'required|date',
            'jam_mulai'       => 'nullable|string',
            'jam_selesai'     => 'nullable|string',
            'gedung'          => 'nullable|string|max:255',
            'area'            => 'nullable|string|max:255',
            'lokasi'          => 'nullable|string|max:255',
            'tingkat_risiko'  => 'nullable|string',
            'apd'             => 'nullable|array',
            'apd.*'           => 'string',
        ]);

        $permit->update([
            'jenis_pekerjaan' => $request->jenis_pekerjaan,
            'nama_pekerjaan'  => $request->nama_pekerjaan,
            'deskripsi'       => $request->deskripsi,
            'tanggal_kerja'   => $request->tanggal_kerja,
            'jam_mulai'       => $request->jam_mulai,
            'jam_selesai'     => $request->jam_selesai,
            'gedung'          => $request->gedung,
            'area'            => $request->area,
            'lokasi'          => $request->lokasi,
            'tingkat_risiko'  => $request->tingkat_risiko,
            'apd'             => $request->apd ?? [],
        ]);

        return redirect()->route('pekerja.dashboard')
            ->with('success', 'Permit berhasil diperbarui.');
    }

    // ═══════════════════════════════════════════════════════════
    // SUPERVISOR: Approve / Reject
    // ═══════════════════════════════════════════════════════════

    /**
     * Supervisor menyetujui permit.
     */
    public function approve(Request $request, Permit $permit)
    {
        $user = Auth::user();

        // Hanya supervisor yang di-assign ke permit ini
        if ($permit->supervisor_id !== $user->id) {
            return back()->with('error', 'Anda tidak berhak menyetujui permit ini.');
        }

        if ($permit->status !== Permit::STATUS_PENDING) {
            return back()->with('error', 'Hanya permit berstatus Pending yang dapat disetujui.');
        }

        $request->validate([
            'catatan_supervisor' => 'nullable|string|max:1000',
        ]);

        $permit->update([
            'status'             => Permit::STATUS_DISETUJUI,
            'catatan_supervisor' => $request->catatan_supervisor,
        ]);

        return back()->with('success', 'Permit berhasil disetujui.');
    }

    /**
     * Supervisor menolak permit.
     */
    public function reject(Request $request, Permit $permit)
    {
        $user = Auth::user();

        if ($permit->supervisor_id !== $user->id) {
            return back()->with('error', 'Anda tidak berhak menolak permit ini.');
        }

        if ($permit->status !== Permit::STATUS_PENDING) {
            return back()->with('error', 'Hanya permit berstatus Pending yang dapat ditolak.');
        }

        $request->validate([
            'catatan_supervisor' => 'required|string|max:1000',
        ]);

        $permit->update([
            'status'             => Permit::STATUS_DITOLAK,
            'catatan_supervisor' => $request->catatan_supervisor,
        ]);

        return back()->with('success', 'Permit telah ditolak.');
    }

    /**
     * Supervisor menandai permit sebagai selesai.
     */
    public function selesai(Request $request, Permit $permit)
    {
        $user = Auth::user();

        if ($permit->supervisor_id !== $user->id) {
            return back()->with('error', 'Anda tidak berhak mengubah status permit ini.');
        }

        if ($permit->status !== Permit::STATUS_DISETUJUI) {
            return back()->with('error', 'Hanya permit berstatus Disetujui yang dapat diselesaikan.');
        }

        $request->validate([
            'catatan_supervisor' => 'nullable|string|max:1000',
        ]);

        $permit->update([
            'status'             => Permit::STATUS_SELESAI,
            'catatan_supervisor' => $request->catatan_supervisor ?? $permit->catatan_supervisor,
        ]);

        return back()->with('success', 'Permit telah diselesaikan.');
    }

    // ═══════════════════════════════════════════════════════════
    // SAFETY OFFICER: Evaluasi Risiko
    // ═══════════════════════════════════════════════════════════

    /**
     * Safety Officer mengisi evaluasi risiko dan catatan.
     */
    public function evaluasi(Request $request, Permit $permit)
    {
        $user = Auth::user();

        if ($permit->safety_officer_id !== $user->id) {
            return back()->with('error', 'Anda tidak berhak mengevaluasi permit ini.');
        }

        $request->validate([
            'evaluasi_risiko' => 'required|string|max:2000',
            'catatan_safety'  => 'nullable|string|max:1000',
        ]);

        $permit->update([
            'evaluasi_risiko' => $request->evaluasi_risiko,
            'catatan_safety'  => $request->catatan_safety,
        ]);

        return back()->with('success', 'Evaluasi risiko berhasil disimpan.');
    }

    // ═══════════════════════════════════════════════════════════
    // PRIVATE HELPER
    // ═══════════════════════════════════════════════════════════

    /**
     * Cek apakah user berhak mengakses permit ini.
     */
    private function authorizePermitAccess(User $user, Permit $permit): void
    {
        $authorized = match (true) {
            $user->isAdmin()                                => true,
            $user->isPekerja()                              => $permit->user_id === $user->id,
            $user->isSupervisor()                           => $permit->supervisor_id === $user->id,
            $user->isSafetyOfficer()                        => $permit->safety_officer_id === $user->id,
            default                                         => false,
        };

        if (!$authorized) {
            abort(403, 'Anda tidak memiliki akses ke permit ini.');
        }
    }
}
