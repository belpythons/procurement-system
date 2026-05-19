<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel departments: menyimpan data departemen beserta
     * supervisor_id dan safety_officer_id yang bertugas.
     *
     * FK ke users TIDAK menggunakan constrained() di sini karena
     * tabel users belum dibuat pada saat migrasi ini dijalankan.
     * Relasi dijaga di level Eloquent Model.
     */
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('nama_departemen');

            // ID supervisor & safety officer (referensi ke users, tapi tanpa FK constraint
            // karena tabel users dibuat setelahnya — relasi dijaga di Eloquent)
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->unsignedBigInteger('safety_officer_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
