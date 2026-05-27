<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_evaluations', function (Blueprint $table) {

            $table->id();

            $table->string('kode_te');

            $table->string('vendor');

            $table->text('hasil_evaluasi');

            $table->string('status');

            $table->date('tanggal');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_evaluations');
    }
};