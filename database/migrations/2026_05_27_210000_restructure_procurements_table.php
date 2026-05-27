<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Restructure procurements table.
     * Drops old schema and creates new centralized master table.
     */
    public function up(): void
    {
        Schema::dropIfExists('procurements');

        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengadaan')->unique();
            $table->string('nama_barang');
            $table->string('vendor');

            // Phase tracking dates (nullable — filled as procurement progresses)
            $table->date('tanggal_te')->nullable();
            $table->date('tanggal_rete')->nullable();
            $table->date('tanggal_po')->nullable();

            // Auto-computed by model mutator based on date fields
            $table->string('status')->default('RP');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurements');
    }
};
