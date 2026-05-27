<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalEvaluation extends Model
{
    protected $table = 'technical_evaluations';

    protected $fillable = [

        'kode_te',
        'vendor',
        'hasil_evaluasi',
        'status',
        'tanggal'

    ];
}