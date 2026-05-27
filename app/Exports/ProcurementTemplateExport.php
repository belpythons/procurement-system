<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProcurementTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
     * Column headings matching the import format.
     * These are the exact headers that ProcurementImport expects.
     */
    public function headings(): array
    {
        return [
            'kode_pengadaan',
            'nama_barang',
            'vendor',
            'tanggal_te',
            'tanggal_rete',
            'tanggal_po',
        ];
    }

    /**
     * One example/guide row showing correct data format.
     */
    public function array(): array
    {
        return [
            // Row 1: Example data with guide values
            [
                'PKD-001',
                'Contoh Nama Barang',
                'PT. Contoh Vendor',
                '2026-01-15',
                '2026-02-20',
                '2026-03-10',
            ],
            // Row 2: Guide notes explaining format rules
            [
                '(Wajib diisi, unik)',
                '(Wajib diisi)',
                '(Wajib diisi)',
                '(Opsional, format: YYYY-MM-DD)',
                '(Opsional, format: YYYY-MM-DD)',
                '(Opsional, format: YYYY-MM-DD)',
            ],
        ];
    }

    /**
     * Style the template for clarity.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row: dark blue background, white bold text
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2F3F52'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            // Example data row: light green background
            2 => [
                'font' => ['size' => 11],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8F5E9'],
                ],
            ],
            // Guide row: light yellow background, italic
            3 => [
                'font' => ['italic' => true, 'color' => ['rgb' => '666666'], 'size' => 10],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFF9C4'],
                ],
            ],
        ];
    }
}
