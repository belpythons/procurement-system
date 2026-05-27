<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $po = PurchaseOrder::orderBy('id', 'desc')->get();
        return view('po.index', compact('po'));
    }

    public function create()
    {
        return view('po.create');
    }

    // SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'kode_po' => 'required',
            'nama_barang' => 'required',
            'vendor' => 'required',
            'status' => 'required',
            'tanggal' => 'required',
        ]);

        PurchaseOrder::create([
            'kode_po' => $request->kode_po,
            'nama_barang' => $request->nama_barang,
            'vendor' => $request->vendor,
            'status' => $request->status,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('po.index')
        ->with('success', ' Data PO berhasil ditambahkan');
    }

    // EDIT
    public function edit($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        return view('po.edit', compact('po'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_po' => 'required',
            'nama_barang' => 'required',
            'vendor' => 'required',
            'status' => 'required',
            'tanggal' => 'required',
        ]);

        $po = PurchaseOrder::findOrFail($id);

        $po->update([
            'kode_po' => $request->kode_po,
            'nama_barang' => $request->nama_barang,
            'vendor' => $request->vendor,
            'status' => $request->status,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('po.index')
        ->with('success', ' Data PO berhasil diupdate');
    }

    // HAPUS
    public function destroy($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        $po->delete();

        return redirect()->route('po.index')
        ->with('success', ' Data PO berhasil dihapus');
    }
}