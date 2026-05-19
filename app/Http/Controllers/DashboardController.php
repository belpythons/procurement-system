<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permit;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * ═══════════════════════════════════════
     * DASHBOARD ADMIN
     * Melihat statistik global semua permit.
     * ═══════════════════════════════════════
     */
    public function admin()
    {
        // Statistik global
        $stats = [
            'total'     => Permit::count(),
            'pending'   => Permit::where('status', Permit::STATUS_PENDING)->count(),
            'disetujui' => Permit::where('status', Permit::STATUS_DISETUJUI)->count(),
            'ditolak'   => Permit::where('status', Permit::STATUS_DITOLAK)->count(),
            'selesai'   => Permit::where('status', Permit::STATUS_SELESAI)->count(),
        ];

        // Chart data: jumlah per jenis pekerjaan
        $chartData = [
            'hot'        => Permit::where('jenis_pekerjaan', 'Hot Work')->count(),
            'cold'       => Permit::where('jenis_pekerjaan', 'Cold Work')->count(),
            'penggalian' => Permit::where('jenis_pekerjaan', 'Penggalian')->count(),
            'listrik'    => Permit::where('jenis_pekerjaan', 'Listrik & Instrument')->count(),
            'kendaraan'  => Permit::where('jenis_pekerjaan', 'Kendaraan & Alat Berat')->count(),
            'confined'   => Permit::where('jenis_pekerjaan', 'Confined Space')->count(),
            'kompresor'  => Permit::where('jenis_pekerjaan', 'Kompressor Oksigen')->count(),
        ];

        // Permit terbaru untuk tabel dashboard (paginated)
        $permits = Permit::with('user', 'department')
            ->latest()
            ->paginate(10);

        return view('admin.dashboard', compact('stats', 'chartData', 'permits'));
    }

    /**
     * ═══════════════════════════════════════
     * DASHBOARD PEKERJA
     * Melihat statistik permit miliknya saja.
     * ═══════════════════════════════════════
     */
    public function pekerja()
    {
        $user = Auth::user();

        $stats = [
            'total'     => $user->permits()->count(),
            'pending'   => $user->permits()->where('status', Permit::STATUS_PENDING)->count(),
            'disetujui' => $user->permits()->where('status', Permit::STATUS_DISETUJUI)->count(),
            'ditolak'   => $user->permits()->where('status', Permit::STATUS_DITOLAK)->count(),
            'selesai'   => $user->permits()->where('status', Permit::STATUS_SELESAI)->count(),
        ];

        // Permit terbaru milik pekerja ini
        $permits = $user->permits()
            ->with('supervisor', 'safetyOfficer')
            ->latest()
            ->paginate(10);

        return view('pekerja.dashboard', compact('stats', 'permits'));
    }

    /**
     * ═══════════════════════════════════════
     * DASHBOARD SUPERVISOR
     * Melihat permit yang di-assign ke dirinya.
     * ═══════════════════════════════════════
     */
    public function supervisor()
    {
        $user = Auth::user();

        $stats = [
            'total'     => $user->supervisedPermits()->count(),
            'pending'   => $user->supervisedPermits()->where('status', Permit::STATUS_PENDING)->count(),
            'disetujui' => $user->supervisedPermits()->where('status', Permit::STATUS_DISETUJUI)->count(),
            'ditolak'   => $user->supervisedPermits()->where('status', Permit::STATUS_DITOLAK)->count(),
            'selesai'   => $user->supervisedPermits()->where('status', Permit::STATUS_SELESAI)->count(),
        ];

        $chartData = [
            'hot'        => $user->supervisedPermits()->where('jenis_pekerjaan', 'Hot Work')->count(),
            'cold'       => $user->supervisedPermits()->where('jenis_pekerjaan', 'Cold Work')->count(),
            'penggalian' => $user->supervisedPermits()->where('jenis_pekerjaan', 'Penggalian')->count(),
            'listrik'    => $user->supervisedPermits()->where('jenis_pekerjaan', 'Listrik & Instrument')->count(),
            'kendaraan'  => $user->supervisedPermits()->where('jenis_pekerjaan', 'Kendaraan & Alat Berat')->count(),
            'confined'   => $user->supervisedPermits()->where('jenis_pekerjaan', 'Confined Space')->count(),
            'kompresor'  => $user->supervisedPermits()->where('jenis_pekerjaan', 'Kompressor Oksigen')->count(),
        ];

        // Permit yang perlu di-review oleh supervisor ini
        $permits = $user->supervisedPermits()
            ->with('user', 'department')
            ->latest()
            ->paginate(10);

        return view('supervisor.dashboard', compact('stats', 'chartData', 'permits'));
    }

    /**
     * ═══════════════════════════════════════
     * DASHBOARD SAFETY OFFICER
     * Melihat permit yang di-assign ke dirinya.
     * ═══════════════════════════════════════
     */
    public function safetyOfficer()
    {
        $user = Auth::user();

        $stats = [
            'total'     => $user->reviewedPermits()->count(),
            'pending'   => $user->reviewedPermits()->where('status', Permit::STATUS_PENDING)->count(),
            'disetujui' => $user->reviewedPermits()->where('status', Permit::STATUS_DISETUJUI)->count(),
            'ditolak'   => $user->reviewedPermits()->where('status', Permit::STATUS_DITOLAK)->count(),
            'selesai'   => $user->reviewedPermits()->where('status', Permit::STATUS_SELESAI)->count(),
        ];

        $chartData = [
            'hot'        => $user->reviewedPermits()->where('jenis_pekerjaan', 'Hot Work')->count(),
            'cold'       => $user->reviewedPermits()->where('jenis_pekerjaan', 'Cold Work')->count(),
            'penggalian' => $user->reviewedPermits()->where('jenis_pekerjaan', 'Penggalian')->count(),
            'listrik'    => $user->reviewedPermits()->where('jenis_pekerjaan', 'Listrik & Instrument')->count(),
            'kendaraan'  => $user->reviewedPermits()->where('jenis_pekerjaan', 'Kendaraan & Alat Berat')->count(),
            'confined'   => $user->reviewedPermits()->where('jenis_pekerjaan', 'Confined Space')->count(),
            'kompresor'  => $user->reviewedPermits()->where('jenis_pekerjaan', 'Kompressor Oksigen')->count(),
        ];

        $permits = $user->reviewedPermits()
            ->with('user', 'department')
            ->latest()
            ->paginate(10);

        return view('safety_officer.dashboard', compact('stats', 'chartData', 'permits'));
    }
}