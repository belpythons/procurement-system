<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReTechnicalEvaluation;

class ReTechnicalEvaluationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TAMPIL DATA RE-TE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $rete = ReTechnicalEvaluation::latest()->get();

        return view('rete.index', compact('rete'));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA RE-TE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        ReTechnicalEvaluation::create([

            'kode_rete'   => $request->kode_rete,
            'nama_barang' => $request->nama_barang,
            'vendor'      => $request->vendor,
            'catatan'     => $request->catatan,
            'status'      => $request->status,
            'tanggal'     => $request->tanggal,

        ]);

        return redirect()->route('rete.index')

        ->with(
            'success',
            'Data RE-TE berhasil ditambahkan'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE DATA RE-TE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $rete = ReTechnicalEvaluation::findOrFail($id);

        $rete->update([

            'kode_rete'   => $request->kode_rete,
            'nama_barang' => $request->nama_barang,
            'vendor'      => $request->vendor,
            'catatan'     => $request->catatan,
            'status'      => $request->status,
            'tanggal'     => $request->tanggal,
        ]);

        return redirect()->route('rete.index')

        ->with(
            'success',
            'Data RE-TE berhasil diupdate'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS DATA RE-TE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $rete = ReTechnicalEvaluation::findOrFail($id);

        $rete->delete();

        return redirect()->route('rete.index')

        ->with(
            'success',
            'Data RE-TE berhasil dihapus'
        );
    }
}