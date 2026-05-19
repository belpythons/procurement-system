<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel permits: menyimpan semua data Permit-to-Work.
     *
     * - user_id       → Pekerja yang mengajukan
     * - department_id  → Departemen pekerja (snapshot saat pengajuan)
     * - supervisor_id  → Auto-assigned dari department saat submit
     * - safety_officer_id → Auto-assigned dari department saat submit
     */
    public function up(): void
    {
        Schema::create('permits', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_permit')->unique();           // PRM-001, PRM-002, ...

            // Relasi ke users (pekerja, supervisor, safety officer)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('supervisor_id')->constrained('users');
            $table->foreignId('safety_officer_id')->constrained('users');

            // Detail pekerjaan
            $table->string('jenis_pekerjaan');                  // Hot Work, Cold Work, Confined Space, dll
            $table->string('nama_pekerjaan');                   // Judul/nama singkat pekerjaan
            $table->text('deskripsi')->nullable();              // Deskripsi lengkap
            $table->date('tanggal_kerja');                      // Tanggal pelaksanaan
            $table->string('jam_mulai')->nullable();            // 08:00
            $table->string('jam_selesai')->nullable();          // 10:00

            // Lokasi
            $table->string('gedung')->nullable();
            $table->string('area')->nullable();
            $table->string('lokasi')->nullable();               // Detail lokasi spesifik

            // Risiko & APD
            $table->string('tingkat_risiko')->nullable();       // Risiko Rendah, Sedang, Tinggi
            $table->json('apd')->nullable();                    // ["Helm","Sarung tangan","Sepatu safety"]

            // Status & Approval
            $table->enum('status', ['Pending', 'Disetujui', 'Ditolak', 'Selesai'])->default('Pending');

            // Catatan dari atasan
            $table->text('catatan_supervisor')->nullable();
            $table->text('catatan_safety')->nullable();

            // Evaluasi risiko dari Safety Officer
            $table->text('evaluasi_risiko')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permits');
    }
};
