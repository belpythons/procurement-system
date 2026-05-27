<?php

namespace App\Exports;

use App\Models\Procurement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Illuminate\Support\Enumerable;

class ProcurementExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * Row counter for numbering.
     */
    private int $rowNumber = 0;

    /**
     * Query all procurement data ordered by latest.
     */
    public function collection(): Enumerable
    {
        return Procurement::orderBy('id', 'desc')->get();
    }

    /**
     * Column headings for the first row.
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Pengadaan',
            'Nama Barang',
            'Vendor',
            'Tanggal TE',
            'Tanggal RE-TE',
            'Tanggal PO',
            'Status',
        ];
    }

    /**
     * Map each Procurement model to a row.
     */
    public function map($procurement): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $procurement->kode_pengadaan,
            $procurement->nama_barang,
            $procurement->vendor,
            $procurement->tanggal_te?->format('Y-m-d') ?? '-',
            $procurement->tanggal_rete?->format('Y-m-d') ?? '-',
            $procurement->tanggal_po?->format('Y-m-d') ?? '-',
            $procurement->status,
        ];
    }

    /**
     * Style the header row.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2F3F52'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
