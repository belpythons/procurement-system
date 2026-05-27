<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RequestPurchasing;
use App\Models\TechnicalEvaluation;
use App\Models\ReTechnicalEvaluation;
use App\Models\PurchaseOrder;

class LaporanController extends Controller
{
    public function index()
    {

        /*
        |--------------------------------------------------------------------------
        | REQUEST PURCHASING
        |--------------------------------------------------------------------------
        */

        $rp = RequestPurchasing::select(
            'kode_rp as kode',
            'nama_barang as barang',
            'status',
            'tanggal'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | TECHNICAL EVALUATION
        |--------------------------------------------------------------------------
        */

        $te = TechnicalEvaluation::select(
            'kode_te as kode',
            'nama_barang as barang',
            'status',
            'tanggal'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | RE TECHNICAL EVALUATION
        |--------------------------------------------------------------------------
        */

        $rete = ReTechnicalEvaluation::select(
            'kode_rete as kode',
            'nama_barang as barang',
            'status',
            'tanggal'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | PURCHASE ORDER
        |--------------------------------------------------------------------------
        */

        $po = PurchaseOrder::select(
            'kode_po as kode',
            'nama_barang as barang',
            'status',
            'tanggal'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | GABUNGKAN SEMUA DATA
        |--------------------------------------------------------------------------
        */

        $laporan = collect([]);

        $laporan = $laporan
            ->merge($rp)
            ->merge($te)
            ->merge($rete)
            ->merge($po)
            ->sortByDesc('tanggal');


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('laporan.index', compact('laporan'));
    }
}