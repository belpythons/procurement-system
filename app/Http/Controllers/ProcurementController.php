<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Procurement;

class ProcurementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | READ DATA
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Procurement::query();

        // SEARCH
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('barang', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");

            });

        }

        $procurements = $query
            ->orderBy('id', 'desc')
            ->get();

        // TOTAL CARD
        $totalRP = Procurement::where(
            'status',
            'Request Purchasing'
        )->count();

        $totalTE = Procurement::where(
            'status',
            'Technical Evaluation'
        )->count();

        $totalRETE = Procurement::where(
            'status',
            'Re-Technical Evaluation'
        )->count();

        $totalPO = Procurement::where(
            'status',
            'Purchase Order'
        )->count();

        return view('dashboard', compact(
            'procurements',
            'totalRP',
            'totalTE',
            'totalRETE',
            'totalPO'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([

            'kode' => 'required',
            'barang' => 'required',
            'status' => 'required',
            'tanggal' => 'required',

        ]);

        Procurement::create([

            'kode' => $request->kode,
            'barang' => $request->barang,
            'status' => $request->status,
            'tanggal' => $request->tanggal,

        ]);

        return redirect('/dashboard')
            ->with('success', 'Data berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $request->validate([

            'kode' => 'required',
            'barang' => 'required',
            'status' => 'required',
            'tanggal' => 'required',

        ]);

        $procurement = Procurement::findOrFail($id);

        $procurement->update([

            'kode' => $request->kode,
            'barang' => $request->barang,
            'status' => $request->status,
            'tanggal' => $request->tanggal,

        ]);

        return redirect('/dashboard')
            ->with('success', 'Data berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $procurement = Procurement::findOrFail($id);

        $procurement->delete();

        return redirect('/dashboard')
            ->with('success', 'Data berhasil dihapus');
    }
}