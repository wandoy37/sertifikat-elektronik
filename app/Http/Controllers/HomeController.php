<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    public function find(Request $request)
    {
        return redirect()->route('home.show', $request->code);
    }

    public function show($code)
    {
        // Get Verified Code Sertifikat
        $sertifikat = Sertifikat::where('verified_code', $code)->first();

        if (!$sertifikat) {
            $pesan = 'Sertifikat dengan kode ' . $code . ' tidak ditemukan';
            return view('home.show_error', compact('pesan'));
        } else {
            $kegiatan = DB::table('sertifikats')
                ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
                ->join('kategoris', 'kegiatans.kategori_id', '=', 'kategoris.id')
                ->where('sertifikats.verified_code', $code)
                ->select(
                    'sertifikats.id',
                    'sertifikats.verified_code',
                    'sertifikats.peserta_id',
                    'kegiatans.judul_kegiatan',
                    'kegiatans.tanggal_mulai_kegiatan AS tanggal_mulai_kegiatan',
                    'kegiatans.tanggal_akhir_kegiatan AS tanggal_akhir_kegiatan',
                    'kegiatans.total_jam_kegiatan AS total_jam_kegiatan',
                    'kegiatans.tahun_kegiatan AS tahun_kegiatan',
                )
                ->first();
        }
        // return response()->json($kegiatan);
        return view('home.show', compact('sertifikat', 'kegiatan'));
    }

    public function download($id)
    {
        $filePath = public_path("sertifikat/" . 'doc-sertifikat-' . $id . '.' . 'pdf');
        return response()->download($filePath);
    }

    public function preview($id)
    {
        $filePath = public_path("sertifikat/" . 'doc-sertifikat-' . $id . '.' . 'pdf');
        return response()->file($filePath);
    }
}
