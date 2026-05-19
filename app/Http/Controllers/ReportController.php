<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permit;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class ReportController extends Controller
{
    // ═══════════════════════════════════════════════════════════
    // EXPORT EXCEL (.xlsx) — via OpenSpout streaming
    // ═══════════════════════════════════════════════════════════

    /**
     * Export data permit ke file Excel (.xlsx).
     *
     * Filter opsional: start_date, end_date.
     * Skop data berdasarkan role user yang login.
     */
    public function exportExcel(Request $request)
    {
        $permits = $this->getFilteredPermits($request);

        // Buat file temporary di storage
        $filename = 'laporan_permit_' . now()->format('Ymd_His') . '.xlsx';
        $tempPath = storage_path('app/' . $filename);

        $writer = new Writer();
        $writer->openToFile($tempPath);

        // ── Header Row ──
        $writer->addRow(Row::fromValues([
            'No',
            'Nomor Permit',
            'Nama Pekerja',
            'Departemen',
            'Jenis Pekerjaan',
            'Nama Pekerjaan',
            'Tanggal Kerja',
            'Jam',
            'Gedung',
            'Area',
            'Lokasi',
            'Tingkat Risiko',
            'APD',
            'Status',
            'Supervisor',
            'Safety Officer',
            'Catatan Supervisor',
            'Catatan Safety',
            'Evaluasi Risiko',
        ]));

        // ── Data Rows ──
        $no = 1;
        foreach ($permits as $p) {
            $writer->addRow(Row::fromValues([
                $no++,
                $p->nomor_permit,
                $p->user->name ?? '-',
                $p->department->nama_departemen ?? '-',
                $p->jenis_pekerjaan,
                $p->nama_pekerjaan,
                $p->tanggal_kerja?->format('d/m/Y') ?? '-',
                ($p->jam_mulai ?? '') . ' - ' . ($p->jam_selesai ?? ''),
                $p->gedung ?? '-',
                $p->area ?? '-',
                $p->lokasi ?? '-',
                $p->tingkat_risiko ?? '-',
                is_array($p->apd) ? implode(', ', $p->apd) : '-',
                $p->status,
                $p->supervisor->name ?? '-',
                $p->safetyOfficer->name ?? '-',
                $p->catatan_supervisor ?? '-',
                $p->catatan_safety ?? '-',
                $p->evaluasi_risiko ?? '-',
            ]));
        }

        $writer->close();

        // Download lalu hapus file temporary
        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // ═══════════════════════════════════════════════════════════
    // EXPORT PDF — via DomPDF
    // ═══════════════════════════════════════════════════════════

    /**
     * Export data permit ke file PDF.
     *
     * Filter opsional: start_date, end_date.
     * Skop data berdasarkan role user yang login.
     */
    public function exportPdf(Request $request)
    {
        $permits = $this->getFilteredPermits($request);
        $user    = Auth::user();

        $filterInfo = $this->buildFilterLabel($request);

        $pdf = Pdf::loadView('exports.permits_pdf', [
            'permits'    => $permits,
            'user'       => $user,
            'filterInfo' => $filterInfo,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ]);

        $pdf->setPaper('A4', 'landscape');

        $filename = 'laporan_permit_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    // ═══════════════════════════════════════════════════════════
    // PRIVATE: Query Builder berdasarkan Role & Filter Tanggal
    // ═══════════════════════════════════════════════════════════

    /**
     * Ambil data permit sesuai role user + filter tanggal opsional.
     *
     * - Admin       → semua permit
     * - Pekerja     → permit miliknya sendiri
     * - Supervisor  → permit di bawah departemennya
     * - Safety Off. → permit di bawah departemennya
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getFilteredPermits(Request $request)
    {
        $user  = Auth::user();
        $query = Permit::with('user', 'department', 'supervisor', 'safetyOfficer');

        // ── Skop berdasarkan role ──
        if ($user->isPekerja()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isSupervisor()) {
            $query->where('supervisor_id', $user->id);
        } elseif ($user->isSafetyOfficer()) {
            $query->where('safety_officer_id', $user->id);
        }
        // Admin: tanpa filter skop, ambil semua

        // ── Filter rentang tanggal (opsional) ──
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_kerja', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_kerja', '<=', $request->end_date);
        }

        return $query->orderBy('tanggal_kerja', 'desc')->get();
    }

    /**
     * Build label deskriptif untuk filter yang aktif.
     */
    private function buildFilterLabel(Request $request): string
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            return 'Periode: ' . $request->start_date . ' s/d ' . $request->end_date;
        }
        if ($request->filled('start_date')) {
            return 'Mulai dari: ' . $request->start_date;
        }
        if ($request->filled('end_date')) {
            return 'Sampai dengan: ' . $request->end_date;
        }
        return 'Seluruh Riwayat';
    }
}
