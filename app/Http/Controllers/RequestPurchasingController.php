<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestPurchasing;
use App\Models\Procurement;

class RequestPurchasingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |*/
    public function index()
    {
        $rps = RequestPurchasing::latest()->get();

        return view('rp.index', compact('rps'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE (CREATE RP + PROCUREMENT DISINKRONKAN)
    |--------------------------------------------------------------------------
    |*/
    public function store(Request $request)
    {
        $request->validate([
            'kode_rp'      => 'required',
            'nama_barang'  => 'required',
            'qty'          => 'required',
            'departemen'   => 'required',
            'status'       => 'required',
            'tanggal'      => 'required',
        ]);

        // 1. Tetap simpan status asli (Lolos/Tidak Lolos/Pending) ke tabel internal RP
        $rp = RequestPurchasing::create([
            'kode_rp'      => $request->kode_rp,
            'nama_barang'  => $request->nama_barang,
            'qty'          => $request->qty,
            'departemen'   => $request->departemen,
            'tanggal'      => $request->tanggal,
            'keterangan'   => $request->keterangan,
            'status'       => $request->status, 
        ]);

    
        Procurement::create([
            'kode'         => $request->kode_rp,
            'barang'       => $request->nama_barang,
            'status'       => 'Request Purchasing',
            'tanggal'      => $request->tanggal,
        ]);

        return redirect()
            ->route('rp.index')
            ->with('success', 'Data RP berhasil Ditambahkan ');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE (SYNC RP + PROCUREMENT DISINKRONKAN)
    |--------------------------------------------------------------------------
    |*/
    public function update(Request $request, $id)
    {
        $rp = RequestPurchasing::findOrFail($id);

        $request->validate([
            'kode_rp'      => 'required',
            'nama_barang'  => 'required',
            'qty'          => 'required',
            'departemen'   => 'required',
            'status'       => 'required',
            'tanggal'      => 'required',
        ]);

        $kodeLama = $rp->kode_rp;

        // Update data di tabel internal RP
        $rp->update([
            'kode_rp'      => $request->kode_rp,
            'nama_barang'  => $request->nama_barang,
            'qty'          => $request->qty,
            'departemen'   => $request->departemen,
            'tanggal'      => $request->tanggal,
            'keterangan'   => $request->keterangan,
            'status'       => $request->status,
        ]);

        // Update data di tabel Procurement dan pastikan statusnya tetap terkunci sebagai 'Request Purchasing'
        Procurement::where('kode', $kodeLama)->update([
            'kode'         => $request->kode_rp,
            'barang'       => $request->nama_barang,
            'status'       => 'Request Purchasing', // <--- DIUBAH DI SINI JUGA
            'tanggal'      => $request->tanggal,
        ]);

        return redirect()
            ->route('rp.index')
            ->with('success', 'Data RP berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE (SYNC RP + PROCUREMENT)
    |--------------------------------------------------------------------------
    |*/
    public function destroy($id)
    {
        $rp = RequestPurchasing::findOrFail($id);

        // Hapus data dari tabel procurement berdasarkan kodenya
        Procurement::where('kode', $rp->kode_rp)->delete();

        // Hapus data dari tabel internal RP
        $rp->delete();

        return redirect()
            ->route('rp.index')
            ->with('success', 'Data RP berhasil dihapus');
    }
}