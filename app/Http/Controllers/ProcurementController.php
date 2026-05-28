<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Procurement;
use Inertia\Inertia;

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
                $q->where('kode_pengadaan', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('vendor', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        // STATUS FILTER
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $procurements = $query
            ->orderBy('id', 'desc')
            ->get();

        // TOTAL CARD (using class constants)
        $totalRP = Procurement::where('status', Procurement::STATUS_RP)->count();
        $totalTE = Procurement::where('status', Procurement::STATUS_TE)->count();
        $totalRETE = Procurement::where('status', Procurement::STATUS_RETE)->count();
        $totalPO = Procurement::where('status', Procurement::STATUS_PO)->count();

        return Inertia::render('Dashboard/Index', [
            'procurements' => $procurements,
            'totalRP'      => $totalRP,
            'totalTE'      => $totalTE,
            'totalRETE'    => $totalRETE,
            'totalPO'      => $totalPO,
            'filters'      => $request->only(['search', 'status']),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE FORM
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return Inertia::render('Dashboard/Create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $rules = [
            'kode_pengadaan' => 'required|unique:procurements,kode_pengadaan',
            'status'         => 'required|in:RP,TE,RE-TE,PO',
            'nama_barang'    => 'nullable|string|max:255',
            'vendor'         => 'nullable|string|max:255',
            'quantity'       => 'nullable|string|max:100',
            'departemen'     => 'nullable|string|max:255',
            'keterangan'     => 'nullable|string',
            'hasil_evaluasi' => 'nullable|string',
            'catatan'        => 'nullable|string',
            'tanggal'        => 'nullable|date',
        ];

        // Specific conditional validations per phase
        if ($request->status === 'RP') {
            $rules['nama_barang'] = 'required|string|max:255';
            $rules['quantity']    = 'required|string|max:100';
            $rules['departemen']  = 'required|string|max:255';
        } elseif ($request->status === 'TE') {
            $rules['vendor']         = 'required|string|max:255';
            $rules['hasil_evaluasi'] = 'required|string';
        } elseif ($request->status === 'RE-TE') {
            $rules['nama_barang'] = 'required|string|max:255';
            $rules['vendor']      = 'required|string|max:255';
            $rules['catatan']     = 'required|string';
        } elseif ($request->status === 'PO') {
            $rules['nama_barang'] = 'required|string|max:255';
            $rules['vendor']      = 'required|string|max:255';
            $rules['quantity']    = 'required|string|max:100';
            $rules['departemen']  = 'required|string|max:255';
        }

        $request->validate($rules);

        $procurement = new Procurement();
        $procurement->kode_pengadaan = $request->kode_pengadaan;
        $procurement->nama_barang    = $request->nama_barang ?? 'Barang';
        $procurement->vendor         = $request->vendor ?? 'Vendor Default';
        $procurement->quantity       = $request->quantity;
        $procurement->departemen     = $request->departemen;
        $procurement->keterangan     = $request->keterangan;
        $procurement->hasil_evaluasi = $request->hasil_evaluasi;
        $procurement->catatan        = $request->catatan;
        $procurement->status         = $request->status;

        $procurement->syncDatesWithStatus($request->status);
        if ($request->filled('tanggal')) {
            $date = $request->tanggal;
            if ($request->status === Procurement::STATUS_RP) {
                $procurement->tanggal_in = $date;
            } elseif ($request->status === Procurement::STATUS_TE) {
                $procurement->tanggal_te = $date;
            } elseif ($request->status === Procurement::STATUS_RETE) {
                $procurement->tanggal_rete = $date;
            } elseif ($request->status === Procurement::STATUS_PO) {
                $procurement->tanggal_po = $date;
            }
        }

        $procurement->save();

        return redirect()->to('/dashboard')
            ->with('success', 'Data procurement berhasil ditambahkan!');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);

        $rules = [
            'kode_pengadaan' => 'required|unique:procurements,kode_pengadaan,' . $id,
            'status'         => 'required|in:RP,TE,RE-TE,PO',
            'nama_barang'    => 'nullable|string|max:255',
            'vendor'         => 'nullable|string|max:255',
            'quantity'       => 'nullable|string|max:100',
            'departemen'     => 'nullable|string|max:255',
            'keterangan'     => 'nullable|string',
            'hasil_evaluasi' => 'nullable|string',
            'catatan'        => 'nullable|string',
            'tanggal'        => 'nullable|date',
        ];

        // Specific conditional validations per phase
        if ($request->status === 'RP') {
            $rules['nama_barang'] = 'required|string|max:255';
            $rules['quantity']    = 'required|string|max:100';
            $rules['departemen']  = 'required|string|max:255';
        } elseif ($request->status === 'TE') {
            $rules['vendor']         = 'required|string|max:255';
            $rules['hasil_evaluasi'] = 'required|string';
        } elseif ($request->status === 'RE-TE') {
            $rules['nama_barang'] = 'required|string|max:255';
            $rules['vendor']      = 'required|string|max:255';
            $rules['catatan']     = 'required|string';
        } elseif ($request->status === 'PO') {
            $rules['nama_barang'] = 'required|string|max:255';
            $rules['vendor']      = 'required|string|max:255';
            $rules['quantity']    = 'required|string|max:100';
            $rules['departemen']  = 'required|string|max:255';
        }

        $request->validate($rules);

        if ($request->has('kode_pengadaan')) {
            $procurement->kode_pengadaan = $request->kode_pengadaan;
        }
        if ($request->has('nama_barang')) {
            $procurement->nama_barang = $request->nama_barang;
        }
        if ($request->has('vendor')) {
            $procurement->vendor = $request->vendor;
        }
        if ($request->has('quantity')) {
            $procurement->quantity = $request->quantity;
        }
        if ($request->has('departemen')) {
            $procurement->departemen = $request->departemen;
        }
        if ($request->has('keterangan')) {
            $procurement->keterangan = $request->keterangan;
        }
        if ($request->has('hasil_evaluasi')) {
            $procurement->hasil_evaluasi = $request->hasil_evaluasi;
        }
        if ($request->has('catatan')) {
            $procurement->catatan = $request->catatan;
        }

        $statusChanged = $procurement->status !== $request->status;
        $procurement->status = $request->status;

        if ($statusChanged) {
            $procurement->syncDatesWithStatus($request->status);
        }

        if ($request->filled('tanggal')) {
            $date = $request->tanggal;
            if ($request->status === Procurement::STATUS_RP) {
                $procurement->tanggal_in = $date;
            } elseif ($request->status === Procurement::STATUS_TE) {
                $procurement->tanggal_te = $date;
            } elseif ($request->status === Procurement::STATUS_RETE) {
                $procurement->tanggal_rete = $date;
            } elseif ($request->status === Procurement::STATUS_PO) {
                $procurement->tanggal_po = $date;
            }
        }

        $procurement->save();

        return redirect()->to('/dashboard')
            ->with('success', 'Data procurement berhasil diperbarui!');
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

        return redirect()->to('/dashboard')
            ->with('success', 'Data procurement berhasil dihapus!');
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE PHASE (AUTOMATION)
    |--------------------------------------------------------------------------
    */
    public function approvePhase(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);
        $currentStatus = $procurement->status;

        // Auto set tanggal_out
        $procurement->tanggal_out = now();

        if ($currentStatus === Procurement::STATUS_RP) {
            $procurement->tanggal_te = now();
        } elseif ($currentStatus === Procurement::STATUS_TE) {
            $target = $request->input('target', 'RE-TE');
            if ($target === 'RE-TE') {
                $procurement->tanggal_rete = now();
            } else {
                $procurement->tanggal_po = now();
            }
        } elseif ($currentStatus === Procurement::STATUS_RETE) {
            $procurement->tanggal_po = now();
        }

        $procurement->save();

        return redirect()->to('/dashboard')
            ->with('success', 'Fase berhasil disetujui (Approved)!');
    }

    /*
    |--------------------------------------------------------------------------
    | REJECT PHASE (AUTOMATION)
    |--------------------------------------------------------------------------
    |*/
    public function rejectPhase(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);
        $currentStatus = $procurement->status;

        if ($currentStatus === Procurement::STATUS_TE) {
            $procurement->tanggal_te = null;
            $procurement->tanggal_out = null; // Reset tanggal_out when rejected to RP
        } elseif ($currentStatus === Procurement::STATUS_RETE) {
            $procurement->tanggal_rete = null;
        } elseif ($currentStatus === Procurement::STATUS_PO) {
            $procurement->tanggal_po = null;
        }

        $procurement->save();

        return redirect()->to('/dashboard')
            ->with('success', 'Fase berhasil ditolak (Rejected)!');
    }
}