<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Procurement</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #2F3F52;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            color: #2F3F52;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead th {
            background-color: #2F3F52;
            color: #ffffff;
            padding: 8px 6px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #1a2a3a;
        }

        tbody td {
            padding: 6px;
            text-align: center;
            border: 1px solid #ccc;
            font-size: 9px;
        }

        tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        .status-rp   { background-color: #DBEAFE; color: #1E40AF; font-weight: bold; padding: 3px 8px; border-radius: 4px; }
        .status-te   { background-color: #D1FAE5; color: #065F46; font-weight: bold; padding: 3px 8px; border-radius: 4px; }
        .status-rete { background-color: #FEF3C7; color: #92400E; font-weight: bold; padding: 3px 8px; border-radius: 4px; }
        .status-po   { background-color: #FCE7F3; color: #9D174D; font-weight: bold; padding: 3px 8px; border-radius: 4px; }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 8px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN DATA PROCUREMENT</h1>
        <p>PT. KMI — Procurement System</p>
        <p>Dicetak pada: {{ $generatedAt }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 14%;">Kode Pengadaan</th>
                <th style="width: 20%;">Nama Barang</th>
                <th style="width: 18%;">Vendor</th>
                <th style="width: 11%;">Tanggal TE</th>
                <th style="width: 11%;">Tanggal RE-TE</th>
                <th style="width: 11%;">Tanggal PO</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($procurements as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode_pengadaan }}</td>
                    <td style="text-align: left;">{{ $item->nama_barang }}</td>
                    <td style="text-align: left;">{{ $item->vendor }}</td>
                    <td>{{ $item->tanggal_te?->format('Y-m-d') ?? '-' }}</td>
                    <td>{{ $item->tanggal_rete?->format('Y-m-d') ?? '-' }}</td>
                    <td>{{ $item->tanggal_po?->format('Y-m-d') ?? '-' }}</td>
                    <td>
                        @switch($item->status)
                            @case('RP')
                                <span class="status-rp">RP</span>
                                @break
                            @case('TE')
                                <span class="status-te">TE</span>
                                @break
                            @case('RE-TE')
                                <span class="status-rete">RE-TE</span>
                                @break
                            @case('PO')
                                <span class="status-po">PO</span>
                                @break
                            @default
                                {{ $item->status }}
                        @endswitch
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #999; padding: 20px;">
                        Tidak ada data procurement.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Procurement System — Generated {{ $generatedAt }}
    </div>

</body>
</html>
