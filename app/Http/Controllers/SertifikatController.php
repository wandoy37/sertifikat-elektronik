<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Narasumber;
use App\Models\Orang;
use App\Models\Peserta;
use App\Models\Sertifikat;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Services\SertifikatGenerate;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class SertifikatController extends Controller
{
    protected $sertifikatGenerate;

    public function __construct(SertifikatGenerate $sertifikatGenerate)
    {
        $this->sertifikatGenerate = $sertifikatGenerate;
    }
    //
    public function index()
    {
        $sertifikats = DB::table('sertifikats')
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('kategoris', 'kegiatans.kategori_id', '=', 'kategoris.id')
            ->select(
                'sertifikats.id',
                'sertifikats.verified_code',
                'sertifikats.nomor_sertifikat',
                'sertifikats.peserta_id',
                'sertifikats.siswa_id',
                'sertifikats.orang_id',
                'sertifikats.narasumber_id',
                'sertifikats.status',
                'kegiatans.judul_kegiatan AS judul_kegiatan',
                'kategoris.title AS kategori_kegiatan',
            )
            ->get();
        return view('dashboard.sertifikat.index', compact('sertifikats'));
    }

    public function createPeserta($id)
    {
        $kegiatan = Kegiatan::find($id);

        // If Peserta Kegiatan
        if ($kegiatan->kategori->title == 'pelatihan') {
            $client = new Client();
            $response = $client->get(env('SIMPELTAN_API_DATA_PESERTA'));
            $dataPeserta = json_decode($response->getBody(), true);
        }
        // If Bimtek Kegiatan
        if ($kegiatan->kategori->title == 'bimtek') {
            $dataPeserta = Orang::all();
        }
        // If PKL Kegiatan
        if ($kegiatan->kategori->title == 'pkl') {
            $dataPeserta = Siswa::all();
        }

        $sertifikats = DB::table('sertifikats')
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->select(
                'sertifikats.id',
                'sertifikats.verified_code',
                'sertifikats.nomor_sertifikat',
                'kegiatans.judul_kegiatan AS judul_kegiatan',
                'sertifikats.tanggal_terbit',
                'sertifikats.status',
                'sertifikats.peserta_id',
                'sertifikats.siswa_id',
                'sertifikats.narasumber_id',
                'sertifikats.orang_id',
            )
            ->where('sertifikats.kegiatan_id', '=', $kegiatan->id)
            ->get();

        $narasumbers = Narasumber::all();

        return view('dashboard.sertifikat.create_peserta', compact('kegiatan', 'sertifikats', 'dataPeserta', 'narasumbers'));
    }

    public function store(Request $request)
    {
        $kegiatan = Kegiatan::find($request->kegiatan_id);

        // If Peserta Kegiatan
        if ($kegiatan->kategori->title == 'pelatihan') {
            $validator = Validator::make(
                $request->all(),
                [
                    'peserta_id' => 'required',
                ],
                [],
            );
        }
        // If Bimtek Kegiatan
        if ($kegiatan->kategori->title == 'bimtek') {
            $validator = Validator::make(
                $request->all(),
                [
                    'orang_id' => 'required',
                ],
                [],
            );
        }
        // If PKL Kegiatan
        if ($kegiatan->kategori->title == 'pkl') {
            $validator = Validator::make(
                $request->all(),
                [
                    'siswa_id' => 'required',
                ],
                [],
            );
        }

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            // ======================================1St Metode==========================================
            // Last data
            $currentYear = $kegiatan->tahun_kegiatan;
            $lastSertifikat = Sertifikat::max('tahun');

            if ($lastSertifikat !== $currentYear) {
                // Jika tahun berubah, atur $lastSertifikat ke 1
                $lastSertifikat = 1;
            } else {
                // Jika tahun sama, ambil nomor sertifikat terakhir dan tambahkan 1
                $lastSertifikat = Sertifikat::where('tahun', $currentYear)->max('nomor_sertifikat');
                $lastSertifikat++;
            }
            // ======================================End 1St Metode======================================
            if ($kegiatan->kategori->title == 'pelatihan') {
                Sertifikat::create([
                    'verified_code' => Str::random(20),
                    'nomor_sertifikat' => str_pad($lastSertifikat, 4, '0', STR_PAD_LEFT),
                    'kegiatan_id' => $request->kegiatan_id,
                    'peserta_id' => $request->peserta_id,
                    'tanggal_terbit' => '-',
                    'tahun' => $kegiatan->tahun_kegiatan,
                    'siswa_id' => '-',
                    'orang_id' => '-',
                ]);
            }
            if ($kegiatan->kategori->title == 'bimtek') {
                Sertifikat::create([
                    'verified_code' => Str::random(20),
                    'nomor_sertifikat' => str_pad($lastSertifikat, 4, '0', STR_PAD_LEFT),
                    'kegiatan_id' => $request->kegiatan_id,
                    'peserta_id' => '-',
                    'tanggal_terbit' => '-',
                    'tahun' => $kegiatan->tahun_kegiatan,
                    'siswa_id' => '-',
                    'orang_id' => $request->orang_id,
                ]);
            }
            if ($kegiatan->kategori->title == 'pkl') {
                Sertifikat::create([
                    'verified_code' => Str::random(20),
                    'nomor_sertifikat' => str_pad($lastSertifikat, 4, '0', STR_PAD_LEFT),
                    'kegiatan_id' => $request->kegiatan_id,
                    'peserta_id' => '-',
                    'tanggal_terbit' => '-',
                    'tahun' => $kegiatan->tahun_kegiatan,
                    'siswa_id' => $request->siswa_id,
                    'orang_id' => '-',
                ]);
            }

            return redirect()->route('kegiatan.show', $request->kegiatan_id)->with('success', 'Peserta Baru Berhasil Di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            if (Auth::user()->role == 'admin') {
                return redirect()->route('kegiatan.show', $request->kegiatan_id)->with('fails', 'Peserta Baru Gagal Di Tambahkan');
            } else {
                return redirect()->route('kegiatan.show')->with('fails', 'Gagal mendaftar kegiatan');
            }
        } finally {
            DB::commit();
        }
    }

    // Narasumber Store
    public function storeNarasumber(Request $request)
    {
        $kegiatan = Kegiatan::find($request->kegiatan_id);

        // Validator
        $validator = Validator::make(
            $request->all(),
            [
                'narasumber_id' => 'required',
            ],
            [],
        );
        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            // ======================================1St Metode==========================================
            // Last data
            $currentYear = $kegiatan->tahun_kegiatan;
            $lastSertifikat = Sertifikat::max('tahun');

            if ($lastSertifikat !== $currentYear) {
                // Jika tahun berubah, atur $lastSertifikat ke 1
                $lastSertifikat = 1;
            } else {
                // Jika tahun sama, ambil nomor sertifikat terakhir dan tambahkan 1
                $lastSertifikat = Sertifikat::where('tahun', $currentYear)->max('nomor_sertifikat');
                $lastSertifikat++;
            }
            // ======================================End 1St Metode======================================

            Sertifikat::create([
                'verified_code' => Str::random(20),
                'nomor_sertifikat' => str_pad($lastSertifikat, 4, '0', STR_PAD_LEFT),
                'kegiatan_id' => $request->kegiatan_id,
                'peserta_id' => '-',
                'tanggal_terbit' => '-',
                'tahun' => $kegiatan->tahun_kegiatan,
                'status' => 'unsigned',
                'siswa_id' => '-',
                'narasumber_id' => $request->narasumber_id,
                'orang_id' => '-',
            ]);

            return redirect()->route('kegiatan.show', $request->kegiatan_id)->with('success', 'Narasumber Baru Berhasil Di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('kegiatan.show', $request->kegiatan_id)->with('fails', 'Narasumber Baru Gagal Di Tambahkan');
        } finally {
            DB::commit();
        }
    }

    public function preview($id)
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
                'kegiatans.lokasi_kegiatan AS lokasi_kegiatan',
                'kegiatans.penyelenggara_kegiatan AS penyelenggara_kegiatan',
                'penandatangans.nama AS nama_penandatangan',
                'penandatangans.nip AS nip_penandatangan',
                'penandatangans.pangkat_golongan AS pangkat_golongan_penandatangan',
                'penandatangans.jabatan AS jabatan_penandatangan',
                'sertifikats.status',
            )
            ->first();

        if ($sertifikat->kategori_kegiatan == 'pelatihan') {
            $this->sertifikatGenerate->previewSertifikatPelatihan($sertifikat);
        }
        if ($sertifikat->kategori_kegiatan == 'bimtek') {
            $this->sertifikatGenerate->previewSertifikatBimtek($sertifikat);
        }
        if ($sertifikat->kategori_kegiatan == 'pkl') {
            $this->sertifikatGenerate->prosesSingleGenerateSiswa($sertifikat);
        }
    }

    public function narasumber_preview($id)
    {
        $sertifikat = DB::table('sertifikats')
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('kategoris', 'kegiatans.kategori_id', '=', 'kategoris.id')
            ->join('narasumbers', 'sertifikats.narasumber_id', '=', 'narasumbers.id')
            ->join('penandatangans', 'kegiatans.penandatangan_id', '=', 'penandatangans.id')
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
                'kegiatans.lokasi_kegiatan AS lokasi_kegiatan',
                'kegiatans.total_jam_kegiatan AS total_jam_kegiatan',
                'kegiatans.tanggal_penandatanganan AS tanggal_penandatanganan',
                'narasumbers.nama AS nama',
                'narasumbers.nip AS nip',
                'narasumbers.tempat_lahir AS tempat_lahir',
                'narasumbers.tempat_lahir AS tempat_lahir',
                'narasumbers.tanggal_lahir AS tanggal_lahir',
                'narasumbers.pangkat_golongan AS pangkat_golongan',
                'narasumbers.jabatan AS jabatan',
                'narasumbers.instansi AS instansi',
                'penandatangans.nama AS nama_penandatangan',
                'penandatangans.nip AS nip_penandatangan',
                'penandatangans.pangkat_golongan AS pangkat_golongan_penandatangan',
                'penandatangans.jabatan AS jabatan_penandatangan',
                'sertifikats.status',
            )
            ->first();

        $this->sertifikatGenerate->previewSertifikatNarasumber($sertifikat);
    }

    public function delete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $sertifikat = Sertifikat::find($id);

            // Hapus QR Code
            $pathQr = public_path() . '/qrcode/' . 'qr_' . $sertifikat->verified_code . '.' . 'png';
            File::delete($pathQr);

            $sertifikat->delete($sertifikat);
            return redirect()->route('kegiatan.show', $request->kegiatan_id)->with('success', 'Peserta Berhasil Di Hapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('kegiatan.show', $request->kegiatan_id)->with('success', 'Peserta Gagal Di Hapus');
        } finally {
            DB::commit();
        }
    }

    public function deleteSertifikat($id)
    {
        DB::beginTransaction();
        try {
            $sertifikat = Sertifikat::find($id);

            // Hapus Sertifikat
            $path = public_path() . '/sertifikat/';
            $fileName = 'doc-sertifikat-' . $sertifikat->id . '.' . 'pdf';
            File::delete($path . $fileName);

            // Hapus QR Code
            $pathQr = public_path() . '/qrcode/';
            $fileQr = 'qr_sertifikat_' . $sertifikat->id . '.' . 'png';
            File::delete($pathQr . $fileQr);

            $sertifikat->delete($sertifikat);
            return redirect()->route('sertifikat.index')->with('success', 'Sertifikat Peserta Berhasil Di Hapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('sertifikat.index')->with('success', 'Sertifikat Peserta Gagal Di Hapus');
        } finally {
            DB::commit();
        }
    }

    public function download($id)
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
                'kegiatans.lokasi_kegiatan AS lokasi_kegiatan',
                'kegiatans.penyelenggara_kegiatan AS penyelenggara_kegiatan',
                'penandatangans.nama AS nama_penandatangan',
                'penandatangans.nip AS nip_penandatangan',
                'penandatangans.pangkat_golongan AS pangkat_golongan_penandatangan',
                'penandatangans.jabatan AS jabatan_penandatangan',
                'sertifikats.status',
            )
            ->first();

        if ($sertifikat->kategori_kegiatan == 'pelatihan') {
            $this->sertifikatGenerate->downloadSertifikatPelatihan($sertifikat);
        }
        if ($sertifikat->kategori_kegiatan == 'bimtek') {
            $this->sertifikatGenerate->downloadSertifikatBimtek($sertifikat);
        }
        if ($sertifikat->kategori_kegiatan == 'pkl') {
            $this->sertifikatGenerate->prosesSingleGenerateSiswa($sertifikat);
        }
    }

    public function narasumber_download($id)
    {
        $sertifikat = DB::table('sertifikats')
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('kategoris', 'kegiatans.kategori_id', '=', 'kategoris.id')
            ->join('narasumbers', 'sertifikats.narasumber_id', '=', 'narasumbers.id')
            ->join('penandatangans', 'kegiatans.penandatangan_id', '=', 'penandatangans.id')
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
                'kegiatans.lokasi_kegiatan AS lokasi_kegiatan',
                'kegiatans.total_jam_kegiatan AS total_jam_kegiatan',
                'kegiatans.tanggal_penandatanganan AS tanggal_penandatanganan',
                'narasumbers.nama AS nama',
                'narasumbers.nip AS nip',
                'narasumbers.tempat_lahir AS tempat_lahir',
                'narasumbers.tempat_lahir AS tempat_lahir',
                'narasumbers.tanggal_lahir AS tanggal_lahir',
                'narasumbers.pangkat_golongan AS pangkat_golongan',
                'narasumbers.jabatan AS jabatan',
                'narasumbers.instansi AS instansi',
                'penandatangans.nama AS nama_penandatangan',
                'penandatangans.nip AS nip_penandatangan',
                'penandatangans.pangkat_golongan AS pangkat_golongan_penandatangan',
                'penandatangans.jabatan AS jabatan_penandatangan',
                'sertifikats.status',
            )
            ->first();

        $this->sertifikatGenerate->downloadSertifikatNarasumber($sertifikat);
    }
}
