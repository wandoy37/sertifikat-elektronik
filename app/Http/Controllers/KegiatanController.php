<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Kegiatan;
use App\Models\Narasumber;
use App\Models\Orang;
use App\Models\Penandatangan;
use App\Models\Sertifikat;
use App\Models\Siswa;
use App\Services\KegiatanGenerate;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $kegiatanGenerate;

    public function __construct(KegiatanGenerate $kegiatanGenerate)
    {
        $this->kegiatanGenerate = $kegiatanGenerate;
    }

    public function index()
    {
        $kegiatans = Kegiatan::latest()->get();
        return view('dashboard.kegiatan.index', compact('kegiatans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategories = Kategori::latest()->get();
        $penandatangans = Penandatangan::latest()->get();
        return view('dashboard.kegiatan.create', compact('kategories', 'penandatangans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validator
        $validator = Validator::make(
            $request->all(),
            [
                'kode_kegiatan' => 'required',
                'nama_kegiatan' => 'required',
                'tanggal_mulai_kegiatan' => 'required',
                'tanggal_akhir_kegiatan' => 'required',
                'kategori_id' => 'required',
                'tahun_kegiatan' => 'required',
                'lokasi_kegiatan' => 'required',
                'total_jam_kegiatan' => 'required',
                'penandatangan_id' => 'required',
                'tanggal_penandatanganan' => 'required',
                'daftar_mata_pelatihan' => 'required|file|mimes:pdf|max:2048',
            ],
            [],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $path = $request->file('daftar_mata_pelatihan')->store('daftar_mata_pelatihan', 'public');

            Kegiatan::create([
                'kode_kegiatan' => $request->kode_kegiatan,
                'judul_kegiatan' => $request->nama_kegiatan,
                'slug' => Str::slug($request->nama_kegiatan, '-'),
                'kategori_id' => $request->kategori_id,
                'tahun_kegiatan' => $request->tahun_kegiatan,
                'tanggal_mulai_kegiatan' => $request->tanggal_mulai_kegiatan,
                'tanggal_akhir_kegiatan' => $request->tanggal_akhir_kegiatan,
                'total_jam_kegiatan' => $request->total_jam_kegiatan,
                'lokasi_kegiatan' => $request->lokasi_kegiatan,
                'daftar_mata_pelatihan' => 'storage/' . $path,
                'penandatangan_id' => $request->penandatangan_id,
                'tanggal_penandatanganan' => $request->tanggal_penandatanganan,
                'status' => 'open'
            ]);
            return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $request->judul_kegiatan . ' Baru Berhasil Di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $request->judul_kegiatan . ' Baru Gagal Di Tambahkan');
        } finally {
            DB::commit();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kegiatan = Kegiatan::find($id);

        // If Peserta Kegiatan
        if ($kegiatan->kategori->title == 'pelatihan') {
            $client = new Client();
            $response = $client->get(env('SIMPELTAN_API_DATA_PESERTA'));
            $dataPesertas = json_decode($response->getBody(), true);
        }
        // If Bimtek Kegiatan
        if ($kegiatan->kategori->title == 'bimtek') {
            $dataPesertas = Orang::all();
        }
        // If PKL Kegiatan
        if ($kegiatan->kategori->title == 'pkl') {
            $dataPesertas = Siswa::all();
        }

        // Narasumber
        $narasumbers = Narasumber::all();

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


        return view('dashboard.kegiatan.show', compact('kegiatan', 'dataPesertas', 'narasumbers', 'sertifikats'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kategories = Kategori::latest()->get();
        $kegiatan = Kegiatan::find($id);
        $penandatangans = Penandatangan::latest()->get();
        return view('dashboard.kegiatan.edit', compact('kegiatan', 'kategories', 'penandatangans'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validator
        $validator = Validator::make(
            $request->all(),
            [
                'kode_kegiatan' => 'required',
                'nama_kegiatan' => 'required',
                'tanggal_mulai_kegiatan' => 'required',
                'tanggal_akhir_kegiatan' => 'required',
                'kategori_id' => 'required',
                'tahun_kegiatan' => 'required',
                'lokasi_kegiatan' => 'required',
                'total_jam_kegiatan' => 'required',
                'penandatangan_id' => 'required',
                'tanggal_penandatanganan' => 'required',
                'daftar_mata_pelatihan' => 'nullable|file|mimes:pdf|max:2048',
            ],
            [],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $kegiatan = Kegiatan::findOrFail($id);

            // Cek apakah ada file baru
            if ($request->hasFile('daftar_mata_pelatihan')) {
                // Hapus file lama jika ada
                if ($kegiatan->daftar_mata_pelatihan && Storage::exists(str_replace('storage/', '', $kegiatan->daftar_mata_pelatihan))) {
                    Storage::delete(str_replace('storage/', '', $kegiatan->daftar_mata_pelatihan)); // Hapus file lama
                }

                // Upload file baru
                $path = $request->file('daftar_mata_pelatihan')->store('daftar_mata_pelatihan', 'public');
                $kegiatan->update(['daftar_mata_pelatihan' => 'storage/' . $path]);
            }

            $kegiatan->update([
                'kode_kegiatan' => $request->kode_kegiatan,
                'judul_kegiatan' => $request->nama_kegiatan,
                'slug' => Str::slug($request->nama_kegiatan, '-'),
                'kategori_id' => $request->kategori_id,
                'tahun_kegiatan' => $request->tahun_kegiatan,
                'tanggal_mulai_kegiatan' => $request->tanggal_mulai_kegiatan,
                'tanggal_akhir_kegiatan' => $request->tanggal_akhir_kegiatan,
                'total_jam_kegiatan' => $request->total_jam_kegiatan,
                'lokasi_kegiatan' => $request->lokasi_kegiatan,
                'penandatangan_id' => $request->penandatangan_id,
                'tanggal_penandatanganan' => $request->tanggal_penandatanganan,
                'status' => 'open'
            ]);
            return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $request->judul_kegiatan . ' Berhasil Di Update');
        } catch (\Throwable $th) {
            dd(vars: $th);
            DB::rollBack();
            return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $request->judul_kegiatan . ' Baru Gagal Di Update');
        } finally {
            DB::commit();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $kegiatan = Kegiatan::find($id);

            $sertifikats = Sertifikat::where('kegiatan_id', $kegiatan->id)->get();


            foreach ($sertifikats as $sertifikat) {
                // Hapus Sertifikat & QRCODE Peserta Pada Kegiatan 

                // Hapus sertifikat
                $path = public_path('sertifikat/');
                $docSertifikat = 'doc-sertifikat-' . $sertifikat->id . '.' . 'pdf';
                if (file_exists($path . $docSertifikat)) {
                    unlink($path . $docSertifikat);
                }

                // Hapus QR Code
                $pathQr = public_path('qrcode/');
                $fileQr = 'qr_sertifikat_' . $sertifikat->id . '.' . 'png';
                if (file_exists($pathQr . $fileQr)) {
                    unlink($pathQr . $fileQr);
                }

                // Hapus file daftar_mata_pelatihan jika ada
                if ($kegiatan->daftar_mata_pelatihan && Storage::exists(str_replace('storage/', '', $kegiatan->daftar_mata_pelatihan))) {
                    Storage::delete(str_replace('storage/', '', $kegiatan->daftar_mata_pelatihan));
                }

                // Hapus data sertifikat dari database
                $sertifikat->delete();
            }

            // Hapus Sertifikat pada Kegiatan
            $pathSertifikatKegiatan = public_path('sertifikat/kegiatan/');
            $docSertifikatKegiatan = 'doc-sertifikat-kegiatan_' . Str::slug($kegiatan->judul_kegiatan, '-') . '.' . 'pdf';
            File::delete($pathSertifikatKegiatan . $docSertifikatKegiatan);

            $kegiatan->delete($kegiatan);
            return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $kegiatan->judul_kegiatan . ' Berhasil Di Hapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $kegiatan->judul_kegiatan . ' Gagal Di Hapus');
        } finally {
            DB::commit();
        }
    }

    public function print($id)
    {
        $kegiatan = Kegiatan::find($id);
        $this->kegiatanGenerate->prosesKegiatanGenerate($kegiatan);
    }
}
