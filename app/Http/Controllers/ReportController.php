<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use App\Models\ImportLog;
use App\Imports\ProcurementImport;
use App\Exports\ProcurementExport;
use App\Exports\ProcurementTemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Report index page — rendered via Inertia (Vue).
     * Passes procurements data and import history to the frontend.
     */
    public function index()
    {
        $procurements = Procurement::orderBy('id', 'desc')
            ->get()
            ->map(fn (Procurement $p) => [
                'id'             => $p->id,
                'kode_pengadaan' => $p->kode_pengadaan,
                'nama_barang'    => $p->nama_barang,
                'vendor'         => $p->vendor,
                'tanggal_te'     => $p->tanggal_te?->format('Y-m-d'),
                'tanggal_rete'   => $p->tanggal_rete?->format('Y-m-d'),
                'tanggal_po'     => $p->tanggal_po?->format('Y-m-d'),
                'status'         => $p->status,
            ]);

        $importLogs = ImportLog::where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->map(fn (ImportLog $log) => [
                'id'             => $log->id,
                'file_name'      => $log->file_name,
                'total_rows'     => $log->total_rows,
                'processed_rows' => $log->processed_rows,
                'success_count'  => $log->success_count,
                'failure_count'  => $log->failure_count,
                'has_error_log'  => $log->hasErrorLog(),
                'status'         => $log->status,
                'created_at'     => $log->created_at->format('d M Y H:i'),
            ]);

        // Summary counts for stats cards
        $stats = [
            'total' => Procurement::count(),
            'rp'    => Procurement::where('status', 'RP')->count(),
            'te'    => Procurement::where('status', 'TE')->count(),
            'rete'  => Procurement::where('status', 'RE-TE')->count(),
            'po'    => Procurement::where('status', 'PO')->count(),
        ];

        return Inertia::render('Report/Index', [
            'procurements' => $procurements,
            'importLogs'   => $importLogs,
            'stats'        => $stats,
        ]);
    }

    /**
     * Handle Excel file upload and dispatch queued import job.
     * Returns the import_log ID for WebSocket progress tracking.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv,xls', 'max:51200'], // max 50MB
        ], [
            'file.required' => 'File wajib diunggah.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 50MB.',
        ]);

        $file = $request->file('file');

        // Create ImportLog record
        $importLog = ImportLog::create([
            'user_id'   => auth()->id(),
            'file_name' => $file->getClientOriginalName(),
            'status'    => ImportLog::STATUS_PROCESSING,
        ]);

        // Store the uploaded file temporarily
        $storedPath = $file->storeAs(
            'imports',
            "upload_{$importLog->id}." . $file->getClientOriginalExtension(),
            'local'
        );

        // Dispatch queued import job
        $import = new ProcurementImport($importLog->id);
        Excel::queueImport($import, $storedPath, 'local');

        return back()->with('flash', [
            'import_log_id' => $importLog->id,
            'message'       => 'Import sedang diproses di background.',
        ]);
    }

    /**
     * Download empty Excel template with headers and guide row.
     */
    public function downloadTemplate()
    {
        return Excel::download(
            new ProcurementTemplateExport(),
            'template_import_procurement.xlsx'
        );
    }

    /**
     * Export all procurement data as Excel file.
     */
    public function exportExcel()
    {
        return Excel::download(
            new ProcurementExport(),
            'laporan_procurement_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }

    /**
     * Export all procurement data as PDF.
     * Paper: Folio/F4 Landscape — ensures wide table is not clipped.
     */
    public function exportPdf()
    {
        $procurements = Procurement::orderBy('id', 'desc')->get();

        $pdf = Pdf::loadView('exports.procurement-pdf', [
            'procurements' => $procurements,
            'generatedAt'  => now()->format('d M Y H:i:s'),
        ]);

        // Folio/F4 Landscape: [0, 0, 612.00, 936.00]
        $pdf->setPaper([0, 0, 612.00, 936.00], 'landscape');

        return $pdf->download(
            'laporan_procurement_' . now()->format('Y-m-d_His') . '.pdf'
        );
    }

    /**
     * Download the error log Excel for a specific import job.
     */
    public function downloadErrorLog(ImportLog $importLog)
    {
        // Authorization: only the owner can download their error log
        if ($importLog->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        if (!$importLog->hasErrorLog()) {
            abort(404, 'Error log tidak tersedia.');
        }

        $path = Storage::disk('local')->path($importLog->error_file_path);

        if (!file_exists($path)) {
            abort(404, 'File error log tidak ditemukan.');
        }

        return response()->download(
            $path,
            "error_log_import_{$importLog->id}.xlsx"
        );
    }
}
