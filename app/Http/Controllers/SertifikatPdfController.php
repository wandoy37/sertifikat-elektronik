<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\SertifikatGenerate;
use setasign\Fpdi\Fpdi;

class SertifikatPdfController extends Controller
{
    protected $sertifikatGenerate;

    public function __construct(SertifikatGenerate $sertifikatGenerate)
    {
        $this->sertifikatGenerate = $sertifikatGenerate;
    }

    public function generateCertificate($id)
    {
        $sertifikat = DB::table('sertifikats')
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('kategoris', 'kegiatans.kategori_id', '=', 'kategoris.id')
            ->join('penandatangans', 'kegiatans.penandatangan_id', '=', 'penandatangans.id')
            ->where('sertifikats.id', $id)
            ->select(
                'sertifikats.id',
                'sertifikats.verified_code',
                'sertifikats.nomor_sertifikat',
                'sertifikats.peserta_id',
                'sertifikats.siswa_id',
                'sertifikats.orang_id',
                'kegiatans.kode_kegiatan AS kode_kegiatan',
                'kegiatans.judul_kegiatan AS judul_kegiatan',
                'kategoris.title AS kategori_kegiatan',
                'kategoris.template AS template_sertifikat',
                'kegiatans.tahun_kegiatan AS tahun_kegiatan',
                'kegiatans.tanggal_mulai_kegiatan AS tanggal_mulai_kegiatan',
                'kegiatans.tanggal_akhir_kegiatan AS tanggal_akhir_kegiatan',
                'kegiatans.total_jam_kegiatan AS total_jam_kegiatan',
                'kegiatans.tanggal_penandatanganan AS tanggal_penandatanganan',
                'penandatangans.nama AS nama_penandatangan',
                'penandatangans.nip AS nip_penandatangan',
                'penandatangans.pangkat_golongan AS pangkat_golongan_penandatangan',
                'penandatangans.jabatan AS jabatan_penandatangan',
            )
            ->first();

        if ($sertifikat->kategori_kegiatan == 'pelatihan') {
            $this->sertifikatGenerate->prosesSingleGenerate($sertifikat);
        }
        if ($sertifikat->kategori_kegiatan == 'bimtek') {
            $this->sertifikatGenerate->prosesSingleGenerateBimtek($sertifikat);
        }
        if ($sertifikat->kategori_kegiatan == 'pkl') {
            $this->sertifikatGenerate->prosesSingleGenerateSiswa($sertifikat);
        }
    }

    public function generateAllCertificate($id)
    {
        $sertifikats = DB::table('sertifikats')->where('kegiatan_id', $id)
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('kategoris', 'kegiatans.kategori_id', '=', 'kategoris.id')
            ->join('penandatangans', 'kegiatans.penandatangan_id', '=', 'penandatangans.id')
            ->select(
                'sertifikats.id',
                'sertifikats.verified_code',
                'sertifikats.nomor_sertifikat',
                'sertifikats.peserta_id',
                'sertifikats.siswa_id',
                'sertifikats.orang_id',
                'kegiatans.kode_kegiatan AS kode_kegiatan',
                'kegiatans.judul_kegiatan AS judul_kegiatan',
                'kategoris.title AS kategori_kegiatan',
                'kategoris.template AS template_sertifikat',
                'kegiatans.tahun_kegiatan AS tahun_kegiatan',
                'kegiatans.tanggal_mulai_kegiatan AS tanggal_mulai_kegiatan',
                'kegiatans.tanggal_akhir_kegiatan AS tanggal_akhir_kegiatan',
                'kegiatans.total_jam_kegiatan AS total_jam_kegiatan',
                'kegiatans.tanggal_penandatanganan AS tanggal_penandatanganan',
                'penandatangans.nama AS nama_penandatangan',
                'penandatangans.nip AS nip_penandatangan',
                'penandatangans.pangkat_golongan AS pangkat_golongan_penandatangan',
                'penandatangans.jabatan AS jabatan_penandatangan',
            )
            ->get();

        // If Kategori Kegiatan Pelatihan
        if ($sertifikats[0]->kategori_kegiatan == 'pelatihan') {
            $this->sertifikatGenerate->prosesAllGenerate($sertifikats);
        }
        // If Kategori Kegiatan Bimtek
        if ($sertifikats[0]->kategori_kegiatan == 'bimtek') {
            $this->sertifikatGenerate->prosesAllGenerateBimtek($sertifikats);
        }
        // If Kategori Kegiatan PKL
        if ($sertifikats[0]->kategori_kegiatan == 'pkl') {
            $this->sertifikatGenerate->prosesAllGenerateSiswa($sertifikats);
        }
    }

