<?php

namespace App\Http\Controllers;

use App\Models\TechnicalEvaluation;
use App\Models\RequestPurchasing;
use Illuminate\Http\Request;

class TechnicalEvaluationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TAMPIL DATA TE
    |--------------------------------------------------------------------------
    |*/
    public function index()
    {
        $te = TechnicalEvaluation::latest()->get();

        return view('te.index', compact('te'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH TE
    |--------------------------------------------------------------------------
    |*/
    public function create()
    {
        $rp = RequestPurchasing::all();

        return view('te.create', compact('rp'));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN DATA TE
    |--------------------------------------------------------------------------
    |*/
    public function store(Request $request)
    {
        $request->validate([
            'kode_te'          => 'required',
            'vendor'           => 'required',
            'hasil_evaluasi'   => 'required',
            'status'           => 'required',
            'tanggal'          => 'required',
    ]);

    // 2. Simpan Data ke Database
    TechnicalEvaluation::create([
        'kode_te'          => $request->kode_te,
        'vendor'           => $request->vendor,
        'hasil_evaluasi'   => $request->hasil_evaluasi,
        'status'           => $request->status,
        'tanggal'          => $request->tanggal,
    ]);

    return redirect()->route('te.index')
        ->with('success', 'Data TE Berhasil Ditambahkan');
}

    /*
    |--------------------------------------------------------------------------
    | FORM EDIT TE
    |--------------------------------------------------------------------------
    |*/
    public function edit($id)
    {
        $te = TechnicalEvaluation::findOrFail($id);

        return view('te.edit', compact('te'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE TE
    |--------------------------------------------------------------------------
    |*/
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_te'         => 'required',
            'vendor'          => 'required',
            'hasil_evaluasi'  => 'required',
            'status'          => 'required',
            'tanggal'         => 'required',
        ]);

        $te = TechnicalEvaluation::findOrFail($id);

        $te->update([
            'kode_te'         => $request->kode_te,
            'vendor'          => $request->vendor,
            'hasil_evaluasi'  => $request->hasil_evaluasi,
            'status'          => $request->status,
            'tanggal'         => $request->tanggal,
        ]);

        return redirect()->route('te.index')
            ->with('success', 'Data TE Berhasil Diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS TE
    |--------------------------------------------------------------------------
    |*/
    public function destroy($id)
    {
        $te = TechnicalEvaluation::findOrFail($id);

        $te->delete();

        return redirect()->route('te.index')
            ->with('success', 'Data TE Berhasil Dihapus');
    }
}