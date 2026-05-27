<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    // nama tabel (opsional tapi aman)
    protected $table = 'purchase_orders';

    // field yang boleh diisi
    protected $fillable = [
        'kode_po',
        'nama_barang',
        'vendor',
        'status',
        'tanggal',
    ];
}