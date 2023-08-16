<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SertifikatController extends Controller
{
    //
    public function index()
    {
        $sertifikats = DB::table('sertifikats')
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('pesertas', 'sertifikats.peserta_id', '=', 'pesertas.id')
            ->select(
                'sertifikats.id',
                'sertifikats.status',
                'kegiatans.judul_kegiatan AS judul_kegiatan',
                'pesertas.nama AS nama_peserta',
            )
            ->get();
        return view('dashboard.sertifikat.index', compact('sertifikats'));
    }

    public function createPeserta($id)
    {
        $kegiatan = Kegiatan::find($id);
        $pesertas = Peserta::all();

        $sertifikats = DB::table('sertifikats')->where('kegiatan_id', $id)
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('pesertas', 'sertifikats.peserta_id', '=', 'pesertas.id')
            ->select(
                'sertifikats.id',
                'sertifikats.status',
                'kegiatans.judul_kegiatan AS judul_kegiatan',
                'pesertas.nama AS nama_peserta',
            )
            ->get();

        return view('dashboard.sertifikat.create_peserta', compact('kegiatan', 'pesertas', 'sertifikats'));
    }

    public function store(Request $request)
    {
        // Validator
        $validator = Validator::make(
            $request->all(),
            [
                'peserta_id' => 'required',
            ],
            [],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            Sertifikat::create([
                'kegiatan_id' => $request->kegiatan_id,
                'peserta_id' => $request->peserta_id,
                'tanggal_terbit' => date('d-m-Y'),
            ]);
            return redirect()->route('sertifikat.create.peserta', $request->kegiatan_id)->with('success', 'Peserta Baru Berhasil Di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('sertifikat.create.peserta', $request->kegiatan_id)->with('success', 'Peserta Baru Gagal Di Tambahkan');
        } finally {
            DB::commit();
        }
    }

    public function deletePeserta($id)
    {
        DB::beginTransaction();
        try {
            $sertifikat = Sertifikat::find($id);
            $sertifikat->delete($sertifikat);
            return redirect()->route('sertifikat.create.peserta', $sertifikat->kegiatan_id)->with('success', 'Peserta Berhasil Di Hapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('sertifikat.create.peserta', $sertifikat->kegiatan_id)->with('success', 'Peserta Gagal Di Hapus');
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
}
