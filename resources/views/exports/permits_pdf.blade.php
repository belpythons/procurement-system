<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Permit-to-Work</title>
    <style>
        /* ── Reset & Base ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #1f2937;
            padding: 20px;
        }

        /* ── Header ── */
        .report-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 12px;
        }
        .report-header h1 {
            font-size: 18px;
            color: #1e40af;
            margin-bottom: 4px;
        }
        .report-header p {
            font-size: 10px;
            color: #6b7280;
        }

        /* ── Meta Info ── */
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 14px;
            font-size: 9px;
            color: #6b7280;
        }
        .meta-info table {
            width: 100%;
        }
        .meta-info td {
            padding: 2px 8px 2px 0;
        }
        .meta-info td:last-child {
            text-align: right;
        }

        /* ── Table ── */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #1e40af;
            color: white;
            font-weight: 600;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 6px 4px;
            text-align: left;
            border: 1px solid #1e40af;
        }
        table.data-table td {
            padding: 5px 4px;
            border: 1px solid #d1d5db;
            font-size: 9px;
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f3f4f6;
        }

        /* ── Status Badges ── */
        .status-pending { color: #d97706; font-weight: 600; }
        .status-disetujui { color: #059669; font-weight: 600; }
        .status-ditolak { color: #dc2626; font-weight: 600; }
        .status-selesai { color: #2563eb; font-weight: 600; }

        /* ── Footer ── */
        .report-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #d1d5db;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
        }
    </style>
</head>
<body>

    {{-- ═══ HEADER ═══ --}}
    <div class="report-header">
        <h1>LAPORAN PERMIT-TO-WORK</h1>
        <p>Sistem Manajemen Izin Kerja</p>
    </div>

    {{-- ═══ META INFO ═══ --}}
    <div class="meta-info">
        <table>
            <tr>
                <td><strong>Dicetak oleh:</strong> {{ $user->name }} ({{ ucfirst($user->role) }})</td>
                <td><strong>Tanggal cetak:</strong> {{ $generatedAt }}</td>
            </tr>
            <tr>
                <td><strong>Filter:</strong> {{ $filterInfo }}</td>
                <td><strong>Total data:</strong> {{ $permits->count() }} permit</td>
            </tr>
        </table>
    </div>

    {{-- ═══ DATA TABLE ═══ --}}
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>No. Permit</th>
                <th>Pekerja</th>
                <th>Departemen</th>
                <th>Jenis</th>
                <th>Pekerjaan</th>
                <th>Tgl Kerja</th>
                <th>Lokasi</th>
                <th>Risiko</th>
                <th>Status</th>
                <th>Supervisor</th>
                <th>Safety Officer</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permits as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->nomor_permit }}</td>
                <td>{{ $p->user->name ?? '-' }}</td>
                <td>{{ $p->department->nama_departemen ?? '-' }}</td>
                <td>{{ $p->jenis_pekerjaan }}</td>
                <td>{{ $p->nama_pekerjaan }}</td>
                <td>{{ $p->tanggal_kerja?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $p->lokasi ?? '-' }}</td>
                <td>{{ $p->tingkat_risiko ?? '-' }}</td>
                <td>
                    @switch($p->status)
                        @case('Pending')
                            <span class="status-pending">Pending</span>
                            @break
                        @case('Disetujui')
                            <span class="status-disetujui">Disetujui</span>
                            @break
                        @case('Ditolak')
                            <span class="status-ditolak">Ditolak</span>
                            @break
                        @case('Selesai')
                            <span class="status-selesai">Selesai</span>
                            @break
                        @default
                            {{ $p->status }}
                    @endswitch
                </td>
                <td>{{ $p->supervisor->name ?? '-' }}</td>
                <td>{{ $p->safetyOfficer->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" style="text-align: center; padding: 20px; color: #9ca3af;">
                    Tidak ada data permit untuk filter yang dipilih.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ═══ FOOTER ═══ --}}
    <div class="report-footer">
        Dokumen ini dihasilkan secara otomatis oleh Sistem Permit-to-Work &mdash; {{ $generatedAt }}
    </div>

</body>
</html>
