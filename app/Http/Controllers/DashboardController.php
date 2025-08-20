<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use App\Models\Penandatangan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // 1St Method
        // $pesertas = Http::get(env('SIMPELTAN_API_DATA_PESERTA'))->json();

        // 2nd Method
        // $url = env('SIMPELTAN_API_DATA_PESERTA');
        // $response = file_get_contents($url);
        // $pesertas = json_decode($response, true);

        // 3nd Method
        $url = env('SIMPELTAN_API_DATA_PESERTA');

        try {
            $response = Http::timeout(10)->get($url);

            if ($response->successful()) {
                $pesertas = $response->json();

                // Simpan data ke cache untuk fallback
                Cache::put('pesertas_cache', $pesertas, now()->addMinutes(30));

                // return response()->json([
                //     'status' => 'success',
                //     'source' => 'api',
                //     'data' => $pesertas
                // ]);
            } else {
                throw new \Exception('API Error: ' . $response->status());
            }
        } catch (\Throwable $e) {
            // Ambil data dari cache jika API gagal
            $pesertas = Cache::get('pesertas_cache', []);

            // return response()->json([
            //     'status' => 'fallback',
            //     'message' => $e->getMessage(),
            //     'data' => $pesertas
            // ]);
        }

        $tahun_saat_ini = date('Y');

        $kategori_bimtek = [1];
        $kategori_pelatihan = '2';
        $kategori_pkl = '3';

        $kegiatan_bimtek = Kegiatan::where('tahun_kegiatan', $tahun_saat_ini)
            ->whereIn('kategori_id', [1])
            ->latest() // Mengurutkan berdasarkan tanggal terbaru jika ada kolom created_at/updated_at
            ->get();

        $kegiatan_pelatihan = Kegiatan::where('tahun_kegiatan', $tahun_saat_ini)
            ->whereIn('kategori_id', [2])
            ->latest() // Mengurutkan berdasarkan tanggal terbaru jika ada kolom created_at/updated_at
            ->get();

        $kegiatan_pkl = Kegiatan::where('tahun_kegiatan', $tahun_saat_ini)
            ->whereIn('kategori_id', [3])
            ->latest() // Mengurutkan berdasarkan tanggal terbaru jika ada kolom created_at/updated_at
            ->get();

        $sertifikats = Sertifikat::all();
        $penandatangans = Penandatangan::all();
        return view('dashboard.index', compact('pesertas', 'kegiatan_bimtek', 'kegiatan_pelatihan', 'kegiatan_pkl', 'sertifikats', 'penandatangans'));
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
