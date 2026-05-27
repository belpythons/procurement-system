<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Original procurements table (DEPRECATED — superseded by restructure migration).
 */
return new class extends Migration
{
    public function up(): void
    {
        // This migration is intentionally left as a no-op.
        // The table is dropped and recreated by 2026_05_27_210000_restructure_procurements_table.php
    }

    public function down(): void
    {
        //
    }
};