<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {

            // CEK DULU BIAR TIDAK DUPLICATE ERROR

            if (!Schema::hasColumn('purchase_orders', 'kode_po')) {
                $table->string('kode_po')->after('id');
            }

            if (!Schema::hasColumn('purchase_orders', 'nama_barang')) {
                $table->string('nama_barang')->after('kode_po');
            }

            if (!Schema::hasColumn('purchase_orders', 'vendor')) {
                $table->string('vendor')->after('nama_barang');
            }

            if (!Schema::hasColumn('purchase_orders', 'status')) {
                $table->string('status')->default('Process')->after('vendor');
            }

            if (!Schema::hasColumn('purchase_orders', 'tanggal')) {
                $table->date('tanggal')->after('status');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {

            // DROP AMAN (HANYA JIKA ADA)

            if (Schema::hasColumn('purchase_orders', 'kode_po')) {
                $table->dropColumn('kode_po');
            }

            if (Schema::hasColumn('purchase_orders', 'nama_barang')) {
                $table->dropColumn('nama_barang');
            }

            if (Schema::hasColumn('purchase_orders', 'vendor')) {
                $table->dropColumn('vendor');
            }

            if (Schema::hasColumn('purchase_orders', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('purchase_orders', 'tanggal')) {
                $table->dropColumn('tanggal');
            }

        });
    }
};