    public function terbitkanCertificate($id)
    {
        $sertifikat = DB::table('sertifikats')
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('kategoris', 'kegiatans.kategori_id', '=', 'kategoris.id')
            ->join('penandatangans', 'kegiatans.penandatangan_id', '=', 'penandatangans.id')
            ->where('sertifikats.id', $id)
            ->select(
                'sertifikats.id',
                'sertifikats.status',
                'sertifikats.verified_code',
                'sertifikats.nomor_sertifikat',
                'sertifikats.peserta_id',
                'sertifikats.siswa_id',
                'sertifikats.orang_id',
                'kegiatans.id AS id_kegiatan',
                'kegiatans.kode_kegiatan AS kode_kegiatan',
                'kegiatans.judul_kegiatan AS judul_kegiatan',
                'kategoris.title AS kategori_kegiatan',
                'kategoris.template AS template_sertifikat',
                'kegiatans.tahun_kegiatan AS tahun_kegiatan',
                'kegiatans.tanggal_mulai_kegiatan AS tanggal_mulai_kegiatan',
                'kegiatans.tanggal_akhir_kegiatan AS tanggal_akhir_kegiatan',
                'kegiatans.total_jam_kegiatan AS total_jam_kegiatan',
                'kegiatans.tanggal_penandatanganan AS tanggal_penandatanganan',
                'penandatangans.nama AS nama_penandatangan',
                'penandatangans.nip AS nip_penandatangan',
                'penandatangans.pangkat_golongan AS pangkat_golongan_penandatangan',
                'penandatangans.jabatan AS jabatan_penandatangan',
                'penandatangans.tanda_tangan_stempel AS tanda_tangan_stempel',
            )
            ->first();

        if ($sertifikat->kategori_kegiatan == 'pelatihan') {
            $this->sertifikatGenerate->prosesSingleTerbit($sertifikat);
        }
        if ($sertifikat->kategori_kegiatan == 'bimtek') {
            $this->sertifikatGenerate->prosesSingleTerbitBimtek($sertifikat);
        }
        if ($sertifikat->kategori_kegiatan == 'pkl') {
            $this->sertifikatGenerate->prosesSingleTerbitSiswa($sertifikat);
        }
    }

    public function generateAllPartsCertificate($id)
    {
        $sertifikats = DB::table('sertifikats')->where('kegiatan_id', $id)
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('kategoris', 'kegiatans.kategori_id', '=', 'kategoris.id')
            ->join('penandatangans', 'kegiatans.penandatangan_id', '=', 'penandatangans.id')
            ->select(
                'sertifikats.id',
                'sertifikats.verified_code',
                'sertifikats.nomor_sertifikat',
                'sertifikats.peserta_id',
                'kegiatans.kode_kegiatan AS kode_kegiatan',
                'kegiatans.judul_kegiatan AS judul_kegiatan',
                'kategoris.title AS kategori_kegiatan',
                'kategoris.template AS template_sertifikat',
                'kegiatans.tahun_kegiatan AS tahun_kegiatan',
                'kegiatans.tanggal_mulai_kegiatan AS tanggal_mulai_kegiatan',
                'kegiatans.tanggal_akhir_kegiatan AS tanggal_akhir_kegiatan',
                'kegiatans.total_jam_kegiatan AS total_jam_kegiatan',
                'kegiatans.tanggal_penandatanganan AS tanggal_penandatanganan',
                'penandatangans.nama AS nama_penandatangan',
                'penandatangans.nip AS nip_penandatangan',
                'penandatangans.pangkat_golongan AS pangkat_golongan_penandatangan',
                'penandatangans.jabatan AS jabatan_penandatangan',
            )
            ->get();

        $this->sertifikatGenerate->prosesAllPartsGenerate($sertifikats);
    }

    public function generateCertificateNarasumber($id)
    {
        $sertifikat = DB::table('sertifikats')
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('kategoris', 'kegiatans.kategori_id', '=', 'kategoris.id')
            ->join('narasumbers', 'sertifikats.narasumber_id', '=', 'narasumbers.id')
            ->where('sertifikats.id', $id)
            ->select(
                'sertifikats.id',
                'sertifikats.verified_code',
                'sertifikats.nomor_sertifikat',
                'kegiatans.kode_kegiatan AS kode_kegiatan',
                'kegiatans.judul_kegiatan AS judul_kegiatan',
                'kategoris.title AS kategori_kegiatan',
                'kegiatans.tahun_kegiatan AS tahun_kegiatan',
                'kegiatans.tanggal_mulai_kegiatan AS tanggal_mulai_kegiatan',
                'kegiatans.tanggal_akhir_kegiatan AS tanggal_akhir_kegiatan',
                'narasumbers.nama AS nama',
                'narasumbers.nip AS nip',
                'narasumbers.tempat_lahir AS tempat_lahir',
                'narasumbers.tempat_lahir AS tempat_lahir',
                'narasumbers.tanggal_lahir AS tanggal_lahir',
                'narasumbers.pangkat_golongan AS pangkat_golongan',
                'narasumbers.jabatan AS jabatan',
                'narasumbers.instansi AS instansi',
            )
            ->first();

        $this->sertifikatGenerate->generateSertifikatNarasumber($sertifikat);
    }

    public function generateCertificateNarasumberDownload($id)
    {
        $sertifikat = DB::table('sertifikats')
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('kategoris', 'kegiatans.kategori_id', '=', 'kategoris.id')
            ->join('narasumbers', 'sertifikats.narasumber_id', '=', 'narasumbers.id')
            ->where('sertifikats.id', $id)
            ->select(
                'sertifikats.id',
                'sertifikats.verified_code',
                'sertifikats.nomor_sertifikat',
                'kegiatans.kode_kegiatan AS kode_kegiatan',
                'kegiatans.judul_kegiatan AS judul_kegiatan',
                'kategoris.title AS kategori_kegiatan',
                'kegiatans.tahun_kegiatan AS tahun_kegiatan',
                'kegiatans.tanggal_mulai_kegiatan AS tanggal_mulai_kegiatan',
                'kegiatans.tanggal_akhir_kegiatan AS tanggal_akhir_kegiatan',
                'narasumbers.nama AS nama',
                'narasumbers.nip AS nip',
                'narasumbers.tempat_lahir AS tempat_lahir',
                'narasumbers.tempat_lahir AS tempat_lahir',
                'narasumbers.tanggal_lahir AS tanggal_lahir',
                'narasumbers.pangkat_golongan AS pangkat_golongan',
                'narasumbers.jabatan AS jabatan',
                'narasumbers.instansi AS instansi',
            )
            ->first();

        $this->sertifikatGenerate->generateSertifikatNarasumberDownload($sertifikat);
    }
}
