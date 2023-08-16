<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pesertas = Peserta::all();
        $kegiatans = Kegiatan::all();
        $sertifikats = Sertifikat::all();
        return view('dashboard.index', compact('pesertas', 'kegiatans', 'sertifikats'));
        // if (Auth::user()->role == 'admin') {
        //     $pesertas = Peserta::all();
        //     $kegiatans = Kegiatan::all();
        //     $sertifikats = Sertifikat::all();
        //     return view('dashboard.index', compact('pesertas', 'kegiatans', 'sertifikats'));
        // } else {
        //     $kegiatans = Kegiatan::all();
        //     $sertifikats = Sertifikat::all();
        //     if (!Auth::user()->peserta_id == null) {
        //         $totalsSertifikats = Sertifikat::where('peserta_id', Auth::user()->peserta->id)->where('status', 'terbit')->get();
        //     } else {
        //         $totalsSertifikats = [];
        //     }
        //     return view('dashboard.index', compact('kegiatans', 'sertifikats', 'totalsSertifikats'));
        // }
    }
}
