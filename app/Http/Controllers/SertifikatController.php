<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\Sertifikat;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class SertifikatController extends Controller
{
    //
    public function index()
    {
        $sertifikats = DB::table('sertifikats')
            ->join('kegiatans', 'sertifikats.kegiatan_id', '=', 'kegiatans.id')
            ->join('kategoris', 'kegiatans.kategori_id', '=', 'kategoris.id')
            ->select(
                'sertifikats.id',
                'sertifikats.verified_code',
                'sertifikats.peserta_id',
                'sertifikats.siswa_id',
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

        if ($kegiatan->kategori->title == 'pkl') {
            $dataPeserta = Siswa::all();
        } else {
            // Inisialisasi Guzzle Client
            $client = new Client();
            $response = $client->get(env('SIMPELTAN_API_DATA_PESERTA'));

            // Decode respons JSON dari API
            $dataPeserta = json_decode($response->getBody(), true);
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
            )
            ->where('sertifikats.kegiatan_id', '=', $kegiatan->id)
            ->get();


        // return response()->json($dataPeserta);

        return view('dashboard.sertifikat.create_peserta', compact('kegiatan', 'sertifikats', 'dataPeserta'));
    }

    public function store(Request $request)
    {
        $kegiatan = Kegiatan::find($request->kegiatan_id);
        if ($kegiatan->kategori->title == 'pkl') {
            // Validator
            $validator = Validator::make(
                $request->all(),
                [
                    'siswa_id' => 'required',
                ],
                [],
            );
        } else {
            // Validator
            $validator = Validator::make(
                $request->all(),
                [
                    'peserta_id' => 'required',
                ],
                [],
            );
        }
        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
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

            if ($kegiatan->kategori->title == 'pkl') {
                Sertifikat::create([
                    'verified_code' => Str::random(20),
                    'nomor_sertifikat' => str_pad($lastSertifikat, 4, '0', STR_PAD_LEFT),
                    'kegiatan_id' => $request->kegiatan_id,
                    'peserta_id' => '-',
                    'tanggal_terbit' => '-',
                    'tahun' => $kegiatan->tahun_kegiatan,
                    'siswa_id' => $request->siswa_id,
                ]);
            } else {
                Sertifikat::create([
                    'verified_code' => Str::random(20),
                    'nomor_sertifikat' => str_pad($lastSertifikat, 4, '0', STR_PAD_LEFT),
                    'kegiatan_id' => $request->kegiatan_id,
                    'peserta_id' => $request->peserta_id,
                    'tanggal_terbit' => '-',
                    'tahun' => $kegiatan->tahun_kegiatan,
                    'siswa_id' => '-',
                ]);
            }

            // if (Auth::user()->role == 'admin') {
            //     return redirect()->route('sertifikat.create.peserta', $request->kegiatan_id)->with('success', 'Peserta Baru Berhasil Di Tambahkan');
            // } else {
            //     return redirect()->route('dashboard.index')->with('success', 'Anda berhasil mendaftar kegiatan');
            // }

            return redirect()->route('sertifikat.create.peserta', $request->kegiatan_id)->with('success', 'Peserta Baru Berhasil Di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            if (Auth::user()->role == 'admin') {
                return redirect()->route('sertifikat.create.peserta', $request->kegiatan_id)->with('fails', 'Peserta Baru Gagal Di Tambahkan');
            } else {
                return redirect()->route('dashboard.index')->with('fails', 'Gagal mendaftar kegiatan');
            }
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

    public function download($id)
    {
        $filePath = public_path("sertifikat/" . 'doc-sertifikat-' . $id . '.' . 'pdf');
        return response()->download($filePath);
    }
}
