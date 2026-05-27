<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {

            $table->id();

            $table->foreignId('rp_id');

            $table->string('supplier');

            $table->date('tanggal_po');

            $table->string('status')->default('Approved');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};