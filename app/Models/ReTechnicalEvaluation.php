<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReTechnicalEvaluation extends Model
{
    use HasFactory;

    protected $table = 're_technical_evaluations';

    protected $fillable = [

        'kode_rete',
        'nama_barang',
        'vendor',
        'catatan',
        'status',
        'tanggal',

    ];
}