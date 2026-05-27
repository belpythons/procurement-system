<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class FailedRowsExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
     * Array of failure data collected from ProcurementImport.
     * Each entry has: row, attribute, errors, values
     */
    private array $failures;

    public function __construct(array $failures)
    {
        $this->failures = $failures;
    }

    /**
     * Column headings — mirrors import columns + error reason column.
     */
    public function headings(): array
    {
        return [
            'Baris ke-',
            'Kode Pengadaan',
            'Nama Barang',
            'Vendor',
            'Tanggal TE',
            'Tanggal RE-TE',
            'Tanggal PO',
            'Alasan Gagal / Error',
        ];
    }

    /**
     * Map failure data to rows.
     */
    public function array(): array
    {
        return array_map(function (array $failure) {
            $values = $failure['values'] ?? [];

            return [
                $failure['row'] ?? '-',
                $values['kode_pengadaan'] ?? '-',
                $values['nama_barang'] ?? '-',
                $values['vendor'] ?? '-',
                $values['tanggal_te'] ?? '-',
                $values['tanggal_rete'] ?? '-',
                $values['tanggal_po'] ?? '-',
                implode('; ', $failure['errors'] ?? ['Unknown error']),
            ];
        }, $this->failures);
    }

    /**
     * Style the error log for readability.
     */
    public function styles(Worksheet $sheet): array
    {
        $lastRow = count($this->failures) + 1; // +1 for header

        return [
            // Header: red background, white bold text
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DC3545'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            // Error reason column (H): light red background for emphasis
            "H2:H{$lastRow}" => [
                'font' => ['color' => ['rgb' => 'CC0000'], 'bold' => true],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFEBEE'],
                ],
            ],
        ];
    }
}
