<?php

namespace Database\Factories;

use App\Models\Permit;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permit>
 */
class PermitFactory extends Factory
{
    protected $model = Permit::class;

    private static int $counter = 0;

    public function definition(): array
    {
        self::$counter++;

        $jenisOptions = [
            'Hot Work',
            'Cold Work',
            'Confined Space',
            'Listrik & Instrument',
            'Kendaraan & Alat Berat',
            'Penggalian',
            'Kompressor Oksigen',
        ];

        $namaMap = [
            'Hot Work'               => ['Pengelasan rangka mesin', 'Pemotongan besi', 'Pengelasan pipa'],
            'Cold Work'              => ['Perbaikan rak gudang', 'Pemotongan material', 'Pembersihan area'],
            'Confined Space'         => ['Pemindahan bahan kimia', 'Inspeksi tangki', 'Pembersihan tangki'],
            'Listrik & Instrument'   => ['Perbaikan panel listrik', 'Pemasangan kabel', 'Kalibrasi instrumen'],
            'Kendaraan & Alat Berat' => ['Pemindahan material', 'Pengangkutan peralatan', 'Mobilisasi crane'],
            'Penggalian'             => ['Penggalian pondasi', 'Penggalian saluran', 'Penggalian kabel'],
            'Kompressor Oksigen'     => ['Pengisian tabung oksigen', 'Perawatan kompressor', 'Uji tekanan'],
        ];

        $gedungOptions = [
            'CB (Control Building)',
            'Maintenance',
            'Warehouse',
            'Klorin Unit',
            'Admin Building',
            'Power Plant',
        ];

        $areaOptions = ['Workshop', 'Tangki', 'Pipa Utama', 'Mesin Produksi', 'Penyimpanan', 'Control Room'];

        $lokasiOptions = [
            'UNIT 1500 - CONTROL BUILDING (Line 1)',
            'UNIT 700 - MAIN POWER GENERATOR',
            'UNIT 1200 - FIRE TANK SYSTEM',
            'UNIT 1150 - LOADING ARM SYSTEM',
            'UNIT 800 - COOLING TOWER',
        ];

        $risikoOptions = ['Risiko Rendah', 'Risiko Sedang', 'Risiko Tinggi'];

        $apdOptions = [
            ['Helm', 'Sarung tangan', 'Sepatu safety'],
            ['Helm', 'Sarung tangan'],
            ['APD Lengkap'],
            ['Helm', 'Masker', 'Sepatu safety'],
            ['Masker', 'Sarung tangan'],
            ['Helm'],
        ];

        $jenis = $this->faker->randomElement($jenisOptions);
        $namaPekerjaan = $this->faker->randomElement($namaMap[$jenis]);

        return [
            'nomor_permit'      => 'PRM-' . str_pad(self::$counter, 3, '0', STR_PAD_LEFT),
            'jenis_pekerjaan'   => $jenis,
            'nama_pekerjaan'    => $namaPekerjaan,
            'deskripsi'         => $this->faker->sentence(12),
            'tanggal_kerja'     => $this->faker->dateTimeBetween('-3 months', 'now'),
            'jam_mulai'         => $this->faker->randomElement(['07:00', '08:00', '09:00', '10:00', '13:00']),
            'jam_selesai'       => $this->faker->randomElement(['10:00', '12:00', '14:00', '15:00', '17:00']),
            'gedung'            => $this->faker->randomElement($gedungOptions),
            'area'              => $this->faker->randomElement($areaOptions),
            'lokasi'            => $this->faker->randomElement($lokasiOptions),
            'tingkat_risiko'    => $this->faker->randomElement($risikoOptions),
            'apd'               => $this->faker->randomElement($apdOptions),
            'status'            => Permit::STATUS_PENDING,
            'catatan_supervisor'=> null,
            'catatan_safety'    => null,
            'evaluasi_risiko'   => null,
        ];
    }

    /**
     * State: Permit sudah disetujui.
     */
    public function disetujui(): static
    {
        return $this->state(fn (array $attrs) => [
            'status'             => Permit::STATUS_DISETUJUI,
            'catatan_supervisor' => 'Pekerjaan dapat dilakukan sesuai prosedur.',
            'catatan_safety'     => 'Area kerja aman dan terkendali.',
        ]);
    }

    /**
     * State: Permit ditolak.
     */
    public function ditolak(): static
    {
        return $this->state(fn (array $attrs) => [
            'status'             => Permit::STATUS_DITOLAK,
            'catatan_supervisor' => 'Ditolak karena APD tidak lengkap.',
            'catatan_safety'     => null,
        ]);
    }

    /**
     * State: Permit selesai.
     */
    public function selesai(): static
    {
        return $this->state(fn (array $attrs) => [
            'status'             => Permit::STATUS_SELESAI,
            'catatan_supervisor' => 'Pekerjaan telah selesai dilaksanakan.',
            'catatan_safety'     => 'Inspeksi akhir: area kerja bersih dan aman.',
            'evaluasi_risiko'    => 'Risiko terkendali selama pelaksanaan.',
        ]);
    }
}
