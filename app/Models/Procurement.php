<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    protected $table = 'procurements';

    protected $fillable = [
        'rp_id',   // 🔥 TAMBAHAN WAJIB
        'kode',
        'barang',
        'status',
        'tanggal'
    ];

    public $timestamps = false;

    // =========================
    // RELASI KE REQUEST RP
    // =========================
    public function requestPurchasing()
    {
        return $this->belongsTo(RequestPurchasing::class, 'rp_id');
    }
}