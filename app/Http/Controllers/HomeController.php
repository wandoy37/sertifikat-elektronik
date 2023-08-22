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
        return redirect()->route('home.show', $request->id);
    }

    public function show($id)
    {
        $sertifikat = DB::table('sertifikats')
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('pesertas', 'sertifikats.peserta_id', '=', 'pesertas.id')
            ->join('kategoris', 'kegiatans.kategori_id', '=', 'kategoris.id')
            ->join('penandatangans', 'kegiatans.penandatangan_id', '=', 'penandatangans.id')
            ->where('sertifikats.id', $id)
            ->select(
                'sertifikats.id',
                'sertifikats.status',
                'kegiatans.id AS id_kegiatan',
                'kegiatans.kode_kegiatan AS kode_kegiatan',
                'kegiatans.judul_kegiatan AS judul_kegiatan',
                'kategoris.title AS kategori_kegiatan',
                'kategoris.template AS template_sertifikat',
                'kegiatans.tahun_kegiatan AS tahun_kegiatan',
                'kegiatans.tanggal_mulai_kegiatan AS tanggal_mulai_kegiatan',
                'kegiatans.tanggal_akhir_kegiatan AS tanggal_akhir_kegiatan',
                'kegiatans.total_jam_kegiatan AS total_jam_kegiatan',
                'pesertas.nama AS nama_peserta',
                'pesertas.nomor_identitas AS nomor_identitas_peserta',
                'pesertas.tempat_lahir AS tempat_lahir_peserta',
                'pesertas.tanggal_lahir AS tanggal_lahir_peserta',
                'pesertas.jenis_kelamin AS jenis_kelamin_peserta',
                'pesertas.foto AS foto_peserta',
                'pesertas.pangkat_golongan AS pangkat_golongan_peserta',
                'pesertas.jabatan AS jabatan_peserta',
                'pesertas.instansi AS instansi_peserta',
                'penandatangans.nama AS nama_penandatangan',
                'penandatangans.nip AS nip_penandatangan',
                'penandatangans.pangkat_golongan AS pangkat_golongan_penandatangan',
                'penandatangans.jabatan AS jabatan_penandatangan',
                'penandatangans.tanda_tangan_stempel AS tanda_tangan_stempel',
            )
            ->first();

        if (!$sertifikat) {
            $pesan = 'Sertifikat dengan kode ' . $id . ' tidak ditemukan';
            return view('home.show_error', compact('pesan'));
        }
        $pathToFile = public_path('sertifikat/' . 'doc-sertifikat-' . $sertifikat->id . '.' . 'pdf');
        // return response()->file($pathToFile);
        return view('home.show', compact('sertifikat'));
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
