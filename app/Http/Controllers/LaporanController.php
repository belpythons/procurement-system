<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Procurement;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Procurement::orderBy('id', 'desc')->get();

        return view('laporan.index', compact('laporan'));
    }
}