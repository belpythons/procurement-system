<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestPurchasing extends Model
{
    protected $table = 'request_purchasings';

    protected $fillable = [
        'kode_rp',
        'nama_barang',
        'qty',
        'departemen',
        'tanggal',
        'keterangan',
        'status',
    ];
}