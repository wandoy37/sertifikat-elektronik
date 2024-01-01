<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $pesertas = Http::get(env('SIMPELTAN_API_DATA_PESERTA'))->json();
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
