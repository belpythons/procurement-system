<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('re_technical_evaluations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('te_id');

            $table->text('catatan_revisi');

            $table->string('status')->default('Revisi');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('re_technical_evaluations');
    }
};